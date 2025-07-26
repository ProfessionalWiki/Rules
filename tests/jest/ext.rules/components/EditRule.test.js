const { mount } = require( '@vue/test-utils' );
const EditRule = require( '../../../../modules/ext.rules/components/EditRule.vue' );
const { CdxDialog, CdxTextInput } = require( '../../../../modules/codex.js' );
const { createRule } = require( '../helpers.js' );

const mockMw = {
	msg: jest.fn( ( key ) => key ),
	util: {
		debounce: jest.fn( ( fn ) => fn )
	}
};

global.mw = mockMw;

const mountEditRule = ( props ) => mount( EditRule, { props } );

describe( 'EditRule.vue', () => {
	it( 'renders the dialog with the correct title when adding a new rule', () => {
		const wrapper = mountEditRule( { open: true } );

		expect( wrapper.findComponent( CdxDialog ).props( 'title' ) ).toBe( 'rules-table-add-rule' );
	} );

	it( 'renders the dialog with the correct title when editing a rule', () => {
		const rule = createRule( { name: 'My Rule' } );

		const wrapper = mountEditRule( { open: true, rule } );

		expect( wrapper.findComponent( CdxDialog ).props( 'title' ) ).toBe( 'rules-table-edit-rule' );
	} );

	it( 'updates the name when the text input is changed', async () => {
		const wrapper = mountEditRule( { open: true } );
		const textInput = wrapper.findComponent( CdxTextInput );
		await textInput.vm.$emit( 'update:modelValue', 'New Rule Name' );

		expect( wrapper.vm.name ).toBe( 'New Rule Name' );
	} );

	it( 'emits save and update:open when the save button is clicked', async () => {
		const wrapper = mountEditRule( { open: true } );
		const dialog = wrapper.findComponent( CdxDialog );
		await dialog.vm.$emit( 'primary' );

		expect( wrapper.emitted( 'save' ) ).toHaveLength( 1 );
		expect( wrapper.emitted( 'update:open' )[ 0 ][ 0 ] ).toBe( false );
	} );

	it( 'resets the form state when the dialog is opened to add a new rule', async () => {
		const wrapper = mountEditRule( { open: false } );
		wrapper.vm.name = 'Old Rule Name';
		await wrapper.setProps( { open: true } );

		expect( wrapper.vm.name ).toBe( '' );
	} );
} );
