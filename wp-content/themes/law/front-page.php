<?php
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>
    <!--post navigation-->
<?php else: ?>
    <!--no posts found-->
<?php endif; ?>
    <!--reset the post data-->
<?php get_footer(); ?>