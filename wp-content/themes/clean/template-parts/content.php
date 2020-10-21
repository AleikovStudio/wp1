<?php
if ( has_post_thumbnail() ) {
	$img_url = get_the_post_thumbnail_url();
} else {
	$img_url = 'https://i.imgflip.com/2hcl0v.jpg';
}
?>
<img src="<?php echo $img_url ?>" alt="">
<div class="fh5co-portfolio-description">
    <h2><?php the_title(); ?></h2>
	<?php the_content(); ?>
</div>