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

use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Registerables\Module\Registerable;
use Gin0115\Perique_Registerables_Example\Car\Car_Post_Type;
use Gin0115\Perique_Registerables_Example\Car\Car_Brand_Taxonomy;


require_once __DIR__ . '/vendor/autoload.php';

// Boot a bare bones version of perique
( new App_Factory( __DIR__ ) )
	->default_setup()
	->app_config(
		array(
			'post_types' => array(
				'car' => 'example_car',
			),
			'taxonomies' => array(
				'brand' => 'example_brand',
			),
			'meta'       => array(
				App_Config::POST_META => array(
					'year'  => 'example_car_year',
					'doors' => 'example_car_doors',
				),
			),
		)
	)
	->module( Registerable::class )
	->registration_classes(
		array(
			Car_Post_Type::class,
			Car_Brand_Taxonomy::class,
		)
	)
	->boot();
