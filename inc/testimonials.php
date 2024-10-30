<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * nl_Testimonial_Post class.
**/
class MSTK_Testimonial_Post {

	/**
	 * Constructor.
	 */
	public function __construct() {
		
		add_action( 'init', array( $this, 'create_testimonial' ) );
		add_action( 'admin_init', array( $this, 'testimonial_admin' ) );
		add_action( 'save_post', array( $this,'add_testimonial_fields'), 10, 2 );
		
	}

	function create_testimonial() {
		register_post_type( 'testimonials',
			array(
				'labels' => array(
					'name' =>  __('Testimonials', 'million-shades-toolkit' ),
					'singular_name' =>  __('Testimonial', 'million-shades-toolkit' ),
					'add_new' =>  __('Add New', 'million-shades-toolkit' ),
					'add_new_item' =>  __('Add New Testimonial', 'million-shades-toolkit' ),
					'edit' =>  __('Edit', 'million-shades-toolkit' ),
					'edit_item' =>  __('Edit Testimonial', 'million-shades-toolkit' ),
					'new_item' =>  __('New Testimonial','million-shades-toolkit' ),
					'view' =>  __('View', 'million-shades-toolkit' ),
					'view_item' =>  __('View Testimonial','million-shades-toolkit' ),
					'search_items' =>  __('Search Testimonials', 'million-shades-toolkit' ),
					'not_found' =>  __('No Testimonials found', 'million-shades-toolkit' ),
					'not_found_in_trash' =>  __('No Testimonials found in Trash', 'million-shades-toolkit' ),
					'parent' =>  __('Parent Testimonial', 'million-shades-toolkit' )
				),
 
				'public' => true,
				'menu_position' => 15,
				'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
				'taxonomies' => array( '' ),
				'menu_icon' => 'dashicons-heart',
				'has_archive' => true
        )
    );
	
	
	/* Create Testimonial Categories */

	  $labels = array(
			'name' => __( 'Testimonial Categories', 'million-shades-toolkit' ),
			'singular_name' => __( 'Testimonial Category', 'million-shades-toolkit' ),
			'search_items' =>  __( 'Search Testimonial Categories','million-shades-toolkit' ),
			'all_items' => __( 'All Testimonial Categories','million-shades-toolkit' ),
			'parent_item' => __( 'Parent Testimonial Category','million-shades-toolkit' ),
			'parent_item_colon' => __( 'Parent Testimonial Category:','million-shades-toolkit' ),
			'edit_item' => __( 'Edit Testimonial Category','million-shades-toolkit' ), 
			'update_item' => __( 'Update Testimonial Category','million-shades-toolkit' ),
			'add_new_item' => __( 'Add New Testimonial Category','million-shades-toolkit' ),
			'new_item_name' => __( 'New Testimonial Category Name','million-shades-toolkit' ),
			'menu_name' => __( 'Testimonial Categories','million-shades-toolkit' ),
	  );     

	  register_taxonomy('testimonial-category',array('testimonials'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'testimonial-category' ),
            'show_admin_column' => true
	  ));
	}	


	function testimonial_admin() {
		add_meta_box( 'testimonial_meta_box',
        'Testimonial Details',
        array( $this, 'display_testimonial_meta_box' ),
        'testimonials', 'normal', 'high'
		);
	}

	function display_testimonial_meta_box( $testimonial ) {
		
		wp_nonce_field( 'million_shades_toolkit_testimonial', 'million_shades_toolkit_testimonial_nonce' );
		$company_name = esc_html( get_post_meta( $testimonial->ID, 'company_name', true ) );
		$company_url = esc_url( get_post_meta( $testimonial->ID, 'company_url', true ) );
		?>
		<table>
			<tr>
				<td style="width: 100%"><?php esc_html_e('Company Name:', 'million-shades-toolkit'); ?></td>
				<td><input type="text" size="80" name="testimonial_company_name" value="<?php echo esc_attr($company_name); ?>" /></td>
			</tr>
			<tr>
				<td style="width: 100%"><?php esc_html_e('Company Url:', 'million-shades-toolkit'); ?></td>
				<td><input type="text" size="80" name="testimonial_company_url" value="<?php echo esc_url($company_url); ?>" /></td>
			</tr>
		</table>
		<?php
	}



	function add_testimonial_fields( $testimonial_id, $testimonial ) {
		
		if ( ! isset( $_POST['million_shades_toolkit_testimonial_nonce'] ) )
			return $testimonial_id;

		$nonce = $_POST['million_shades_toolkit_testimonial_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'million_shades_toolkit_testimonial' ) )
			return $testimonial_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $testimonial_id;

		if ($testimonial->post_type == 'testimonials' ) {

			if ( ! current_user_can( 'edit_page', $testimonial_id ) )
				return $testimonial_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $testimonial_id ) )
				return $testimonial_id;
		}
		
		// Check post type for Testimonials
		if ( $testimonial->post_type == 'testimonials' ) {
			// Save data of testimonial post fields
			if ( isset( $_POST['testimonial_company_name'] )) {
				update_post_meta( $testimonial_id, 'company_name', sanitize_text_field($_POST['testimonial_company_name']) );
			}
			if ( isset( $_POST['testimonial_company_url'] )) {
				update_post_meta( $testimonial_id, 'company_url', esc_url_raw($_POST['testimonial_company_url']) );
			}
		}
	}	
}



return new MSTK_Testimonial_Post();



// return path testimonial-single
function mstk_get_testimonial_template_path( $template_path ) {
    if ( get_post_type() == 'testimonials' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-testimonials.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = 'single-testimonials.php';
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'mstk_get_testimonial_template_path', 1 );