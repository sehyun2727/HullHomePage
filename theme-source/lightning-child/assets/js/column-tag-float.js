/**
 * Column archive (/column/, /column/category/{slug}/) floating tag panel.
 * Collapsed by default (handle tab only), visible from page load; click
 * toggles the sliding panel open. position:fixed keeps it on screen for
 * the entire scroll.
 */
document.addEventListener('DOMContentLoaded', function () {
	var floatEl = document.querySelector('[data-column-tag-float]');
	if (!floatEl) {
		return;
	}

	var handle    = floatEl.querySelector('.column-tag-float__handle');
	var closeBtn  = floatEl.querySelector('.column-tag-float__close');

	function open() {
		floatEl.classList.add('is-open');
		handle.setAttribute('aria-expanded', 'true');
	}

	function close() {
		floatEl.classList.remove('is-open');
		handle.setAttribute('aria-expanded', 'false');
	}

	function toggle() {
		if (floatEl.classList.contains('is-open')) {
			close();
		} else {
			open();
		}
	}

	handle.addEventListener('click', toggle);

	if (closeBtn) {
		closeBtn.addEventListener('click', close);
	}

	document.addEventListener('click', function (e) {
		if (floatEl.classList.contains('is-open') && !floatEl.contains(e.target)) {
			close();
		}
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			close();
		}
	});
});
