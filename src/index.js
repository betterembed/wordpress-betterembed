import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

import icon from './icon';
import edit from './edit';
import save from './save';

registerBlockType( 'betterembed/embed', {
	title: __( 'Better Embed', 'betterembed' ),
	description: __( 'Include the essential content of any website or service such as Facebook posts, Twitter tweets, Instagram posts, YouTube videos, WordPress Blogposts etc. into your own page without any extra effort.', 'betterembed' ),
	category: 'embed',
	icon,
	keywords: [ __( 'social' ) ],
	attributes: {
		url: {
			type: 'string',
		},
	},
	example: {},
	supports: {
		align: true,
		html: false
	},
	edit,
	save,
} );
