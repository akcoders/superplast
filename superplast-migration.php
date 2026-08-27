<?php
/**
 * One-time encrypted database installer for the Super Plast deployment.
 *
 * The decryption key is never stored in Git. The installer is restricted to
 * the intended hostname and permanently locks after a successful import.
 */

declare(strict_types=1);

const SUPERPLAST_MIGRATION_HOST       = 'superplast.webignitors.in';
const SUPERPLAST_MIGRATION_URL        = 'https://superplast.webignitors.in';
const SUPERPLAST_MIGRATION_TOKEN_HASH = 'f21bb72c622195c05fd37ed88ba80d57c7d286c9e6cd30fc2970f0f1f1310c6e';
const SUPERPLAST_MIGRATION_PREFIX     = 'wp_';

/**
 * Execute an SQL export one statement at a time to stay below conservative
 * max_allowed_packet limits. The parser respects quoted strings and escapes.
 */
function superplast_import_sql(mysqli $connection, string $sql): int
{
    $statement = '';
    $quote     = null;
    $escaped   = false;
    $executed  = 0;
    $length    = strlen($sql);

    for ($index = 0; $index < $length; $index++) {
        $character = $sql[$index];
        $statement .= $character;

        if (null !== $quote) {
            if ($escaped) {
                $escaped = false;
                continue;
            }
            if ('\\' === $character) {
                $escaped = true;
                continue;
            }
            if ($character === $quote) {
                $quote = null;
            }
            continue;
        }

        if ("'" === $character || '"' === $character || '`' === $character) {
            $quote = $character;
            continue;
        }

        if (';' !== $character) {
            continue;
        }

        $query = trim($statement);
        $statement = '';
        if ('' === $query) {
            continue;
        }

        $executed++;
        try {
            $result = $connection->query($query);
        } catch (Throwable $query_error) {
            throw new RuntimeException('Database statement ' . $executed . ' failed: ' . $query_error->getMessage());
        }
        if (false === $result) {
            throw new RuntimeException('Database statement ' . $executed . ' failed: ' . $connection->error);
        }
        if ($result instanceof mysqli_result) {
            $result->free();
        }
    }

    if ('' !== trim($statement)) {
        throw new RuntimeException('The SQL package ended with an incomplete statement.');
    }

    return $executed;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Robots-Tag: noindex, nofollow, noarchive', true);
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; script-src 'unsafe-inline'; form-action 'self'; base-uri 'none'; frame-ancestors 'none'");

$migration_lock    = __DIR__ . '/wp-content/uploads/.superplast-migration-complete';
$migration_payload = __DIR__ . '/wp-content/migration/superplast-db.enc';
$request_host      = strtolower((string) preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
$is_post           = 'POST' === ($_SERVER['REQUEST_METHOD'] ?? 'GET');
$notice            = '';
$notice_type       = 'info';

if ($request_host !== SUPERPLAST_MIGRATION_HOST) {
    http_response_code(403);
    $notice = 'This migration package is restricted to the configured live domain.';
    $notice_type = 'error';
} elseif (is_file($migration_lock)) {
    http_response_code(410);
    $notice = 'Migration is already complete. This installer is permanently locked.';
    $notice_type = 'success';
} elseif ($is_post) {
    $token = strtolower(trim((string) ($_POST['migration_key'] ?? '')));
    $confirmed = 'replace' === ($_POST['confirm_replace'] ?? '');

    if (!$confirmed || 64 !== strlen($token) || !ctype_xdigit($token) || !hash_equals(SUPERPLAST_MIGRATION_TOKEN_HASH, hash('sha256', $token))) {
        http_response_code(403);
        $notice = 'The migration key or confirmation is invalid.';
        $notice_type = 'error';
    } elseif (!is_file($migration_payload) || !is_readable($migration_payload)) {
        http_response_code(500);
        $notice = 'The encrypted database package is missing.';
        $notice_type = 'error';
    } else {
        $maintenance_file = __DIR__ . '/.maintenance';

        try {
            if (!extension_loaded('openssl')) {
                throw new RuntimeException('OpenSSL is unavailable on the server.');
            }

            $encrypted = file_get_contents($migration_payload);
            if (false === $encrypted || strlen($encrypted) < 33 || 'SPM1' !== substr($encrypted, 0, 4)) {
                throw new RuntimeException('The encrypted package is invalid.');
            }

            $iv         = substr($encrypted, 4, 12);
            $tag        = substr($encrypted, 16, 16);
            $ciphertext = substr($encrypted, 32);
            $sql        = openssl_decrypt($ciphertext, 'aes-256-gcm', hash('sha256', $token, true), OPENSSL_RAW_DATA, $iv, $tag);

            if (false === $sql || false === strpos($sql, 'DROP TABLE IF EXISTS `wp_options`') || false === strpos($sql, SUPERPLAST_MIGRATION_URL)) {
                throw new RuntimeException('Package authentication failed.');
            }

            define('WP_USE_THEMES', false);
            require_once __DIR__ . '/wp-load.php';

            global $wpdb, $table_prefix;
            if (!isset($wpdb->dbh) || !($wpdb->dbh instanceof mysqli)) {
                throw new RuntimeException('A MySQL connection could not be initialized.');
            }

            $target_prefix = (string) $table_prefix;
            if (!preg_match('/^[A-Za-z0-9_]+$/', $target_prefix)) {
                throw new RuntimeException('The target WordPress table prefix is invalid.');
            }

            if (SUPERPLAST_MIGRATION_PREFIX !== $target_prefix) {
                $sql = str_replace('`' . SUPERPLAST_MIGRATION_PREFIX, '`' . $target_prefix, $sql);
                $sql = str_replace(
                    array("'wp_user_roles'", "'wp_capabilities'", "'wp_user_level'"),
                    array("'{$target_prefix}user_roles'", "'{$target_prefix}capabilities'", "'{$target_prefix}user_level'"),
                    $sql
                );
            }

            file_put_contents($maintenance_file, '<?php $upgrading = ' . time() . ';');

            $connection = $wpdb->dbh;
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            superplast_import_sql($connection, $sql);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');

            $options_table = '`' . $target_prefix . 'options`';
            $live_url      = $connection->real_escape_string(SUPERPLAST_MIGRATION_URL);
            $connection->query("UPDATE {$options_table} SET option_value='{$live_url}' WHERE option_name IN ('home','siteurl')");
            $connection->query("UPDATE {$options_table} SET option_value='patrai-bs' WHERE option_name IN ('template','stylesheet')");
            $connection->query("DELETE FROM {$options_table} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'");

            if (!is_dir(dirname($migration_lock))) {
                mkdir(dirname($migration_lock), 0755, true);
            }
            $lock_data = json_encode(
                array(
                    'completed_at' => gmdate('c'),
                    'target'       => SUPERPLAST_MIGRATION_URL,
                    'theme'        => 'patrai-bs',
                ),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
            if (false === file_put_contents($migration_lock, $lock_data)) {
                throw new RuntimeException('Database imported, but the completion lock could not be written.');
            }

            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
            }
            if (function_exists('do_action')) {
                do_action('litespeed_purge_all');
            }

            $notice = 'Migration completed successfully. PATRAI BS and the complete local database are now installed.';
            $notice_type = 'success';
        } catch (Throwable $error) {
            http_response_code(500);
            $notice = 'Migration failed: ' . $error->getMessage();
            $notice_type = 'error';
        } finally {
            if (is_file($maintenance_file)) {
                unlink($maintenance_file);
            }
        }
    }
}

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Plast Secure Migration</title>
    <style>
        :root{color-scheme:light;--blue:#1268a8;--pale:#eef8ff;--ink:#15324a;--danger:#b42318;--success:#067647}*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;background:linear-gradient(145deg,#f7fcff,#dff3ff);font:16px/1.55 system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;color:var(--ink)}main{width:min(660px,100%);background:#fff;border:1px solid #cfe8f7;border-radius:22px;padding:clamp(24px,5vw,44px);box-shadow:0 25px 70px rgba(26,95,139,.14)}.mark{display:inline-flex;padding:7px 11px;border-radius:999px;background:var(--pale);color:var(--blue);font-size:12px;font-weight:800;letter-spacing:.1em;text-transform:uppercase}h1{font-size:clamp(28px,5vw,42px);line-height:1.1;margin:18px 0 12px}p{margin:0 0 18px}.notice{padding:14px 16px;border-radius:12px;margin:20px 0;font-weight:650}.notice.info{background:#eef8ff;color:#175b86}.notice.error{background:#fff0ee;color:var(--danger)}.notice.success{background:#ecfdf3;color:var(--success)}label{display:block;font-weight:700;margin:18px 0 7px}input{width:100%;padding:13px 14px;border:1px solid #b7d4e6;border-radius:10px;font:inherit}button{width:100%;margin-top:18px;border:0;border-radius:12px;background:var(--blue);color:#fff;padding:14px 18px;font:inherit;font-weight:800;cursor:pointer}button:hover{background:#0c558a}.warning{padding:16px;border-left:4px solid #f79009;background:#fffaeb}.small{font-size:13px;color:#547086;margin-top:14px}form[hidden]{display:none}
    </style>
</head>
<body>
<main>
    <span class="mark">One-time installer</span>
    <h1>Super Plast database migration</h1>
    <p>This installer replaces the current WordPress database with the approved PATRAI BS local database and switches the site to the new theme.</p>
    <?php if ($notice) : ?>
        <div class="notice <?php echo htmlspecialchars($notice_type, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($notice, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if (!$notice && $request_host === SUPERPLAST_MIGRATION_HOST && !is_file($migration_lock)) : ?>
        <div class="warning"><strong>Before continuing:</strong> confirm that a fresh Hostinger backup exists. Existing live database content will be replaced.</div>
        <form method="post" id="migration-form">
            <label for="migration-key">Private migration key</label>
            <input id="migration-key" name="migration_key" type="password" autocomplete="off" required>
            <label><input name="confirm_replace" type="checkbox" value="replace" required style="width:auto;margin-right:8px">I confirm the live database can be replaced.</label>
            <button type="submit">Install complete database</button>
        </form>
        <p class="small">The link key is removed from browser history before submission. After success, this installer permanently locks itself.</p>
    <?php endif; ?>
</main>
<script>
(() => {
    const params = new URLSearchParams(location.hash.slice(1));
    const key = params.get('key');
    const input = document.getElementById('migration-key');
    if (key && input) {
        input.value = key;
        history.replaceState(null, '', location.pathname);
    }
})();
</script>
</body>
</html>
