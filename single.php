<?php
/**
 * nopost — single.php
 */
get_header();

if ( ! have_posts() ) {
    get_footer();
    exit;
}

the_post();
?>

<main class="np-single-wrap">

  <article class="np-single" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="np-single__inner">

      <!-- EN-TÊTE -->
      <header class="np-single-header">
        <?php
        $cats = get_the_category();
        if ( $cats ) : ?>
          <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="np-cat-badge">
            <?php echo esc_html( $cats[0]->name ); ?>
          </a>
        <?php endif; ?>

        <h1 class="np-single-title"><?php the_title(); ?></h1>



        <div class="np-single-meta">
          <span class="np-meta-date">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <?php echo get_the_date( '' ); ?>
          </span>
          <span class="np-meta-sep">·</span>
          <span class="np-meta-time">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <?php echo nopost_reading_time(); ?>
          </span>
          <span class="np-meta-sep">·</span>
          <span class="np-meta-author">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <?php the_author(); ?>
          </span>
        </div>

        <hr class="np-single-rule">
      </header>

      <!-- IMAGE À LA UNE -->
      <?php if ( has_post_thumbnail() ) : ?>
      <div class="np-single-thumbnail">
        <?php the_post_thumbnail( 'full', [ 'class' => 'np-single-thumbnail__img' ] ); ?>
      </div>
      <?php endif; ?>

      <!-- PUB début article -->
      <?php nopost_ad( 'nopost_ad_article' ); ?>

      <!-- CONTENU -->
      <div class="np-single-content entry-content">
        <?php the_content(); ?>
      </div>

      <!-- TAGS -->
      <?php
      $tags = get_the_tags();
      if ( $tags ) : ?>
      <div class="np-single-tags">
        <?php foreach ( $tags as $tag ) : ?>
          <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="np-tag">
            #<?php echo esc_html( $tag->name ); ?>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- PARTAGE -->
      <div class="np-single-share">
        <span class="np-single-share__label"><?php esc_html_e( 'Partager', 'nopost' ); ?></span>
        <a class="np-share-btn"
           href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
           target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Partager sur X / Twitter', 'nopost' ); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.259 5.627 5.905-5.627Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <button class="np-share-btn np-share-copy" data-url="<?php echo esc_attr( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copier le lien', 'nopost' ); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        </button>
      </div>

      <!-- COMMENTAIRES -->
      <?php comments_template(); ?>

    </div>
  </article>

  <!-- ARTICLES LIÉS -->
  <?php
  $cats_obj = get_the_category();
  if ( $cats_obj ) {
      $related = new WP_Query( [
          'cat'            => $cats_obj[0]->term_id,
          'posts_per_page' => 3,
          'post__not_in'   => [ get_the_ID() ],
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
      ] );
      if ( $related->have_posts() ) :
  ?>
  <section class="np-related">
    <div class="np-section__inner">
      <div class="np-section-header">
        <h2 class="np-section-title"><?php esc_html_e( 'Dans la même catégorie', 'nopost' ); ?></h2>
      </div>
      <div class="np-grid">
        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
        <article class="np-card">
          <?php if ( has_post_thumbnail() ) : ?>
          <div class="np-card__image">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
          </div>
          <?php endif; ?>
          <div class="np-card__body">
            <?php
            $rcats = get_the_category();
            if ( $rcats ) : ?>
              <a href="<?php echo esc_url( get_category_link( $rcats[0]->term_id ) ); ?>" class="np-cat-badge np-cat-badge--sm">
                <?php echo esc_html( $rcats[0]->name ); ?>
              </a>
            <?php endif; ?>
            <h3 class="np-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if ( has_excerpt() ) : ?><p class="np-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p><?php endif; ?>
            <div class="np-card__meta">
              <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
              <span class="np-meta-sep">·</span>
              <span class="np-meta-time"><?php echo nopost_reading_time(); ?></span>
            </div>
          </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
  <?php
      endif;
  }
  ?>

</main>

<?php get_footer(); ?>
