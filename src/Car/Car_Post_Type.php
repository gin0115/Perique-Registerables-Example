<?php

declare(strict_types=1);

/**
 * Registration of the Car Post Type
 */

namespace Gin0115\Perique_Registerables_Example\Car;

use PinkCrab\Perique\Application\App_Config;
use Gin0115\Perique_Registerables_Example\Translations;
use PinkCrab\Registerables\{Meta_Box,Meta_Data,Post_Type};

class Car_Post_Type extends Post_Type {

	// Post type details.
	public string $dashicon = 'dashicons-car';
	public ?bool $gutenberg = true;
	public array $supports  = array( 'editor', 'title', 'thumbnail' );

	// The Meta Service.
	private Car_Details_Meta $car_details_meta;

	// Make use of injected services to build the post type.
	public function __construct(
		App_Config $app_config,
		Translations $translations,
		Car_Details_Meta $car_details_meta
	) {
		// Hold Meta Box Service as a prop for used in callbacks.
		$this->car_details_meta = $car_details_meta;

		// Key and taxonomies used from App_Config
		$this->key        = $app_config->post_types( 'car' );
		$this->taxonomies = array( $app_config->taxonomies( 'brand' ) );

		// Labels from Translation dictionary.
		$this->singular    = $translations->cpt_singular();
		$this->plural      = $translations->cpt_plural();
		$this->description = $translations->cpt_description();

		// Define the template for the post type.
		$this->template = array(
			array(
				'core/heading',
				array(
					'placeholder' => $translations->cpt_template_sub_heading_placeholder(),
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => $translations->cpt_template_sell_it_placeholder(),
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
		// All the meta data is registered in the Car_Details_Meta class.
		return $this->car_details_meta->get_meta_fields();
	}

	/**
	 * Allows for the setting of meta boxes specifically for this post type.
	 *
	 * @param Meta_Box[] $meta_boxes
	 * @return Meta_Box[]
	 */
	public function meta_boxes( array $meta_boxes ): array {
		// The meta box is registered in the Car_Details_Meta class.
		$meta_boxes[] = $this->car_details_meta->get_meta_box();
		return $meta_boxes;
	}
}
