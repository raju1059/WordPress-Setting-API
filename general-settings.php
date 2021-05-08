<?php
add_action( 'admin_init', 'jmra_general_settings' );

function jmra_general_settings(){
	$settings['description'] = __( 'Configure general settings of your theme. Upload your preferred logo, favicon, and insert your analytics tracking code.', 'stag' );

	$settings[] = array(
	    'title' => __( 'Plain Text Logo', 'stag' ),
	    'desc'  => __( 'Check this box to enable a plain text logo rather than an image logo. Will use your site title.', 'stag' ),
	    'type'  => 'checkbox',
	    'id'    => 'general_text_logo',
	    'val'   => 'off'
	);

	$settings[] = array(
	    'title' => __( 'Custom Logo Upload', 'stag' ),
	    'desc'  => __( 'Upload a logo for your theme.', 'stag' ),
	    'type'  => 'file',
	    'id'    => 'general_custom_logo',
	    'val'   => __( 'Upload Image', 'stag' )
	);

	$settings[] = array(
	    'title' => __( 'Custom Favicon Upload', 'stag' ),
	    'desc'  => __( 'Upload a 16px x 16px Png/Gif image that will represent your website\'s favicon. Use <a href="//www.xiconeditor.com/" target="_blank" rel="nofollow">X-Icon Editor</a> to easily create a favicon.', 'stag' ),
	    'type'  => 'file',
	    'id'    => 'general_custom_favicon',
	    'val'   => __( 'Upload Image', 'stag' )
	);

	$settings[] = array(
		'title' => __( 'Contact Form Email Address', 'stag' ),
		'desc'  => __( 'Enter the email address where you\'d like to receive emails from the contact form, or leave blank to use admin email.', 'stag' ),
		'type'  => 'text',
		'id'    => 'general_contact_email'
	);

	$settings[] = array(
	    'title' => __( 'Tracking Code', 'stag' ),
	    'desc'  => __( 'Paste your Google Analytics (or other) tracking code here. It will be inserted before the closing body tag of your theme.', 'stag' ),
	    'type'  => 'textarea',
	    'id'    => 'general_tracking_code'
	);

	$settings[] = array(
	    'title' => __( 'Footer Copyright Text (Left)', 'stag' ),
	    'desc'  => __( 'Enter the text to display in footer copyright area for left side.', 'stag' ),
	    'type'  => 'wysiwyg',
	    'id'    => 'general_footer_text_left',
	    'val' => '<a href="'. esc_url( __( "http://wordpress.org/", "stag" ) ) .'" title="'. esc_attr__( "Semantic Personal Publishing Platform", "stag" ) .'">Proudly Powered by WordPress</a>'
	);

	$settings[] = array(
	    'title' => __( 'Footer Copyright Text (Right)', 'stag' ),
	    'desc'  => __( 'Enter the text to display in footer copyright area for right side.', 'stag' ),
	    'type'  => 'wysiwyg',
	    'id'    => 'general_footer_text_right',
	    'val'   => '&copy; '.date('Y').' <a href="'. esc_url( home_url( '/' ) ) .'">'.get_bloginfo( 'name' ).'</a> &mdash; A WordPress Theme by <a href="http://wordpress.org">jmra</a>'
	);

	$settings[] = array(
	    'title' => __( 'Enable Sticky Navigation', 'stag' ),
	    'desc'  => __( 'Enable sticky navigation.', 'stag' ),
	    'type'  => 'checkbox',
	    'id'    => 'general_fixed_navigation',
	    'val'   => 'off'
	);

	$settings[] = array(
	    'title' => __( 'Disable Admin Bar', 'stag' ),
	    'desc'  => __( 'Enable/Disable WordPress Admin Bar for all users.', 'stag' ),
	    'type'  => 'checkbox',
	    'id'    => 'general_disable_admin_bar',
	    'val'   => 'off'
	);

	jmra_add_framework_page( __( 'General Settings', 'stag' ), $settings, 0 );
}

/**
* Output the tracking code
*/
function jmra_tracking_code() {
    $stag_values = get_option( 'stag_framework_values' );
    if( array_key_exists( 'general_tracking_code', $stag_values ) && $stag_values['general_tracking_code'] != '' )
        echo stripslashes( $stag_values['general_tracking_code'] );
}
add_action( 'wp_footer', 'jmra_tracking_code' );
