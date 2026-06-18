<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/favicon.svg">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="site-header__inner">
    <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
      <?php
      $logo_id = get_theme_mod('custom_logo');
      $logo_svg = get_template_directory() . '/assets/logo.svg';
      if ($logo_id) {
        echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'site-logo__img']);
      } elseif (file_exists($logo_svg)) {
        echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/logo.svg' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="site-logo__img">';
      } else {
        echo '<span class="site-logo__text">' . esc_html(get_bloginfo('name')) . '</span>';
      }
      ?>
    </a>

    <nav class="site-nav" aria-label="<?php esc_attr_e('Navigation principale', 'nopost'); ?>">
      <?php wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav__list',
        'walker'         => new nopost_Nav_Walker(),
        'fallback_cb'    => false,
      ]); ?>
    </nav>

    <div class="site-header__actions">
      <button class="nav-search-toggle" aria-label="<?php esc_attr_e('Rechercher', 'nopost'); ?>" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
      <button class="nav-burger" aria-label="<?php esc_attr_e('Menu', 'nopost'); ?>" type="button">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <div class="nav-search-bar" hidden>
    <form class="nav-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input class="nav-search-input" type="search" name="s"
        placeholder="<?php echo esc_attr(get_theme_mod('nopost_search_placeholder', 'Rechercher…')); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        autocomplete="off">
      <button type="submit" aria-label="<?php esc_attr_e('Lancer la recherche', 'nopost'); ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
    </form>
  </div>
</header>
