<?php
/**
 *
 * JMRA Framework
 *
 * @package WordPress
 * @subpackage JMRASettingAPI
 * @since 2.0.0
 * @version 2.0.1
 * @author Raju Ahmed
 * @link https://wordpress.org
 *
 */
if ( !class_exists('JMRASettingAPI' ) ):
class JMRASettingAPI{
	public $version = '2.0.1';

	function __construct(){
		define( 'JMRA_FRAMEWORK_VERSION', $this->version );

		if(function_exists('wp_get_theme')){
		  if(is_child_theme()){
		    $temp_obj = wp_get_theme();
		    $theme_obj = wp_get_theme( $temp_obj->get('Template') );
		  }else{
		    $theme_obj = wp_get_theme();
		  }
		  $theme_version = $theme_obj->get('Version');
		  $theme_name = $theme_obj->get('Name');
		  $theme_uri = $theme_obj->get('ThemeURI');
		  $theme_author = $theme_obj->get('Author');
		  $theme_author_uri = $theme_obj->get('AuthorURI');
		}

		define( 'JMRA_THEME_NAME', $theme_name );
		define( 'JMRA_THEME_VERSION', $theme_version );
		define( 'JMRA_THEME_URI', $theme_uri );
		define( 'JMRA_THEME_AUTHOR', $theme_author );
		define( 'JMRA_THEME_AUTHOR_URI', $theme_author_uri );

		define( 'JMRA_SUPPORT_URI', '//wordpress.org' );
		define( 'JMRA_HOME', '//wordpress.org' );
		define( 'JMRA_DEBUG', true );
	}
}
endif;

$GLOBALS['JMRASettingAPI'] = new JMRASettingAPI();
