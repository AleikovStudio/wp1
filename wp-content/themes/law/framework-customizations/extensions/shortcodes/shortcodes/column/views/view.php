<?php if ( ! defined( 'FW' ) ) die( 'Forbidden' );

$class = fw_ext_builder_get_item_width( 'page-builder', $atts['width'] . '/frontend_class' );
?>
<div class="<?php echo esc_attr( $class ); ?> <?php if ( ! empty( $atts['col_class'] ) ) echo $atts['col_class'] ?>" <?php if ( ! empty( $atts['col_id'] ) ) echo "id='{$atts['col_id']}'" ?>>
	<?php echo do_shortcode( $content ); ?>
</div>
