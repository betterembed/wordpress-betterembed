const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
const path = require('path');
module.exports = {
	...defaultConfig,
	entry: [
		'./assets/src/js/editor/index.js',
		'./assets/src/scss/editor.scss',
		'./assets/src/scss/style.scss',
		'./assets/src/scss/theme.scss'
	],
	output: {
		path: path.resolve(__dirname, 'assets/build'),
		filename: 'index.js',
	},
	module: {
		rules: [
			/**
			 * Running Babel on JS files.
			 */
			...defaultConfig.module.rules,
			{
				test: /\.scss$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].css',
						}
					},
					{
						loader: 'extract-loader'
					},
					{
						loader: 'css-loader?-url'
					},
					{
						loader: 'postcss-loader'
					},
					{
						loader: 'sass-loader'
					}
				]
			}
		]
	}
};
