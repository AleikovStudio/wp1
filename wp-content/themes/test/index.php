<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="row">
				<?php if ( have_posts() ) : while ( have_posts() ) :
					the_post(); ?>
                    <!--post-->

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                            </div>
                            <div class="card-body">
								<?php if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'thumbnail', array( 'class' => 'float-left mr-3' ) );
								} else {
									echo '<img src="https://picsum.photos/100/100" alt="random picture" width="100" height="100" class="float-left mr-3">';
								} ?>

                                <p class="card-text"><?php the_excerpt(); ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'Read More', 'test' ); ?></a>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
					<?php the_posts_pagination( $args = array(
						'show_all' => true,
						'type'     => 'list',
					) ); ?>
                    <!--post navigation-->
					<?php paginate_links() ?>
				<?php else: ?>

				<?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php //$query = new WP_Query( 'cat=15&posts_per_page=-1' ); ?>
<?php $query = new WP_Query( array(
	'category_name'  => 'edge-case-2, markup',
	'posts_per_page' => - 1,
	'orderby'        => 'title',
	'order'          => 'ASC'
) ); ?>
<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
    <!--post-->
    <h3><?php the_title(); ?></h3>
<?php endwhile; ?>
    <!--post navigation-->
<?php else: ?>

<?php endif; ?>
<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>
