<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'icon'  => array(
		'type'  => 'icon',
		'label' => __( 'Icon', 'fw' )
	),
	'title' => array(
		'type'  => 'text',
		'label' => __( 'Title', 'fw' ),
		'desc'  => __( 'Icon title', 'fw' ),
	),
	'url' => array(
		'label' => __( 'URL', 'fw' ),
		'type'  => 'text',
	),
/*	'title' => array(
		'type'  => 'text',
		'label' => __( 'Title', 'fw' ),
		'desc'  => __( 'Icon title', 'fw' ),
	)*/
);