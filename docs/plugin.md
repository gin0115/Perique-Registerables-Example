
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