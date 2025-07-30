const { mount } = require( '@vue/test-utils' );
const FormOption = require( '../../../../modules/ext.rules/components/FormOption.vue' );
const { CdxButton } = require( '../../../../modules/codex.js' );

const mockMw = {
	msg: jest.fn( ( key ) => key ),
	i18n: jest.fn( () => ( {
		text: jest.fn()
	} ) )
};

global.mw = mockMw;

const mountFormOption = ( props ) => mount( FormOption, { props } );

describe( 'FormOption.vue', () => {
	it( 'renders the label', () => {
		const wrapper = mountFormOption( {
			label: 'My Label',
			inputComponent: { template: '<div></div>' },
			modelValue: ''
		} );

		expect( wrapper.text() ).toContain( 'My Label' );
	} );

	it( 'renders the input component', () => {
		const wrapper = mountFormOption( {
			label: 'My Label',
			inputComponent: { template: '<div class="test-input"></div>' },
			modelValue: ''
		} );

		expect( wrapper.find( '.test-input' ).exists() ).toBe( true );
	} );

	it( 'emits an update when the input component emits an update', async () => {
		const inputComponent = {
			template: '<input />',
			props: [ 'modelValue' ],
			emits: [ 'update:modelValue' ]
		};
		const wrapper = mountFormOption( {
			label: 'My Label',
			inputComponent,
			modelValue: ''
		} );
		await wrapper.findComponent( inputComponent ).vm.$emit( 'update:modelValue', 'foo' );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toBe( 'foo' );
	} );

	it( 'shows the remove button when showRemove is true', () => {
		const wrapper = mountFormOption( {
			label: 'My Label',
			inputComponent: { template: '<div></div>' },
			modelValue: '',
			showRemove: true
		} );

		expect( wrapper.findComponent( CdxButton ).exists() ).toBe( true );
	} );

	it( 'does not show the remove button when showRemove is false', () => {
		const wrapper = mountFormOption( {
			label: 'My Label',
			inputComponent: { template: '<div></div>' },
			modelValue: '',
			showRemove: false
		} );

		expect( wrapper.findComponent( CdxButton ).exists() ).toBe( false );
	} );

	it( 'emits remove when the remove button is clicked', async () => {
		const wrapper = mountFormOption( {
			label: 'My Label',
			inputComponent: { template: '<div></div>' },
			modelValue: '',
			showRemove: true
		} );
		await wrapper.findComponent( CdxButton ).trigger( 'click' );

		expect( wrapper.emitted( 'remove' ) ).toHaveLength( 1 );
	} );
} );
