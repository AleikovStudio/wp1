<?php get_header(); ?>

<div id="fh5co-blog" class="fw-main-row fh5co-bg-section" style="background-color:#f2f2f2;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php
					if ( has_post_thumbnail() ) {
						$img_url = get_the_post_thumbnail_url();
					} else {
						$img_url = 'https://i.imgflip.com/2hcl0v.jpg';
					} ?>
                    <h1><?php the_title() ?></h1>
                    <img class="pull-left law-post-image" src="<?php echo $img_url ?>" title="<?php the_title() ?>">
					<?php the_content(); ?>
					<?php

					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

					?>
				<?php endwhile; ?>
                    <!--post navigation-->
				<?php else: ?>
                    <!--no posts found-->
				<?php endif; ?>
                <!--reset the post data-->
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
