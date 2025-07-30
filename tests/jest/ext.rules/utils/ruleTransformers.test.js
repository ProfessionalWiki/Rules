const { formStateToRule, ruleToTableRow } = require( '../../../../modules/ext.rules/utils/ruleTransformers.js' );
const { RuleActionType, RuleConditionType } = require( '../../../../modules/ext.rules/types.js' );

/**
 * @typedef {import('../../../../modules/ext.rules/types.js').FormState} FormState
 * @typedef {import('../../../../modules/ext.rules/types.js').Rule} Rule
 * @typedef {import('../../../../modules/ext.rules/types.js').TableRow} TableRow
 */

describe( 'formStateToRule', () => {
	it( 'transforms a form state into a Rule object, preserving relevant data', () => {
		// TODO: Also ensure condition and action type is preserved
		// when more condition and action types are added
		const testRuleName = 'Test Rule';
		const testConditionCategories = [ 'Category 1', 'Category 2' ];
		const testActionCategory = 'Category 3';

		/** @type {FormState} */
		const formState = {
			name: testRuleName,
			conditions: [ {
				type: 'does-not-matter-because-it-is-ignored',
				categories: testConditionCategories,
				inputComponent: { name: 'MockConditionComponent' },
				isFieldset: true
			} ],
			actions: [ {
				type: 'does-not-matter-because-it-is-ignored',
				category: testActionCategory,
				inputComponent: { name: 'MockActionComponent' },
				isFieldset: false
			} ]
		};

		/** @type {Rule} */
		const expectedRule = {
			name: testRuleName,
			conditions: [ {
				type: RuleConditionType.IN_CATEGORY,
				categories: testConditionCategories
			} ],
			actions: [ {
				type: RuleActionType.ADD_CATEGORY,
				category: testActionCategory
			} ]
		};

		expect( formStateToRule( formState ) ).toEqual( expectedRule );
	} );
} );

describe( 'ruleToTableRow', () => {
	beforeEach( () => {
		global.mw = {
			msg: ( key ) => key,
			util: {
				getUrl: ( title ) => `/w/index.php?title=${ title }`
			}
		};
	} );

	it( 'transforms a Rule object into a table row', () => {
		const testRuleName = 'Test Rule';
		const testConditionCategories = [ 'Category 1', 'Category 2' ];
		const testActionCategory = 'Category 3';

		/** @type {Rule} */
		const rule = {
			name: testRuleName,
			conditions: [ {
				type: RuleConditionType.IN_CATEGORY,
				categories: testConditionCategories
			} ],
			actions: [ {
				type: RuleActionType.ADD_CATEGORY,
				category: testActionCategory
			} ]
		};

		/** @type {TableRow} */
		const expectedTableRow = {
			name: testRuleName,
			conditions: [ {
				label: 'rules-table-condition-in-category',
				links: [
					{ label: 'Category 1', href: '/w/index.php?title=Category:Category 1' },
					{ label: 'Category 2', href: '/w/index.php?title=Category:Category 2' }
				]
			} ],
			actions: [ {
				label: 'rules-table-action-add-category',
				links: [ {
					label: 'Category 3',
					href: '/w/index.php?title=Category:Category 3'
				} ]
			} ]
		};

		const tableRow = ruleToTableRow( rule );
		expect( tableRow ).toEqual( expectedTableRow );
	} );
} );
