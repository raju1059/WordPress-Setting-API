<?php

/**
 * Convert a string into slug
 * 
 * @param string $str Input string
 * @return string Valid URL string
 */
function stag_to_slug( $str ) {
	$str = strtolower( trim( $str ) );
	$str = preg_replace( '/[^a-z0-9-]/', '-', $str );
	$str = preg_replace( '/-+/', '-', $str );
	return $str;
}

/**
 * Pass inputs for StagFramework Settings Page
 * 
 * @param array $item Array holding input item values
 */
function  jmra_create_field( $item ) {
	$stag_values = get_option( 'stag_framework_values' );

	echo '<div class="input '. stag_to_slug( $item['type'] ) .'">';

	// Set the class
	$class = '';
	if( array_key_exists( 'class', $item ) ) $class = ' class="'. $item['class'] .'"';

	// Set any additional attribute
	$attr = '';
	if( array_key_exists( 'attr', $item ) ) $attr = $item['attr'];

	$prefix = 'settings';
	if( array_key_exists( 'ignore', $item ) && $item['ignore'] == true ) $prefix = 'ignore';

	if( isset($stag_values[$item['id']]) && $stag_values[$item['id']] != '' ){
		$stag_values[$item['id']] = stripslashes( $stag_values[$item['id']] );
	}

	// Text Input
	if( $item['type'] == 'text' ) {
		$val = '';
		if(array_key_exists('val', $item)) $val = ' value="'. $item['val'] .'"';
		if(array_key_exists($item['id'], $stag_values)) $val = ' value="'. $stag_values[$item['id']] .'"';
		echo '<input type="text" id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']" '. $val . $class . $attr .'  />';
	}

	// Textarea
	if( $item['type'] == 'textarea' ) {
		$val = '';
		if(array_key_exists('val', $item)) $val = $item['val'];
		if(array_key_exists($item['id'], $stag_values)) $val = $stag_values[$item['id']];
		echo '<textarea id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']"'. $class. $attr .'>'. $val .'</textarea>';
	}

	// Select
	if( $item['type'] == 'select' && array_key_exists( 'options', $item ) ) {
		echo '<select id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']"'. $class. $attr .'>';
		foreach( $item['options'] as $key => $value ) {
			$val = '';
			if( array_key_exists( $item['id'], $stag_values ) ) {
				if( $stag_values[$item['id']] == $key ) $val = ' selected="selected"';
			}else{
				if( array_key_exists( 'val', $item ) && $item['val'] == $key ) $val = ' selected="selected"';
			}
			echo '<option value="'. $key .'"'. $val .'>'. $value .'</option>';
		}
		echo '</select>';
	}

	// Page Select
	if( $item['type'] == 'pages' ) {
		$pages_obj = get_pages();

		echo '<select id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']"'. $class. $attr .'>';
		echo '<option value="-1">'. __('Select', 'stag') .'</option>';
		foreach( $pages_obj as $stag_page ) {
			$val = '';
			if( array_key_exists( $item['id'], $stag_values ) ) {
				if( $stag_values[$item['id']] == $stag_page->ID ) $val = ' selected="selected"';
			} else {
				if( array_key_exists( 'val', $item ) && $item['val'] == $stag_page->ID ) $val = ' selected="selected"';
			}
			echo '<option value="'. $stag_page->ID .'"'. $val .'>'. $stag_page->post_title .'</option>';
		}
		echo '</select>';
	}

	// Categories Select
	if( $item['type'] == 'categories' ) {
		$stag_categories_obj = get_categories('hide_empty=0');

		echo '<select id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']"'. $class. $attr .'>';
		foreach( $stag_categories_obj as $stag_category ) {
		    $val = '';
		    if( array_key_exists($item['id'], $stag_values ) ) {
		        if( $stag_values[$item['id']] == $stag_category->cat_ID ) $val = ' selected="selected"';
		    } else {
		        if( array_key_exists( 'val', $item ) && $item['val'] == $stag_category->cat_ID ) $val = ' selected="selected"';
		    }
		    echo '<option value="'. $stag_category->cat_ID .'"'. $val .'>'. $stag_category->cat_name .'</option>';
		}
		echo '</select>';
	}

	// radio
    if( $item['type'] == 'radio' && array_key_exists('options', $item ) ) {
    	$i = 0;
        foreach($item['options'] as $key=>$value){   
            $val = '';
            if(array_key_exists($item['id'], $stag_values)){
                if($stag_values[$item['id']] == $key) $val = ' checked="checked"';
            } else {
                if(array_key_exists('val', $item) && $item['val'] == $key) $val = ' checked="checked"';
            }
            echo '<label for="'. $item['id'] .'_'. $i .'"><input type="radio" id="'. $item['id'] .'_'. $i .'" name="'. $prefix .'['. $item['id'] .']" value="'. $key .'"'. $val . $class. $attr .'> '. $value .'</label><br />';
            $i++;
        }
    }

    // checkbox
    if( $item['type'] == 'checkbox' ) {
        $val = '';
        if(array_key_exists('val', $item) && $item['val'] == 'on') $val = ' checked="yes"';
        if(array_key_exists($item['id'], $stag_values) && $stag_values[$item['id']] == 'on') $val = ' checked="yes"';
        if(array_key_exists($item['id'], $stag_values) && $stag_values[$item['id']] != 'on') $val = '';
        echo '<input type="hidden" name="'. $prefix .'['. $item['id'] .']" value="off" />
        <input type="checkbox" id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']" value="on"'. $class . $val. $attr .' /> ';
        if(array_key_exists('text', $item)) echo $item['text'];
    }

    // multi checkbox
    if( $item['type'] == 'multi_checkbox' && array_key_exists('options', $item ) ) {
        foreach($item['options'] as $key=>$value){  
            $val = '';
            $id = $item['id'].'_'.stag_to_slug($key);
            if($value == 'on') $val = ' checked="yes"';
            if(array_key_exists($id, $stag_values) && $stag_values[$id] == 'on') $val = ' checked="yes"';
            if(array_key_exists($id, $stag_values) && $stag_values[$id] != 'on') $val = '';
            echo '<input type="hidden" name="'. $prefix .'['. $id .']" value="off" />
            <input type="checkbox" id="'. $id .'" name="'. $prefix .'['. $id .']" value="on"'. $class. $attr . $val .' /> ';
            echo '<label for="'. $id .'">'. $key .'</label><br />';
        }
    }

    // WYSIWYG
    if( $item['type'] == 'wysiwyg' ) {
    	$val = '';
    	$class .= ' class="wysiwyg_editor"';
    	if( array_key_exists( 'val', $item ) ) $val = $item['val'];
    	if( array_key_exists( $item['id'], $stag_values ) ) $val = $stag_values[$item['id']];
    	echo '<textarea id="'. $item['id'] .'" name="'. $prefix .'['. $item['id'] .']"'. $class . $attr .' >'. $val .'</textarea>';
    }

    // Color Picker
    if($item['type'] == 'color'){
		$val = '';
		$class .= 'class="colorpicker"';
		if( array_key_exists( 'val', $item ) ) $val = ' value="'. $item['val'] .'"';
		if( array_key_exists( $item['id'], $stag_values ) ) $val = ' value="'. $stag_values[$item['id']] .'"';
		echo '<input data-default-color="'.$item['val'].'" type="text" id="'. $item['id'] .'_cp" name="'. $prefix .'['. $item['id'] .']"'. $val . $class . $attr .' />';
    }

    // File
    if( $item['type'] == 'file' || $item['type'] == 'files' ) {
    	$val = __( 'Upload', 'stag' );
    	if( array_key_exists( 'val', $item ) ) $val = $item['val'];
    	$wp_uploads = wp_upload_dir();
    	?>
		<div id="uploaded_<?php echo $item['id']; ?>" class="file-upload">
			<?php
			if ( array_key_exists( $item['id'], $stag_values ) && $item['id'] != '' ) {
				$values = $stag_values[$item['id']];
				$values = explode(",", $values);
				foreach($values as $value){
					$ext = substr( $value, strrpos( $value, '.' ) + 1 );
					if ( $ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'webp' ) {
						echo '<img src="' . $value . '" alt"" />';
					} else {
						echo $value;
					}
				}
			}
			?>
		</div>
    	<?php
    	$output = '';
    	if( isset( $stag_values[$item['id']] ) && $stag_values[$item['id']] != '' ) {
    		$output = $stag_values[$item['id']];
    	}
    	?>

    	<input type="text" id="text_<?php echo $item['id']; ?>" name="settings[<?php echo $item['id']; ?>]" hidden value="<?php echo @$output; ?>" >
    	<a href="#" id="file_upload_<?php echo $item['id']; ?>" class="stag-upload-button" <?php echo @$attr; ?>><?php echo $val; ?></a>
    	<a href="#" id="file_remove_<?php echo $item['id']; ?>" class="stag-remove-button" <?php if(@$stag_values[$item['id']] == ''){ echo ' style="display:none;"'; } ?>><?php _e('Remove', 'stag'); ?></a>

    	<script type="text/javascript">
    	jQuery(document).ready(function($){
    		var uploadButton = $("#file_upload_<?php echo $item['id'] ?>"),
    			removeButton = $("#file_remove_<?php echo $item['id'] ?>"),
    			container = $("#uploaded_<?php echo $item['id']; ?>"),
    			text = $("#text_<?php echo $item['id']; ?>"),
    			isMultiple = ( "<?php echo $item['type'] ?>" === 'files' ) ? true : false,
    			file_frame;

			uploadButton.on('click', function(e){
				e.preventDefault();

				if(file_frame){
					file_frame.open();
					return;
				}

				file_frame = wp.media.frames.file_frame = wp.media({
					title: "<?php _e('Upload or Choose your own Image file', 'stag'); ?>",
					button: {
						text: "<?php _e('Insert', 'stag'); ?>"
					},
					frame: 'select',
					library: {
					    type: 'image'
					},
					multiple: isMultiple
				});

				file_frame.on('select', function(){
					container.html('');

					if(isMultiple){
						var selection = file_frame.state().get('selection');
						var files = [];
						
						selection.map(function(attachment){
							files.push(attachment.attributes.url);
						});

						text.val(files);

						for( var i = 0; i < files.length; i++ ) {
							var ext = files[i].substr(files[i].lastIndexOf(".") + 1, files[i].length);
							if(ext && /^(jpg|png|jpeg|gif|webp)$/.test(ext)){
								container.append('<img src="'+files[i]+'" alt="" />');
								removeButton.show();
							}else{
								container.append(files[i]);
							}
						}

					}else{
						var attachment = file_frame.state().get('selection').first().toJSON();
						var file = attachment.url;
						text.val(file);	

						var ext = file.substr(file.lastIndexOf(".") + 1, file.length);
						if(ext && /^(jpg|png|jpeg|gif|webp)$/.test(ext)){
							container.append('<img src="'+file+'" alt="" />');
							removeButton.show();
						}else{
							container.append(file);
						}
					}
				});

				file_frame.open();

			});

			removeButton.on('click', function(e){
				e.preventDefault();
				removeButton.text('Removing...');
				text.val('');
				container.html('');
				removeButton.text('Remove');
				removeButton.hide();
			});

    	});
    	</script>

		<?php
    }

    // Some HTML
    if( $item['type'] == 'html' ) {
    	echo $item['html'];
    }

    // Custom Function
    if( $item['type'] == 'custom' ) {
    	$func = '';
    	$args = array();
    	$id = '';
    	if( array_key_exists( 'function', $item ) ) $func = $item['function'];
    	if( array_key_exists( 'args', $item ) ) $args = $item['args'];
    	if( array_key_exists( 'id', $item ) ) $id = $item['id'];

    	if( $func != '' ) call_user_func( $func, $id, $args );
    }


	echo '</div>';
}

/**
 * Adds a framework page
 * 
 * @param string $title Title of the framework page
 * @return void
 */
function jmra_add_framework_page( $title, $data, $order = 0 ) {
	if( !is_array( $data ) ) return false;

	$stag_options = get_option( 'jmra_framework_options' );
	$stag_framework = array();
	
	if( is_array( $stag_options['stag_framework'] ) ) $stag_framework = $stag_options['stag_framework'];

	$stag_framework[$order] = array( $title => $data );

	$stag_options['stag_framework'] = $stag_framework;
	update_option( 'jmra_framework_options', $stag_options );
}
