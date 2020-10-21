<?php
if ( has_post_thumbnail() ) {
	the_post_thumbnail();
}
?>
<div class="fh5co-portfolio-description">
    <h2><?php the_title(); ?></h2>
	<?php the_content(); ?>
</div>