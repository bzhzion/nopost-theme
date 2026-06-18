<?php
/**
 * nopost — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── ENQUEUE ──────────────────────────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'nopost_enqueue', 20 );

function nopost_enqueue() {
    // Google Fonts preconnect
    wp_enqueue_style( 'nopost-fonts-preconnect', 'https://fonts.googleapis.com', [], null );
    wp_enqueue_style( 'nopost-fonts-preconnect-gstatic', 'https://fonts.gstatic.com', [], null );

    // Google Fonts — EB Garamond + Cormorant Garamond
    wp_enqueue_style(
        'nopost-google-fonts',
        'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap',
        [],
        null
    );

    // CSS principal
    wp_enqueue_style(
        'nopost-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [ 'nopost-google-fonts' ],
        wp_get_theme()->get( 'Version' )
    );

    // JS principal (defer)
    wp_enqueue_script(
        'nopost-theme',
        get_stylesheet_directory_uri() . '/assets/js/theme.js',
        [],
        wp_get_theme()->get( 'Version' ),
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    wp_localize_script( 'nopost-theme', 'nopost', [
        'supportUrl' => esc_url( get_theme_mod( 'nopost_support_url', '' ) ),
    ] );

    // Ads JS (async)
    wp_enqueue_script(
        'nopost-ads',
        get_stylesheet_directory_uri() . '/assets/js/ads.js',
        [],
        wp_get_theme()->get( 'Version' ),
        [ 'strategy' => 'defer', 'in_footer' => false ]
    );

    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

// Exclure ads.js d'Autoptimize
add_filter( 'autoptimize_filter_js_exclude', function( $exclude ) {
    return $exclude . ',assets/js/ads.js';
} );

// Preconnect hints
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

// ── ADSENSE ───────────────────────────────────────────────────────────────────
function nopost_ad( $slot_mod = '', $extra_class = '' ) {
    $pub = trim( get_theme_mod( 'nopost_adsense_pub', '' ) );
    if ( ! $pub ) return;
    $slot = trim( get_theme_mod( $slot_mod, '' ) );
    if ( ! $slot ) return;
    $support_url = esc_url( get_theme_mod( 'nopost_support_url', '' ) );
    $msg         = esc_html__( 'Pub bloquée — pour continuer à accéder gratuitement à ce contenu, désactivez votre bloqueur ou', 'nopost' );
    $link_text   = esc_html__( 'soutenez-nous', 'nopost' );
    $link        = $support_url
        ? '<a href="' . $support_url . '" target="_blank" rel="noopener">' . $link_text . '</a>'
        : $link_text;
    echo '<div class="np-ad ' . esc_attr( $extra_class ) . '">';
    echo '<p class="np-ad__label">' . esc_html__( 'Publicité', 'nopost' ) . '</p>';
    echo '<div class="np-adblock"><span>' . $msg . ' ' . $link . '.</span></div>';
    echo '<ins class="adsbygoogle" style="display:block"';
    echo ' data-ad-client="' . esc_attr( $pub ) . '"';
    echo ' data-ad-slot="' . esc_attr( $slot ) . '"';
    echo ' data-ad-format="auto" data-full-width-responsive="true"></ins>';
    echo '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
    echo '</div>';
}

add_action( 'customize_register', 'nopost_customizer_ads' );
function nopost_customizer_ads( $wp_customize ) {
    $wp_customize->add_section( 'nopost_ads', [
        'title'    => __( 'Publicités AdSense', 'nopost' ),
        'priority' => 160,
    ] );
    $fields = [
        'nopost_adsense_pub'      => 'Publisher ID (ca-pub-XXXXXXXXXXXXXXXX)',
        'nopost_ad_hero'          => 'Slot — Après hero (homepage)',
        'nopost_ad_grid'          => 'Slot — Dans grille après 3e article',
        'nopost_ad_sidebar'       => 'Slot — Sidebar desktop',
        'nopost_ad_before_footer' => 'Slot — Avant footer',
        'nopost_ad_article'       => 'Slot — Début corps article',
    ];
    foreach ( $fields as $key => $label ) {
        $wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [ 'label' => $label, 'section' => 'nopost_ads', 'type' => 'text' ] );
    }
}

// ── SETUP ─────────────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'nopost_setup' );

function nopost_setup() {
    load_textdomain( 'nopost', get_template_directory() . '/languages/nopost-' . get_locale() . '.mo' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo' );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'nopost' ),
        'footer'  => __( 'Footer', 'nopost' ),
    ] );
}

// ── CUSTOMIZER ───────────────────────────────────────────────────────────────
add_action( 'customize_register', 'nopost_customize_register' );

function nopost_customize_register( $wp_customize ) {
    // Section Liens
    $wp_customize->add_section( 'nopost_links', [
        'title'    => __( 'Liens du thème', 'nopost' ),
        'priority' => 110,
    ] );

    $link_settings = [
        'nopost_support_url'        => [ 'label' => __( 'Lien "Nous soutenir" — URL', 'nopost' ), 'type' => 'url', 'sanitize' => 'esc_url_raw', 'default' => '' ],
        'nopost_support_label'      => [ 'label' => __( 'Lien "Nous soutenir" — Texte', 'nopost' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field', 'default' => 'Nous soutenir' ],
        'nopost_search_placeholder' => [ 'label' => __( 'Placeholder recherche', 'nopost' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field', 'default' => 'Rechercher…' ],
    ];

    foreach ( $link_settings as $key => $cfg ) {
        $wp_customize->add_setting( $key, [ 'default' => $cfg['default'], 'sanitize_callback' => $cfg['sanitize'] ] );
        $wp_customize->add_control( $key, [ 'label' => $cfg['label'], 'section' => 'nopost_links', 'type' => $cfg['type'] ] );
    }

    // Section Réseaux sociaux
    $wp_customize->add_section( 'nopost_socials', [
        'title'    => __( 'Réseaux sociaux', 'nopost' ),
        'priority' => 120,
    ] );

    $socials = [
        'nopost_bluesky'   => 'Bluesky',
        'nopost_twitter'   => 'Twitter / X',
        'nopost_facebook'  => 'Facebook',
        'nopost_instagram' => 'Instagram',
        'nopost_youtube'   => 'YouTube',
    ];

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( $key, [ 'label' => $label, 'section' => 'nopost_socials', 'type' => 'url' ] );
    }
}

// ── HELPERS ──────────────────────────────────────────────────────────────────

function nopost_get_latest( $count = 6, $offset = 0 ) {
    return new WP_Query( [
        'posts_per_page' => $count,
        'offset'         => $offset,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

function nopost_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $words   = str_word_count( wp_strip_all_tags( $content ) );
    $minutes = max( 1, round( $words / 200 ) );
    /* translators: %d = number of minutes to read an article */
    return sprintf( _n( '%d min de lecture', '%d min de lecture', $minutes, 'nopost' ), $minutes );
}

// ── POSTS PER PAGE ───────────────────────────────────────────────────────────
add_action( 'pre_get_posts', 'nopost_posts_per_page' );
function nopost_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_search() || $query->is_archive() || $query->is_home() ) {
            $query->set( 'posts_per_page', 9 );
        }
    }
}

// ── PAGINATION ───────────────────────────────────────────────────────────────
function nopost_pagination( $total_pages = 0 ) {
    $args = [
        'type'      => 'array',
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
    ];
    if ( $total_pages > 0 ) {
        $args['total'] = $total_pages;
    }
    $pages = paginate_links( $args );
    if ( ! $pages ) return;
    echo '<nav class="pagination" aria-label="' . esc_attr__( 'Navigation des pages', 'nopost' ) . '">';
    foreach ( $pages as $page ) {
        echo $page;
    }
    echo '</nav>';
}

// ── COMMENTAIRES ─────────────────────────────────────────────────────────────
function nopost_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'nopost-comment-item' ); ?>>
      <div class="nopost-comment-body">
        <div class="nopost-comment-avatar">
          <?php echo get_avatar( $comment, 44, '', get_comment_author( $comment ), [ 'class' => 'nopost-comment-avatar__img' ] ); ?>
        </div>
        <div class="nopost-comment-content">
          <div class="nopost-comment-meta">
            <span class="nopost-comment-author-name"><?php comment_author_link( $comment ); ?></span>
            <span class="nopost-comment-sep">·</span>
            <time class="nopost-comment-date" datetime="<?php comment_date( 'c', $comment ); ?>"><?php comment_date( '', $comment ); ?></time>
            <?php if ( '0' === $comment->comment_approved ) : ?>
              <span class="nopost-comment-awaiting"><?php esc_html_e( 'En attente de modération', 'nopost' ); ?></span>
            <?php endif; ?>
          </div>
          <div class="nopost-comment-text"><?php comment_text( $comment ); ?></div>
          <?php comment_reply_link( array_merge( $args, [
            'depth'      => $depth,
            'max_depth'  => $args['max_depth'],
            'reply_text' => esc_html__( 'Répondre', 'nopost' ),
            'before'     => '<div class="nopost-comment-reply-link">',
            'after'      => '</div>',
          ] ) ); ?>
        </div>
      </div>
    <?php
    // No closing </li> — Walker_Comment::end_el() handles it
}

// ── NAV WALKER ───────────────────────────────────────────────────────────────
class nopost_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '<div class="nav__dropdown">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item         = $data_object;
        $classes      = empty( $item->classes ) ? [] : (array) $item->classes;
        $active       = in_array( 'current-menu-item', $classes ) ? ' is-active' : '';
        $has_children = in_array( 'menu-item-has-children', $classes );

        if ( 0 === $depth ) {
            $output .= '<div class="nav__item-wrap' . ( $has_children ? ' nav__item-wrap--has-children' : '' ) . '">';
            if ( $has_children ) {
                $output .= sprintf(
                    '<button class="nav__item nav__item--parent%s" type="button">%s<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg></button>',
                    esc_attr( $active ),
                    esc_html( $item->title )
                );
            } else {
                $output .= sprintf(
                    '<a href="%s" class="nav__item%s">%s</a>',
                    esc_url( $item->url ),
                    esc_attr( $active ),
                    esc_html( $item->title )
                );
            }
        } elseif ( 1 === $depth ) {
            $output .= sprintf(
                '<a href="%s" class="nav__dropdown-item%s">%s</a>',
                esc_url( $item->url ),
                esc_attr( $active ),
                esc_html( $item->title )
            );
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }
}

// ── LOGIN PAGE ───────────────────────────────────────────────────────────────
add_action( 'login_enqueue_scripts', function() {
    $logo_id  = get_theme_mod( 'custom_logo' );
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : null;
    ?>
    <style>
    :root {
        --accent: #c0392b;
        --bg:     #1a1210;
        --surface:#261815;
        --border: rgba(255,255,255,.08);
        --fg:     #f5f0eb;
        --fg-sec: #c9bdb7;
    }
    body.login { background: var(--bg) !important; font-family: Georgia, serif !important; }
    body.login #login { padding: 0 !important; width: 380px !important; }
    body.login #login h1 a {
        <?php if ( $logo_url ) : ?>
        background-image: url('<?php echo esc_url( $logo_url ); ?>') !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        width: 180px !important;
        height: 56px !important;
        <?php else : ?>
        background-image: none !important;
        text-indent: 0 !important;
        color: var(--fg) !important;
        font-family: 'Cormorant Garamond', Georgia, serif !important;
        font-size: 28px !important;
        font-weight: 600 !important;
        width: auto !important;
        height: auto !important;
        <?php endif; ?>
    }
    body.login #loginform,
    body.login #registerform,
    body.login #lostpasswordform {
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 32px rgba(0,0,0,.5) !important;
        padding: 28px 32px !important;
    }
    body.login label { color: var(--fg-sec) !important; font-size: 12px !important; font-weight: 600 !important; letter-spacing: .04em !important; text-transform: uppercase !important; }
    body.login input[type=text],
    body.login input[type=password],
    body.login input[type=email] {
        background: var(--bg) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 4px !important;
        color: var(--fg) !important;
        font-size: 14px !important;
        padding: 10px 14px !important;
        box-shadow: none !important;
    }
    body.login input[type=text]:focus,
    body.login input[type=password]:focus,
    body.login input[type=email]:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(192,57,43,.2) !important;
        outline: none !important;
    }
    body.login .button-primary {
        background: var(--accent) !important;
        border: none !important;
        border-radius: 4px !important;
        color: #fff !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        padding: 10px 0 !important;
        width: 100% !important;
        box-shadow: none !important;
        text-shadow: none !important;
    }
    body.login .button-primary:hover { background: #a93226 !important; }
    body.login #nav a, body.login #backtoblog a { color: var(--fg-sec) !important; font-size: 13px !important; }
    body.login #nav a:hover, body.login #backtoblog a:hover { color: var(--accent) !important; }
    body.login #login_error,
    body.login .message {
        background: var(--surface) !important;
        border-left: 4px solid var(--accent) !important;
        color: var(--fg-sec) !important;
        box-shadow: none !important;
    }
    body.login input[type=checkbox] { accent-color: var(--accent) !important; }
    body.login h1 { padding-top: 32px !important; }
    </style>
    <?php
} );

add_filter( 'login_headerurl', fn() => home_url( '/' ) );
add_filter( 'login_headertext', fn() => get_bloginfo( 'name' ) );

// ── AUTO-UPDATE — GitHub Releases ─────────────────────────────────────────────
add_filter( 'pre_set_site_transient_update_themes', 'nopost_check_theme_update' );
function nopost_check_theme_update( $transient ) {
    if ( empty( $transient->checked ) ) return $transient;

    $theme_slug    = get_option( 'stylesheet' );
    $current_ver   = wp_get_theme()->get( 'Version' );
    $transient_key = 'nopost_github_release';

    $release = get_transient( $transient_key );
    if ( false === $release ) {
        $response = wp_remote_get( 'https://api.github.com/repos/bzhzion/nopost-theme/releases/latest', [
            'timeout' => 10,
            'headers' => [
                'User-Agent'    => 'nopost-theme-updater',
                'Cache-Control' => 'no-cache, no-store',
                'Pragma'        => 'no-cache',
            ],
        ] );
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            set_transient( $transient_key, [ 'tag_name' => $current_ver ], HOUR_IN_SECONDS );
            return $transient;
        }
        $release = json_decode( wp_remote_retrieve_body( $response ), true );
        set_transient( $transient_key, $release, HOUR_IN_SECONDS );
    }

    $latest_ver = ltrim( $release['tag_name'] ?? '', 'v' );
    if ( ! preg_match( '/^\d+\.\d+(\.\d+)?$/', $latest_ver ) ) return $transient;
    if ( version_compare( $latest_ver, $current_ver, '>' ) ) {
        $transient->response[ $theme_slug ] = [
            'theme'       => $theme_slug,
            'new_version' => $latest_ver,
            'url'         => 'https://github.com/bzhzion/nopost-theme',
            'package'     => 'https://github.com/bzhzion/nopost-theme/releases/download/v' . $latest_ver . '/nopost-theme.zip',
        ];
        $sha_url  = 'https://github.com/bzhzion/nopost-theme/releases/download/v' . $latest_ver . '/nopost-theme.zip.sha256';
        $sha_resp = wp_remote_get( $sha_url, [ 'timeout' => 10, 'headers' => [ 'User-Agent' => 'nopost-theme-updater' ] ] );
        if ( ! is_wp_error( $sha_resp ) && 200 === wp_remote_retrieve_response_code( $sha_resp ) ) {
            $sha = trim( wp_remote_retrieve_body( $sha_resp ) );
            if ( preg_match( '/^[a-f0-9]{64}$/', $sha ) ) {
                set_transient( 'nopost_expected_sha256', $sha, HOUR_IN_SECONDS );
            }
        }
    }

    return $transient;
}

add_filter( 'upgrader_pre_download', function( $reply, $package, $upgrader, $hook_extra ) {
    if ( ! isset( $hook_extra['theme'] ) || $hook_extra['theme'] !== get_option( 'stylesheet' ) ) {
        return $reply;
    }
    $expected_sha = get_transient( 'nopost_expected_sha256' );
    if ( ! $expected_sha ) return $reply;

    $tmpfile = download_url( $package );
    if ( is_wp_error( $tmpfile ) ) return $tmpfile;

    $actual_sha = hash_file( 'sha256', $tmpfile );
    if ( ! hash_equals( $expected_sha, $actual_sha ) ) {
        @unlink( $tmpfile );
        return new WP_Error( 'nopost_sha256_mismatch', __( 'Mise à jour annulée : checksum SHA256 invalide.', 'nopost' ) );
    }
    return $tmpfile;
}, 10, 4 );

add_filter( 'upgrader_source_selection', function( $source, $remote_source, $upgrader, $args ) {
    if ( ! isset( $args['hook_extra']['theme'] ) || $args['hook_extra']['theme'] !== get_option( 'stylesheet' ) ) {
        return $source;
    }
    $style_css = trailingslashit( $source ) . 'style.css';
    if ( ! file_exists( $style_css ) ) {
        return new WP_Error( 'nopost_update_invalid', __( 'Paquet thème invalide : style.css manquant.', 'nopost' ) );
    }
    $theme_data = get_file_data( $style_css, [ 'Theme Name' => 'Theme Name' ] );
    if ( $theme_data['Theme Name'] !== 'nopost' ) {
        return new WP_Error( 'nopost_update_invalid', __( 'Paquet thème invalide : nom incorrect.', 'nopost' ) );
    }
    return $source;
}, 10, 4 );

add_action( 'send_headers', function() {
    if ( headers_sent() ) return;
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
} );

add_action( 'template_redirect', function() {
    if ( is_admin() ) return;
    if ( ! isset( $_SERVER['QUERY_STRING'] ) ) return;
    if ( ! preg_match( '/\bauthor=\d+\b/', $_SERVER['QUERY_STRING'] ) ) return;
    wp_redirect( home_url( '/' ), 302 );
    exit;
} );
