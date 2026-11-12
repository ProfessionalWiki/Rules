'use strict';

const { defineConfig, globalIgnores } = require( 'eslint/config' );
const globals = require( 'globals' );
const noJquery = require( 'eslint-plugin-no-jquery' );
const wikimedia = require( 'eslint-config-wikimedia/flat' );

module.exports = defineConfig( [
	globalIgnores( [
		'coverage/',
		'i18n/',
		'vendor/'
	] ),

	// modules/ runs in the browser, and tests/ tests it: both get the client config below
	{
		ignores: [ 'modules/**', 'tests/**' ],
		extends: [ wikimedia( 'server' ) ]
	},

	{
		files: [ 'modules/**', 'tests/**' ],
		extends: [
			noJquery.configs[ 'flat/all' ],
			wikimedia( 'client', 'mediawiki' )
		],
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'module',
			globals: {
				exports: 'writable'
			}
		},
		rules: {
			'es-x/no-optional-chaining': 'off'
		}
	},
	{
		files: [ 'tests/**' ],
		languageOptions: {
			ecmaVersion: 2018,
			globals: {
				...globals.node,
				...globals.jest
			}
		}
	}
] );
