<?php
/**
 * Single column article entry template
 * Loaded by Lightning G2's content.php via get_template_part('template-parts/post/article', get_post_type())
 */

// 태그 기능 ON/OFF 스위치. 기사 30개 이상 쌓이면 true로 변경
$show_tags = true;

$lead_text   = function_exists('get_field') ? get_field('lead_text')                   : '';
$summary     = function_exists('get_field') ? get_field('summary')                     : '';
$cta_con_url = function_exists('get_field') ? get_field('cta_contact_url')             : '';
if ( ! $cta_con_url ) {
	// 記事別URL未設定時は、コラム一覧CTA設定の共通URLにフォールバック
	$cta_con_url = get_option( 'column_archive_cta_contact_url', '' );
}

// Build FAQ items (only include pairs where both Q and A are filled)
$faq_items = [];
if ( function_exists('get_field') ) {
	for ( $i = 1; $i <= 5; $i++ ) {
		$q = get_field( 'faq_question_' . $i );
		$a = get_field( 'faq_answer_' . $i );
		if ( $q && $a ) {
			$faq_items[] = [ 'q' => $q, 'a' => $a ];
		}
	}
}

// Fetch related articles (same column_tag, exclude current)
$related_query = null;
$terms         = get_the_terms( get_the_ID(), 'column_tag' );
if ( $terms && ! is_wp_error( $terms ) ) {
	$term_ids      = wp_list_pluck( $terms, 'term_id' );
	$related_query = new WP_Query( [
		'post_type'      => 'column',
		'posts_per_page' => 3,
		'post__not_in'   => [ get_the_ID() ],
		'tax_query'      => [ [
			'taxonomy' => 'column_tag',
			'field'    => 'term_id',
			'terms'    => $term_ids,
		] ],
		'no_found_rows'  => true,
	] );
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-full column-single' ); ?>>

	<!-- ========== HEADER: タイトル / 日付 / タグ / サムネイル ========== -->
	<header class="column-single__header">

		<h1 class="entry-title column-single__title"><?php the_title(); ?></h1>

		<div class="column-single__meta">
			<span class="column-single__date">
				<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
					公開日：<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
				</time>
			</span>
			<?php if ( get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' ) ) : ?>
			<span class="column-single__updated">
				<time datetime="<?php echo esc_attr( get_the_modified_date( 'Y-m-d' ) ); ?>">
					更新日：<?php echo esc_html( get_the_modified_date( 'Y.m.d' ) ); ?>
				</time>
			</span>
			<?php endif; ?>
		</div>

		<?php if ( $show_tags && $terms && ! is_wp_error( $terms ) ) : ?>
		<div class="column-single__tags">
			<?php foreach ( $terms as $term ) : ?>
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="column-tag">
				<?php echo esc_html( $term->name ); ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
		<div class="column-single__thumbnail">
			<?php the_post_thumbnail( 'large', [ 'class' => 'column-single__thumbnail-img' ] ); ?>
		</div>
		<?php endif; ?>

	</header><!-- /.column-single__header -->

	<!-- ========== リード文 ========== -->
	<?php if ( $lead_text ) : ?>
	<div class="column-single__lead">
		<p><?php echo nl2br( esc_html( $lead_text ) ); ?></p>
	</div>
	<?php endif; ?>

	<!-- ========== 目次（Easy Table of Contents が自動挿入） ========== -->

	<!-- ========== 本文 ========== -->
	<div class="entry-body column-single__body">
		<?php the_content(); ?>
	</div>

	<!-- ========== FAQ アコーディオン ========== -->
	<?php if ( ! empty( $faq_items ) ) : ?>
	<section class="column-faq">
		<h2 class="column-faq__heading">よくある質問</h2>
		<dl class="column-faq__list">
			<?php foreach ( $faq_items as $item ) : ?>
			<div class="column-faq__item">
				<dt class="column-faq__question" role="button" aria-expanded="false" tabindex="0">
					<span class="faq-icon faq-icon--q">Q</span>
					<span class="faq-question-text"><?php echo esc_html( $item['q'] ); ?></span>
					<span class="faq-toggle-icon" aria-hidden="true"></span>
				</dt>
				<dd class="column-faq__answer" hidden>
					<span class="faq-icon faq-icon--a">A</span>
					<div class="faq-answer-text"><?php echo nl2br( esc_html( $item['a'] ) ); ?></div>
				</dd>
			</div>
			<?php endforeach; ?>
		</dl>
	</section>
	<?php endif; ?>

	<!-- ========== まとめ ========== -->
	<?php if ( $summary ) : ?>
	<section class="column-summary">
		<h2 class="column-summary__heading">まとめ</h2>
		<div class="column-summary__body">
			<?php echo nl2br( esc_html( $summary ) ); ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- ========== CTA ========== -->
	<?php if ( $cta_con_url ) : ?>
	<div class="column-cta">
		<p class="column-cta__text">デジタルサイネージの導入をお考えの方は、<br>お気軽にご相談ください。</p>
		<div class="column-cta__buttons">
			<a href="<?php echo esc_url( $cta_con_url ); ?>" class="column-cta__btn column-cta__btn--contact column-cta__btn--single">無料の資料請求・お見積もり依頼はこちら</a>
		</div>
	</div>
	<?php endif; ?>

	<!-- ========== 関連コラム ========== -->
	<?php if ( $related_query && $related_query->have_posts() ) : ?>
	<section class="column-related">
		<h2 class="column-related__heading">関連コラム</h2>
		<div class="column-related__grid">
			<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
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
				if ( $show_tags && $card_terms && ! is_wp_error( $card_terms ) ) :
				?>
				<div class="column-card__tags">
					<?php foreach ( $card_terms as $ct ) : ?>
					<a href="<?php echo esc_url( get_term_link( $ct ) ); ?>" class="column-tag column-tag--small"><?php echo esc_html( $ct->name ); ?></a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- ========== コラム一覧へ戻る ========== -->
	<div class="column-back">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'column' ) ); ?>" class="column-back__link">
			← コラム一覧へ戻る
		</a>
	</div>

	<?php do_action( 'lightning_comment_before' ); ?>
	<?php comments_template( '', true ); ?>
	<?php do_action( 'lightning_comment_after' ); ?>

</article><!-- /#post-<?php the_ID(); ?> -->
