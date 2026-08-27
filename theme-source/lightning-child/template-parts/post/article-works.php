<?php
/**
 * Single works post article template.
 * Mirrors article-column.php's header / meta / thumbnail / body / back-link
 * structure so /works/ single posts render at the same size as /column/ ones
 * instead of the parent theme's full-width .icatchCont fallback.
 * ACF-driven column sections (lead / FAQ / summary / CTA / related) are
 * intentionally omitted — works posts don't use those fields.
 */

$show_tags = true;
$terms     = get_the_terms( get_the_ID(), 'works_tag' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-full works-single' ); ?>>

	<header class="works-single__header">

		<h1 class="entry-title works-single__title"><?php the_title(); ?></h1>

		<div class="works-single__meta">
			<span class="works-single__date">
				<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
					公開日：<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
				</time>
			</span>
			<?php if ( get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' ) ) : ?>
			<span class="works-single__updated">
				<time datetime="<?php echo esc_attr( get_the_modified_date( 'Y-m-d' ) ); ?>">
					更新日：<?php echo esc_html( get_the_modified_date( 'Y.m.d' ) ); ?>
				</time>
			</span>
			<?php endif; ?>
		</div>

		<?php if ( $show_tags && $terms && ! is_wp_error( $terms ) ) : ?>
		<div class="works-single__tags">
			<?php foreach ( $terms as $term ) : ?>
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="works-tag">
				<?php echo esc_html( $term->name ); ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
		<div class="works-single__thumbnail">
			<?php the_post_thumbnail( 'large', [ 'class' => 'works-single__thumbnail-img' ] ); ?>
		</div>
		<?php endif; ?>

	</header>

	<div class="entry-body works-single__body">
		<?php the_content(); ?>
	</div>

	<div class="works-back">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'works' ) ); ?>" class="works-back__link">
			← 事例一覧へ戻る
		</a>
	</div>

	<?php do_action( 'lightning_comment_before' ); ?>
	<?php comments_template( '', true ); ?>
	<?php do_action( 'lightning_comment_after' ); ?>

</article>
