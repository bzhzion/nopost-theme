<?php
/**
 * nopost — comments.php
 */

if ( post_password_required() ) return;

$commenter = wp_get_current_commenter();
?>

<section class="np-comments" id="comments">
  <div class="np-comments__inner">

    <?php if ( have_comments() ) : ?>
    <h2 class="np-comments-title">
      <?php
      printf(
        esc_html( _n( '%s commentaire', '%s commentaires', get_comments_number(), 'nopost' ) ),
        '<span>' . number_format_i18n( get_comments_number() ) . '</span>'
      );
      ?>
    </h2>

    <ol class="nopost-comment-list">
      <?php wp_list_comments( [
        'callback'    => 'nopost_comment',
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 44,
      ] ); ?>
    </ol>

    <?php the_comments_navigation( [
      'prev_text' => '&larr; ' . esc_html__( 'Commentaires plus anciens', 'nopost' ),
      'next_text' => esc_html__( 'Commentaires plus récents', 'nopost' ) . ' &rarr;',
    ] ); ?>

    <?php elseif ( comments_open() ) : ?>
    <p class="np-comments-none"><?php esc_html_e( 'Soyez le premier à commenter.', 'nopost' ); ?></p>
    <?php endif; ?>

    <?php if ( comments_open() ) :
      comment_form( [
        'title_reply'          => esc_html__( 'Laisser un commentaire', 'nopost' ),
        'title_reply_to'       => esc_html__( 'Répondre à %s', 'nopost' ),
        'cancel_reply_link'    => esc_html__( 'Annuler', 'nopost' ),
        'label_submit'         => esc_html__( 'Publier', 'nopost' ),
        'class_submit'         => 'np-btn-comment-submit',
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg></button>',
        'class_form'           => 'np-comment-form',
        'id_form'              => 'commentform',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'logged_in_as'         => '',
        'comment_field'        => '<div class="np-cf-field np-cf-field--full"><label for="comment">' . esc_html__( 'Commentaire', 'nopost' ) . ' <span aria-hidden="true">*</span></label><textarea id="comment" name="comment" rows="5" maxlength="65525" required></textarea></div>',
        'fields'               => [
          'author'  => '<div class="np-cf-field"><label for="author">' . esc_html__( 'Nom', 'nopost' ) . ' <span aria-hidden="true">*</span></label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" maxlength="245" autocomplete="name" required /></div>',
          'email'   => '<div class="np-cf-field"><label for="email">' . esc_html__( 'Email', 'nopost' ) . ' <span aria-hidden="true">*</span></label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" maxlength="100" autocomplete="email" required /></div>',
          'cookies' => '',
        ],
      ] );
    endif; ?>

  </div>
</section>
