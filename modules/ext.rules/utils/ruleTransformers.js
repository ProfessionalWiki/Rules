/**
 * @typedef {import('../types.js').Rule} Rule
 * @typedef {import('../types.js').FormState} FormState
 */
const { RuleActionType, RuleConditionType } = require( '../types.js' );

/**
 * Transforms the state from the edit form into a clean Rule object ready for saving.
 *
 * @param {FormState} formState
 * @return {Rule}
 */
function formStateToRule( formState ) {
	/** @type {Rule} */
	const rule = {
		name: formState.name,
		conditions: [],
		actions: []
	};

	if ( formState.conditions ) {
		for ( const condition of formState.conditions ) {
			const categories = condition.categories
				.map( ( /** @type {string} */ c ) => c.trim() )
				.filter( ( /** @type {string} */ c ) => c );

			if ( categories.length > 0 ) {
				rule.conditions.push( {
					type: RuleConditionType.IN_CATEGORY,
					categories: categories
				} );
			}
		}
	}

	if ( formState.actions ) {
		for ( const action of formState.actions ) {
			if ( action.category ) {
				rule.actions.push( {
					type: RuleActionType.ADD_CATEGORY,
					category: action.category
				} );
			}
		}
	}

	return rule;
}

/**
 * @param {Rule} rule
 * @return {import( '@wikimedia/codex' ).TableRow}
 */
function ruleToTableRow( rule ) {
	const andText = mw.msg( 'rules-operator-and' );

	// TODO: Add links to the categories and bold the type and operator
	// TODO: Do not hardcode the message keys
	return {
		name: rule.name,
		conditions: rule.conditions.map( ( c ) => mw.msg( 'rules-table-condition-in-category', c.categories.join( ', ' ) ) ).join( ` ${ andText } ` ),
		actions: rule.actions.map( ( a ) => mw.msg( 'rules-table-action-add-category', a.category ) ).join( ` ${ andText } ` )
	};
}

module.exports = {
	formStateToRule,
	ruleToTableRow
};
