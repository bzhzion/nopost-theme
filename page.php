<?php
/**
 * nopost — page.php
 */
get_header();

if ( ! have_posts() ) {
    get_footer();
    exit;
}

the_post();
?>

<main id="main-content">
  <article class="np-page-article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="np-page-article__inner">
      <header class="np-page-header">
        <h1 class="np-page-title"><?php the_title(); ?></h1>
      </header>
      <div class="np-single-content entry-content">
        <?php the_content(); ?>
      </div>
      <?php if ( comments_open() ) comments_template(); ?>
    </div>
  </article>
</main>

<?php get_footer(); ?>
