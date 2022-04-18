<?php

declare(strict_types=1);

/**
 * Integration test for post type with defined post meta and rest.
 *
 * @since 0.7.1
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Perique
 */

namespace PinkCrab\Registerables\Tests\Application\Post_Type;

use WP_UnitTestCase;
use Gin0115\WPUnit_Helpers\WP\Meta_Data_Inspector;
use PinkCrab\Registerables\Tests\App_Helper_Trait;
use Gin0115\WPUnit_Helpers\WP\Entities\Meta_Data_Entity;
use PinkCrab\Registerables\Tests\Fixtures\CPT\Meta_Data_CPT;
use PinkCrab\Registerables\Tests\Fixtures\CPT\Meta_Data_Rest_CPT;

class Test_Meta_Data_Rest_CPT extends WP_UnitTestCase {

	use App_Helper_Trait;

	/**
	 * Holds instance of the Post_Type object.
	 *
	 * @var \PinkCrab\Registerables\Post_Type
	 */
	protected $cpt;

	/**
	 * Holds all the current meta box global
	 *
	 * @var array
	 */
	protected $wp_meta_boxes;

	/**
	 * Holds the instance of the meta box inspector.
	 *
	 * @var Meta_Data_Inspector
	 */
	protected $meta_data_inspector;

	/**
	 * Reset the app data after each test.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		self::unset_app_instance();
	}

	public function setUp(): void {
		parent::setup();
		// Create the CPT and Loader instances.
		$this->cpt = new Meta_Data_Rest_CPT();

		self::create_with_registerables( Meta_Data_Rest_CPT::class )->boot();
		do_action( 'init' );

		// Build inspector.
		$this->meta_data_inspector = Meta_Data_Inspector::initialise();

	}

	/** @testdox When defining meta in the Post Types meta_data array, should see these meta values created within wp core, when we register the post type. */
	public function test_meta_data_registered(): void {
		// Check post type has 2 meta fields applied.
		$this->assertCount( 2, $this->meta_data_inspector->for_post_types( $this->cpt->key ) );

		// Meta 1 Values.
		$meta1 = $this->meta_data_inspector->find_post_meta( $this->cpt->key, Meta_Data_Rest_CPT::META_1['key'] );
		$this->assertInstanceOf( Meta_Data_Entity::class, $meta1 );
		$this->assertEquals( Meta_Data_Rest_CPT::meta_rest_key_1_schema(), $meta1->show_in_rest );


		// Meta 2 Values.
		$meta2 = $this->meta_data_inspector->find_post_meta( $this->cpt->key, Meta_Data_Rest_CPT::META_2['key'] );
		$this->assertInstanceOf( Meta_Data_Entity::class, $meta2 );
		$this->assertEquals( Meta_Data_Rest_CPT::meta_rest_key_2_schema_as_array(), $meta2->show_in_rest );
	}

}
