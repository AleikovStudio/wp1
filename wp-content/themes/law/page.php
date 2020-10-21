<?php get_header(); ?>

<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-12">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>

			<?php endwhile; ?>
                <!--post navigation-->
			<?php else: ?>
                <!--no posts found-->
			<?php endif; ?>
            <!--reset the post data-->
			<?php wp_reset_postdata(); ?>

        </div>
    </div>
</div>


<?php get_footer(); ?>
