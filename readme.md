# Gin0115 Example Registerables Plugin

A simple plugin that shows how you can create a custom post, with custom fields, and a custom taxonomy. Above is acheived using the [Registerables](https://github.com/Pink-Crab/Perique-Registerables) module for the [Perique Plugin Framework](https://github.com/Pink-Crab/Perique-Framework).

# Explanation

## plugin.php

The main entry point for the plugin. This is where we create an instance of Perique and add the Registerables module. 
```php
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
```

### [Default Setup](https://perique.info/core/App/setup#using-the-factory)

Here we use the [App_Factory](https://perique.info/core/App/app_factory) to create the basis of our Perique app. We pass `__DIR__` to the factory, so that it can find the plugin root to use as the basis for paths.

We then call the `default_setup()` method, which will setup the plugin to use the default internal dependencies and initial configuration for the following.

* Setup the [View](https://perique.info/core/App/view) service using the PHP render engine.
* Setup PinkCrab_Dice as the [DI Container](https://perique.info/core/DI).
* Register [App_Config](https://perique.info/core/App/app_config) using the defined paths (base and view)
* Initialise the [Hook_Loader](https://perique.info/lib/Hook_Loader) used by the [Registration](https://perique.info/core/Registration/) and [Module](https://perique.info/core/Registration/Modules) services.
* Initialise the [Module](https://perique.info/core/Registration/Modules) service used to load the modules, adds the built in [Hookable](https://perique.info/core/Registration/Hookable) module.

> It is advisable to use the default setup, as it will ensure that the plugin is setup correctly, and will allow you to use the built in modules and services.


