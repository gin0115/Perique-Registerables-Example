<?php

declare(strict_types=1);

/**
 * Registration of the Car Brand Taxonomy
 */

namespace Gin0115\Perique_Registerables_Example\Car;

use PinkCrab\Registerables\Taxonomy;
use PinkCrab\Perique\Application\App_Config;
use Gin0115\Perique_Registerables_Example\Translations;

class Car_Brand_Taxonomy extends Taxonomy {

	public function __construct(
		App_Config $config,
		Translations $translations
	) {
		$this->slug        = $config->taxonomies( 'brand' );
		$this->singular    = $translations->tax_singular();
		$this->plural      = $translations->tax_plural();
		$this->description = $translations->tax_description();

		// Include for the cars post type.
		$this->object_type = array( $config->post_types( 'car' ) );

		// Ensures the taxonomy is available in the REST API and Gutenberg Editor
		$this->show_in_rest = true;
	}
}
