<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="row">
				<?php while ( have_posts() ) : the_post(); ?>
                    <!--post-->

                    <div class="col-md-12">
                        <div class="card">
							<?php if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'large' );
							} ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <p class="card-text"><?php the_content(); ?></p>
                            </div>
                        </div>
                    </div>

                    <div b4></div>

				<?php endwhile; ?>
            </div>
        </div>
		<?php get_sidebar(); ?>
    </div>
</div>


<?php get_footer(); ?>
