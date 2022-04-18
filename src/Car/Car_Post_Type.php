<?php

declare(strict_types=1);

/**
 * Registration of the Car Post Type
 */

namespace Gin0115\Perique_Registerables_Example\Car;

use PinkCrab\Registerables\Meta_Box;
use PinkCrab\Registerables\Meta_Data;
use PinkCrab\Registerables\Post_Type;
use PinkCrab\Perique\Application\App_Config;

class Car_Post_Type extends Post_Type {

	private Car_Details_Meta_Box $car_details_meta_box;

	public function __construct(
		App_Config $app_config,
		Car_Translations $car_translations,
		Car_Details_Meta_Box $car_details_meta_box
	) {
		// Hold Meta Box Service as a prop for used in callbacks.
		$this->car_details_meta_box = $car_details_meta_box;

		// Set labels and key from injected services.
		$this->key         = $app_config->post_types( 'car' );
		$this->singular    = $car_translations->singular();
		$this->plural      = $car_translations->plural();
		$this->description = $car_translations->cpt_description();

		// Define which features are enabled.
		$this->supports = array( 'editor', 'title', 'thumbnail' );

		// Enable Gutenberg and define a basic template.
		$this->gutenberg = true;
		$this->templates = array(
			array(
				'core/heading',
				array(
					'placeholder' => 'Intro blurb',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => 'Sel it them!',
				),
			),
		);
	}

	/**
	 * Allows for the setting of meta data specifically for this post type.
	 *
	 * @param Meta_Data[] $meta_data
	 * @return Meta_Data[]
	 */
	public function meta_data( array $meta_data ): array {
		// All the meta data is registered in the Car_Details_Meta_Box class.
		return $this->car_details_meta_box->get_meta_data();
	}

	/**
	 * Allows for the setting of meta boxes specifically for this post type.
	 *
	 * @param Meta_Box[] $meta_boxes
	 * @return Meta_Box[]
	 */
	public function meta_boxes( array $meta_boxes ): array {
		// The meta box is registered in the Car_Details_Meta_Box class.
		$meta_boxes[] = $this->car_details_meta_box->get_meta_box();
		return $meta_boxes;
	}
}
