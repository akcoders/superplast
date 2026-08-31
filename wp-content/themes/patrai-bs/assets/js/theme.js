(function () {
	'use strict';

	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function initProductMenu() {
		const menu = document.querySelector('.patrai-products-menu');
		if (!menu) return;

		const toggle = menu.querySelector('.mega-menu-toggle');
		if (!toggle) return;

		const setOpen = (open) => {
			menu.classList.toggle('mega-open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		};

		toggle.addEventListener('click', (event) => {
			event.preventDefault();
			event.stopPropagation();
			setOpen(!menu.classList.contains('mega-open'));
		});

		document.addEventListener('click', (event) => {
			if (!menu.contains(event.target)) setOpen(false);
		});

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				setOpen(false);
				toggle.focus();
			}
		});

		const navCollapse = document.getElementById('patraiPrimaryNav');
		if (navCollapse) navCollapse.addEventListener('hidden.bs.collapse', () => setOpen(false));
	}

	function initBackToTop() {
		const button = document.querySelector('.back-to-top');
		if (!button) return;

		let ticking = false;
		const update = () => {
			button.classList.toggle('is-visible', window.scrollY > 520);
			ticking = false;
		};

		window.addEventListener('scroll', () => {
			if (!ticking) {
				window.requestAnimationFrame(update);
				ticking = true;
			}
		}, { passive: true });

		button.addEventListener('click', () => {
			window.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' });
		});
		update();
	}

	function initRevealAnimations() {
		const selector = [
			'.product-card', '.case-card', '.principle-card', '.sector-card',
			'.feature-showcase-card', '.timeline-card', '.journey-step',
			'.quick-value', '.focus-grid > a', '.contact-detail',
			'.gallery-item', '.tech-model', '.application-panel',
			'.product-family-nav > a', '.product-feature-item', '.future-panel',
			'.contact-form-card', '.case-next'
		].join(',');
		const cards = Array.from(document.querySelectorAll(selector));
		if (!cards.length || reducedMotion || !('IntersectionObserver' in window)) return;

		cards.forEach((card, index) => {
			card.classList.add('motion-card');
			card.style.setProperty('--motion-delay', `${(index % 6) * 70}ms`);
		});
		document.documentElement.classList.add('motion-enabled');

		const observer = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-visible');
				observer.unobserve(entry.target);
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

		cards.forEach((card) => observer.observe(card));
	}

	initProductMenu();
	initBackToTop();
	initRevealAnimations();
}());
