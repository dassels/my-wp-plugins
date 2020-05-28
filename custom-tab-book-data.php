<?php
/**
 * Plugin Name: Custom Tabs for Book Data
 * Plugin URI: http://www.pilulerouge.com/
 * Description: Adds a tab to edit product page for additional book details, then display these details in additional info tab
 * Version: 1.0
 * Author: Damian Assels
 * Author URI: http://www.pilulerouge.com
 */
/**
 * Add a custom product tab.
 */
function bookdata_product_tabs( $tabs) {

	$tabs['bookdata'] = array(
		'label'		=> __( 'Book Data', 'woocommerce' ),
		'target'	=> 'bookdata_options',
		'class'		=> array( 'show_if_simple', 'show_if_variable'  ),
	);

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'bookdata_product_tabs' );


/**
 * Contents of the book data options product tab.
 */
function bookdata_options_product_tab_content() {

	global $post;

	// Note the 'id' attribute needs to match the 'target' parameter set above
	?><div id='bookdata_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php

			woocommerce_wp_text_input( array(
				'id'				=> 'book_author',
				'label'				=> 'Author',
				'desc_tip'			=> 'true',
				'description'		=> 'Enter the author name',
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_subtitle',
				'label'				=> 'Subtitle',
				'desc_tip'			=> 'true',
				'description'		=> 'Second title if applicable',
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_isbn',
				'label'				=> 'ISBN',
				'desc_tip'			=> 'true',
				'description'		=> 'Enter the book ISBN',
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_edition',
				'label'				=> 'Edition',
				'desc_tip'			=> 'true',
				'description'		=> 'Edition or volume information',
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_series',
				'label'				=> 'Part of Series',
				'desc_tip'			=> 'true',
				'description'		=> 'Enter title of series',
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_format',
				'label'				=> 'Format',
				'desc_tip'			=> 'true',
				'description'		=> 'Additional info such as book size, color, BW, hardcover, paperback etc',
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_pages',
				'label'				=> '# of Pages',
				'desc_tip'			=> 'true',
				'description'		=> 'Total number of pages in book',
				'type' 				=> 'text',
			) );

		?></div>

	</div><?php

}

add_filter( 'woocommerce_product_data_panels', 'bookdata_options_product_tab_content' ); // WC 2.6 and up

/**
 * Save the custom fields.
 */
function save_bookdata_option_fields( $post_id ) {

  $woocommerce_text_field_1 = $_POST['book_author'];
	if( !empty( $woocommerce_text_field_1 ) )
	update_post_meta( $post_id, 'book_author', esc_attr( $woocommerce_text_field_1 ) );

	$woocommerce_text_field_2 = $_POST['book_subtitle'];
	if( !empty( $woocommerce_text_field_2 ) )
	update_post_meta( $post_id, 'book_subtitle', esc_attr( $woocommerce_text_field_2 ) );

	$woocommerce_text_field_3 = $_POST['book_isbn'];
	if( !empty( $woocommerce_text_field_3 ) )
	update_post_meta( $post_id, 'book_isbn', esc_attr( $woocommerce_text_field_3 ) );

	$woocommerce_text_field_4 = $_POST['book_edition'];
	if( !empty( $woocommerce_text_field_4 ) )
	update_post_meta( $post_id, 'book_edition', esc_attr( $woocommerce_text_field_4 ) );

	$woocommerce_text_field_5 = $_POST['book_series'];
	if( !empty( $woocommerce_text_field_5 ) )
	update_post_meta( $post_id, 'book_series', esc_attr( $woocommerce_text_field_5 ) );

	$woocommerce_text_field_6 = $_POST['book_format'];
	if( !empty( $woocommerce_text_field_6 ) )
	update_post_meta( $post_id, 'book_format', esc_attr( $woocommerce_text_field_6 ) );

	$woocommerce_text_field_7 = $_POST['book_pages'];
	if( !empty( $woocommerce_text_field_7 ) )
	update_post_meta( $post_id, 'book_pages', esc_attr( $woocommerce_text_field_7 ) );

}

add_action( 'woocommerce_process_product_meta', 'save_bookdata_option_fields'  );
