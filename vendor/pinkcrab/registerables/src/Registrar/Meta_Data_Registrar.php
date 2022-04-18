<?php

declare(strict_types=1);

/**
 * Used for registering Meta Data.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Registerables
 * @since 0.7.1
 */

namespace PinkCrab\Registerables\Registrar;

use PinkCrab\Registerables\Meta_Box;
use PinkCrab\Registerables\Meta_Data;

class Meta_Data_Registrar {

	/**
	 * Registers meta data for post types.
	 *
	 * @param \PinkCrab\Registerables\Meta_Data $meta
	 * @param string $post_type
	 * @return bool
	 * @throws \Exception if fails to register meta data.
	 */
	public function register_for_post_type( Meta_Data $meta, string $post_type ):bool {
		return $this->register_meta( $meta, 'post', $post_type );
	}

	/**
	 * Registers meta data for terms.
	 *
	 * @param \PinkCrab\Registerables\Meta_Data $meta
	 * @param string $taxonomy
	 * @return bool
	 * @throws \Exception if fails to register meta data.
	 */
	public function register_for_term( Meta_Data $meta, string $taxonomy ):bool {
		return $this->register_meta( $meta, 'term', $taxonomy );
	}

	/**
	 * Registers meta data for a defined type.
	 *
	 * Will cast WP Rest Schema model to array
	 *
	 * @param \PinkCrab\Registerables\Meta_Data $meta
	 * @param  string $meta_type The object type ('post', 'user', 'comment', 'term')
	 * @param  string $sub_type The object sub-type ('post_type', 'taxonomy')
	 * @return bool
	 * @throws \Exception if fails to register meta data.
	 */
	protected function register_meta( Meta_Data $meta, string $meta_type, string $sub_type ): bool {
		// Clone and set the post type, while enforcing it as a post meta.
		$meta = clone $meta;
		$meta->object_subtype( $sub_type );
		$meta->meta_type( $meta_type );

		// Normalise rest schema model to array.
		$meta = $this->normalise_rest_schema( $meta );

		$result = register_meta( $meta->get_meta_type(), $meta->get_meta_key(), $meta->parse_args() );
		if ( ! $result ) {
			throw new \Exception(
				"Failed to register {$meta->get_meta_key()} (meta) for {$sub_type} of {$meta_type} type"
			);
		}

		// Maybe register rest fields.
		if ( false !== $meta->get_rest_schema() ) {
			$this->register_meta_rest( $meta );
		}

		return $result;
	}

	/**
	 * Registers a Meta Data object as defined REST field.
	 *
	 * @param \PinkCrab\Registerables\Meta_Data $meta
	 * @return void
	 */
	protected function register_meta_rest( Meta_Data $meta ) {
		add_action(
			'rest_api_init',
			function () use ( $meta ) {
				register_rest_field(
					$meta->get_subtype(),
					$meta->get_meta_key(),
					array(
						'get_callback'    => $meta->get_rest_get()
							?? $this->create_rest_get_method( $meta->get_type(), $meta->get_meta_key() ),
						'schema'          => $meta->get_rest_schema(),
						'update_callback' => $meta->get_rest_set() ?? $this->create_rest_update_method( $meta ),
					),
				);

				// dump($GLOBALS['wp_rest_additional_fields']);

			}
		);
	}

	protected function create_rest_update_method( Meta_Data $meta ): callable {
		return function( $value, $object ) use ( $meta ) {
			switch ( $meta->get_meta_type() ) {
				case 'post':
					// @var \WP_Post $object
					$value = update_post_meta( $object->ID, $meta->get_meta_key(), $value );
					break;

				case 'term':
					// @var \WP_Term $object
					$value = get_term_meta( $object->term_id, $meta->get_meta_key(), $value );
					break;

				case 'user':
					$value = get_user_meta( $object['id'], $meta->get_meta_key(), $value );
					break;

				case 'comment':
					$value = get_comment_meta( $object['id'], $meta->get_meta_key(), $value );
					break;

				default:
					$value = null;
					break;
			}

			return $value;
		};
	}

	/**
	 * Creates a fallback rest get callback.
	 *
	 * @param string $type The meta type.
	 * @param string $meta_key The meta key.
	 * @return callable
	 */
	protected function create_rest_get_method( string $type, string $meta_key ): callable {
		return function( $object ) use ( $type, $meta_key ) {
			switch ( $type ) {
				case 'post':
					$value = get_post_meta( $object['id'], $meta_key, true );
					break;

				case 'term':
					$value = get_term_meta( $object['id'], $meta_key, true );
					break;

				case 'user':
					$value = get_user_meta( $object['id'], $meta_key, true );
					break;

				case 'comment':
					$value = get_comment_meta( $object['id'], $meta_key, true );
					break;

				default:
					$value = null;
					break;
			}

			return $value;
		};
	}

	/**
	 * Potentially casts a Rest Schema to an array.
	 *
	 * Only if the module active and the schema is Argument type.
	 *
	 * @param \PinkCrab\Registerables\Meta_Data $meta
	 * @return \PinkCrab\Registerables\Meta_Data
	 */
	protected function normalise_rest_schema( Meta_Data $meta ): Meta_Data {
		if ( \class_exists( 'PinkCrab\WP_Rest_Schema\Argument\Argument' )
		&& $meta->get_rest_schema() instanceof \PinkCrab\WP_Rest_Schema\Argument\Argument
		) {
			$meta->rest_schema( \PinkCrab\WP_Rest_Schema\Parser\Argument_Parser::for_meta_data( $meta->get_rest_schema() ) );
		}
		return $meta;
	}
}

// add_action(
		// 	'rest_api_init',
		// 	function () {
		// 		register_rest_field(
		// 			$this->app_config->post_types( 'car' ),
		// 			$this->app_config->post_meta( 'year' ),
		// 			array(
		// 				'get_callback'    => function ( $object ) {
		// 					// Get field as single value from post meta.
		// 					return get_post_meta( $object['id'], $this->app_config->post_meta( 'year' ), true );
		// 				},
		// 				'update_callback' => function ( $value, $object ) {
		// 					// Update the field/meta value.
		// 					update_post_meta( $object->ID, $this->app_config->post_meta( 'year' ), $value );
		// 				},
		// 				'schema'          => Argument_Parser::as_single(
		// 					Integer_Type::on( $this->app_config->post_meta( 'year' ) )
		// 						->minimum( 1850 )
		// 						->maximum( 2020 )
		// 						->description( $this->car_translations->year_description() )
		// 						->required()
		// 						->sanitization( 'absint' )
		// 				),
		// 			)
		// 		);
		// 	}
		// );
