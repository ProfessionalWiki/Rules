const { mount } = require( '@vue/test-utils' );
const RuleConditions = require( '../../../../modules/ext.rules/components/RuleConditions.vue' );
const FormOptions = require( '../../../../modules/ext.rules/components/FormOptions.vue' );

const mockMw = {
	msg: jest.fn( ( key ) => key ),
	i18n: jest.fn( () => ( {
		text: jest.fn()
	} ) )
};

global.mw = mockMw;

const mountRuleConditions = ( props ) => mount( RuleConditions, { props } );

describe( 'RuleConditions.vue', () => {
	it( 'renders the FormOptions component', () => {
		const conditions = [ { categories: [ 'foo' ] } ];

		const wrapper = mountRuleConditions( { conditions } );

		expect( wrapper.findComponent( FormOptions ).exists() ).toBe( true );
	} );

	it( 'updates the conditions when the FormOptions component emits an update', async () => {
		const conditions = [ { categories: [ 'foo' ] } ];

		const wrapper = mountRuleConditions( { conditions } );
		const formOptions = wrapper.findComponent( FormOptions );
		await formOptions.vm.$emit( 'update:modelValue', [ { categories: [ 'bar' ] } ] );

		expect( wrapper.emitted( 'update:conditions' )[ 0 ][ 0 ] ).toEqual( [ { categories: [ 'bar' ] } ] );
	} );
} );
