<?php

declare(strict_types=1);

/**
 * Class of translation string/template for Cars
 */

namespace Gin0115\Perique_Registerables_Example\Car;

class Car_Translations {

	public function singular(): string {
		return _x( 'Car', 'Singular label for the Car post type', 'gin0115-pinkcrab-examples' );
	}

	public function plural(): string {
		return _x( 'Cars', 'Plural label for the Car post type', 'gin0115-pinkcrab-examples' );
	}

	public function cpt_description(): string {
		return _x( 'This is an example of a custom post type using Perique and Registerables.', 'Description of the Car post type', 'gin0115-pinkcrab-examples' );
	}

	public function year_label():string {
		return _x( 'Year', 'Label for the year field', 'gin0115-pinkcrab-examples' );
	}

	public function year_description():string {
		return _x( 'The year the car was made.', 'Description of the year field', 'gin0115-pinkcrab-examples' );
	}

	public function door_label():string {
		return _x( 'Doors', 'Label for the doors field', 'gin0115-pinkcrab-examples' );
	}

	public function door_description():string {
		return _x( 'The number of doors the car has.', 'Description of the doors field', 'gin0115-pinkcrab-examples' );
	}


}
