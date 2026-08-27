<?php
/**
 * Column category taxonomy archive template (/column/category/{slug}/)
 * Mirrors archive-column.php but scopes both the visible page and the
 * hidden "search-extra" pass to the current column_category term, and
 * shows the category filter tabs with this term marked active.
 */

// 태그 기능 ON/OFF 스위치. 기사 30개 이상 쌓이면 true로 변경
$show_tags = true;

$column_category_term = get_queried_object();
?>
<?php lightning_get_template_part( 'header' ); ?>

<?php
// 다른 페이지들(デジタルサイネージ 등)과 동일한 히어로 레이아웃
// (좌측 타이틀+설명, 우측 사진)을 커스텀 마크업으로 구성.
// archive-column.php와 동일한 마크업/CSS(.column-hero)를 공유.
if ( lightning_is_page_header() ) :
?>
<div class="column-hero">
	<div class="column-hero__image"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="column-hero__text">
					<h1 class="column-hero__title">コラム</h1>
					<p class="column-hero__desc">デジタルサイネージやサイン施工、輸入雑貨販売からリフォームまで、<br>幅広い分野に関する知識や事例をわかりやすくご紹介します。</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php do_action( 'lightning_breadcrumb_before' ); ?>

<?php // No breadcrumb on column category archives — see archive-column.php, taxonomy-column_tag.php, single-column.php. ?>

<?php do_action( 'lightning_breadcrumb_after' ); ?>

<div class="<?php lightning_the_class_name( 'siteContent' ); ?>">
<div class="container">
<div class="row">

	<div class="<?php lightning_the_class_name( 'mainSection' ); ?>" id="main" role="main">

		<?php
		// column_render_search_card() and column_render_category_tabs() live in functions.php.
		column_render_category_tabs( $column_category_term instanceof WP_Term ? $column_category_term->slug : '' );
		?>

		<div class="column-search-bar">
			<h2 class="column-search-bar__title"><?php echo esc_html( $column_category_term instanceof WP_Term ? $column_category_term->name : 'コラム一覧' ); ?></h2>
			<div class="column-search__form">
				<input type="text" id="column-search-input" class="column-search__input" placeholder="検索..." aria-label="記事を検索">
				<button type="button" id="column-search-btn" class="column-search__btn" aria-label="検索する">
					<svg class="column-search__icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<circle cx="7" cy="7" r="5.5" stroke="currentColor" stroke-width="1.5"/>
						<line x1="11.3" y1="11.3" x2="15" y2="15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</button>
			</div>
		</div>
		<p id="column-search-noresults" class="column-search__noresults" hidden>該当する記事が見つかりませんでした。</p>

		<?php if ( $show_tags ) : ?>
		<div class="column-tag-float-anchor">
			<div class="column-tag-float" data-column-tag-float>
				<button type="button" class="column-tag-float__handle" aria-expanded="false" aria-controls="column-tag-float-panel">
					<span class="column-tag-float__handle-text">関連ワードから探す</span>
				</button>
				<div class="column-tag-float__panel" id="column-tag-float-panel">
					<div class="column-tag-float__panel-inner">
						<div class="column-tag-float__panel-head">
							<span class="column-tag-float__heading">関連ワードから探す</span>
							<button type="button" class="column-tag-float__close" aria-label="閉じる">&times;</button>
						</div>
						<ul class="column-tag-float__list">
							<?php
							$float_tags = get_terms( array( 'taxonomy' => 'column_tag', 'hide_empty' => false ) );
							if ( $float_tags && ! is_wp_error( $float_tags ) ) :
								foreach ( $float_tags as $float_tag ) :
							?>
							<li>
								<a href="<?php echo esc_url( get_term_link( $float_tag ) ); ?>" class="column-tag-float__link">
									<?php echo esc_html( $float_tag->name ); ?>
								</a>
							</li>
							<?php
								endforeach;
							endif;
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<div class="column-archive__grid card-grid">
				<?php
				$shown_ids = array();
				while ( have_posts() ) : the_post();
					$shown_ids[] = get_the_ID();
					column_render_search_card( $show_tags );
				endwhile;
				?>
				<?php
				// Every other published post in THIS category, pre-rendered but hidden
				// (see .column-card--extra in style.css) so search stays scoped to the category.
				$search_extra_query = new WP_Query( array(
					'post_type'      => 'column',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'post__not_in'   => ! empty( $shown_ids ) ? $shown_ids : array( 0 ),
					'orderby'        => 'date',
					'order'          => 'DESC',
					'no_found_rows'  => true,
					'tax_query'      => array( array(
						'taxonomy' => 'column_category',
						'field'    => 'term_id',
						'terms'    => $column_category_term instanceof WP_Term ? $column_category_term->term_id : 0,
					) ),
				) );
				if ( $search_extra_query->have_posts() ) :
					while ( $search_extra_query->have_posts() ) : $search_extra_query->the_post();
						column_render_search_card( $show_tags, 'column-card--extra' );
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div><!-- /.column-archive__grid -->

			<?php
			the_posts_pagination( array(
				'mid_size'           => 1,
				'prev_text'          => '&laquo;',
				'next_text'          => '&raquo;',
				'type'               => 'list',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'lightning' ) . ' </span>',
			) );
			?>

		<?php else : ?>
			<div class="well"><p>コラム記事がありません。</p></div>
		<?php endif; ?>

		<!-- ========== 下部CTA（Column メニュー > CTA設定 で管理） ========== -->
		<?php
		$archive_cta_contact_url = get_option( 'column_archive_cta_contact_url', '' );
		?>
		<?php if ( $archive_cta_contact_url ) : ?>
		<div class="column-cta column-archive__cta">
			<p class="column-cta__text">デジタルサイネージの導入をお考えの方は、<br>お気軽にご相談ください。</p>
			<div class="column-cta__buttons">
				<a href="<?php echo esc_url( $archive_cta_contact_url ); ?>" class="column-cta__btn column-cta__btn--contact column-cta__btn--single">無料の資料請求・お見積もり依頼はこちら</a>
			</div>
		</div>
		<?php endif; ?>

	</div><!-- /.mainSection -->

</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.siteContent -->

<?php lightning_get_template_part( 'footer' ); ?>
