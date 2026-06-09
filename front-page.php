<?php
/**
 * nopost — front-page.php
 */
get_header();

$featured = nopost_get_latest( 1, 0 );
$featured_id = 0;
?>

<main id="main-content">

<?php if ( $featured->have_posts() ) :
  $featured->the_post();
  $featured_id = get_the_ID();
?>

<!-- FEATURED ARTICLE -->
<section class="np-featured">
  <div class="np-featured__inner">

    <?php if ( has_post_thumbnail() ) : ?>
    <div class="np-featured__image">
      <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'fetchpriority' => 'high' ] ); ?>
    </div>
    <?php endif; ?>

    <div class="np-featured__content">
      <?php
      $cats = get_the_category();
      if ( $cats ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="np-cat-badge">
          <?php echo esc_html( $cats[0]->name ); ?>
        </a>
      <?php endif; ?>

      <h1 class="np-featured__title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h1>

      <?php if ( has_excerpt() ) : ?>
      <p class="np-featured__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
      <?php endif; ?>

      <div class="np-featured__meta">
        <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
        <span class="np-meta-sep">·</span>
        <span class="np-meta-time"><?php echo nopost_reading_time(); ?></span>
      </div>

      <a href="<?php the_permalink(); ?>" class="np-btn-read">
        <?php esc_html_e( 'Lire', 'nopost' ); ?>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
      </a>
    </div>

  </div>
</section>

<?php wp_reset_postdata(); endif; ?>

<!-- PUB après featured -->
<?php nopost_ad( 'nopost_ad_hero' ); ?>

<!-- GRID ARTICLES -->
<?php
$grid = nopost_get_latest( 9, 1 );
if ( $grid->have_posts() ) : ?>
<section class="np-section">
  <div class="np-section__inner">
    <div class="np-section-header">
      <h2 class="np-section-title"><?php esc_html_e( 'Derniers articles', 'nopost' ); ?></h2>
    </div>

    <div class="np-grid">
      <?php $card_idx = 0; while ( $grid->have_posts() ) : $grid->the_post(); $card_idx++; ?>

      <article class="np-card reveal">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="np-card__image">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium_large' ); ?>
          </a>
        </div>
        <?php endif; ?>
        <div class="np-card__body">
          <?php
          $cats = get_the_category();
          if ( $cats ) : ?>
            <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="np-cat-badge np-cat-badge--sm">
              <?php echo esc_html( $cats[0]->name ); ?>
            </a>
          <?php endif; ?>
          <h3 class="np-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <?php if ( has_excerpt() ) : ?><p class="np-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p><?php endif; ?>
          <div class="np-card__meta">
            <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
            <span class="np-meta-sep">·</span>
            <span class="np-meta-time"><?php echo nopost_reading_time(); ?></span>
          </div>
        </div>
      </article>

      <?php if ( 3 === $card_idx ) nopost_ad( 'nopost_ad_grid', 'np-ad--grid-span' ); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php nopost_pagination(); ?>
  </div>
</section>
<?php endif; ?>

</main>

<?php get_footer(); ?>
