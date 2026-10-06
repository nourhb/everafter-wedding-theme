/**
 * Ever After theme front-end interactions.
 * Vanilla JS: scroll reveals, animated counters, back-to-top.
 * Honors prefers-reduced-motion.
 */
(function () {
	'use strict';

	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* Scroll reveal */
	var revealEls = document.querySelectorAll('.everafter-reveal');
	if (revealEls.length && 'IntersectionObserver' in window && !reduceMotion) {
		var revealObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					revealObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12 });
		revealEls.forEach(function (el) { revealObserver.observe(el); });
	} else {
		revealEls.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* Animated counters */
	var counters = document.querySelectorAll('.everafter-count');
	function animateCount(el) {
		var target = parseInt(el.getAttribute('data-count'), 10) || 0;
		if (reduceMotion) {
			el.textContent = target;
			return;
		}
		var duration = 1600;
		var start = null;
		function step(timestamp) {
			if (!start) { start = timestamp; }
			var progress = Math.min((timestamp - start) / duration, 1);
			var eased = 1 - Math.pow(1 - progress, 3);
			el.textContent = Math.round(target * eased);
			if (progress < 1) { window.requestAnimationFrame(step); }
		}
		window.requestAnimationFrame(step);
	}
	if (counters.length && 'IntersectionObserver' in window) {
		var countObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					animateCount(entry.target);
					countObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.5 });
		counters.forEach(function (el) { countObserver.observe(el); });
	} else {
		counters.forEach(animateCount);
	}

	/* Back to top */
	var toTop = document.createElement('button');
	toTop.className = 'everafter-to-top';
	toTop.setAttribute('aria-label', 'Back to top');
	toTop.innerHTML = '&uarr;';
	document.body.appendChild(toTop);

	function toggleToTop() {
		if (window.scrollY > 600) {
			toTop.classList.add('is-visible');
		} else {
			toTop.classList.remove('is-visible');
		}
	}
	window.addEventListener('scroll', toggleToTop, { passive: true });
	toggleToTop();

	toTop.addEventListener('click', function () {
		window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
	});
})();
