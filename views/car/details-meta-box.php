<?php

/**
 * The Details meta box view for the Car post type.
 *
 * @var \Gin0115\Perique_Registerables_Example\Car\Car_Translations $car_translations.
 * @var \PinkCrab\Perique\Application\App_Config                    $app_config.
 * @var string                                                      $nonce.
 * @var \WP_Post                                                    $post.
 */

use PinkCrab\Form_Fields\Label_Config;
use PinkCrab\Form_Fields\Fields\Input_Color;
use PinkCrab\Form_Fields\Fields\Input_Hidden;
use PinkCrab\Form_Fields\Fields\Input_Number;
dump(get_defined_vars());
?>
<div class="meta-field number">
	<?php Input_Hidden::create( 'car_nonce' )->current( $nonce )->render(); ?> 
	<?php
	Input_Number::create( $app_config->post_meta( 'year' ) )
		->label( $car_translations->year_label() )
		->label_position( Label_Config::BEFORE_INPUT | Label_Config::LINKED_LABEL )
		->current( get_post_meta( $post->ID, $app_config->post_meta( 'year' ), true ) )
		->min( 1850 )
		->max( 2020 )
		->step( 1 )
		->render();
	?>
</div>

<div class="meta-field color">
	<?php
	Input_Number::create( $app_config->post_meta( 'doors' ) )
		->label( $car_translations->door_label() )
		->label_position( Label_Config::BEFORE_INPUT | Label_Config::LINKED_LABEL )
		->current( get_post_meta( $post->ID, $app_config->post_meta( 'doors' ), true ) )
		->render();
	?>
</div>
