import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

const blockStyle = {
	backgroundColor: '#900',
	color: '#fff',
	padding: '20px',
};

registerBlockType( 'betterembed/betterembed', {
	title: __( 'Better Embed', 'betterembed' ),
	category: 'embed',
	example: {},
	edit() {
		return (
			<div style={ blockStyle }>
			Hello World, step 1 (from the editor).
	</div>
	);
	},
	save() {
		return (
			<div style={ blockStyle }>
			Hello World, step 1 (from the frontend).
	</div>
	);
	},
} );
