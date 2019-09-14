<?php

namespace MyListing\Src\Queries;

class Related_Listings extends Query {
	use \MyListing\Src\Traits\Instantiatable;

	public $action = 'get_related_listings_by_id';

	public function handle() {
		check_ajax_referer( 'c27_ajax_nonce', 'security' );

		if ( empty( $_POST['listing_id'] ) ) {
			return false;
		}

		$page       = absint( isset($_POST['page']) ? $_POST['page'] : 0 );
		$per_page   = 9;

		$meta_query = [];
		$meta_query[] = [
			'key' => '_related_listing',
			'value' => absint( $_POST['listing_id'] ),
		];

		if ( ! empty( $_POST['listing_type'] ) ) {
			$meta_query[] = [
				'key' => '_case27_listing_type',
				'value' => sanitize_text_field( $_POST['listing_type'] ),
			];
		}

		$meta_query[] = [
			'relation' => 'AND',
			[
				'key' => '_job_date',
				'value' => date('Y-m-d'),
	            'compare' => '>=',
	            'type' => 'DATE',
			]
		];

		return $this->send( [
			'offset' => $page * $per_page,
			'meta_key'            => '_job_date',
			'orderby'             => 'meta_value',
			'order'               => 'ASC',
			'posts_per_page' => $per_page,
			'meta_query' => $meta_query,
			'output' => [ 'item-wrapper' => 'col-md-4 col-sm-6 col-xs-12' ],
			'mylisting_ignore_priority'	=> true,
		] );
	}
}