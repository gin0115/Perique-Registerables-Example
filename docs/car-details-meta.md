# Car_Details_Meta.php

The Meta Data Service is used to both define the meta data for the `Car` post type, as well as the accompanying [Meta Box](https://perique.info/module/Registerables/#meta-box) for the post type.

## [Injected Services](https://perique.info/core/DI/)

* [App_Config](https://perique.info/core/App/app_config) - Used to get the meta keys.
* [Translations](translations.md) - Used to translate the labels.

```php
public function __construct( App_Config $app_config, Translations $translations ) {
   $this->app_config   = $app_config;
   $this->translations = $translations;
}
```
## [Defining the Meta Data](https://perique.info/module/Registerables/#meta-data)

Here we return an array with the 2 meta fields defined to represent the `year` and `door` count of the car.

### Year

```php
// Add the meta data definition for the year.
$meta_fields[] = ( new Meta_Data( $this->app_config->post_meta( 'year' ) ) )
   ->type( 'integer' )
   ->single()
   ->description( $this->translations->meta_year_description() )
   ->default( 2000 )
   ->rest_schema(
      Integer_Type::on( $this->app_config->post_meta( 'year' ) )
         ->minimum( 1850 )
         ->maximum( (int) gmdate( 'Y' ) )
         ->description( $this->translations->meta_year_description() )
         ->required()
         ->context( 'view', 'edit' )
         ->sanitization( 'absint' )
   );
```

Defined with the key from [App_Config](https://perique.info/core/App/app_config) as a [single](https://perique.info/module/Registerables/docs/Meta_Data#singlebool-single-meta_data) [integer (type)](https://perique.info/module/Registerables/docs/Meta_Data#typetype-meta_data) with a description provided by the [Translation](translations.md) service. The `default` value is set to `2000`, and the [REST Schema](https://perique.info/module/Registerables/docs/Meta-Data#rest_schema) is defined using the [Rest_Schema Library](https://perique.info/lib/Rest_Schema).

> When using the [Rest_Schema Library](https://perique.info/lib/Rest_Schema), it is automatically parsed when the meta field is registered, and the schema is added to the [REST API](https://developer.wordpress.org/rest-api/).

### Doors

```php
// Add the meta data definition for the doors.
$meta_fields[] = ( new Meta_Data( $this->app_config->post_meta( 'doors' ) ) )
   ->type( 'integer' )
   ->single()
   ->description( $this->translations->meta_door_description() )
   ->default( 5 )
   ->rest_schema(
      array(
         '$schema'           => 'http://json-schema.org/draft-04/schema#',
         'description'       => $this->translations->meta_door_description(),
         'type'              => 'integer',
         'context'           => array( 'view' ),
         'required'          => true,
         'sanitize_callback' => 'absint',
      )
);
```
Defined with the key from [App_Config](https://perique.info/core/App/app_config) as a [single](https://perique.info/module/Registerables/docs/Meta_Data#singlebool-single-meta_data) [integer (type)](https://perique.info/module/Registerables/docs/Meta_Data#typetype-meta_data) with a description provided by the [Translation](#translationsphp) service. The `default` value is set to `5`, and the [REST Schema](https://perique.info/module/Registerables/docs/Meta-Data#rest_schema) is defined using the regular [WordPress schema definitions](https://developer.wordpress.org/rest-api/extending-the-rest-api/schema/).
