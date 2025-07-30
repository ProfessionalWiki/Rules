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
	return {
		name: rule.name,
		conditions: rule.conditions.map( formatConditionForTableRow ),
		actions: rule.actions.map( formatActionForTableRow )
	};
}

/**
 * @param {import('../types.js').RuleCondition} condition
 * @return {import('../types.js').RuleTableCell}
 */
function formatConditionForTableRow( condition ) {
	// TODO: Do not hardcode the message keys
	return {
		label: mw.msg( 'rules-table-condition-in-category' ),
		links: condition.categories.map( ( category ) => ( {
			label: category,
			href: mw.util.getUrl( `Category:${ category }` )
		} ) )
	};
}

/**
 * @param {import('../types.js').RuleAction} action
 * @return {import('../types.js').RuleTableCell}
 */
function formatActionForTableRow( action ) {
	// TODO: Do not hardcode the message keys
	return {
		label: mw.msg( 'rules-table-action-add-category' ),
		links: [ {
			label: action.category,
			href: mw.util.getUrl( `Category:${ action.category }` )
		} ]
	};
}

module.exports = {
	formStateToRule,
	ruleToTableRow,
};
