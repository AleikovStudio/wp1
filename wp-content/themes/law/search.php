<?php get_header(); ?>

<div id="fh5co-blog" class="fw-main-row fh5co-bg-section" style="background-color:#fcfcfc;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
				<?php if ( have_posts() ): ?>
                    <h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'law' ), '<span>' . get_search_query() . '</span>' );
						?>
                    </h1>
                    <ul>
						<?php while ( have_posts() ):the_post(); ?>
                            <li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>
						<?php endwhile; ?>
                    </ul>
				<?php else : ?>
					<p><?php _e('Nothing found', 'law'); ?></p>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
