<?php

/**
 * The Details meta box view for the Car post type.
 *
 * @var \Gin0115\Perique_Registerables_Example\Translations $translations.
 * @var \PinkCrab\Perique\Application\App_Config                    $app_config.
 * @var string                                                      $nonce.
 * @var \WP_Post                                                    $post.
 */
// dump( get_defined_vars() );
?>
<div id="car-meta-box" class="wraps">

	<input type="hidden" name="car_nonce" id="car_nonce" value="<?php echo esc_attr( $nonce ); ?>">
	
	<div id="meta-year" class="meta-row">
		<label for="car_year"><?php echo $translations->meta_year_label(); ?></label>
		<input type="number" name="<?php echo esc_attr( $app_config->meta( 'year' ) ); ?>" id="car_year" value="<?php echo esc_attr( $year ); ?>">
		<p class="small"><?php echo $translations->meta_year_description(); ?></p>
	</div>

	<div id="meta-doors" class="meta-row">
		<label for="car_door"><?php echo $translations->meta_door_label(); ?></label>
		<input type="number" name="<?php echo esc_attr( $app_config->meta( 'doors' ) ); ?>" id="car_door" value="<?php echo esc_attr( $doors ); ?>">
		<p class="small"><?php echo $translations->meta_door_description(); ?></p>
	</div>
</div>
