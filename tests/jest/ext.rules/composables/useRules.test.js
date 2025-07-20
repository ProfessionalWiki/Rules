const { useRules } = require( '../../../../modules/ext.rules/composables/useRules.js' );
const { createRule, createRules } = require( '../helpers.js' );

describe( 'useRules', () => {
	it( 'adds a rule', () => {
		const initialRules = createRules();
		const { rules, addRule } = useRules( initialRules );
		const newRule = createRule( { name: 'New rule' } );

		addRule( newRule );

		expect( rules.value ).toEqual( [ ...initialRules, newRule ] );
	} );

	it( 'updates a rule', () => {
		const initialRules = createRules();
		const { rules, updateRule } = useRules( initialRules );
		const ruleToUpdate = initialRules[ 1 ];
		const updatedRule = {
			...ruleToUpdate,
			name: 'Updated rule'
		};

		updateRule( ruleToUpdate, updatedRule );

		expect( rules.value ).toEqual( [
			initialRules[ 0 ],
			updatedRule,
			initialRules[ 2 ]
		] );
	} );

	it( 'deletes a rule', () => {
		const initialRules = createRules();
		const { rules, deleteRule } = useRules( initialRules );
		const ruleToDelete = initialRules[ 1 ];

		deleteRule( ruleToDelete );

		expect( rules.value ).toEqual( [
			initialRules[ 0 ],
			initialRules[ 2 ]
		] );
	} );
} );
