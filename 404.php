<?php
/**
 * nopost — 404.php
 */
get_header();
?>

<main id="main-content">
<div class="np-404">

  <div class="np-404__inner">
    <p class="np-404__code">404</p>
    <h1 class="np-404__title"><?php esc_html_e( 'Page introuvable', 'nopost' ); ?></h1>
    <p class="np-404__msg"><?php esc_html_e( 'Cette page n\'existe pas ou a été déplacée.', 'nopost' ); ?></p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="np-btn-read">
      <?php esc_html_e( 'Retour à l\'accueil', 'nopost' ); ?>
    </a>
  </div>

  <!-- 3 articles récents -->
  <?php
  $recent = nopost_get_latest( 3, 0 );
  if ( $recent->have_posts() ) : ?>
  <div class="np-section__inner" style="margin-top: var(--sp-3xl)">
    <div class="np-section-header">
      <h2 class="np-section-title"><?php esc_html_e( 'Articles récents', 'nopost' ); ?></h2>
    </div>
    <div class="np-grid">
      <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
      <article class="np-card">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="np-card__image">
          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
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
          <h3 class="np-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="np-card__meta">
            <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
          </div>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
