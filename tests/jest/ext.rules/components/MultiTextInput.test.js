const { mount } = require( '@vue/test-utils' );
const MultiTextInput = require( '../../../../modules/ext.rules/components/MultiTextInput.vue' );
const { CdxTextInput } = require( '../../../../modules/codex.js' );

const mountMultiTextInput = ( props ) => mount( MultiTextInput, { props } );

describe( 'MultiTextInput.vue', () => {
	it( 'renders the correct number of text inputs', () => {
		const modelValue = [ 'foo', 'bar', '' ];

		const wrapper = mountMultiTextInput( { modelValue } );

		expect( wrapper.findAllComponents( CdxTextInput ) ).toHaveLength( 3 );
	} );

	it( 'updates the model value when a text input is changed', async () => {
		const modelValue = [ 'foo', 'bar', '' ];

		const wrapper = mountMultiTextInput( { modelValue } );
		const textInput = wrapper.findComponent( CdxTextInput );
		await textInput.vm.$emit( 'update:modelValue', 'baz' );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toEqual( [ 'baz', 'bar', '' ] );
	} );

	it( 'adds a new empty input when the last input is filled', async () => {
		const modelValue = [ 'foo', '' ];

		const wrapper = mountMultiTextInput( { modelValue } );
		const textInputs = wrapper.findAllComponents( CdxTextInput );
		await textInputs[ 1 ].vm.$emit( 'update:modelValue', 'bar' );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toEqual( [ 'foo', 'bar' ] );
	} );

	it( 'always has at least two inputs', async () => {
		const modelValue = [ '' ];

		const wrapper = mountMultiTextInput( { modelValue } );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toEqual( [ '', '' ] );
	} );

	it( 'handles empty modelValue', async () => {
		const modelValue = [];

		const wrapper = mountMultiTextInput( { modelValue } );

		expect( wrapper.emitted( 'update:modelValue' )[ 0 ][ 0 ] ).toEqual( [ '', '' ] );
	} );
} );
