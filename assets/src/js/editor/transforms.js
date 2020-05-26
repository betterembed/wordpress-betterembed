/**
 * WordPress dependencies
 */
import { renderToString } from '@wordpress/element';
import { createBlock } from '@wordpress/blocks';

/**
 * Default transforms for generic embeds.
 */
const transforms = {
	from: [
		{
			type: 'block',
			blocks: [ 'core/embed' ],
			transform: ( { url } ) => {
				return createBlock( 'betterembed/embed', { url } );
			},
		},
		{
			type: 'raw',
			isMatch: ( node ) =>
				node.nodeName === 'P' &&
				/^\s*(https?:\/\/\S+)\s*$/i.test( node.textContent ),
			priority: 11, //For now the default core embeds should take priority
			transform: ( node ) => {
				return createBlock( 'betterembed/embed', {
					url: node.textContent.trim(),
				} );
			},
		},
	],
	to: [
		{
			type: 'block',
			blocks: [ 'core/embed' ],
			isMatch: ( { url } ) => url,
			transform: ( { url } ) => {
				return createBlock( 'core/embed', { url } );
			},
		},
		{
			type: 'block',
			blocks: [ 'core/paragraph' ],
			transform: ( { url } ) => {
				const link = <a href={ url }>{ url }</a>;
				return createBlock( 'core/paragraph', {
					content: renderToString( link ),
				} );
			},
		},
	],
};

export function addEmbedsFrom() {
	const filteredBlockType = wp.blocks
		.getBlockTypes()
		.filter( ( blockType ) => /core-embed\//i.test( blockType.name ) );
	const mappedBlockTypes = filteredBlockType.map(
		( blockType ) => blockType.name
	);

	const transformSetting = wp.blocks.getBlockType( 'betterembed/embed' )
		.transforms;

	transformSetting.from = [
		...transformSetting.from,
		{
			type: 'block',
			blocks: mappedBlockTypes,
			transform: ( { url } ) => {
				return createBlock( 'betterembed/embed', { url } );
			},
		},
	];
}

export default transforms;
