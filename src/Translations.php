<?php

declare(strict_types=1);

/**
 * Holds all translations
 */

namespace Gin0115\Perique_Registerables_Example;

class Translations {

	public function cpt_singular(): string {
		return _x( 'Car', 'Singular label for the Car post type', 'gin0115-pinkcrab-examples' );
	}

	public function cpt_plural(): string {
		return _x( 'Cars', 'Plural label for the Car post type', 'gin0115-pinkcrab-examples' );
	}

	public function cpt_description(): string {
		return _x( 'This is an example of a custom post type using Perique and Registerables.', 'Description of the Car post type', 'gin0115-pinkcrab-examples' );
	}

	public function cpt_template_sub_heading_placeholder():string {
		return _x( 'Car Sub Heading', 'Placeholder for the cars sub heading, template', 'gin0115-pinkcrab-examples' );
	}

	public function cpt_template_sell_it_placeholder():string {
		return _x( 'Sell it them!', 'Placeholder for the cars sell it them, template', 'gin0115-pinkcrab-examples' );
	}

	public function meta_year_label():string {
		return _x( 'Year', 'Label for the year field', 'gin0115-pinkcrab-examples' );
	}

	public function meta_year_description():string {
		return _x( 'The year the car was made.', 'Description of the year field', 'gin0115-pinkcrab-examples' );
	}

	public function meta_door_label():string {
		return _x( 'Doors', 'Label for the doors field', 'gin0115-pinkcrab-examples' );
	}

	public function meta_door_description():string {
		return _x( 'The number of doors the car has.', 'Description of the doors field', 'gin0115-pinkcrab-examples' );
	}

	public function meta_box_title():string {
		return _x( 'Car Details', 'Meta Box title.', 'gin0115-pinkcrab-examples' );
	}

	public function tax_singular():string {
		return _x( 'Brand', 'Singular label for the Car Brand taxonomy', 'gin0115-pinkcrab-examples' );
	}

	public function tax_plural():string {
		return _x( 'Brands', 'Plural label for the Car Brand taxonomy', 'gin0115-pinkcrab-examples' );
	}

	public function tax_description():string {
		return _x( 'This is an example of a custom taxonomy using Perique and Registerables.', 'Description of the Car Brand taxonomy', 'gin0115-pinkcrab-examples' );
	}


}
