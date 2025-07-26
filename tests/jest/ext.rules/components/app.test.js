const { mount } = require( '@vue/test-utils' );
const App = require( '../../../../modules/ext.rules/components/App.vue' );
const RulesTable = require( '../../../../modules/ext.rules/components/RulesTable.vue' );
const EditRule = require( '../../../../modules/ext.rules/components/EditRule.vue' );
const { createRule } = require( '../helpers.js' );

const mockConfig = {
	wgPageName: 'MediaWiki:Rules'
};

const mockMw = {
	notify: jest.fn(),
	msg: jest.fn( ( key ) => key ),
	Api: jest.fn( () => ( {
		postWithToken: jest.fn()
	} ) ),
	config: {
		get: jest.fn( ( key ) => mockConfig[ key ] )
	}
};

global.mw = mockMw;

const mountApp = ( props = { canEdit: false, initialRules: [] } ) => mount( App, { props } );

describe( 'App.vue', () => {
	beforeEach( () => {
		mockMw.notify.mockClear();
		mockMw.msg.mockClear();
		mockMw.Api.mockClear();
	} );

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
		const wrapper = mountApp( { initialRules, canEdit: false } );

		expect( wrapper.findComponent( RulesTable ).props( 'rules' ) ).toEqual( initialRules );
	} );

	it( 'shows the edit form when add-rule is emitted', async () => {
		const wrapper = mountApp( { canEdit: true } );

		await wrapper.findComponent( RulesTable ).vm.$emit( 'add-rule' );

		expect( wrapper.findComponent( EditRule ).props( 'open' ) ).toBe( true );
		expect( wrapper.findComponent( EditRule ).props( 'rule' ) ).toBe( null );
	} );

	it( 'shows the edit form with a rule when edit-rule is emitted', async () => {
		const initialRules = [ createRule( { name: 'Rule to edit' } ) ];
		const wrapper = mountApp( { canEdit: true, initialRules } );

		await wrapper.findComponent( RulesTable ).vm.$emit( 'edit-rule', initialRules[ 0 ] );

		expect( wrapper.findComponent( EditRule ).props( 'open' ) ).toBe( true );
		expect( wrapper.findComponent( EditRule ).props( 'rule' ) ).toEqual( initialRules[ 0 ] );
	} );

	it( 'deletes a rule and shows notification when delete-rule is emitted', async () => {
		const initialRules = [
			createRule( { name: 'Rule 1' } ),
			createRule( { name: 'Rule 2' } )
		];
		const wrapper = mountApp( { canEdit: true, initialRules } );
		const ruleToDelete = initialRules[ 0 ];

		await wrapper.findComponent( RulesTable ).vm.$emit( 'delete-rule', ruleToDelete );

		expect( wrapper.findComponent( RulesTable ).props( 'rules' ) ).toEqual( [ initialRules[ 1 ] ] );
		expect( mw.notify ).toHaveBeenCalledWith(
			ruleToDelete.name,
			{ title: 'rules-notification-deleted-rule', type: 'success' }
		);
	} );

	it( 'adds a rule and shows notification when a new rule is saved', async () => {
		const wrapper = mountApp( { canEdit: true, initialRules: [] } );
		const newRule = createRule( { name: 'New Rule' } );

		await wrapper.findComponent( RulesTable ).vm.$emit( 'add-rule' );
		await wrapper.findComponent( EditRule ).vm.$emit( 'save', newRule );

		expect( wrapper.findComponent( RulesTable ).props( 'rules' ) ).toEqual( [ newRule ] );
		expect( mw.notify ).toHaveBeenCalledWith(
			newRule.name,
			{ title: 'rules-notification-added-rule', type: 'success' }
		);
	} );

	it( 'updates a rule and shows notification when an existing rule is saved', async () => {
		const ruleToUpdate = createRule( { name: 'Original Rule' } );
		const initialRules = [ ruleToUpdate ];
		const wrapper = mountApp( { canEdit: true, initialRules } );
		const updatedRule = { ...ruleToUpdate, name: 'Updated Rule' };

		await wrapper.findComponent( RulesTable ).vm.$emit( 'edit-rule', ruleToUpdate );
		await wrapper.findComponent( EditRule ).vm.$emit( 'save', updatedRule );

		const rulesProp = wrapper.findComponent( RulesTable ).props( 'rules' );
		expect( rulesProp[ 0 ].name ).toBe( 'Updated Rule' );
		expect( mw.notify ).toHaveBeenCalledWith(
			updatedRule.name,
			{ title: 'rules-notification-updated-rule', type: 'success' }
		);
	} );
} );
