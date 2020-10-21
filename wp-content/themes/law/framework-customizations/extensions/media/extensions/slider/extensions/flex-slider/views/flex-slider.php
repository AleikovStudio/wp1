<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
} ?>


<?php if ( $data['slides'] ): ?>

    <aside id="fh5co-hero" class="js-fullheight">
        <div class="flexslider js-fullheight">
            <ul class="slides">
				<?php foreach ( $data['slides'] as $slide ): ?>
                    <li style="background-image: url(<?php echo $slide['src'] ?>);">
                        <div class="overlay-gradient"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 text-center js-fullheight slider-text">
                                    <div class="slider-text-inner">
                                        <h1><?php echo $slide['title'] ?></h1>
                                        <h2><?php echo $slide['desc'] ?></a></h2>
										<?php if ( ! empty( $slide['extra']['link'] ) && ! empty( $slide['extra']['link_title'] ) ): ?>
                                            <p><a class="btn btn-primary btn-lg" href="<?php echo $slide['extra']['link'] ?>"><?php echo $slide['extra']['link_title'] ?></a></p>
										<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
    </aside>


<?php endif; ?>