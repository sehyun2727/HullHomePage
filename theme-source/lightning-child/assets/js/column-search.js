/**
 * Column archive (/column/) client-side title/lead search.
 * All published column posts are already in the DOM (see archive-column.php) —
 * posts beyond the current page are pre-hidden via the .column-card--extra class.
 * Searching just toggles inline display on top of that, so clearing the
 * search restores the original pagination state for free.
 */
document.addEventListener('DOMContentLoaded', function () {
	var grid = document.querySelector('.column-archive__grid.card-grid');
	if (!grid) {
		return;
	}

	var input      = document.getElementById('column-search-input');
	var searchBtn  = document.getElementById('column-search-btn');
	var noResults  = document.getElementById('column-search-noresults');
	var pagination = document.querySelector('.navigation.pagination');

	function clearSearch() {
		var cards = grid.querySelectorAll('.column-card');
		cards.forEach(function (card) {
			card.classList.remove('is-search-match', 'is-search-hidden');
		});
		if (pagination) {
			pagination.style.display = '';
		}
		noResults.hidden = true;
		input.value = '';
	}

	function runSearch() {
		var query = input.value.trim().toLowerCase();

		if (!query) {
			clearSearch();
			return;
		}

		var cards   = grid.querySelectorAll('.column-card');
		var matches = 0;

		cards.forEach(function (card) {
			var titleEl = card.querySelector('.column-card__title');
			var title   = titleEl ? titleEl.textContent.toLowerCase() : '';
			var lead    = (card.getAttribute('data-lead') || '').toLowerCase();
			var isMatch = title.indexOf(query) !== -1 || lead.indexOf(query) !== -1;

			// Class toggle instead of inline style.display: .column-card--extra's
			// `display: none` is a CSS class rule, so clearing an inline style only
			// falls back to it rather than overriding it. A same-specificity class
			// (.is-search-match) is needed to actually beat it.
			card.classList.toggle('is-search-match', isMatch);
			card.classList.toggle('is-search-hidden', !isMatch);
			if (isMatch) {
				matches++;
			}
		});

		if (pagination) {
			pagination.style.display = 'none';
		}
		noResults.hidden = matches !== 0;
	}

	searchBtn.addEventListener('click', runSearch);

	input.addEventListener('keydown', function (e) {
		if (e.key === 'Enter') {
			e.preventDefault();
			runSearch();
		}
	});

	input.addEventListener('input', function () {
		if (input.value.trim() === '') {
			clearSearch();
		}
	});
});
