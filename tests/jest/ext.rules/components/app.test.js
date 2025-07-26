const { mount } = require( '@vue/test-utils' );
const App = require( '../../../../modules/ext.rules/components/App.vue' );
const RulesTable = require( '../../../../modules/ext.rules/components/RulesTable.vue' );
const EditRule = require( '../../../../modules/ext.rules/components/EditRule.vue' );
const { createRule } = require( '../helpers.js' );

const mountApp = ( props ) => mount( App, { props } );

describe( 'App.vue', () => {
	it( 'renders the RulesTable component', () => {
		const wrapper = mountApp();

		expect( wrapper.findComponent( RulesTable ).exists() ).toBe( true );
	} );

	it( 'renders the EditRule component if canEdit is true', () => {
		const wrapper = mountApp( { canEdit: true } );

		expect( wrapper.findComponent( EditRule ).exists() ).toBe( true );
	} );

	it( 'does not render the EditRule component if canEdit is false', () => {
		const wrapper = mountApp( { canEdit: false } );

		expect( wrapper.findComponent( EditRule ).exists() ).toBe( false );
	} );

	it( 'renders the rules table with the correct rules', () => {
		const initialRules = [ createRule( { name: 'Initial Rule' } ) ];
		const wrapper = mountApp( { initialRules } );

		expect( wrapper.findComponent( RulesTable ).props( 'rules' ) ).toEqual( initialRules );
	} );
} );
