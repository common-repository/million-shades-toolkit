<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * MSTK_Portfolio_Post  Class.
**/
class MSTK_Portfolio_Post {

	/**
	 * Constructor.
	 */
	public function __construct() {
		
		add_action( 'init', array( $this, 'create_portfolio' ) );
		add_action( 'admin_init', array( $this, 'portfolio_admin' ) );
		add_action( 'save_post', array( $this,'add_portfolio_fields'), 10, 2 );
		
	}

	function create_portfolio() {
		register_post_type( 'portfolios',
			array(
				'labels' => array(
					'name' => __('Portfolios', 'million-shades-toolkit' ),
					'singular_name' => __('portfolio', 'million-shades-toolkit' ),
					'add_new' => __('Add New', 'million-shades-toolkit' ),
					'add_new_item' => __('Add New portfolio', 'million-shades-toolkit' ),
					'edit' => __('Edit', 'million-shades-toolkit' ),
					'edit_item' => __('Edit portfolio', 'million-shades-toolkit' ),
					'new_item' => __('New portfolio', 'million-shades-toolkit' ),
					'view' => __('View', 'million-shades-toolkit' ),
					'view_item' => __('View portfolio', 'million-shades-toolkit' ),
					'search_items' => __('Search portfolios', 'million-shades-toolkit' ),
					'not_found' => __('No portfolios found', 'million-shades-toolkit' ),
					'not_found_in_trash' => __('No portfolios found in Trash', 'million-shades-toolkit' ),
					'parent' => __('Parent portfolio', 'million-shades-toolkit' )
				),
 
				'public' => true,
				'menu_position' => 15,
				'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
				'taxonomies' => array( '' ),
				'menu_icon' => 'dashicons-desktop', 
				'has_archive' => true
        )
    );



/* Create Portfolio Categories */

	  $labels = array(
			'name' => __('Portfolio Categories', 'million-shades-toolkit' ),
			'singular_name' => __( 'Portfolio Category', 'million-shades-toolkit' ),
			'search_items' =>  __( 'Search Portfolio Categories','million-shades-toolkit' ),
			'all_items' => __( 'All Portfolio Categories','million-shades-toolkit' ),
			'parent_item' => __( 'Parent Portfolio Category','million-shades-toolkit' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:','million-shades-toolkit' ),
			'edit_item' => __( 'Edit Portfolio Category','million-shades-toolkit' ), 
			'update_item' => __( 'Update Portfolio Category','million-shades-toolkit' ),
			'add_new_item' => __( 'Add New Portfolio Category','million-shades-toolkit' ),
			'new_item_name' => __( 'New Portfolio Category Name','million-shades-toolkit' ),
			'menu_name' => __( 'Portfolio Categories','million-shades-toolkit' ),
	  );     

	  register_taxonomy('portfolio-category',array('portfolios'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'portfolio-category' ),
            'show_admin_column' => true
	  ));

	


	}


	public function portfolio_admin() {
		add_meta_box( 'portfolio_meta_box',
        'portfolio Details',
        array( $this, 'display_portfolio_meta_box' ),
        'portfolios', 'normal', 'high'
		);
	}


	public function display_portfolio_meta_box( $portfolio ) {
		wp_nonce_field( 'million_shades_toolkit_portfolio', 'million_shades_toolkit_portfolio_nonce' );
		$portfolio_url = esc_url( get_post_meta( $portfolio->ID, 'portfolio_url', true ) );
		?>
		<table>

			<tr>
				<td style="width: 100%"><?php esc_html_e('Portfolio Url:(Leave it blank)', 'million-shades-toolkit'); ?></td>
				<td><input type="text" size="80" name="portfolio_url" value="<?php echo esc_url($portfolio_url); ?>" /></td>
			</tr>
		</table>
		<?php
	}



	public function add_portfolio_fields( $portfolio_id, $portfolio ) {
		
		if ( ! isset( $_POST['million_shades_toolkit_portfolio_nonce'] ) )
			return $portfolio_id;

		$nonce = $_POST['million_shades_toolkit_portfolio_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'million_shades_toolkit_portfolio' ) )
			return $portfolio_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $portfolio_id;

		if ( $portfolio->post_type == 'portfolios' ) {

			if ( ! current_user_can( 'edit_page', $portfolio_id ) )
				return $portfolio_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $portfolio_id ) )
				return $portfolio_id;
		}
		
		// Check post type for portfolios
		if ( $portfolio->post_type == 'portfolios' ) {
        // Save data of portfolio_url field
       
        if ( isset( $_POST['portfolio_url'] ) ) {
            update_post_meta( $portfolio_id, 'portfolio_url', esc_url_raw($_POST['portfolio_url']) );
        }
		}
	}

}


new MSTK_Portfolio_Post();


// return path portfolio-single
function mstk_get_portfolio_template_path( $template_path ) {
    if ( get_post_type() == 'portfolios' ) {
        if ( is_single() ) {
            
            if ( $theme_file = locate_template( array ( 'single-portfolios.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = 'single-portfolios.php';
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'mstk_get_portfolio_template_path', 1 );