<?php
/**
 * column_tag taxonomy archive template (/column/tag/{tag}/)
 * Same layout as archive-column.php, plus a current-tag heading
 * and an active state on the matching sidebar tag link.
 * Picked up via WP standard template hierarchy (taxonomy-column_tag.php).
 */

$current_term = get_queried_object();
?>
<?php lightning_get_template_part( 'header' ); ?>

<?php
// 다른 페이지들(archive-column.php, taxonomy-column_category.php)과 동일한
// 히어로 레이아웃(좌측 타이틀+설명, 우측 사진)을 커스텀 마크업으로 구성.
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

<?php // No breadcrumb on column tag archives — see archive-column.php, taxonomy-column_category.php, single-column.php. ?>

<?php do_action( 'lightning_breadcrumb_after' ); ?>

<div class="<?php lightning_the_class_name( 'siteContent' ); ?>">
<div class="container">
<div class="row">

	<div class="<?php lightning_the_class_name( 'mainSection' ); ?>" id="main" role="main">

		<h2 class="column-archive__current-tag">
			<?php echo esc_html( $current_term->name ); ?>
		</h2>

		<?php if ( have_posts() ) : ?>

			<div class="column-archive__grid">
				<?php while ( have_posts() ) : the_post(); ?>
				<article class="column-card">
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
					// Tags render outside .column-card__link so they never nest inside
					// the card-wide link (nested <a> would send tag clicks to the article).
					$card_terms = get_the_terms( get_the_ID(), 'column_tag' );
					if ( $card_terms && ! is_wp_error( $card_terms ) ) :
					?>
					<div class="column-card__tags">
						<?php foreach ( $card_terms as $ct ) : ?>
						<a href="<?php echo esc_url( get_term_link( $ct ) ); ?>" class="column-tag column-tag--small"><?php echo esc_html( $ct->name ); ?></a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</article>
				<?php endwhile; ?>
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
			<div class="well"><p>このタグの記事がありません。</p></div>
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

	<div class="<?php lightning_the_class_name( 'sideSection' ); ?>">
		<aside class="column-archive__sidebar">
			<h2 class="column-archive__sidebar-heading">関連ワードから探す</h2>
			<ul class="column-archive__tag-list">
				<?php
				$all_tags = get_terms( array( 'taxonomy' => 'column_tag', 'hide_empty' => false ) );
				if ( $all_tags && ! is_wp_error( $all_tags ) ) :
					foreach ( $all_tags as $tag ) :
						$is_active = ( $tag->term_id === $current_term->term_id );
						$tag_link  = $is_active ? get_post_type_archive_link( 'column' ) : get_term_link( $tag );
				?>
				<li>
					<a href="<?php echo esc_url( $tag_link ); ?>" class="column-archive__tag-link<?php echo $is_active ? ' is-active' : ''; ?>">
						<?php echo esc_html( $tag->name ); ?>
					</a>
				</li>
				<?php
					endforeach;
				endif;
				?>
			</ul>
		</aside>
	</div><!-- /.sideSection -->

</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.siteContent -->

<?php lightning_get_template_part( 'footer' ); ?>
