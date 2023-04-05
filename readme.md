# Gin0115 Example Registerables Plugin

A simple plugin that shows how you can create a custom post, with custom fields, and a custom taxonomy. Above is acheived using the [Registerables](https://github.com/Pink-Crab/Perique-Registerables) module for the [Perique Plugin Framework](https://github.com/Pink-Crab/Perique-Framework).

# Explanation

* [Plugin File](docs/plugin.md)
* [Car_Post_Type.php](#car_post_typephp)
* [Car_Brand_Taxonomy.php](#car_brand_taxonomyphp)
* [Car_Details_Meta.php](#car_details_metaphp)
* [Translations.php](#translationsphp)





## Car_Details_Meta.php

The Meta Data Service is used to both define the meta data for the `Car` post type, as well as the accompanying [Meta Box](https://perique.info/module/Registerables/#meta-box) for the post type.

### [Injected Services](https://perique.info/core/DI/)

* [App_Config](https://perique.info/core/App/app_config) - Used to get the meta keys.
* [Translations](#translationsphp) - Used to translate the labels.

```php
public function __construct( App_Config $app_config, Translations $translations ) {
   $this->app_config   = $app_config;
   $this->translations = $translations;
}
```
### [Defining the Meta Data](https://perique.info/module/Registerables/#meta-data)

Here we return an array with the 2 meta fields defined to represent the `year` and `door` count of the car.

#### Year

```php
// Add the meta data definition for the year.
$meta_fields[] = ( new Meta_Data( $this->app_config->post_meta( 'year' ) ) )
   ->type( 'integer' )
   ->single()
   ->description( $this->translations->meta_year_description() )
   ->default( 2000 )
   ->rest_schema(
      Argument_Parser::for_meta_data(
         Integer_Type::on( $this->app_config->post_meta( 'year' ) )
            ->minimum( 1850 )
            ->maximum( (int) gmdate( 'Y' ) )
            ->description( $this->translations->meta_year_description() )
            ->required()
            ->context( 'view', 'edit' )
            ->sanitization( 'absint' )
      )
   );
```

Defined with the key from [App_Config](https://perique.info/core/App/app_config) as a [single](https://perique.info/module/Registerables/docs/Meta_Data#singlebool-single-meta_data) [integer (type)](https://perique.info/module/Registerables/docs/Meta_Data#typetype-meta_data) with a description provided by the [Translation](#translationsphp) service. The `default` value is set to `2000`, and the [REST Schema](https://perique.info/module/Registerables/docs/Meta-Data#rest_schema) is defined using the [Rest_Schema Library](https://perique.info/lib/Rest_Schema) and its built in Meta Parser. The Rest Schema can also be defined using the regular [WordPress schema definitions](https://make.wordpress.org/core/2016/05/03/a-data-schema-for-meta/).

#### Doors

```php
// Add the meta data definition for the doors.
$meta_fields[] = ( new Meta_Data( $this->app_config->post_meta( 'doors' ) ) )
   ->type( 'integer' )
   ->single()
   ->description( $this->translations->meta_door_description() )
   ->default( 5 )
   ->rest_schema(
      Argument_Parser::for_meta_data(
         Integer_Type::on( $this->app_config->post_meta( 'doors' ) )
            ->description( $this->translations->meta_door_description() )
            ->required()
            ->context( 'view' )
            ->sanitization( 'absint' )
      )
);
```
Defined with the key from [App_Config](https://perique.info/core/App/app_config) as a [single](https://perique.info/module/Registerables/docs/Meta_Data#singlebool-single-meta_data) [integer (type)](https://perique.info/module/Registerables/docs/Meta_Data#typetype-meta_data) with a description provided by the [Translation](#translationsphp) service. The `default` value is set to `5`, and the [REST Schema](https://perique.info/module/Registerables/docs/Meta-Data#rest_schema) is defined using the [Rest_Schema Library](https://perique.info/lib/Rest_Schema) and its built in Meta Parser. The Rest Schema can also be defined using the regular [WordPress schema definitions](https://make.wordpress.org/core/2016/05/03/a-data-schema-for-meta/).


## Translations.php

## details-meta-box.php

