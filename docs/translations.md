# Translations.php

This is a simple class, which can be used to define any strings which can be translated and possibly reused in the application. By defining them into a single class, this makes it easier to keep them upto date, and also to reuse them in other parts of the application.

## Post Type

In this example project we hold the following strings for the post type.

```php
// Labels
$translations->cpt_singular();
$translations->cpt_plural();
$translations->cpt_description();

// Template Placeholders
$translations->cpt_template_sub_heading_placeholder();
$translations->cpt_template_sell_it_placeholder();
```

## Taxonomy

In this example project we hold the following strings for the taxonomy.

```php
// Labels
$translations->tax_singular();
$translations->tax_plural();
$translations->tax_description();
```

## Meta Box

In this example project we hold the following strings for the meta box.

```php
// Labels
$translations->meta_box_title();
```

## Meta Fields

In this example project we hold the following strings for the meta fields.

```php
// Labels
$translations->meta_year_label();
$translations->meta_door_label();

// Descriptions
$translations->meta_year_description();
$translations->meta_door_description();
```

All of the translations are defined using `_x()` function, which allows us to define the context of the string, and also the text domain.

```php
public function meta_year_label():string {
   return _x( 'Year', 'Label for the year field', 'gin0115-pinkcrab-examples' );
}

public function cpt_singular(): string {
   return _x( 'Car', 'Singular label for the Car post type', 'gin0115-pinkcrab-examples' );
}
```