<?php
/**
 * nopost — front-page.php
 */
get_header();

$paged    = max( 1, (int) get_query_var( 'paged' ) );
$per_page = 9;

// Exclude featured (1 post) from total count for pagination
$total_published = (int) wp_count_posts( 'post' )->publish;
$total_pages     = max( 1, (int) ceil( ( $total_published - 1 ) / $per_page ) );

// Grid offset: page 1 skips featured (1 post), then 9 per page
$grid_offset = ( $paged - 1 ) * $per_page + 1;
?>

<main id="main-content">

<?php if ( 1 === $paged ) :
  $featured    = nopost_get_latest( 1, 0 );
  $featured_id = 0;

  if ( $featured->have_posts() ) :
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

<?php endif; // end page 1 featured block ?>

<!-- GRID ARTICLES -->
<?php
$grid = nopost_get_latest( $per_page, $grid_offset );
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

    <?php nopost_pagination( $total_pages ); ?>
  </div>
</section>
<?php endif; ?>

</main>

<?php get_footer(); ?>
