<?php
/**
 * Plugin Name:       Details Gallery Block
 * Description:       A block to add a gallery with images including data. The data includes a name, description and link.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            <a href="https://max.gruson.studio" target="_blank">Max Gruson</a>
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       details-gallery
 *
 * @package           maxgruson/details-gallery-block
 */

namespace DETAILS_GALLERY_BLOCK;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Create a wrapped image tag that's lazy loaded and responsive
 *
 * @param  integer $id      The attachment image id. Default the post thumbnail.
 * @param  string  $sizes   The HTML sizes attribute.
 *                          Default the automatic sizes attribute returned by WordPress.
 *                          E.g. "(max-width: 1440px) 100vw, 1440px".
 * @param  string  $maxsize The maximum image size. Must be an existing WordPress image size.
 *                          Default the full image, not cropped or resized.
 * @return string           HTML <div><img></div>.
 *
 * @package maxgruson/details-gallery
 */
function image_wrapper( $id = null, $sizes = null, $maxsize = 'full' ) {
	if ( ! $id && has_post_thumbnail() ) {
		$id = get_post_thumbnail_id();
	}
	if ( wp_get_attachment_image_src( $id, $maxsize ) && ! $sizes ) {
		$sizes = wp_get_attachment_image_sizes( $id, $maxsize );
	}

	$placeholder = wp_get_attachment_image_src( $id, 'tiny-lazyload-thumbnail' )[0];
	$src         = wp_get_attachment_image_src( $id, $maxsize )[0];
	$width       = wp_get_attachment_image_src( $id, $maxsize )[1];
	$height      = wp_get_attachment_image_src( $id, $maxsize )[2];
	$srcset      = wp_get_attachment_image_srcset( $id, $maxsize );
	$alt         = get_post_meta( $id, '_wp_attachment_image_alt', true );
	?>
	<div class="img-container" style="background-image: url(<?php echo esc_attr( $placeholder ); ?>);">
	<img
		class="img-container__image 
		<?php
		if ( ! str_contains( get_post_mime_type( $id ), 'svg' ) ) {
			echo 'wp-image-' . esc_attr( $id );
		}
		?>
		"
		loading="lazy" 
		decoding="async"
		width="<?php echo esc_attr( $width ); ?>px"
		height="<?php echo esc_attr( $height ); ?>px"
		src="<?php echo esc_attr( $src ); ?>"
		srcset="<?php echo esc_attr( $srcset ); ?>"
		sizes="<?php echo esc_attr( $sizes ); ?>"
		alt="<?php echo esc_attr( $alt ); ?>"
		onload="this.style.opacity='1'"
		style="opacity: 0;"
		/>
	</div>
	<?php
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function details_gallery_block_init() {
	register_block_type( __DIR__ . '/build/details-gallery' );
	register_block_type( __DIR__ . '/build/details-gallery-item' );
}
add_action( 'init', __NAMESPACE__ . '\details_gallery_block_init' );
