<?php

declare(strict_types=1);

/**
 * Handles the Meta Data and Meta Boxs for the Car  Details
 */

namespace Gin0115\Perique_Registerables_Example\Car;

use OutOfBoundsException;
use PinkCrab\Registerables\Meta_Box;
use PinkCrab\Registerables\Meta_Data;
use PinkCrab\Perique\Application\App_Config;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;
use Gin0115\Perique_Registerables_Example\Translations;

class Car_Details_Meta {

	private App_Config $app_config;
	private Translations $translations;

	public function __construct( App_Config $app_config, Translations $translations ) {
		$this->app_config   = $app_config;
		$this->translations = $translations;

	}

	/**
	 * Returns the Meta Boxes for the Car Details
	 *
	 * @return \PinkCrab\Registerables\Meta_Box
	 */
	public function get_meta_box(): Meta_Box {
		return Meta_Box::side( 'car_details' )
			->label( $this->translations->meta_box_title() )
			->view_template( 'car/details-meta-box' )
			->view_vars(
				array(
					'translations' => $this->translations,
					'app_config'   => $this->app_config,
					'nonce'        => wp_create_nonce( 'car_details_meta_box' ),
				)
			)
			->view_data_filter(
				function( \WP_Post $post, array $meta ) {
					// Add the meta data via the filter, this runs before the view is rended
					// but after the view vars have been set above (which is to early to access the post id.)
					$meta['year']  = get_post_meta( $post->ID, $this->app_config->post_meta( 'year' ), true );
					$meta['doors'] = get_post_meta( $post->ID, $this->app_config->post_meta( 'doors' ), true );
					return $meta;
				}
			)
			->add_action( 'save_post', array( $this, 'upsert_met_box_data' ) )
			->add_action( 'edit_post', array( $this, 'upsert_met_box_data' ) );
	}

	/**
	 * Callback for the car details meta box
	 * @param int $post_id
	 * @return void
	 * @throws OutOfBoundsException
	 */
	public function upsert_met_box_data( int $post_id ): void {
		// Bail if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Bail if nonce not set or not valid
		if ( ! isset( $_POST['car_nonce'] )
			|| ! wp_verify_nonce( $_POST['car_nonce'], 'car_details_meta_box' ) ) {
			return;
		}

		// Loop through all expected meta keys and update meta data.
		$meta_keys = array_map(
			fn( Meta_Data $meta): string => $meta->get_meta_key(),
			$this->get_meta_data()
		);

		// Get all the current data from global post and set against post meta.
		foreach ( $meta_keys as $key ) {
			update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
		}
	}

	/**
	 * Get all meta data for the car details meta box.
	 *
	 * @return \PinkCrab\Registerables\Meta_Data[]
	 */
	public function get_meta_data(): array {
		$meta_data = array();

		// Add the meta data definition for the year.
		$meta_data[] = ( new Meta_Data( $this->app_config->post_meta( 'year' ) ) )
			->type( 'integer' )
			->single()
			->description( $this->translations->meta_year_description() )
			->default( 2000 )
			->rest_schema(
				Argument_Parser::for_meta_data(
					Integer_Type::on( $this->app_config->post_meta( 'year' ) )
						->minimum( 1850 )
						->maximum( (int) gmdate( 'Y' ) )
						->description( $this->translations->meta_year_description() )
						->required()
						->context( 'view', 'edit' )
						->sanitization( 'absint' )
				)
			);

		// Add the meta data definition for the doors.
		$meta_data[] = ( new Meta_Data( $this->app_config->post_meta( 'doors' ) ) )
			->type( 'integer' )
			->single()
			->description( $this->translations->meta_door_description() )
			->default( 5 )
			->rest_schema(
				Argument_Parser::for_meta_data(
					Integer_Type::on( $this->app_config->post_meta( 'doors' ) )
						->description( $this->translations->meta_door_description() )
						->required()
						->context( 'view' )
						->sanitization( 'absint' )
				)
			);
		return $meta_data;
	}
}
