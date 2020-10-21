<footer id="fh5co-footer" role="contentinfo">
    <div class="container">
		<?php if ( is_active_sidebar( 'footer' ) ) : ?>
            <div class="row row-pb-md">
				<?php dynamic_sidebar( 'footer' ) ?>
            </div>
		<?php endif; ?>

        <div class="row copyright">
            <div class="col-md-12 text-center">
                <p>
                    <small class="block">&copy; <?php echo date('Y') ?> Free HTML5. All Rights Reserved.</small>
                    <small class="block">Designed by <a href="http://freehtml5.co/" target="_blank">FreeHTML5.co</a> Demo Images: <a href="http://unsplash.co/" target="_blank">Unsplash</a></small>
                </p>
                <p>
                <ul class="fh5co-social-icons">
                    <li><a href="#"><i class="icon-twitter"></i></a></li>
                    <li><a href="#"><i class="icon-facebook"></i></a></li>
                    <li><a href="#"><i class="icon-linkedin"></i></a></li>
                    <li><a href="#"><i class="icon-dribbble"></i></a></li>
                </ul>
                </p>
            </div>
        </div>

    </div>
</footer>
</div>

<div class="gototop js-top">
    <a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
</div>

<?php wp_footer(); ?>
</body>
</html>