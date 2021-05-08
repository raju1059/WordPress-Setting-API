<?php

add_action( 'admin_init', 'jmra_blog_settings' );

function jmra_blog_settings(){
	$settings['description'] = __( 'Customize your blog settings.', 'stag' );

	$settings[] = array(
	    'title' => __( 'Blog Title', 'stag' ),
	    'desc'  => __( 'Enter the default title for the blog header section.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'blog_title',
	    'val'   => 'Blog Posts'
	);

	$settings[] = array(
	    'title' => __( 'Blog Subtitle', 'stag' ),
	    'desc'  => __( 'Enter the default subtitle for the blog header section.', 'stag' ),
	    'type'  => 'text',
	    'id'    => 'blog_subtitle',
	    'val'   => 'I am blogging bout things all the time. dive in!'
	);

	jmra_add_framework_page( __( 'Blog Settings', 'stag' ), $settings, 15 );
}
