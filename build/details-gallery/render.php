<?php
/**
 * Reactions block template.
 *
 * @param   array $attributes - A clean associative array of block attributes.
 * @param   array $block - All the block settings and attributes.
 * @param   string $content - The block inner HTML (usually empty unless using inner blocks).
 *
 * @package maxgruson/reactions-block
 */

namespace DETAILS_GALLERY_BLOCK;

?>
<ul <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo wp_kses_post( $content ); ?>
</ul>
