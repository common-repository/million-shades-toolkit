<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * MSTK_Staff_Post
**/
class MSTK_Staff_Post {

	/**
	 * Constructor.
	 */
	public function __construct() {
		
		add_action( 'init', array( $this, 'create_staff' ) );
		add_action( 'admin_init', array( $this, 'staff_admin' ) );
		add_action( 'save_post', array( $this,'add_staff_fields'), 10, 2 );
		
	}

	function create_staff() {
		register_post_type( 'staffs',
			array(
            'labels' => array(
                'name' =>  __('Staffs', 'million-shades-toolkit' ),
                'singular_name' =>  __('staff', 'million-shades-toolkit' ),
                'add_new' =>  __('Add New', 'million-shades-toolkit' ),
                'add_new_item' =>  __('Add New staff', 'million-shades-toolkit' ),
                'edit' =>  __('Edit', 'million-shades-toolkit' ),
                'edit_item' =>  __('Edit staff', 'million-shades-toolkit' ),
                'new_item' =>  __('New staff', 'million-shades-toolkit' ),
                'view' =>  __('View', 'million-shades-toolkit' ),
                'view_item' =>  __('View staff', 'million-shades-toolkit' ),
                'search_items' =>  __('Search staffs', 'million-shades-toolkit' ),
                'not_found' =>  __('No staffs found', 'million-shades-toolkit' ),
                'not_found_in_trash' =>  __('No staffs found in Trash', 'million-shades-toolkit' ),
                'parent' =>  __('Parent staff', 'million-shades-toolkit' )
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-businessman',
            'has_archive' => true
        )
    );
	
	 $labels = array(
			'name' => __( 'Staff Categories', 'million-shades-toolkit' ),
			'singular_name' => __( 'Staff Category', 'million-shades-toolkit' ),
			'search_items' =>  __( 'Search Staff Categories','million-shades-toolkit' ),
			'all_items' => __( 'All Staff Categories','million-shades-toolkit' ),
			'parent_item' => __( 'Parent Staff Category','million-shades-toolkit' ),
			'parent_item_colon' => __( 'Parent Staff Category:','million-shades-toolkit' ),
			'edit_item' => __( 'Edit Staff Category','million-shades-toolkit' ), 
			'update_item' => __( 'Update Staff Category','million-shades-toolkit' ),
			'add_new_item' => __( 'Add New Staff Category','million-shades-toolkit' ),
			'new_item_name' => __( 'New Staff Category Name','million-shades-toolkit' ),
			'menu_name' => __( 'Staff Categories','million-shades-toolkit' ),
	  );     

	  register_taxonomy('staff-category',array('staffs'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'staff-category' ),
            'show_admin_column' => true
	  ));
	}


	function staff_admin() {
		add_meta_box( 'staff_meta_box',
        'Staff Details',
        array( $this, 'display_staff_meta_box' ),
        'staffs', 'normal', 'high'
		);
	}

	function display_staff_meta_box( $staff ) {
    
  
    	$social_icons_list = array(
		''=>__('none','million-shades-toolkit'),
		'behance'=>__('behance','million-shades-toolkit'),'codepen'=>__('codepen','million-shades-toolkit'),'deviantart'=>__('deviantart','million-shades-toolkit'),'digg'=>__('digg','million-shades-toolkit'),
		'dribbble'=>__('dribbble','million-shades-toolkit'),'dropbox'=>__('dropbox','million-shades-toolkit'),'facebook'=>__('facebook','million-shades-toolkit'),'flickr'=>__('flickr','million-shades-toolkit'),
		'foursquare'=>__('foursquare','million-shades-toolkit'),'google-plus'=>__('google-plus','million-shades-toolkit'),'github'=>__('github','million-shades-toolkit'),'instagram'=>__('instagram','million-shades-toolkit'),
		'linkedin'=>__('linkedin','million-shades-toolkit'),'envelope-o'=>__('envelope-o','million-shades-toolkit'),'medium'=>__('medium','million-shades-toolkit'),'pinterest-p'=>__('pinterest-p','million-shades-toolkit'),
		'get-pocket'=>__('get-pocket','million-shades-toolkit'),'reddit-alien'=>__('reddit-alien','million-shades-toolkit'),'skype'=>__('skype','million-shades-toolkit'),
		'skype'=>__('skype','million-shades-toolkit'),'slideshare'=>__('slideshare','million-shades-toolkit'),'snapchat-ghost'=>__('snapchat-ghost','million-shades-toolkit'),'soundcloud'=>__('soundcloud','million-shades-toolkit'),
		'spotify'=>__('spotify','million-shades-toolkit'),'stumbleupon'=>__('stumbleupon','million-shades-toolkit'),'tumblr'=>__('tumblr','million-shades-toolkit'),'twitch'=>__('twitch','million-shades-toolkit'),'twitter'=>__('twitter','million-shades-toolkit'),'vimeo'=>__('vimeo','million-shades-toolkit'),'vine'=>__('vine','million-shades-toolkit'),
		'vk'=>__('vk','million-shades-toolkit'),'wordpress'=>__('wordpress','million-shades-toolkit'),'wordpress'=>__('wordpress','million-shades-toolkit'),'yelp'=>__('yelp','million-shades-toolkit'),
		'youtube'=>__('youtube','million-shades-toolkit'),'none'=>__('none','million-shades-toolkit'));
	
	wp_nonce_field( 'million_shades_toolkit_staff', 'million_shades_toolkit_staff_nonce' );
	$staff_designation = esc_html( get_post_meta( $staff->ID, 'staff_designation', true ) );
	
   
    ?>
    <table>
	    <tr>
            <td style="width: 120px"><b><?php esc_html_e('Staff Designation:', 'million-shades-toolkit'); ?></b></td>
            <td><input type="text" size="80" name="staff_designation" value="<?php echo esc_attr($staff_designation); ?>" /></td>
        </tr>
	<?php

						 $staff_social_icon1 = esc_attr( get_post_meta( $staff->ID, 'staff_social_icon1', true ) );
						$staff_social_url1 = esc_url_raw( get_post_meta( $staff->ID, 'staff_social_url1', true ) );
						 $staff_social_icon2 = esc_attr( get_post_meta( $staff->ID, 'staff_social_icon2', true ) );
						$staff_social_url2 = esc_url_raw( get_post_meta( $staff->ID, 'staff_social_url2', true ) );
						 $staff_social_icon3 = esc_attr( get_post_meta( $staff->ID, 'staff_social_icon3', true ) );
						$staff_social_url3 = esc_url_raw( get_post_meta( $staff->ID, 'staff_social_url3', true ) );
						 $staff_social_icon4 = esc_attr( get_post_meta( $staff->ID, 'staff_social_icon4', true ) );
						$staff_social_url4 = esc_url_raw( get_post_meta( $staff->ID, 'staff_social_url4', true ) );
                ?>
		<tr>
		<div >
            <td style="width:120px;"><b><?php esc_html_e('Staff Social Icon #1:', 'million-shades-toolkit'); ?></b></td>
            <td >
                <select style="width: 150px" name="staff_social_icon1">
                <?php
                // Generate all items of drop-down list
				
                foreach ( $social_icons_list as $icon ) {
					$staff_icon = $icon; 
                ?>
                    <option value="<?php echo esc_attr($staff_icon); ?>" <?php echo selected( esc_attr($staff_icon), esc_attr($staff_social_icon1) ); ?>>
                    <?php echo esc_attr($staff_icon);  } ?>
                </select>
            </td>
		</div>
        </tr>
        <tr>
            <td style="width: 120px"><b><?php esc_html_e('Staff Social Url #1:', 'million-shades-toolkit'); ?></b></td>
            <td><input type="text" size="80" name="staff_social_url1" value="<?php echo esc_url($staff_social_url1); ?>" /></td>
        </tr>
		<!-- #2 -->
			<tr>
		<div >
            <td style="width:120px;"><b><?php esc_html_e('Staff Social Icon #2:', 'million-shades-toolkit'); ?></b></td>
            <td >
                <select style="width: 150px " name="staff_social_icon2">
                <?php
                // Generate all items of drop-down list
				
                foreach ( $social_icons_list as $icon ) {
					$staff_icon = $icon; 
                ?>
                    <option value="<?php echo esc_attr($staff_icon); ?>" <?php echo selected( esc_attr($staff_icon), esc_attr($staff_social_icon2) ); ?>>
                    <?php echo esc_attr($staff_icon);  } ?>
                </select>
            </td>
		</div>
        </tr>
        <tr>
            <td style="width: 120px"><b><?php esc_html_e('Staff Social Url #2:', 'million-shades-toolkit'); ?>:</b></td>
            <td><input type="text" size="80" name="staff_social_url2" value="<?php echo esc_url($staff_social_url2); ?>" /></td>
        </tr>
        <!-- #3 -->
			<tr>
		<div >
            <td style="width:120px;"><b><?php esc_html_e('Staff Social Icon #3:', 'million-shades-toolkit'); ?></b></td>
            <td >
                <select style="width: 150px " name="staff_social_icon3">
                <?php
                // Generate all items of drop-down list
				
                foreach ( $social_icons_list as $icon ) {
					$staff_icon = $icon; 
                ?>
                    <option value="<?php echo esc_attr($staff_icon); ?>" <?php echo selected( esc_attr($staff_icon), esc_attr($staff_social_icon3) ); ?>>
                    <?php echo esc_attr($staff_icon);  } ?>
                </select>
            </td>
		</div>
        </tr>
        <tr>
            <td style="width: 120px"><b><?php esc_html_e('Staff Social Url #3:', 'million-shades-toolkit'); ?></b></td>
            <td><input type="text" size="80" name="staff_social_url3" value="<?php echo esc_url($staff_social_url3); ?>" /></td>
        </tr>
		<!-- #4 -->
		<tr>
		<div >
            <td style="width:120px;"><b><?php esc_html_e('Staff Social Icon #4:', 'million-shades-toolkit'); ?></b></td>
            <td >
                <select style="width: 150px" name="staff_social_icon4">
                <?php
                // Generate all items of drop-down list
				
                foreach ( $social_icons_list as $icon ) {
					$staff_icon = $icon; 
                ?>
                    <option value="<?php echo esc_attr($staff_icon); ?>" <?php echo selected( esc_attr($staff_icon), esc_attr($staff_social_icon4) ); ?>>
                    <?php echo esc_attr($staff_icon);  } ?>
                </select>
            </td>
		</div>
        </tr>
        <tr>
            <td style="width: 120px"><b><?php esc_html_e('Staff Social Url #4:', 'million-shades-toolkit'); ?></b></td>
            <td><input type="text" size="80" name="staff_social_url4" value="<?php echo esc_url($staff_social_url4); ?>" /></td>
        </tr>
		
    </table>
    <?php
	}



	function add_staff_fields( $staff_id, $staff ) {
		
		if ( ! isset( $_POST['million_shades_toolkit_staff_nonce'] ) )
			return $staff_id;

		$nonce = $_POST['million_shades_toolkit_staff_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'million_shades_toolkit_staff' ) )
			return $staff_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $staff_id;

		if ( $staff->post_type == 'staffs' ) {

			if ( ! current_user_can( 'edit_page', $staff_id ) )
				return $staff_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $staff_id ) )
				return $staff_id;
		}
		
    // Check post type for staffs
		if ( $staff->post_type == 'staffs' ) {
        // Save data of staff post fields
			    if ( isset( $_POST['staff_designation'] ) ) {
					update_post_meta( $staff_id, 'staff_designation', sanitize_text_field($_POST['staff_designation']) );
				}
     
				if ( isset( $_POST['staff_social_url1'] )) {
					update_post_meta( $staff_id, 'staff_social_url1', esc_url_raw($_POST['staff_social_url1']) );
				}
		        if ( isset( $_POST['staff_social_icon1'] ) ) {
					update_post_meta( $staff_id, 'staff_social_icon1', sanitize_text_field($_POST['staff_social_icon1']) );
				}
		
				if ( isset( $_POST['staff_social_url2'] ) ) {
					update_post_meta( $staff_id, 'staff_social_url2', esc_url_raw($_POST['staff_social_url2']) );
				}
		        if ( isset( $_POST['staff_social_icon2'] ) ) {
					update_post_meta( $staff_id, 'staff_social_icon2', sanitize_text_field($_POST['staff_social_icon2']) );
				}
		
				if ( isset( $_POST['staff_social_url3'] ) ) {
					update_post_meta( $staff_id, 'staff_social_url3', esc_url_raw($_POST['staff_social_url3']) );
				}
		        if ( isset( $_POST['staff_social_icon3'] ) ) {
					update_post_meta( $staff_id, 'staff_social_icon3', sanitize_text_field($_POST['staff_social_icon3']) );
				}
		
				 if ( isset( $_POST['staff_social_url4'] )) {
					update_post_meta( $staff_id, 'staff_social_url4', esc_url_raw($_POST['staff_social_url4']) );
				}
		        if ( isset( $_POST['staff_social_icon4'] ) ) {
				update_post_meta( $staff_id, 'staff_social_icon4', sanitize_text_field($_POST['staff_social_icon4']) );
				}

		}
	}
}



new MSTK_Staff_Post();


add_filter( 'template_include', 'mstk_get_staff_template_path', 1 );

// return path staff-single
function mstk_get_staff_template_path( $template_path ) {
    if ( get_post_type() == 'staffs' ) {
        if ( is_single() ) {
            
            if ( $theme_file = locate_template( array ( 'single-staffs.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = 'single-staffs.php';
            }
        }
    }
    return $template_path;
}
