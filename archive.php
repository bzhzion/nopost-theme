<?php
/**
 * nopost — archive.php
 */
get_header();
?>

<main id="main-content">
<div class="np-section__inner">

  <header class="np-archive-header">
    <h1 class="np-page-title"><?php echo get_the_archive_title(); ?></h1>
    <?php if ( get_the_archive_description() ) : ?>
    <p class="np-archive-desc"><?php echo wp_kses_post( get_the_archive_description() ); ?></p>
    <?php endif; ?>
  </header>

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
        <?php
        $cats = get_the_category();
        if ( $cats ) : ?>
          <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="np-cat-badge np-cat-badge--sm">
            <?php echo esc_html( $cats[0]->name ); ?>
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

    <?php if ( 3 === $card_idx ) nopost_ad( 'nopost_ad_grid', 'np-ad--grid-span' ); ?>
    <?php endwhile; ?>
  </div>

  <?php nopost_pagination(); ?>

  <?php else : ?>
  <p class="np-no-results"><?php esc_html_e( 'Aucun article dans cette archive.', 'nopost' ); ?></p>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
