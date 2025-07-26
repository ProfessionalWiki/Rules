const { mount } = require( '@vue/test-utils' );
const FormOptions = require( '../../../../modules/ext.rules/components/FormOptions.vue' );
const { CdxButton } = require( '../../../../modules/codex.js' );

const mockMw = {
	msg: jest.fn( ( key ) => key ),
	i18n: jest.fn( () => ( {
		text: jest.fn()
	} ) )
};

global.mw = mockMw;

const mountFormOptions = ( props, slots ) => mount( FormOptions, {
	props,
	slots
} );

describe( 'FormOptions.vue', () => {
	const newItemFactory = () => ( { key: '', value: '' } );

	it( 'renders the slot content for each item', () => {
		const modelValue = [ { key: 'foo', value: 'bar' } ];
		const wrapper = mountFormOptions(
			{ modelValue, newItemFactory, addButtonLabel: 'Add' },
			{
				default: '<div class="test-slot-content"></div>'
			}
		);

		expect( wrapper.findAll( '.test-slot-content' ) ).toHaveLength( 1 );
	} );

	it( 'adds an item when the add button is clicked', async () => {
		const modelValue = [];
		const wrapper = mountFormOptions( { modelValue, newItemFactory, addButtonLabel: 'Add' } );

		await wrapper.findComponent( CdxButton ).trigger( 'click' );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toEqual( [ { key: '', value: '' } ] );
	} );

	it( 'removes an item when the remove function is called from the slot', async () => {
		const modelValue = [ { key: 'foo', value: 'bar' } ];
		const wrapper = mountFormOptions(
			{ modelValue, newItemFactory, addButtonLabel: 'Add' },
			{
				default: '<button class="remove-button" @click="remove()"></button>'
			}
		);

		await wrapper.find( '.remove-button' ).trigger( 'click' );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toEqual( [] );
	} );
} );
