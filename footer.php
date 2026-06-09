<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo__text">
        <?php bloginfo('name'); ?>
      </a>
      <p class="site-footer__tagline"><?php bloginfo('description'); ?></p>
    </div>

    <?php if (has_nav_menu('footer')): ?>
    <nav class="site-footer__nav" aria-label="Footer">
      <?php wp_nav_menu([
        'theme_location' => 'footer',
        'container'      => false,
        'menu_class'     => 'footer-nav',
        'depth'          => 1,
        'fallback_cb'    => false,
      ]); ?>
    </nav>
    <?php endif; ?>

    <?php
    $socials = [
      'nopost_bluesky'   => ['Bluesky',    '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 10.8c-1.087-2.114-4.046-6.053-6.798-7.995C2.566.944 1.561 1.266.902 1.565.139 1.908 0 3.08 0 3.768c0 .69.378 5.65.624 6.479.815 2.736 3.713 3.66 6.383 3.364.136-.02.275-.039.415-.056-.138.022-.276.04-.415.056-3.912.58-7.387 2.005-2.83 7.078 5.013 5.19 6.87-1.113 7.823-4.308.953 3.195 2.05 9.271 7.733 4.308 4.265-4.51 1.17-6.487-2.742-7.078a8.741 8.741 0 0 1-.415-.056c.14.017.279.036.415.056 2.67.297 5.568-.628 6.383-3.364.246-.828.624-5.79.624-6.478 0-.69-.139-1.861-.902-2.204-.659-.299-1.664-.62-4.3 1.24C16.046 4.748 13.087 8.687 12 10.8Z"/></svg>'],
      'nopost_twitter'   => ['Twitter',    '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.74l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
      'nopost_facebook'  => ['Facebook',   '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
      'nopost_instagram' => ['Instagram',  '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
      'nopost_youtube'   => ['YouTube',    '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'],
    ];
    $has_socials = false;
    foreach ($socials as $key => [$name, $icon]) {
      if (get_theme_mod($key)) { $has_socials = true; break; }
    }
    if ($has_socials): ?>
    <div class="site-footer__socials">
      <?php foreach ($socials as $key => [$name, $icon]):
        $url = get_theme_mod($key);
        if (!$url) continue; ?>
        <a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($name); ?>">
          <?php echo $icon; ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <div class="site-footer__bottom">
    <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
