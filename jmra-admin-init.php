<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function stag_admin_init(){

	

	$data = get_option('jmra_framework_options');

	$data['theme_version'] = JMRA_THEME_VERSION;
	$data['theme_name'] = JMRA_THEME_NAME;
	$data['framework_version'] = JMRA_FRAMEWORK_VERSION;
	$data['stag_framework'] = array();
	update_option('jmra_framework_options', $data);

	$stag_values = get_option('stag_framework_values');
	if( !is_array($stag_values) ) update_option( 'stag_framework_values', array() );

}
add_action('init', 'stag_admin_init', 2);


function jmra_admin_menu(){
	add_object_page( JMRA_THEME_NAME, JMRA_THEME_NAME, 'manage_options', 'stagframework', 'jmra_options_page' );
	add_submenu_page('stagframework', __('Theme Options', 'stag'), __('Theme Options', 'stag'), 'manage_options', 'stagframework', 'jmra_options_page' );
}
add_action('admin_menu', 'jmra_admin_menu');