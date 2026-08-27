<?php
/**
 * works_tag taxonomy archive template (/works/tag/{tag}/)
 * Mirrored from taxonomy-column_tag.php — column → works throughout.
 */

$current_term = get_queried_object();
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

		<h2 class="works-archive__current-tag">
			<?php echo esc_html( $current_term->name ); ?>
		</h2>

		<?php if ( have_posts() ) : ?>

			<div class="works-archive__grid">
				<?php while ( have_posts() ) : the_post(); ?>
				<article class="works-card">
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
					if ( $card_terms && ! is_wp_error( $card_terms ) ) :
					?>
					<div class="works-card__tags">
						<?php foreach ( $card_terms as $ct ) : ?>
						<a href="<?php echo esc_url( get_term_link( $ct ) ); ?>" class="works-tag works-tag--small"><?php echo esc_html( $ct->name ); ?></a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</article>
				<?php endwhile; ?>
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
			<div class="well"><p>このタグの記事がありません。</p></div>
		<?php endif; ?>

	</div><!-- /.mainSection -->

	<div class="<?php lightning_the_class_name( 'sideSection' ); ?>">
		<aside class="works-archive__sidebar">
			<h2 class="works-archive__sidebar-heading">関連ワードから探す</h2>
			<ul class="works-archive__tag-list">
				<?php
				$all_tags = get_terms( array( 'taxonomy' => 'works_tag', 'hide_empty' => false ) );
				if ( $all_tags && ! is_wp_error( $all_tags ) ) :
					foreach ( $all_tags as $tag ) :
						$is_active = ( $tag->term_id === $current_term->term_id );
						$tag_link  = $is_active ? get_post_type_archive_link( 'works' ) : get_term_link( $tag );
				?>
				<li>
					<a href="<?php echo esc_url( $tag_link ); ?>" class="works-archive__tag-link<?php echo $is_active ? ' is-active' : ''; ?>">
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
