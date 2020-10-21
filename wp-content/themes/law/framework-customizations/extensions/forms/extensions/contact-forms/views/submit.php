<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * @var int $form_id
 * @var string $submit_button_text
 * @var array $extra_data
 */
?>
<div>
<!--	<input type="submit" class="btn btn-primary" value="--><?php //echo esc_attr( $submit_button_text ) ?><!--"/>-->
    <button type="submit" class="btn btn-primary"><?php echo esc_attr( $submit_button_text ) ?></button>
</div>