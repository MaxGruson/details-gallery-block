/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {__experimentalLinkControl as LinkControl, useBlockProps, MediaPlaceholder, RichText, BlockControls} from '@wordpress/block-editor';

import { useSelect } from '@wordpress/data';

import { ToolbarGroup, ToolbarButton, Popover, Button } from '@wordpress/components';

import { useState } from '@wordpress/element';

import { trash, link } from '@wordpress/icons';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({attributes, setAttributes}) {

	const {imageID, imageURL} = attributes;

	const thumbnailURL = useSelect( select => {
		const image = imageID && select( 'core' ).getMedia( imageID );
		const size = 'tiny-lazyload-thumbnail';
		return image && image?.media_details?.sizes[size]?.source_url || imageURL;
	}, [ imageID ] );

	const imageWidth = useSelect( select => {
		const image = imageID && select( 'core' ).getMedia( imageID );
		const size = 'large';
		return image && image?.media_details?.sizes[size]?.width || 0;
	}, [ imageID ] );

	const imageHeight = useSelect( select => {
		const image = imageID && select( 'core' ).getMedia( imageID );
		const size = 'large';
		return image && image?.media_details?.sizes[size]?.height || 0;
	}, [ imageID ] );

	const [ showLinkPopover, setShowLinkPopover ] = useState( false );
	const toggleLinkPopover = () => {
			setShowLinkPopover( ( state ) => ! state );
	};

	return (
		<>
		{/* Toolbar zone */}
		<BlockControls>
			<ToolbarGroup>
				<ToolbarButton
					icon={link}
					label={__('Link', 'details-gallery')}
					onClick={toggleLinkPopover}
					isPressed={showLinkPopover}
				/>
			</ToolbarGroup>
			{showLinkPopover && (
				<Popover>
					<LinkControl
						searchInputPlaceholder={__('Zoek of typ URL', 'details-gallery')}
						value={ attributes.link }
						onChange={ ( newLink ) => {
							setAttributes( { link: {...newLink || ''} } ) }
						}
					>
					</LinkControl>
				</Popover>
			)}
		</BlockControls>
		{/* End Toolbar zone */}

		{/* Main block zone */}
		<li className='details-gallery__item'>
			<figure { ...useBlockProps() }>
				{!!imageID && !!imageURL ? (
					<div className='img-container' style={{backgroundImage: 'url(' + {thumbnailURL} + ')' }}>
						<img
							className='img-container__image'
							loading='lazy'
							decoding='async'
							width={imageWidth}
							height={imageHeight}
							src={imageURL}
						/>
						<Button
							icon={ trash }
							label={__( 'Afbeelding verwijderen', 'details-gallery')}
							className='trash-icon'
							onClick={() => setAttributes({imageURL: null, imageID: null})} 
						>
						</Button>
					</div>
				):(
					<MediaPlaceholder
						onSelect = {
							( selectedImage ) => {
								setAttributes( { imageURL: selectedImage.url, imageID: selectedImage.id } );
							}
						}
						allowedTypes = { [ 'image' ] }
						multiple = { false }
						labels = { { title: __( 'Kies een afbeelding', 'details-gallery') } }
					/>
				) }
				<figcaption>
					<RichText 
						tagName='h3'
						allowedFormats={[]}
						value={attributes.name}
						onChange={(name) => setAttributes({name})}
						placeholder={__( 'Naam (bijv. Festival Cement)...', 'details-gallery')}
					/>
					<RichText 
						tagName='p'
						allowedFormats={[
								'core/italic',
								'core/bold',
								'core/strikethrough',
								'core/subscript',
								'core/superscript',
								'core/underline'
							]}
						value={attributes.description}
						onChange={(description) => setAttributes({description})}
						placeholder={__( 'Omschrijving (bijv. Theater van nieuwe makers)...', 'details-gallery')}
					/>
				</figcaption>
			</figure>
		</li>
		{/* End Main block zone */}
		</>
	);
}
