<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// END ENQUEUE PARENT ACTION

// ---- Column: force one-column layout (no sidebar) on single articles ----
add_filter( 'lightning_is_layout_onecolumn', function( $is_onecolumn ) {
	if ( is_singular( 'column' ) ) {
		return true;
	}
	return $is_onecolumn;
} );

add_filter( 'lightning_is_subsection_display', function( $is_subsection ) {
	if ( is_singular( 'column' ) ) {
		return false;
	}
	return $is_subsection;
} );

// ---- Column: hide the shared green page-header banner on single articles ----
// (it only ever showed the CPT label "コラム", duplicating the breadcrumb/H1 below)
add_filter( 'lightning_is_page_header', function( $is_page_header ) {
	if ( is_singular( 'column' ) ) {
		return false;
	}
	return $is_page_header;
} );

// ---- News category archive: replace the plain color page-header banner
// with the same photo + white-gradient hero used on the column archive
// (/column/). Only the hero markup changes; breadcrumb, sidebar, and
// article list below are untouched.
add_filter( 'lightning_pageTitHtml', function( $html ) {
	if ( is_category( 'news' ) ) {
		ob_start();
		?>
<div class="news-hero">
	<div class="news-hero__image"></div>
	<div class="news-hero__gradient"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="news-hero__text">
					<h1 class="news-hero__title">ニュース</h1>
					<p class="news-hero__desc">HULLに関するニュースをお届けします。</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- [ /.news-hero ] -->
		<?php
		return ob_get_clean();
	}
	return $html;
} );

// ---- News category archive: hide the "HOME / お知らせ" breadcrumb ----
// (the custom news-hero banner above already shows the "ニュース" title,
// so the breadcrumb line is redundant here — same idea as the column-CPT
// page-header hide above, just targeting the breadcrumb instead.)
add_filter( 'lightning_is_breadcrumb', function( $is_breadcrumb ) {
	if ( is_category( 'news' ) ) {
		return false;
	}
	return $is_breadcrumb;
} );

// ---- Site-wide: fix Easy Table of Contents (目次) smooth-scroll overshoot ----
// The plugin computes its scroll offset from <header>'s own CSS position, which
// stays "relative" always. The actual sticky element is a nested .gMenu_outer
// (parent theme _g2 style.css) that switches to position:fixed only when a
// .header_scrolled ancestor class is toggled on scroll — the plugin never sees
// this and so under-shoots its offset, landing ~54px too far down and cutting
// off the target heading. 64 = ~54px fixed nav height + small buffer.
add_filter( 'eztoc_get_option_smooth_scroll_offset', function( $value ) {
	return 64;
} );

// ---- Site-wide: hide footer "Powered by WordPress & Lightning Theme..." line ----
// No Customizer toggle exists for this (parent theme _g2 echoes it unconditionally
// in lightning_the_footerCopyRight()); live site (hull-inc.jp) doesn't show it either.
add_filter( 'lightning_footerPoweredCustom', function( $html ) {
	return '';
} );

// ---- Site-wide: guarantee Easy Table of Contents toggle button never jumps to top ----
// Defensive capture-phase preventDefault on .ez-toc-toggle (<a href="#">), so the
// browser's default anchor-jump never fires regardless of the plugin's own
// handler timing/state.
add_action( 'wp_enqueue_scripts', function() {
	wp_add_inline_script( 'jquery', "
		document.addEventListener('click', function(e) {
			var toggle = e.target.closest('.ez-toc-toggle');
			if (toggle) {
				e.preventDefault();
			}
		}, true);
	" );
} );

// ---- Column: limit archive-column.php / taxonomy-column_category.php lists to 9 posts per page ----
add_action( 'pre_get_posts', function( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'column' ) || is_tax( 'column_category' ) ) ) {
		$query->set( 'posts_per_page', 9 );
	}
} );

// ---- Column: category taxonomy (business-line filter, separate from the hidden column_tag) ----
// Real archive URLs at /column/category/{slug}/ for SEO, shown as filter tabs on
// both /column/ and each category's own taxonomy-column_category.php archive.
add_action( 'init', function() {
	register_taxonomy( 'column_category', 'column', array(
		'labels' => array(
			'name'          => 'コラムカテゴリ',
			'singular_name' => 'コラムカテゴリ',
			'menu_name'     => 'カテゴリ',
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		// Keep the normal slug-based rewrite so get_term_link() can build pretty
		// URLs (it reads from this permastruct directly, not from the rules
		// array). Incoming *requests*, however, need the explicit 'top' rules
		// below: the 'column' CPT (registered by CPT UI, earlier on the same
		// 'init' hook) already contributes a "column/<slug>/<slug>" attachment
		// fallback rule that sits ahead of this auto-generated one in the
		// compiled rules array and would otherwise swallow "column/category/{term}/"
		// first.
		'rewrite'           => array( 'slug' => 'column/category', 'with_front' => false ),
	) );
} );

// Explicit high-priority copies of the same rules generated above, inserted at
// 'top' so they're matched before the CPT's conflicting attachment-fallback rule.
// Not prefixed with the site's permalink front ("archives/"): public column URLs
// are fixed at /column/... regardless of the site's own permalink structure.
add_action( 'init', function() {
	add_rewrite_rule( '^column/category/([^/]+)/page/([0-9]+)/?$', 'index.php?column_category=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( '^column/category/([^/]+)/?$', 'index.php?column_category=$matches[1]', 'top' );
}, 20 );

// One-time seed of the 3 fixed categories (safe to run every load — term_exists()
// short-circuits once they exist, and there are only 3 to check).
// v6 2026-08-25: changed from 4 categories (digital-signage / sign-construction /
// import-ec / reform) to 3 (all / hull-vision / hull-space).
add_action( 'init', function() {
	$column_categories = array(
		'all'        => '全体',
		'hull-vision' => 'HULL VISION',
		'hull-space'  => 'HULL SPACE',
	);
	foreach ( $column_categories as $slug => $name ) {
		if ( ! term_exists( $slug, 'column_category' ) ) {
			wp_insert_term( $name, 'column_category', array( 'slug' => $slug ) );
		}
	}
}, 20 );

// Short admin-facing reminder next to the "カテゴリ" (column_category) panel
// in the block editor sidebar (the "よく使うもの" quick-pick list handles the
// actual UI — this is just guidance text, no selection limit is enforced).
// The panel is a React-rendered FlatTermSelector, so there's no server-side
// meta box markup to hook into; find it client-side by its panel title.
add_action( 'admin_footer-post.php', 'column_category_hint_notice' );
add_action( 'admin_footer-post-new.php', 'column_category_hint_notice' );
function column_category_hint_notice() {
	if ( get_current_screen()->post_type !== 'column' ) {
		return;
	}
	?>
	<script>
	document.addEventListener( 'DOMContentLoaded', function () {
		function insertHint() {
			var buttons = document.querySelectorAll( '.components-panel__body-toggle' );
			for ( var i = 0; i < buttons.length; i++ ) {
				if ( buttons[ i ].textContent.trim() === 'カテゴリ' ) {
					var titleEl = buttons[ i ].closest( '.components-panel__body-title' );
					var panel = buttons[ i ].closest( '.components-panel__body' );
					if ( titleEl && panel && ! panel.querySelector( '.column-category-hint' ) ) {
						var hint = document.createElement( 'p' );
						hint.className = 'column-category-hint';
						hint.style.cssText = 'margin:8px 0 0;color:#d63638;font-size:12px;';
						hint.textContent = '※ 3つのカテゴリから1つだけお選びください';
						titleEl.parentNode.insertBefore( hint, titleEl.nextSibling );
						return true;
					}
				}
			}
			return false;
		}
		if ( ! insertHint() ) {
			var observer = new MutationObserver( function () {
				if ( insertHint() ) {
					observer.disconnect();
				}
			} );
			observer.observe( document.body, { childList: true, subtree: true } );
		}
	} );
	</script>
	<?php
}

// Renders one .column-card. Shared by archive-column.php (all posts) and
// taxonomy-column_category.php (posts in one category), each of which also
// renders a hidden "search-extra" pass so client-side search can match
// against posts outside the current pagination slice.
if ( ! function_exists( 'column_render_search_card' ) ) {
	function column_render_search_card( $show_tags, $extra_class = '' ) {
		$lead_text_for_search = function_exists( 'get_field' ) ? get_field( 'lead_text' ) : '';
		?>
		<article class="column-card<?php echo $extra_class ? ' ' . esc_attr( $extra_class ) : ''; ?>" data-lead="<?php echo esc_attr( wp_strip_all_tags( (string) $lead_text_for_search ) ); ?>">
			<a href="<?php the_permalink(); ?>" class="column-card__link">
				<div class="column-card__thumb">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium', [ 'class' => 'column-card__img' ] ); ?>
					<?php else : ?>
						<div class="column-card__no-image"></div>
					<?php endif; ?>
				</div>
				<div class="column-card__body">
					<time class="column-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
						<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
					</time>
					<h3 class="column-card__title"><?php the_title(); ?></h3>
				</div>
			</a>
			<?php
			// Tags render as their own <a> outside .column-card__link so they never
			// nest inside the card-wide link (nested <a> would make tag clicks
			// navigate to the article instead of the tag archive).
			$card_terms = get_the_terms( get_the_ID(), 'column_tag' );
			if ( $show_tags && $card_terms && ! is_wp_error( $card_terms ) ) :
			?>
			<div class="column-card__tags">
				<?php foreach ( $card_terms as $ct ) : ?>
				<a href="<?php echo esc_url( get_term_link( $ct ) ); ?>" class="column-tag column-tag--small"><?php echo esc_html( $ct->name ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</article>
		<?php
	}
}

// Renders the 3 category filter tabs shown above the search box on /column/
// and on each category archive. $active_slug === '' means the "全体" tab is
// active (main /column/ archive). The 'all' term links back to the main
// archive so all three tabs are rendered from the column_category terms.
// v6 2026-08-25: tabs changed to 全体 → HULL VISION → HULL SPACE.
if ( ! function_exists( 'column_render_category_tabs' ) ) {
	function column_render_category_tabs( $active_slug = '' ) {
		$tab_order  = array( 'all', 'hull-vision', 'hull-space' );
		$categories = array();
		foreach ( $tab_order as $slug ) {
			$term = get_term_by( 'slug', $slug, 'column_category' );
			if ( $term ) {
				$categories[] = $term;
			}
		}
		if ( empty( $categories ) ) {
			return;
		}
		?>
		<nav class="column-category-tabs" aria-label="カテゴリで絞り込む">
			<?php foreach ( $categories as $category ) :
				// 'all' term → link to main archive, active when no category selected
				if ( 'all' === $category->slug ) :
					$href      = get_post_type_archive_link( 'column' );
					$is_active = '' === $active_slug || 'all' === $active_slug;
				else :
					$href      = get_term_link( $category );
					$is_active = $active_slug === $category->slug;
				endif;
			?>
			<a href="<?php echo esc_url( $href ); ?>" class="column-category-tabs__item<?php echo $is_active ? ' is-active' : ''; ?>">
				<?php echo esc_html( $category->name ); ?>
			</a>
			<?php endforeach; ?>
		</nav>
		<?php
	}
}

// ---- Column: enqueue FAQ accordion script ----
add_action( 'wp_enqueue_scripts', function() {
	if ( ! is_singular( 'column' ) ) {
		return;
	}
	wp_add_inline_script( 'jquery', "
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('.column-faq__question').forEach(function(btn) {
				btn.addEventListener('click', function() {
					var answer = this.nextElementSibling;
					var expanded = this.getAttribute('aria-expanded') === 'true';
					this.setAttribute('aria-expanded', String(!expanded));
					if (expanded) {
						answer.setAttribute('hidden', '');
					} else {
						answer.removeAttribute('hidden');
					}
				});
				btn.addEventListener('keydown', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						this.click();
					}
				});
			});
		});
	" );
} );

// ---- Column: enqueue archive search script ----
add_action( 'wp_enqueue_scripts', function() {
	if ( ! is_post_type_archive( 'column' ) && ! is_tax( 'column_category' ) ) {
		return;
	}
	$search_js_path = get_stylesheet_directory() . '/assets/js/column-search.js';
	wp_enqueue_script(
		'column-archive-search',
		get_stylesheet_directory_uri() . '/assets/js/column-search.js',
		array(),
		file_exists( $search_js_path ) ? filemtime( $search_js_path ) : false,
		true
	);
} );

// ---- Column: enqueue floating tag panel toggle script ----
add_action( 'wp_enqueue_scripts', function() {
	if ( ! is_post_type_archive( 'column' ) && ! is_tax( 'column_category' ) ) {
		return;
	}
	$tag_float_js_path = get_stylesheet_directory() . '/assets/js/column-tag-float.js';
	wp_enqueue_script(
		'column-tag-float',
		get_stylesheet_directory_uri() . '/assets/js/column-tag-float.js',
		array(),
		file_exists( $tag_float_js_path ) ? filemtime( $tag_float_js_path ) : false,
		true
	);
} );

// ---- Column: archive-page CTA settings (Column menu > CTA設定) ----
// Site-wide URLs for the /column/ list page CTA, separate from the per-article ACF CTA fields.
add_action( 'admin_menu', function() {
	add_submenu_page(
		'edit.php?post_type=column',
		'コラム一覧CTA設定',
		'CTA設定',
		'manage_options',
		'column-cta-settings',
		'column_render_cta_settings_page'
	);
} );

function column_render_cta_settings_page() {
	if ( isset( $_POST['column_cta_nonce'] ) && wp_verify_nonce( $_POST['column_cta_nonce'], 'column_cta_save' ) ) {
		update_option( 'column_archive_cta_doc_url', esc_url_raw( wp_unslash( $_POST['column_archive_cta_doc_url'] ?? '' ) ) );
		update_option( 'column_archive_cta_contact_url', esc_url_raw( wp_unslash( $_POST['column_archive_cta_contact_url'] ?? '' ) ) );
		echo '<div class="updated"><p>保存しました。</p></div>';
	}

	$doc_url     = get_option( 'column_archive_cta_doc_url', '' );
	$contact_url = get_option( 'column_archive_cta_contact_url', '' );
	?>
	<div class="wrap">
		<h1>コラム一覧CTA設定</h1>
		<p>/column/ 一覧ページ・カテゴリ別アーカイブ・タグ別アーカイブの下部に表示するCTAボタンのリンク先です(記事ごとのCTAとは別設定)。現在ボタンは1つに統合されており、下記の「CTAボタンURL」のみが表示に使われます。</p>
		<form method="post">
			<?php wp_nonce_field( 'column_cta_save', 'column_cta_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th><label for="column_archive_cta_contact_url">CTAボタンURL</label></th>
					<td><input type="url" id="column_archive_cta_contact_url" name="column_archive_cta_contact_url" value="<?php echo esc_attr( $contact_url ); ?>" class="regular-text" placeholder="https://..."></td>
				</tr>
				<tr>
					<th><label for="column_archive_cta_doc_url">資料請求URL（現在未使用）</label></th>
					<td>
						<input type="url" id="column_archive_cta_doc_url" name="column_archive_cta_doc_url" value="<?php echo esc_attr( $doc_url ); ?>" class="regular-text" placeholder="https://...">
						<p class="description">現在フロント側では表示されません。将来ボタンを2つに戻す場合のために値を保持できます。</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// ---- Reform business page: noindex until real content/PDF assets are ready ----
// (no SEO plugin active on this site, so there's no per-page noindex meta box —
// this hardcodes it to the "reform-business" page slug specifically)
add_action( 'wp_head', function() {
	if ( is_page( 'reform-business' ) ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}
} );

// ---- Works: enqueue floating tag panel toggle script ----
add_action( 'wp_enqueue_scripts', function() {
	if ( ! is_post_type_archive( 'works' ) && ! is_tax( 'works_category' ) ) {
		return;
	}
	$js_path = get_stylesheet_directory() . '/assets/js/works-tag-float.js';
	wp_enqueue_script(
		'works-tag-float',
		get_stylesheet_directory_uri() . '/assets/js/works-tag-float.js',
		array(),
		file_exists( $js_path ) ? filemtime( $js_path ) : false,
		true
	);
} );

// ---- Works (事例) CPT: archive/taxonomy 9 posts per page ----
add_action( 'pre_get_posts', function( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'works' ) || is_tax( 'works_category' ) ) ) {
		$query->set( 'posts_per_page', 9 );
	}
} );

// ---- Works: works_category taxonomy ----
add_action( 'init', function() {
	register_taxonomy( 'works_category', 'works', array(
		'labels' => array(
			'name'          => '事例カテゴリ',
			'singular_name' => '事例カテゴリ',
			'menu_name'     => 'カテゴリ',
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'works/category', 'with_front' => false ),
	) );
} );

// Explicit rewrite rules — top priority (same pattern as column_category)
add_action( 'init', function() {
	add_rewrite_rule( '^works/category/([^/]+)/page/([0-9]+)/?$', 'index.php?works_category=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( '^works/category/([^/]+)/?$', 'index.php?works_category=$matches[1]', 'top' );
}, 20 );

// Seed 3 fixed categories (safe to run every load)
add_action( 'init', function() {
	$works_categories = array(
		'all'         => '全体',
		'hull-vision' => 'HULL VISION',
		'hull-space'  => 'HULL SPACE',
	);
	foreach ( $works_categories as $slug => $name ) {
		if ( ! term_exists( $slug, 'works_category' ) ) {
			wp_insert_term( $name, 'works_category', array( 'slug' => $slug ) );
		}
	}
}, 20 );

// ---- Works: shared render helpers (mirrors column_render_search_card / column_render_category_tabs) ----
if ( ! function_exists( 'works_render_search_card' ) ) {
	function works_render_search_card( $show_tags, $extra_class = '' ) {
		?>
		<article class="works-card<?php echo $extra_class ? ' ' . esc_attr( $extra_class ) : ''; ?>">
			<a href="<?php the_permalink(); ?>" class="works-card__link">
				<div class="works-card__thumb">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium', [ 'class' => 'works-card__img' ] ); ?>
					<?php else : ?>
						<div class="works-card__no-image"></div>
					<?php endif; ?>
				</div>
				<div class="works-card__body">
					<time class="works-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
						<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
					</time>
					<h3 class="works-card__title"><?php the_title(); ?></h3>
				</div>
			</a>
			<?php
			$card_terms = get_the_terms( get_the_ID(), 'works_tag' );
			if ( $show_tags && $card_terms && ! is_wp_error( $card_terms ) ) :
			?>
			<div class="works-card__tags">
				<?php foreach ( $card_terms as $ct ) : ?>
				<a href="<?php echo esc_url( get_term_link( $ct ) ); ?>" class="works-tag works-tag--small"><?php echo esc_html( $ct->name ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</article>
		<?php
	}
}

if ( ! function_exists( 'works_render_category_tabs' ) ) {
	function works_render_category_tabs( $active_slug = '' ) {
		$tab_order  = array( 'all', 'hull-vision', 'hull-space' );
		$categories = array();
		foreach ( $tab_order as $slug ) {
			$term = get_term_by( 'slug', $slug, 'works_category' );
			if ( $term ) {
				$categories[] = $term;
			}
		}
		if ( empty( $categories ) ) {
			return;
		}
		?>
		<nav class="works-category-tabs" aria-label="カテゴリで絞り込む">
			<?php foreach ( $categories as $category ) :
				if ( 'all' === $category->slug ) :
					$href      = get_post_type_archive_link( 'works' );
					$is_active = '' === $active_slug || 'all' === $active_slug;
				else :
					$href      = get_term_link( $category );
					$is_active = $active_slug === $category->slug;
				endif;
			?>
			<a href="<?php echo esc_url( $href ); ?>" class="works-category-tabs__item<?php echo $is_active ? ' is-active' : ''; ?>">
				<?php echo esc_html( $category->name ); ?>
			</a>
			<?php endforeach; ?>
		</nav>
		<?php
	}
}

// ---- Works: one-column layout on single posts ----
add_filter( 'lightning_is_layout_onecolumn', function( $is_onecolumn ) {
	if ( is_singular( 'works' ) ) {
		return true;
	}
	return $is_onecolumn;
} );

add_filter( 'lightning_is_subsection_display', function( $is_subsection ) {
	if ( is_singular( 'works' ) ) {
		return false;
	}
	return $is_subsection;
} );

add_filter( 'lightning_is_page_header', function( $is_page_header ) {
	if ( is_singular( 'works' ) ) {
		return false;
	}
	return $is_page_header;
} );

// ============================================================================
// Works landing pick: metabox on works edit screen + [hull_landing_works] shortcode
// ----------------------------------------------------------------------------
// Editors mark a works post to appear in a landing page's Works marquee
// (VISION landing 3090, 空間施工事業 landing 6834) and set a display order.
// The shortcode reads that meta, falls back to newest posts in the same
// works_category if not enough picked, and finally pads with placeholder
// cards so the marquee always renders visually.
// ============================================================================

add_action( 'add_meta_boxes', function() {
	add_meta_box(
		'hull_landing_pick',
		'ランディング掲載設定',
		'hull_landing_pick_metabox_render',
		'works',
		'side',
		'high'
	);
} );

function hull_landing_pick_metabox_render( $post ) {
	wp_nonce_field( 'hull_landing_pick_save', 'hull_landing_pick_nonce' );
	$pick  = (int) get_post_meta( $post->ID, '_hull_landing_pick', true );
	$order = get_post_meta( $post->ID, '_hull_landing_order', true );
	if ( '' === $order ) {
		$order = 100;
	}
	?>
	<p style="margin:0 0 10px;">
		<label>
			<input type="checkbox" name="hull_landing_pick" value="1" <?php checked( $pick, 1 ); ?>>
			<strong>ランディングページに掲載する</strong>
		</label>
	</p>
	<p style="margin:0 0 6px;">
		<label for="hull_landing_order">表示順（小さいほど先）</label><br>
		<input type="number" id="hull_landing_order" name="hull_landing_order" value="<?php echo esc_attr( $order ); ?>" step="1" min="0" style="width:100%;">
	</p>
	<p style="margin:0;color:#666;font-size:12px;">
		カテゴリ「HULL VISION」の記事は<code>/digital-signage</code>のWorksに、<br>
		「HULL SPACE」の記事は<code>/reform-business</code>のWorksに表示されます。
	</p>
	<?php
}

add_action( 'save_post_works', function( $post_id ) {
	if ( ! isset( $_POST['hull_landing_pick_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['hull_landing_pick_nonce'], 'hull_landing_pick_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_hull_landing_pick', isset( $_POST['hull_landing_pick'] ) ? 1 : 0 );
	if ( isset( $_POST['hull_landing_order'] ) ) {
		update_post_meta( $post_id, '_hull_landing_order', (int) $_POST['hull_landing_order'] );
	}
} );

// Admin list column so editors can see at a glance which posts are picked and their order
add_filter( 'manage_works_posts_columns', function( $cols ) {
	$cols['hull_landing'] = 'ランディング';
	return $cols;
} );
add_action( 'manage_works_posts_custom_column', function( $col, $post_id ) {
	if ( 'hull_landing' !== $col ) {
		return;
	}
	$pick  = (int) get_post_meta( $post_id, '_hull_landing_pick', true );
	$order = get_post_meta( $post_id, '_hull_landing_order', true );
	echo $pick ? '<span style="color:#2271b1;">● 掲載 (順:' . esc_html( $order === '' ? '-' : $order ) . ')</span>' : '<span style="color:#999;">—</span>';
}, 10, 2 );

// Shortcode: [hull_landing_works cat="hull-vision" count="6"]
add_shortcode( 'hull_landing_works', function( $atts ) {
	$atts = shortcode_atts( array(
		'cat'   => 'hull-vision',
		'count' => 6,
	), $atts, 'hull_landing_works' );
	$cat   = sanitize_key( $atts['cat'] );
	$count = max( 1, (int) $atts['count'] );

	// Step 1: picked posts, ordered by _hull_landing_order asc, tie-broken by date desc
	$picked = get_posts( array(
		'post_type'      => 'works',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'tax_query'      => array( array(
			'taxonomy' => 'works_category',
			'field'    => 'slug',
			'terms'    => $cat,
		) ),
		'meta_query'     => array( array(
			'key'   => '_hull_landing_pick',
			'value' => '1',
		) ),
		'meta_key'       => '_hull_landing_order',
		'orderby'        => array( 'meta_value_num' => 'ASC', 'date' => 'DESC' ),
		'fields'         => 'ids',
	) );

	// Step 2: fill from newest in same category if short
	if ( count( $picked ) < $count ) {
		$fill = get_posts( array(
			'post_type'      => 'works',
			'post_status'    => 'publish',
			'posts_per_page' => $count - count( $picked ),
			'post__not_in'   => $picked,
			'tax_query'      => array( array(
				'taxonomy' => 'works_category',
				'field'    => 'slug',
				'terms'    => $cat,
			) ),
			'orderby'        => 'date',
			'order'          => 'DESC',
			'fields'         => 'ids',
		) );
		$picked = array_merge( $picked, $fill );
	}

	// Step 3: pad with placeholder objects if still short (keeps the marquee visually full)
	$placeholder_pool = array(
		'hull-vision' => array(
			'/wp-content/uploads/2026/08/placeholder-vision-works-01.png',
			'/wp-content/uploads/2026/08/placeholder-vision-works-02.png',
			'/wp-content/uploads/2026/08/placeholder-vision-works-03.png',
			'/wp-content/uploads/2026/08/placeholder-vision-works-04.png',
		),
		'hull-space' => array(
			'/wp-content/uploads/2026/08/시공.png',
			'/wp-content/uploads/2026/08/실현-1.png',
			'/wp-content/uploads/2026/08/유지운영.png',
			'/wp-content/uploads/2026/08/창조-1.png',
		),
	);
	$ph = isset( $placeholder_pool[ $cat ] ) ? $placeholder_pool[ $cat ] : array();

	// Build one card's markup
	$render_card = function( $post_id, $is_mirror = false ) use ( $ph ) {
		$aria = $is_mirror ? ' aria-hidden="true"' : '';
		if ( is_int( $post_id ) && $post_id > 0 ) {
			$permalink = get_permalink( $post_id );
			$title     = get_the_title( $post_id );
			$thumb     = get_the_post_thumbnail_url( $post_id, 'medium' );
			if ( ! $thumb ) {
				// Fallback: try any placeholder from the pool so cards without featured image still show something
				$thumb = ! empty( $ph ) ? home_url( $ph[ ( $post_id ) % count( $ph ) ] ) : 'https://placehold.co/260x195/cccccc/888888?text=Works';
			}
			$alt = $is_mirror ? '' : esc_attr( $title );
			return sprintf(
				'<div class="reform-works-card"%s><a href="%s" class="reform-works-card-link"><img src="%s" alt="%s" loading="lazy"/><p class="reform-works-caption">%s</p></a></div>',
				$aria,
				esc_url( $permalink ),
				esc_url( $thumb ),
				$alt,
				esc_html( $title )
			);
		}
		// Placeholder (no link)
		$idx = is_array( $post_id ) ? (int) $post_id['idx'] : 0;
		$src = ! empty( $ph ) ? home_url( $ph[ $idx % count( $ph ) ] ) : 'https://placehold.co/260x195/cccccc/888888?text=Works';
		return sprintf(
			'<div class="reform-works-card"%s><img src="%s" alt="" loading="lazy"/><p class="reform-works-caption">導入事例</p></div>',
			$aria,
			esc_url( $src )
		);
	};

	// Pad with placeholder tokens up to $count
	$cards = $picked;
	$pad_idx = 0;
	while ( count( $cards ) < $count ) {
		$cards[] = array( 'placeholder' => true, 'idx' => $pad_idx );
		$pad_idx++;
	}

	// Render real strip + aria-hidden mirror strip
	$html = '<div class="reform-works-track"><div class="reform-works-strip">';
	foreach ( $cards as $item ) {
		$html .= $render_card( is_array( $item ) ? $item : (int) $item, false );
	}
	foreach ( $cards as $item ) {
		$html .= $render_card( is_array( $item ) ? $item : (int) $item, true );
	}
	$html .= '</div></div>';

	return $html;
} );

// Style hook: make the card <a> reset link color/underline so the caption stays neutral.
// Kept inline (not in Additional CSS post 2641) so we don't need to touch the CSS lock.
add_action( 'wp_enqueue_scripts', function() {
	wp_register_style( 'hull-landing-works-inline', false );
	wp_enqueue_style( 'hull-landing-works-inline' );
	wp_add_inline_style( 'hull-landing-works-inline', '
		.reform-works-card-link{display:block;color:inherit;text-decoration:none;}
		.reform-works-card-link:hover .reform-works-caption{opacity:.75;}
		.reform-works-card-link:focus{outline:2px solid #2271b1;outline-offset:2px;}
	' );
} );
