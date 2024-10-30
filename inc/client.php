<?php



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * nl_Client_Post.
**/
class MSTK_Client_Post {

	/**
	 * Constructor.
	 */
	public function __construct() {
		
		add_action( 'init', array( $this, 'create_client' ) );
		add_action( 'admin_init', array( $this, 'client_admin' ) );
		
		add_action( 'save_post', array( $this,'add_client_fields'), 10, 2 );
		
	}
	
	function create_client() {
		register_post_type( 'clients',
			array(
				'labels' => array(
					'name' => __('Clients', 'million-shades-toolkit' ),
					'singular_name' => __('client', 'million-shades-toolkit' ),
					'add_new' => __('Add New', 'million-shades-toolkit' ),
					'add_new_item' => __('Add New client', 'million-shades-toolkit' ),
					'edit' => __('Edit', 'million-shades-toolkit' ),
					'edit_item' => __('Edit client', 'million-shades-toolkit' ),
					'new_item' => __('New client', 'million-shades-toolkit' ),
					'view' => __('View', 'million-shades-toolkit' ),
					'view_item' => __('View client', 'million-shades-toolkit' ),
					'search_items' => __('Search clients', 'million-shades-toolkit' ),
					'not_found' => __('No clients found', 'million-shades-toolkit' ),
					'not_found_in_trash' => __('No clients found in Trash','million-shades-toolkit' ),
					'parent' => __('Parent client', 'million-shades-toolkit' )
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-groups',
            'has_archive' => true
        )
    );
	
		 $labels = array(
			'name' => __( 'Client Categories', 'million-shades-toolkit' ),
			'singular_name' => __( 'Client Category', 'million-shades-toolkit' ),
			'search_items' =>  __( 'Search Client Categories','million-shades-toolkit' ),
			'all_items' => __( 'All Client Categories','million-shades-toolkit' ),
			'parent_item' => __( 'Parent Client Category','million-shades-toolkit' ),
			'parent_item_colon' => __( 'Parent Client Category:','million-shades-toolkit' ),
			'edit_item' => __( 'Edit Client Category','million-shades-toolkit' ), 
			'update_item' => __( 'Update Client Category','million-shades-toolkit' ),
			'add_new_item' => __( 'Add New Client Category','million-shades-toolkit' ),
			'new_item_name' => __( 'New Client Category Name','million-shades-toolkit' ),
			'menu_name' => __( 'Client Categories','million-shades-toolkit' ),
	  );     

	  register_taxonomy('client-category',array('clients'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'client-category' ),
            'show_admin_column' => true
	  ));
	}


	function client_admin() {
	
		add_meta_box( 'client_meta_box',
			'client Details',
        
			array( $this, 'display_client_meta_box' ),
			'clients', 'normal', 'high'
		);
	}


	public function display_client_meta_box( $client ) {

		wp_nonce_field( 'million_shades_toolkit_client', 'million_shades_toolkit_client_nonce' );
		$client_url = esc_url( get_post_meta( $client->ID, 'client_url', true ) );
		?>
		<table>

			<tr>
				<td style="width: 100%"><?php esc_html_e('Client Url:(Leave it blank)', 'million-shades-toolkit'); ?></td>
				<td><input type="text" size="80" name="client_url" value="<?php echo esc_url($client_url); ?>" /></td>
			</tr>
		</table>
		<?php
	}



	public function add_client_fields( $client_id, $client ) {
		
		if ( ! isset( $_POST['million_shades_toolkit_client_nonce'] ) )
			return $client_id;

		$nonce = $_POST['million_shades_toolkit_client_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'million_shades_toolkit_client' ) )
			return $client_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $client_id;

		if ( $client->post_type == 'clients' ) {

			if ( ! current_user_can( 'edit_page', $client_id ) )
				return $client_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $client_id ) )
				return $client_id;
		}
		
		if ( $client->post_type == 'clients' ) {
        // Save data of client_url field
       
			if ( isset( $_POST['client_url'] ) ) {
				update_post_meta( $client_id, 'client_url', esc_url_raw($_POST['client_url']) );
			}
		}
	}

}



return new MSTK_Client_Post();



// return path clinet-single
function mstk_get_client_template_path( $template_path ) {
	
    if ( get_post_type() == 'clients' ) {
        if ( is_single() ) {
            
            if ( $theme_file = locate_template( array ( 'single-clients.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = 'single-clients.php';
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'mstk_get_client_template_path', 1 );
