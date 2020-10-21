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
	//clean
	'url' => array(
		'label' => __( 'URL', 'clean' ),
		'type'  => 'text',
	),
/*	'title' => array(
		'type'  => 'text',
		'label' => __( 'Title', 'fw' ),
		'desc'  => __( 'Icon title', 'fw' ),
	)*/
);