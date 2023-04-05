# Gin0115 Example Registerables Plugin

A simple plugin that shows how you can create a custom post, with custom fields, and a custom taxonomy. Above is acheived using the [Registerables](https://github.com/Pink-Crab/Perique-Registerables) module for the [Perique Plugin Framework](https://github.com/Pink-Crab/Perique-Framework).

# Explanation

* [Plugin File](#pluginphp)
* [Car_Post_Type.php](#car_post_typephp)

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

### [Default App_Factory Setup](https://perique.info/core/App/setup#using-the-factory)

Here we use the [App_Factory](https://perique.info/core/App/app_factory) to create the basis of our Perique app. We pass `__DIR__` to the factory, so that it can find the plugin root to use as the basis for paths.

We then call the `default_setup()` method, which will setup the plugin to use the default internal dependencies and initial configuration for the following.

* Setup the [View](https://perique.info/core/App/view) service using the PHP render engine.
* Setup PinkCrab_Dice as the [DI Container](https://perique.info/core/DI).
* Register [App_Config](https://perique.info/core/App/app_config) using the defined paths (base and view)
* Initialise the [Hook_Loader](https://perique.info/lib/Hook_Loader) used by the [Registration](https://perique.info/core/Registration/) and [Module](https://perique.info/core/Registration/Modules) services.
* Initialise the [Module](https://perique.info/core/Registration/Modules) service used to load the modules, adds the built in [Hookable](https://perique.info/core/Registration/Hookable) module.

> It is advisable to use the default setup, as it will ensure that the plugin is setup correctly, and will allow you to use the built in modules and services.

### [App_Config](https://perique.info/core/App/app_config)

Here we pass a few custom values to the App_Config service. This allows us to define the keys/slugs used by the Post_Type, Taxonomy and Meta Data.

```php
'post_types' => array(
   'car' => 'example_car',
),
```
This allows us to call `$app_config->post_types('car')` and get back the slug `example_car`. 

```php
'taxonomies' => array(
   'brand' => 'example_brand',
),
```
This allows us to call `$app_config->taxonomies('brand')` and get back the slug `example_brand`. 

```php
'meta' => array(
   App_Config::POST_META => array(
      'year'  => 'example_car_year',
      'doors' => 'example_car_doors',
   ),
),
```
This allows us to call `$app_config->post_meta('year')` and get back the slug `example_car_year`.

### [Module](https://perique.info/core/Registration/Modules) & [Registration Classes](https://perique.info/core/Registration/)

We add the [Registerable](https://perique.info/core/Registration/Modules/Registerable) module using the `module()` method. There is no optional config for this module, so we can just pass the class name.

Once the module is added the `Registerable_Middleware` is added, this allows us to register our defintions for [Post Types](https://perique.info/module/Registerables/#post-type), [Taxonomies](https://perique.info/module/Registerables/#taxonomy), [Meta Data](https://perique.info/module/Registerables/#metadata) and [Meta Boxes](https://perique.info/module/Registerables/#meta-box).

In the above example we pass the definitions for the `Cars` post type and the `Brand` taxonomy. These are defined in the following classes.

```php
use Gin0115\Perique_Registerables_Example\Car\Car_Post_Type;
use Gin0115\Perique_Registerables_Example\Car\Car_Brand_Taxonomy;

->registration_classes(
   array(
      Car_Post_Type::class,
      Car_Brand_Taxonomy::class,
   )
)
```

## Car_Post_Type.php

This class defines the `Car` post type. It extends the [Post_Type](https://perique.info/module/Registerables/#post-type) class, which allows us to define a post type, while allowing us to inject various services and dependencies.

### [Injected Services](https://perique.info/core/DI/)

* [App_Config](https://perique.info/core/App/app_config) - Used to get the post type slug.
* [Translations](#translationsphp) - Used to translate the labels.
* [Car_Details_Meta](#car_details_metaphp) - Service that provides the meta data definiens and the accompanying [Meta Box](https://perique.info/module/Registerables/#meta-box) for the post type.

```php
public function __construct(
   App_Config $app_config,
   Translations $translations,
   Car_Details_Meta $car_details_meta
) {..}
```

### [Defining the Post_Type](https://perique.info/module/Registerables/#post-type)

![Cars Post Type Editor](docs/images/Cars-Post-Type-Editor.png "Image of the Car Post Type Editor, with the defined block template.")

As per the definable fields of the [Post_Type](https://perique.info/module/Registerables/docs/Post-Type) class, we can define the following.

```php
## As Properties
public string $dashicon = 'dashicons-car';
public ?bool $gutenberg = true;
public array $supports  = array( 'editor', 'title', 'thumbnail' );

## Using the constructor

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
```

The [Car Brand Taxonomy](#car_brand_taxonomyphp) is added to the post type, so that we can assign a brand to the car.

## Car_Brand_Taxonomy.php

As with the `Car_Post_Type`, this taxonomy extends from the similar [Taxonomy](https://perique.info/module/Registerables/#taxonomy) class. This allows us to define the taxonomy, while allowing us to inject various services and dependencies.

### [Injected Services](https://perique.info/core/DI/)

* [App_Config](https://perique.info/core/App/app_config) - Used to get the taxonomy slug.
* [Translations](#translationsphp) - Used to translate the labels.

```php
public function __construct(
   App_Config $app_config,
   Translations $translations
) {..}
```

### [Defining the Taxonomy](https://perique.info/module/Registerables/#taxonomy)

![Cars Taxonomy Editor](docs/images/Cars-Taxonomy-Editor.png "Image of the Car Taxonomy Editor, with the defined block template.")


## Car_Details_Meta.php


## Translations.php

## details-meta-box.php

