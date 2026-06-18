<?php
/**
 * nopost — category.php
 */
get_header();

$cat_obj = get_queried_object();
?>

<main id="main-content">
<div class="np-section__inner">

  <header class="np-archive-header">
    <h1 class="np-page-title"><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
    <?php if ( category_description() ) : ?>
    <p class="np-archive-desc"><?php echo wp_kses_post( category_description() ); ?></p>
    <?php endif; ?>
  </header>

  <?php if ( have_posts() ) :
    // First post as cat hero
    the_post();
  ?>

  <!-- CAT HERO -->
  <div class="np-cat-hero">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="np-cat-hero__image">
      <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
    </div>
    <?php endif; ?>
    <div class="np-cat-hero__content">
      <h2 class="np-cat-hero__title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
      <?php if ( has_excerpt() ) : ?>
      <p class="np-cat-hero__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></p>
      <?php endif; ?>
      <div class="np-card__meta">
        <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
        <span class="np-meta-sep">·</span>
        <span class="np-meta-time"><?php echo nopost_reading_time(); ?></span>
      </div>
    </div>
  </div>

  <!-- REMAINING ARTICLES -->
  <?php if ( have_posts() ) : ?>
  <div class="np-grid" style="margin-top: var(--sp-3xl)">
    <?php $card_idx = 0; while ( have_posts() ) : the_post(); $card_idx++; ?>

    <article class="np-card reveal">
      <?php if ( has_post_thumbnail() ) : ?>
      <div class="np-card__image">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
      </div>
      <?php endif; ?>
      <div class="np-card__body">
        <h3 class="np-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php if ( has_excerpt() ) : ?><p class="np-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p><?php endif; ?>
        <div class="np-card__meta">
          <span class="np-meta-date"><?php echo get_the_date( '' ); ?></span>
          <span class="np-meta-sep">·</span>
          <span class="np-meta-time"><?php echo nopost_reading_time(); ?></span>
        </div>
      </div>
    </article>

    <?php if ( 2 === $card_idx ) nopost_ad( 'nopost_ad_grid', 'np-ad--grid-span' ); ?>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>

  <?php nopost_pagination(); ?>

  <?php else : ?>
  <p class="np-no-results"><?php esc_html_e( 'Aucun article dans cette catégorie.', 'nopost' ); ?></p>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
