<?php
/*
 * Plugin Name: Custom Tabs for Book Data
 * Plugin URI: http://www.pilulerouge.com/
 * Description: Adds a tab to edit product page for additional book details, then display these details in additional info tab
 * Version: 1.0
 * Author: Damian Assels
 * Author URI: http://www.pilulerouge.com
 */

/*
 * Add a custom product tab for additional book information
 */
function bookdata_product_tabs( $tabs) {

	$tabs['bookdata'] = array(
		'label'			=> __( 'Book Data', 'woocommerce' ),
		'target'		=> 'bookdata_options',
		'priority'	=> 11,
	);

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'bookdata_product_tabs' );

/*
 * Contents of the book data options product tab.
 */
function bookdata_options_product_tab_content() {

	global $post;

	// Let's create the needed options. (The 'id' attribute needs to match the 'target' parameter set in previous function)
	?><div id='bookdata_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php

			woocommerce_wp_text_input( array(
				'id'				=> 'book_author',
				'label'				=> __('Author', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Enter the author name', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_subtitle',
				'label'				=> __('Subtitle', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Second title if applicable', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_isbn',
				'label'				=> __('ISBN', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Enter the book ISBN', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_edition',
				'label'				=> __('Edition', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Edition or volume information', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_series',
				'label'				=> __('Part of Series', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Enter title of series if applicable', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_format',
				'label'				=> __('Format', 'custom-tabs-for-book-data'),
				'placeholder' => __('Softcover, Digital', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Enter format: softcover, hardcover, paperback etc', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'				=> 'book_pages',
				'label'				=> __('No. of Pages', 'custom-tabs-for-book-data'),
				'desc_tip'			=> 'true',
				'description'		=> __('Total number of pages in book', 'custom-tabs-for-book-data'),
				'type' 				=> 'text',
			) );

		?></div>

	</div><?php

}

add_filter( 'woocommerce_product_data_panels', 'bookdata_options_product_tab_content' ); // WC 2.6 and up

/*
 * Save custom fields or clear if empty
 */
function save_bookdata_option_fields( $post_id ) {

  $woocommerce_text_field_1 = $_POST['book_author'];
	if( !empty( $woocommerce_text_field_1 ) )
	update_post_meta( $post_id, 'book_author', esc_attr( $woocommerce_text_field_1 ) );
	else
	delete_post_meta($post_id, 'book_author');

	$woocommerce_text_field_2 = $_POST['book_subtitle'];
	if( !empty( $woocommerce_text_field_2 ) )
	update_post_meta( $post_id, 'book_subtitle', esc_attr( $woocommerce_text_field_2 ) );
	else
	delete_post_meta($post_id, 'book_subtitle');

	$woocommerce_text_field_3 = $_POST['book_isbn'];
	if( !empty( $woocommerce_text_field_3 ) )
	update_post_meta( $post_id, 'book_isbn', esc_attr( $woocommerce_text_field_3 ) );
	else
	delete_post_meta($post_id, 'book_isbn');

	$woocommerce_text_field_4 = $_POST['book_edition'];
	if( !empty( $woocommerce_text_field_4 ) )
	update_post_meta( $post_id, 'book_edition', esc_attr( $woocommerce_text_field_4 ) );
	else
	delete_post_meta($post_id, 'book_edition');

	$woocommerce_text_field_5 = $_POST['book_series'];
	if( !empty( $woocommerce_text_field_5 ) )
	update_post_meta( $post_id, 'book_series', esc_attr( $woocommerce_text_field_5 ) );
	else
	delete_post_meta($post_id, 'book_series');

	$woocommerce_text_field_6 = $_POST['book_format'];
	if( !empty( $woocommerce_text_field_6 ) )
	update_post_meta( $post_id, 'book_format', esc_attr( $woocommerce_text_field_6 ) );
	else
	delete_post_meta($post_id, 'book_format');

	$woocommerce_text_field_7 = $_POST['book_pages'];
	if( !empty( $woocommerce_text_field_7 ) )
	update_post_meta( $post_id, 'book_pages', esc_attr( $woocommerce_text_field_7 ) );
	else
	delete_post_meta($post_id, 'book_pages');

}

add_action( 'woocommerce_process_product_meta', 'save_bookdata_option_fields'  );


// Display Author and ISBN custom attributes under Product Title before Short Description

function add_custom_html() {
            global $post;
            $product_id = $post->ID;
            $categories = get_the_terms( $product_id, 'product_cat' );
						$author = wc_attribute_label( 'Author');
						$isbn = wc_attribute_label( 'ISBN');


            if( empty ( $categories ) ){
                return;
            }


						$content = '<div class="author-block">';
						$content .= '<p class="bookdata-txt">';
						$content .= esc_attr__($author);
						$content .= ': ';
						$content .= esc_attr(get_post_meta( $post->ID, 'book_author', true) );
						$content .= '</p>';
						$content .= '</div>';
						$content .= '<div class="format-pages-block">';
						$content .= '<p class="bookdata-txt">';
						$content .= esc_attr__($isbn);
						$content .= ': ';
						$content .= esc_attr(get_post_meta( $post->ID, 'book_isbn', true) );
						$content .= '</p>';
						$content .= '</div>';


                foreach($categories as $cat){
                    if( 'books' == $cat->slug ) {
                        echo $content;
                    }
										if( 'livres' == $cat->slug ) {
												echo $content;
										}
										if( 'libros' == $cat->slug ) {
												echo $content;
										}
										if( 'bucher' == $cat->slug ) {
												echo $content;
										}
										if( 'boeken' == $cat->slug ) {
												echo $content;
										}

                }



}

add_action( 'woocommerce_single_product_summary', 'add_custom_html', 15 );

// Display attributes to additional info table if values are present

function bookdata_in_product_add_info_tab($product_attributes, $product){

		$woocommerce_text_field_1 = get_post_meta($product->get_ID(), 'book_author', true);
		if( !empty( $woocommerce_text_field_1 ) ) {
		$product_attributes['book_author'] = [
        'label' => __('Author', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_author', true),
    ];
	  }

		$woocommerce_text_field_2 = get_post_meta($product->get_ID(), 'book_subtitle', true);
		if( !empty( $woocommerce_text_field_2 ) ) {
    $product_attributes['book_subtitle'] = [
        'label' => __('Subtitle', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_subtitle', true),
    ];
	  }

		$woocommerce_text_field_3 = get_post_meta($product->get_ID(), 'book_isbn', true);
		if( !empty( $woocommerce_text_field_3 ) ) {
    $product_attributes['book_isbn'] = [
        'label' => __('ISBN', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_isbn', true),
    ];
		}

		$woocommerce_text_field_4 = get_post_meta($product->get_ID(), 'book_edition', true);
		if( !empty( $woocommerce_text_field_4 ) ) {
		$product_attributes['book_edition'] = [
        'label' => __('Edition', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_edition', true),
    ];
	  }

		$woocommerce_text_field_5 = get_post_meta($product->get_ID(), 'book_series', true);
		if( !empty( $woocommerce_text_field_5 ) ) {
		$product_attributes['book_series'] = [
        'label' => __('Part of Series', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_series', true),
    ];
	  }

		$woocommerce_text_field_6 = get_post_meta($product->get_ID(), 'book_format', true);
		if( !empty( $woocommerce_text_field_6 ) ) {
		$product_attributes['book_format'] = [
        'label' => __('Format', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_format', true),
    ];
		}

		$woocommerce_text_field_7 = get_post_meta($product->get_ID(), 'book_pages', true);
		if( !empty( $woocommerce_text_field_7 ) ) {
		$product_attributes['book_pages'] = [
        'label' => __('No. of Pages', 'custom-tabs-for-book-data'),
        'value' => get_post_meta($product->get_ID(), 'book_pages', true),
    ];
		}

    return $product_attributes;
}
add_filter('woocommerce_display_product_attributes', 'bookdata_in_product_add_info_tab', 10, 2);

// creates excerpt from main description and displays as short description

function create_excerpts_for_product() {

	global $post;
	$product_id = $post->ID;

if ( empty( $post->post_excerpt ) ) {
    echo wp_kses_post( wp_trim_words( $post->post_content ) );
} else {
    echo wp_kses_post( $post->post_excerpt ); // if we have text in the short description that will display instead
}
}

add_filter( 'woocommerce_short_description', 'create_excerpts_for_product' );
