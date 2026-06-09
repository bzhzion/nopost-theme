<?php
/**
 * nopost — index.php (fallback)
 */
get_header();
?>

<main id="main-content">
<div class="np-section__inner">
  <?php if ( have_posts() ) : ?>
  <div class="np-grid">
    <?php $card_idx = 0; while ( have_posts() ) : the_post(); $card_idx++; ?>
    <article class="np-card reveal">
      <?php if ( has_post_thumbnail() ) : ?>
      <div class="np-card__image">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
      </div>
      <?php endif; ?>
      <div class="np-card__body">
        <h3 class="np-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="np-card__meta">
          <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
        </div>
      </div>
    </article>
    <?php if ( 3 === $card_idx ) nopost_ad( 'nopost_ad_grid', 'np-ad--grid-span' ); ?>
    <?php endwhile; ?>
  </div>
  <?php nopost_pagination(); ?>
  <?php else : ?>
  <p class="np-no-results"><?php esc_html_e( 'Aucun article.', 'nopost' ); ?></p>
  <?php endif; ?>
</div>
</main>

<?php get_footer(); ?>
