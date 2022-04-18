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
use Gin0115\Perique_Registerables_Example\Car\Car_Translations;

class Car_Details_Meta_Box {

	private App_Config $app_config;
	private Car_Translations $car_translations;

	public function __construct( App_Config $app_config, Car_Translations $car_translations ) {
		$this->app_config       = $app_config;
		$this->car_translations = $car_translations;

	}

	/**
	 * Returns the Meta Boxes for the Car Details
	 *
	 * @return \PinkCrab\Registerables\Meta_Box
	 */
	public function get_meta_box(): Meta_Box {
		return Meta_Box::side( 'car_details' )
			->label( 'd' )
			->view_template( 'car/details-meta-box' )
			->view_vars(
				array(
					'car_translations' => $this->car_translations,
					'app_config'       => $this->app_config,
					'nonce'            => wp_create_nonce( 'car_details_meta_box' ),
				)
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

		$meta_data[] = ( new Meta_Data( $this->app_config->post_meta( 'year' ) ) )
			->type( 'integer' )
			->single()
			->description( $this->car_translations->year_description() )
			->default( 2000 )
			->rest_schema(
				Integer_Type::on( $this->app_config->post_meta( 'year' ) )
					->minimum( 1850 )
					->maximum( 2020 )
					->description( $this->car_translations->year_description() )
					->required()
					->context( 'view', 'edit' )
					->sanitization( 'absint' )
			);

		$meta_data[] = ( new Meta_Data( $this->app_config->post_meta( 'doors' ) ) )
			->type( 'integer' )
			->single()
			->description( $this->car_translations->door_description() )
			->default( 5 )
			->rest_schema(
				Integer_Type::on( $this->app_config->post_meta( 'doors' ) )
					->description( $this->car_translations->year_description() )
					->required()
					->context( 'view' )
					->sanitization( 'absint' )
			);
		return $meta_data;
	}
}
