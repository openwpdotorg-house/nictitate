<?php
if (post_password_required()) {
    return;
}
?>
<?php if (have_comments()) : ?>  
    <div id="comments">
        <h3>
            <?php comments_number(__('No Comments', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain())); ?><span data-icon="&#xf086;"></span>                
        </h3>
        <ol class="comments-list clearfix">
            <?php
            wp_list_comments(array(
                'walker' => null,
                'style' => 'ul',
                'callback' => 'kopa_comment_callback',
                'end-callback' => null,
                'type' => 'all'
            ));
            ?>
        </ol>
        <center class="pagination kopa-comment-pagination"><?php paginate_comments_links(); ?></center>
        <div class="clear"></div>
    </div>
<?php endif; ?>	  
<?php comment_form(kopa_comment_form_args()); ?>
<?php

function kopa_comment_callback($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">   


        <article id="comment-<?php comment_ID(); ?>" class="comment-wrap clearfix">
            <div class="comment-avatar">
                <?php echo get_avatar($comment->comment_author_email, 60); ?>                                          
            </div>
            <div class="comment-body clearfix">
                <header class="clearfix">

                    <div class="comment-meta">
                        <span class="author"><?php comment_author_link(); ?></span>
                        <span class="date">&nbsp;-&nbsp;<?php comment_time(get_option('date_format') . ' - ' . get_option('time_format')); ?></span>
                    </div><!-- end:comment-meta -->                        

                    <div class="comment-button">

                        <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>   

                        <span>/</span>   

                        <?php edit_comment_link(__('Edit', kopa_get_domain())); ?>                                                        
                    </div>

                </header>
                <div class="comment-content"><?php comment_text(); ?></div>

                <!-- <a href="#" class="comment-reply-link small-button green-button">Reply</a> -->
            </div><!--comment-body -->
        </article>   
                                                                 

    <?php
}

function kopa_comment_form_args() {
    global $user_identity;
    $commenter = wp_get_current_commenter();

    $fields = array(
        'author' => '<div class="clear"></div><div class="comment-left">
                <p class="input-block">               
                <label class="required" for="comment_name" >' . __("Name <span>(required):</span>", kopa_get_domain()) . '</label>
                <input type="text" name="author" id="comment_name"                 
                value="' . esc_attr($commenter['comment_author']) . '">                                               
                </p>',
        'email' => '
                <p class="input-block">   
                <label for="comment_email" class="required">' . __("Email <span>(required):</span>", kopa_get_domain()) . '</label>                                            
                <input type="email" name="email" id="comment_email"                                                                 
                value="' . esc_attr($commenter['comment_author_email']) . '" >
                </p>',
        'url' => '
                <p class="input-block">   
                <label for="comment_url" class="required">' . __("Website", kopa_get_domain()) . '</label>                                                            
                <input type="url" name="url" id="comment_url"                 
                value="' . esc_attr($commenter['comment_author_url']) . '" >
                </p></div>'
    );

    if (is_user_logged_in()) {
        $comment_field = '<div class=""><p class="textarea-block">
            <label class="required" for="comment_message">' . __('Your comment <span>(required):</span>', kopa_get_domain()) . '</label>        
            <textarea name="comment" id="comment_message"></textarea>
            </p></div><div class="clear"></div>';
    } else {
        $comment_field = '<div class="comment-right"><p class="textarea-block">
            <label class="required" for="comment_message">' . __('Your comment <span>(required):</span>', kopa_get_domain()) . '</label>        
            <textarea name="comment" id="comment_message"></textarea>
            </p></div><div class="clear"></div>';
    }


    $args = array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => $comment_field,
        'must_log_in' => '<p class="alert">' . sprintf(__('You must be <a href="%1$s" title="Log in">logged in</a> to post a comment.', kopa_get_domain()), wp_login_url(get_permalink())) . '</p><!-- .alert -->',
        'logged_in_as' => '<p class="log-in-out">' . sprintf(__('Logged in as <a href="%1$s" title="%2$s">%2$s</a>.', kopa_get_domain()), admin_url('profile.php'), esc_attr($user_identity)) . ' <a href="' . wp_logout_url(get_permalink()) . '" title="' . esc_attr__('Log out of this account', kopa_get_domain()) . '">' . __('Log out &raquo;', kopa_get_domain()) . '</a></p><!-- .log-in-out -->',
        'comment_notes_before' => '',
        'comment_notes_after' => '',
        'id_form' => 'kp-comments-form',
        'id_submit' => 'submit-comment',
        'title_reply' => __('Leave a comment<span data-icon="&#xf040;"></span>', kopa_get_domain()),
        'title_reply_to' => __('Reply', kopa_get_domain()),
        'cancel_reply_link' => __('Cancel', kopa_get_domain()),
        'label_submit' => __('Submit', kopa_get_domain()),
    );

    return $args;
}
