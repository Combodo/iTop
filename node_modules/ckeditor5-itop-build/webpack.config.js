/**
 * @license Copyright (c) 2014-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

'use strict';

/* eslint-env node */

const path = require( 'path' );
const TerserWebpackPlugin = require( 'terser-webpack-plugin' );
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyPlugin = require( 'copy-webpack-plugin' );

module.exports = {
    /*
	devtool: 'source-map', // Uncomment to build a development version of the editor. It will be much bigger than the production version, but it will be easier to debug.
    */
	performance: { hints: false },

	entry: path.resolve( __dirname, 'src', 'ckeditor.ts' ),

	output: {
		// The name under which the editor will be exported.
		library: 'ClassicEditor',

		path: path.resolve( __dirname, 'build' ),
		filename: 'ckeditor.js',
		libraryTarget: 'umd',
		libraryExport: 'default'
	},

	optimization: {
		minimizer: [
			new TerserWebpackPlugin( {
				terserOptions: {
                    sourceMap: true,
                    output: {
						// Preserve CKEditor 5 license comments.
						comments: /^!/
					}
				},
				extractComments: false
			} )
		]
	},

	plugins: [
		new MiniCssExtractPlugin({
			filename: 'styles/compiled-theme.scss', // Compiled as SCSS so we can import it in other SCSS files, but it's already compiled in CSS
			chunkFilename: 'styles/compiled-theme-id.css', // We don't know what this is for
		}),
		new CopyPlugin( {
			patterns: [
				{
					from: path.resolve( __dirname, 'node_modules/ckeditor5/dist/translations/*.umd.js' ),
					to: 'translations/[name][ext]'
				}
			]
		} ),
	],

	resolve: {
		extensions: [ '.ts', '.js', '.json']
	},

	module: {
		rules: [ {
			test: /\.svg$/,
			use: [ 'raw-loader' ]
		}, {
			test: /\.ts$/,
			use: 'ts-loader'
		}, {
			test: /\.css$/,
			use: [
				MiniCssExtractPlugin.loader,
				{
					loader: 'css-loader'
				}
			]
		} ]
	}
};
