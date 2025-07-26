const { mount } = require( '@vue/test-utils' );
const RuleActions = require( '../../../../modules/ext.rules/components/RuleActions.vue' );
const FormOption = require( '../../../../modules/ext.rules/components/FormOption.vue' );

const mockMw = {
	msg: jest.fn( ( key ) => key ),
	i18n: jest.fn( () => ( {
		text: jest.fn()
	} ) )
};

global.mw = mockMw;

const mountRuleActions = ( props ) => mount( RuleActions, { props } );

describe( 'RuleActions.vue', () => {
	it( 'renders the correct number of FormOption components', () => {
		const actions = [ { category: 'foo' }, { category: 'bar' } ];
		const wrapper = mountRuleActions( { actions } );
		expect( wrapper.findAllComponents( FormOption ) ).toHaveLength( 2 );
	} );

	it( 'updates the action when the FormOption component emits an update', async () => {
		const actions = [ { category: 'foo' } ];
		const wrapper = mountRuleActions( { actions } );
		const formOption = wrapper.findComponent( FormOption );
		await formOption.vm.$emit( 'update:modelValue', 'baz' );
		expect( actions[ 0 ].category ).toBe( 'baz' );
	} );
} );
