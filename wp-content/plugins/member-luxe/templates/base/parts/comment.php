<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    <div id="comment-<?php comment_ID(); ?>" class="comment-body">
        <div class="comment-avatar-wrap pull-left">
            <?php
            if(!empty($avatar)){
                echo wp_get_attachment_image($avatar, 'thumbnail', '', array('class'	=> "avatar avatar-48 photo"));
            }else{
                echo get_avatar($comment,$size='48' );
            }

            ?>
        </div>
        <div class="comment-content">
            <div class="comment-meta">
                <div class="comment-author vcard">
                    <?php printf(__('<cite class="name">%s</cite>'), get_comment_author_link()) ?>
                    <span class="coment-date"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></span>
                </div>
                <div class="comment-metadata">

                    <?php if ($comment->comment_approved == '0' && is_user_logged_in()) : ?>
                        <em class="not-approved-comment" ><?php _e('Your comment is awaiting moderation.') ?></em>
                        <br />
                    <?php endif; ?>
                </div>
            </div>
            <div class="comment-text">
                <?php comment_text() ?>
                <div class="comment-image row">
                    <?php if(!$attachments_is_disabled):?>
                        <?php $images = get_comment_meta($comment->comment_ID, "comment_image");

                        if(!empty($images)){
                            foreach ($images as $image) {
                                echo '<div class=" col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="'.wpm_remove_protocol($image['url']).'" rel="group_'.$comment->comment_ID.'" class="fancybox wpm-comment-image-item" style="background-image:url('.wpm_remove_protocol($image['url']).')"></a></div>';
                            }
                        }?>
                    <?php endif;?>
                </div>
            </div>
            <div class="comment-nav">
                <?php if(is_user_logged_in()):?>
                    <?php edit_comment_link(__('Edit'),'',' • ') ?>
                    <?php comment_reply_link(array_merge( $args, array('depth' => $depth))) ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</li>