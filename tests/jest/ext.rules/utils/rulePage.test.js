const { getInitialRules, isPageEditable } = require( '../../../../modules/ext.rules/utils/rulePage.js' );

const mockConfig = {};
global.mw.config.get = jest.fn( ( key ) => mockConfig[ key ] );

describe( 'getInitialRules', () => {
	beforeEach( () => {
		mockConfig.rules = undefined;
	} );

	it( 'Returns the initial rules if config exists', () => {
		mockConfig.rules = { value: { rules: [ { name: 'Test Rule' } ] } };
		expect( getInitialRules() ).toEqual( mockConfig.rules.value.rules );
	} );

	it( 'Returns undefined if no config does not exist', () => {
		expect( getInitialRules() ).toEqual( undefined );
	} );
} );

describe( 'isPageEditable', () => {
	beforeEach( () => {
		mockConfig.wgIsProbablyEditable = undefined;
	} );

	it( 'Returns true if page is editable', () => {
		mockConfig.wgIsProbablyEditable = true;

		expect( isPageEditable() ).toEqual( true );
	} );

	it( 'Returns false if page is not editable', () => {
		mockConfig.wgIsProbablyEditable = false;

		expect( isPageEditable() ).toEqual( false );
	} );

	it( 'Returns false if wgIsProbablyEditable is undefined', () => {
		expect( isPageEditable() ).toEqual( false );
	} );
} );
