<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */
?>

<?php if ( ! empty( $atts['span_wrapper'] ) ): ?>
    <div <?php if ( ! empty( $atts['span_wrapper_class'] ) ) echo "class='{$atts['span_wrapper_class']}'" ?>>
<?php endif; ?>
    <span
	<?php if ( ! empty( $atts['span_class'] ) ) echo "class='{$atts['span_class']}'" ?>
		<?php if ( ! empty( $atts['span_id'] ) ) echo "id='{$atts['span_id']}'" ?>
		<?php if ( ! empty( $atts['span_data_attrs'] ) ) echo $atts['span_data_attrs'] ?>
>
    <?php if ( ! empty( $atts['span_icon'] ) ): ?>
        <i class="<?php echo $atts['span_icon'] ?>"></i>
    <?php endif; ?>
		<?php if ( ! empty( $atts['span_content'] ) ) echo $atts['span_content'] ?>
</span>
<?php if ( ! empty( $atts['span_wrapper'] ) ): ?>
    </div>
<?php endif; ?>