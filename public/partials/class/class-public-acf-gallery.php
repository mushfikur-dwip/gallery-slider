<?php
/**
 * ACF Gallery Handler for Custom Post Types
 *
 * Handles retrieval and conversion of ACF gallery fields to gallery format.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Gallery_Slider
 * @subpackage Woo_Gallery_Slider/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * ACF Gallery Handler Class
 */
class WCGS_Public_Acf {

	/**
	 * Check if ACF plugin is active
	 *
	 * @return bool
	 */
	public static function is_acf_active() {
		return function_exists( 'get_field' );
	}

	/**
	 * Get ACF gallery images for a post
	 *
	 * @param int    $post_id Post ID.
	 * @param string $field_name ACF field name.
	 * @return array Array of image IDs or empty array.
	 */
	public static function get_gallery_images( $post_id, $field_name = 'car_gallery' ) {
		if ( ! self::is_acf_active() ) {
			return array();
		}

		$acf_images = get_field( $field_name, $post_id );
		
		if ( empty( $acf_images ) || ! is_array( $acf_images ) ) {
			return array();
		}

		return $acf_images;
	}

	/**
	 * Check if post has ACF gallery
	 *
	 * @param int    $post_id Post ID.
	 * @param string $field_name ACF field name.
	 * @return bool
	 */
	public static function has_gallery( $post_id, $field_name = 'car_gallery' ) {
		if ( ! self::is_acf_active() ) {
			return false;
		}

		$images = self::get_gallery_images( $post_id, $field_name );
		return ! empty( $images );
	}

	/**
	 * Get supported post types for ACF gallery
	 *
	 * @return array
	 */
	public static function get_supported_post_types() {
		return apply_filters( 'wcgs_acf_supported_post_types', array( 'car' ) );
	}

	/**
	 * Get ACF field name for a post type
	 *
	 * @param string $post_type Post type.
	 * @return string
	 */
	public static function get_field_name_for_post_type( $post_type ) {
		$field_names = apply_filters(
			'wcgs_acf_field_names',
			array(
				'car' => 'car_gallery',
			)
		);

		return isset( $field_names[ $post_type ] ) ? $field_names[ $post_type ] : '';
	}

	/**
	 * Check if current post type is supported
	 *
	 * @param string $post_type Post type.
	 * @return bool
	 */
	public static function is_supported_post_type( $post_type ) {
		return in_array( $post_type, self::get_supported_post_types(), true );
	}
}
