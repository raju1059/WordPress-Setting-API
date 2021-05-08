<?php

/**
 * Admin Theme Options Page
 * 
 * @return void
 */
function jmra_options_page(){
	$stag_options = get_option( 'jmra_framework_options' );
	ksort( $stag_options['stag_framework'] );
?>
	
	<?php if( isset( $_GET['activated'] ) ) : ?>
	<div class="updated admin-message" id="message">
		<p><?php echo sprintf( __( '<strong>%s activated.</strong> Please save settings before leaving this page.', 'stag' ), JMRA_THEME_NAME ); ?></p>
	</div>
	<?php endif; ?>

	<div id="stag-notification" class="stag-notification" data-success="<?php _e( 'Settings saved!', 'stag' ); ?>" data-error="<?php _e( 'Error saving settings!', 'stag' ); ?>"></div>
	
	<div id="stag-admin-wrap">
		<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post" id="stag-form">

			<header class="stag-options-header">
				<figure class="stag-logo">
					<a href="<?php echo JMRA_THEME_NAME; ?>" title="Codestag" target="_blank"></a>
				</figure>
				<h1>
					<?php echo JMRA_THEME_NAME; ?>
					<span><?php _e( 'v', 'stag' ); echo JMRA_THEME_VERSION; ?></span>
				</h1>
				<p>
					<?php
					echo sprintf( __( 'By <a href="%1$s" target="_blank">%2$s</a> <span class="divider">&#47;</span> <a href="%3$s" target="_blank">Documentation</a> <span class="divider">&#47;</span> <a href="%4$s" target="_blank">Support</a>', 'stag' ),
					JMRA_THEME_AUTHOR_URI,
					JMRA_THEME_AUTHOR,
					'//wordpress.org/docs/' . stag_to_slug( JMRA_THEME_NAME ) . '.pdf',
					JMRA_SUPPORT_URI
					);
					?>
				</p>
			</header><!-- .stag-options-header -->

			<div class="stag-content stag-clearfix">
				<aside class="stag-sidebar">
					<ul>
						<?php foreach( $stag_options['stag_framework'] as $page ) : ?>
						<li data-section="<?php echo stag_to_slug( key( $page) ); ?>">
							<a href="#<?php echo stag_to_slug( key( $page) ); ?>"><?php echo key( $page ); ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</aside>
				
				<div class="stag-main-content">
					<?php foreach( $stag_options['stag_framework'] as $page ) : ?>
					<div id="page-<?php echo stag_to_slug( key( $page ) ); ?>" class="page">
						<h2><?php echo key( $page ); ?></h2>
						<?php if( isset( $page[key( $page )]['description'] ) && $page[key( $page )]['description'] != '' ) : ?>
						<p class="page-description"><?php echo $page[key( $page )]['description']; ?></p>
						<?php endif; ?>

						<?php
						foreach( $page[key( $page )] as $item ):
							if( key((array)$item) == 'description' ) continue;
						?>

						<div class="<?php echo stag_to_slug( @$item['title'] ); ?> row stag-clearfix">
							<h3><?php echo $item['title']; ?></h3>							
							<?php if( isset( $item['desc'] ) && $item['desc'] != '' ) : ?>
							<div class="desc"><?php echo $item['desc']; ?></div>
							<?php endif; ?>

							<?php jmra_create_field( $item ); ?>
						</div><!-- .row -->

						<?php endforeach ?>
					</div><!-- .page -->
					<?php endforeach; ?>

					<div class="stag-clear"></div>
					
					<footer class="stag-footer">
						<input type="hidden" name="action" value="stag_framework_save" />
						<input type="hidden" name="stag_noncename" id="stag_noncename" value="<?php echo wp_create_nonce('jmra_framework_options'); ?>" />
						<input type="button" value="<?php _e( 'Reset Options', 'stag' ); ?>" class="stag-button stag-button--reset" id="reset-button" />
						<button id="save-button" class="stag-button stag-button--save ladda-button" data-color="white" data-style="zoom-out" data-spinner-color="#fff" name="button" type="submit"><?php _e( 'Save Changes', 'stag' ); ?></button>
					</footer>

				</div>
			
			</div><!-- .stag-content -->

		</form><!-- #stag-form -->

	</div><!-- #stag-admin-wrap -->

<?php
}

/**
 * Admin Scripts for theme options page
 */
function jmra_admin_interface_styles( $hook ) {
	if( $hook != 'toplevel_page_stagframework' ) return;
	
	wp_register_style( 'stag-admin-interface', plugin_dir_url(__FILE__) . '/assets/css/stag-admin-interface.css', array( 'wp-color-picker' ), JMRA_FRAMEWORK_VERSION );
	wp_register_script( 'stag-admin-interface', plugin_dir_url(__FILE__) . '/assets/js/stag-admin-interface.js', array( 'jquery', 'wp-color-picker' ), JMRA_FRAMEWORK_VERSION, true );

	wp_enqueue_style( 'stag-admin-interface' );

	wp_enqueue_media();
	wp_enqueue_script( 'stag-admin-interface' );

}
add_action( 'admin_enqueue_scripts', 'jmra_admin_interface_styles' );


/**
 * Save admin options via Ajax
 */
function jmra_framework_save(){
	$resp['error'] = false;
	$resp['message'] = '';
	$resp['type'] = '';

	// Verify it it's coming from right screen
	if( !isset( $_POST['stag_noncename'] ) || !wp_verify_nonce( $_POST['stag_noncename'], plugin_basename( 'jmra_framework_options' ) ) ) {
		$resp['error'] = true;
		$resp['message'] = __( 'You do not have sufficient permissions to save these options.', 'stag' );
		echo json_encode($resp);
		die;
	}

	$stag_values = get_option( 'stag_framework_values' );
	foreach( $_POST['settings'] as $key => $val ) {
		$stag_values[$key] = $val;
	}

	$stag_values = apply_filters( 'jmra_framework_save', $stag_values );

	update_option( 'stag_framework_values', $stag_values );

	$resp['message'] = __( 'Settings saved', 'stag' );
	echo json_encode($resp);
	die;
}
add_action( 'wp_ajax_stag_framework_save', 'jmra_framework_save' );

/**
 * Reset admin options
 */
function jmra_admin_reset(){
	$resp['error'] = false;
	$resp['message'] = '';

	if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], plugin_basename( 'jmra_framework_options' ) ) ) {
	    $resp['error'] = true;
	    $resp['message'] = __('You do not have sufficient permissions to reset these options.', 'stag' );
	    echo json_encode($resp);
		die;
	}

	update_option( 'stag_framework_values', array() );
	echo json_encode( $resp );
	die;
}
add_action( 'wp_ajax_stag_admin_reset', 'jmra_admin_reset' );
