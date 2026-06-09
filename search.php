<?php
/**
 * nopost — search.php
 */
get_header();
?>

<main id="main-content">
<div class="np-section__inner">

  <header class="np-archive-header">
    <?php if ( have_posts() ) : ?>
    <h1 class="np-page-title">
      <?php
      printf(
        /* translators: %1$d = count, %2$s = search query */
        esc_html( _n( '%1$d résultat pour « %2$s »', '%1$d résultats pour « %2$s »', $wp_query->found_posts, 'nopost' ) ),
        $wp_query->found_posts,
        '<em>' . esc_html( get_search_query() ) . '</em>'
      );
      ?>
    </h1>
    <?php else : ?>
    <h1 class="np-page-title"><?php esc_html_e( 'Aucun résultat', 'nopost' ); ?></h1>
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
        <p class="np-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
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
  <div class="np-no-results-wrap">
    <p class="np-no-results">
      <?php
      printf(
        /* translators: %s = search query */
        esc_html__( 'Aucun résultat pour « %s ». Essayez avec d'autres mots.', 'nopost' ),
        esc_html( get_search_query() )
      );
      ?>
    </p>
    <form class="np-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" name="s" placeholder="<?php esc_attr_e( 'Rechercher…', 'nopost' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
      <button type="submit"><?php esc_html_e( 'Rechercher', 'nopost' ); ?></button>
    </form>
  </div>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
