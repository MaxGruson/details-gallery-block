<?php
/**
 * Reaction block template.
 *
 * @param   array $attributes - A clean associative array of block attributes.
 * @param   array $block - All the block settings and attributes.
 * @param   string $content - The block inner HTML (usually empty unless using inner blocks).
 *
 * @package maxgruson/reactions-block
 */

namespace DETAILS_GALLERY_BLOCK;

$name        = $attributes['name'] ?? '';
$description = $attributes['description'] ?? '';
$link        = $attributes['link'] ?? '';
$image_id    = $attributes['imageID'] ?? '';
$image_url   = $attributes['imageURL'] ?? '';
?>
<li <?php echo get_block_wrapper_attributes( array( 'class' => 'details-gallery__item' ) ); ?>>
	<figure>
		<?php if ( ! empty( $link ) ) { ?>
		<a target="<?php echo isset( $link['opensInNewTab'] ) ? '_blank' : '_self'; ?>" href="<?php echo esc_url( $link['url'] ); ?>">
		<?php } else { ?>
		<div>
		<?php } ?>
			<div className='img-container'>
				<?php image_wrapper( $image_id, null, 'large' ); ?>
			</div>
			<figcaption>
				<h3><?php echo wp_kses_post( $name ); ?></h3>
				<p><?php echo wp_kses_post( $description ); ?></p>
			</figcaption>
		<?php if ( ! empty( $link ) ) { ?>
		</a>
		<?php } else { ?>
		</div>
		<?php } ?>
	</figure>
</li>
