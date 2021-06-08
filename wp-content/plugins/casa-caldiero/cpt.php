<?php

add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
	remove_menu_page('edit.php');
}


add_action( 'init', 'cptui_register_my_cpts_appartamenti' );
function cptui_register_my_cpts_appartamenti() {
	$labels = array(
		"name" => __( 'Apartment', '' ),
		"singular_name" => __( 'Apartments', '' ),
		"menu_name" => __( 'Apartments', '' ),
		);

	$args = array(
		"label" => __( 'Apartments', '' ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => true,
		"show_in_menu" => true,
				"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "appartamenti", "with_front" => true ),
		"query_var" => true,
		"menu_position" => 5,
		"menu_icon" => "dashicons-admin-multisite",
		"supports" => array( "title", "editor", "thumbnail", "author" ),					);
	register_post_type( "appartamenti", $args );

// End of cptui_register_my_cpts_appartamenti()
}

add_action( 'init', 'cptui_register_my_cpts_dicono_di_noi' );
function cptui_register_my_cpts_dicono_di_noi() {
	$labels = array(
		"name" => __( 'Feedbacks', '' ),
		"singular_name" => __( 'Feedback', '' ),
		"menu_name" => __( 'Feedbacks', '' ),
		);

	$args = array(
		"label" => __( 'Feedbacks', '' ),
		"labels" => $labels,
		"description" => "Frasi di feedback per la sezione \"Dicono di noi\" in fondo all\'homepage",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
				"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "dicono_di_noi", "with_front" => true ),
		"query_var" => true,
		"menu_position" => 5,
		"menu_icon" => "dashicons-edit",
		"supports" => array( "title", "editor" ),					);
	register_post_type( "dicono_di_noi", $args );

// End of cptui_register_my_cpts_dicono_di_noi()
}



?>
