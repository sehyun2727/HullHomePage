<?php
/**
 * Works post type archive template (/works/)
 * Mirrored from archive-column.php — column → works throughout.
 */

// 태그 기능 ON/OFF 스위치. 기사 30개 이상 쌓이면 true로 변경
$show_tags = true;
?>
<?php lightning_get_template_part( 'header' ); ?>

<?php
if ( lightning_is_page_header() ) :
?>
<div class="works-hero">
	<div class="works-hero__image"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="works-hero__text">
					<h1 class="works-hero__title">事例</h1>
					<p class="works-hero__desc">HULLのこれまでの事例をご紹介します。</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php do_action( 'lightning_breadcrumb_before' ); ?>
<?php do_action( 'lightning_breadcrumb_after' ); ?>

<div class="<?php lightning_the_class_name( 'siteContent' ); ?>">
<div class="container">
<div class="row">

	<div class="<?php lightning_the_class_name( 'mainSection' ); ?>" id="main" role="main">

		<?php works_render_category_tabs( '' ); ?>

		<div class="works-search-bar">
			<h2 class="works-search-bar__title">事例一覧</h2>
			<div class="works-search__form">
				<input type="text" id="works-search-input" class="works-search__input" placeholder="検索..." aria-label="記事を検索">
				<button type="button" id="works-search-btn" class="works-search__btn" aria-label="検索する">
					<svg class="works-search__icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<circle cx="7" cy="7" r="5.5" stroke="currentColor" stroke-width="1.5"/>
						<line x1="11.3" y1="11.3" x2="15" y2="15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</button>
			</div>
		</div>
		<p id="works-search-noresults" class="works-search__noresults" hidden>該当する記事が見つかりませんでした。</p>

		<?php if ( $show_tags ) : ?>
		<div class="works-tag-float-anchor">
			<div class="works-tag-float" data-works-tag-float>
				<button type="button" class="works-tag-float__handle" aria-expanded="false" aria-controls="works-tag-float-panel">
					<span class="works-tag-float__handle-text">関連ワードから探す</span>
				</button>
				<div class="works-tag-float__panel" id="works-tag-float-panel">
					<div class="works-tag-float__panel-inner">
						<div class="works-tag-float__panel-head">
							<span class="works-tag-float__heading">関連ワードから探す</span>
							<button type="button" class="works-tag-float__close" aria-label="閉じる">&times;</button>
						</div>
						<ul class="works-tag-float__list">
							<?php
							$float_tags = get_terms( array( 'taxonomy' => 'works_tag', 'hide_empty' => false ) );
							if ( $float_tags && ! is_wp_error( $float_tags ) ) :
								foreach ( $float_tags as $float_tag ) :
							?>
							<li>
								<a href="<?php echo esc_url( get_term_link( $float_tag ) ); ?>" class="works-tag-float__link">
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

			<div class="works-archive__grid card-grid">
				<?php
				$shown_ids = array();
				while ( have_posts() ) : the_post();
					$shown_ids[] = get_the_ID();
					works_render_search_card( $show_tags );
				endwhile;
				?>
				<?php
				$search_extra_query = new WP_Query( array(
					'post_type'      => 'works',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'post__not_in'   => ! empty( $shown_ids ) ? $shown_ids : array( 0 ),
					'orderby'        => 'date',
					'order'          => 'DESC',
					'no_found_rows'  => true,
				) );
				if ( $search_extra_query->have_posts() ) :
					while ( $search_extra_query->have_posts() ) : $search_extra_query->the_post();
						works_render_search_card( $show_tags, 'works-card--extra' );
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div><!-- /.works-archive__grid -->

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
			<div class="well"><p>事例がありません。</p></div>
		<?php endif; ?>

	</div><!-- /.mainSection -->

</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.siteContent -->

<?php lightning_get_template_part( 'footer' ); ?>
