const { RuleActionType, RuleConditionType } = require( '../../../modules/ext.rules/types.js' );

/**
 * Creates a mock rule object for testing.
 *
 * @param {Object} [overrides={}]
 * @return {Object}
 */
const createRule = ( overrides = {} ) => ( {
	name: 'Test Rule',
	conditions: [ {
		type: RuleConditionType.IN_CATEGORY,
		categories: [ 'Category 1' ]
	} ],
	actions: [ {
		type: RuleActionType.ADD_CATEGORY,
		category: 'Category 2'
	} ],
	...overrides
} );

/**
 * Creates an array of mock rules for testing.
 *
 * @return {Object[]}
 */
const createRules = () => [
	createRule( { name: 'Test Rule 1' } ),
	createRule( { name: 'Test Rule 2' } ),
	createRule( { name: 'Test Rule 3' } )
];

module.exports = {
	createRule,
	createRules
};
