<?php

/**
 * Plugin Name: PinkCrab Perique Registerables Example Plugin
 * Plugin URI: https://github.com/gin0115/Perique-Registerables-Example
 * Description: This is an example project using Perique and Perique Registerables, for more details please visit https://github.com/Pink-Crab/Registerables
 * Version: 1.0.0
 * Author: Glynn Quelch
 * Author URI: https://github.com/gin0115/Perique-Registerables-Example
 * Text Domain: gin0115-pinkcrab-examples
 * Domain Path: /languages
 * Tested up to: 5.9
 * License: MIT
 **/

use PinkCrab\Perique\Interfaces\Renderable;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Perique\Services\View\PHP_Engine;
use Gin0115\Perique_Registerables_Example\Car\Car_Post_Type;
use PinkCrab\Registerables\Registration_Middleware\Registerable_Middleware;


require_once __DIR__ . '/vendor/autoload.php';

// Boot a barebones version of perique
$app = ( new App_Factory() )
	->with_wp_dice( )
	->di_rules(
		array(
			'*' => array(
				'substitutions' => array(
					Renderable::class => new PHP_Engine( __DIR__ . '/views' ),
				),
			),
		)
	)
	->app_config(
		array(
			'post_types' => array(
				'car' => 'example_car',
			),
			'taxonomies' => array(
				'brand' => 'example_brand',
			),
			'meta'       => array(
				'post' => array(
					'year'  => 'example_car_year',
					'doors' => 'example_car_doors',
				),
				'term' => array(
					'alias' => 'verbose_value',
				),
			),

		)
	)
	->construct_registration_middleware( Registerable_Middleware::class )
	->registration_classes( array( Car_Post_Type::class ) )
	->boot();
add_action(
	'init',
	function() use ( $app ) {

		// dump( $app::view()/* , $app::view()->render('car/details-meta-box') */ );

	},
);
