const { ref } = require( 'vue' );
const { RuleActionType, RuleConditionType } = require( '../types.js' );

/** @typedef {import('../types.js').Rule} Rule */

/**
 * @typedef {object} RulesComposable
 * @property {import('vue').Ref<Rule[]>} rules
 * @property {( rule: Rule ) => void} addRule
 * @property {( originalRule: Rule, updatedRule: Rule ) => void} updateRule
 * @property {( rule: Rule ) => void} deleteRule
 */

/** @type {Rule[]} */
const placeholderRules = [
	{
		name: 'Shorthair cats',
		conditions: [
			{
				type: RuleConditionType.IN_CATEGORY,
				categories: [ 'Siamese', 'British Shorthair', 'Abyssinian', 'Burmese' ]
			}
		],
		actions: [
			{
				type: RuleActionType.ADD_CATEGORY,
				category: 'Shorthair cats'
			}
		]
	},
	{
		name: 'Mediumhair cats',
		conditions: [
			{
				type: RuleConditionType.IN_CATEGORY,
				categories: [ 'American Bobtail', 'Birman', 'Ragdoll', 'Siberian' ]
			}
		],
		actions: [
			{
				type: RuleActionType.ADD_CATEGORY,
				category: 'Mediumhair cats'
			}
		]
	},
	{
		name: 'Longhair cats',
		conditions: [
			{
				type: RuleConditionType.IN_CATEGORY,
				categories: [ 'Persian', 'Maine Coon', 'Norwegian Forest Cat', 'Himalayan' ]
			}
		],
		actions: [
			{
				type: RuleActionType.ADD_CATEGORY,
				category: 'Longhair cats'
			}
		]
	}
];

/**
 * @return {RulesComposable}
 */
function useRules() {
	/** @type {import('vue').Ref<Rule[]>} */
	const rules = ref( placeholderRules );

	/**
	 * @param {Rule} rule
	 */
	function addRule( rule ) {
		// TODO: Save to API
		rules.value.push( rule );
		// TODO: Show success notification
	}

	/**
	 * @param {Rule} originalRule
	 * @param {Rule} updatedRule
	 */
	function updateRule( originalRule, updatedRule ) {
		// TODO: Save to API
		const ruleIndex = rules.value.findIndex( ( r ) => r.name === originalRule.name );
		if ( ruleIndex !== -1 ) {
			rules.value[ ruleIndex ] = updatedRule;
		}
		// TODO: Show success notification
	}

	/**
	 * @param {Rule} rule
	 */
	function deleteRule( rule ) {
		// TODO: Delete from API
		const ruleIndex = rules.value.findIndex( ( r ) => r.name === rule.name );

		if ( ruleIndex === -1 ) {
			return;
		}

		rules.value.splice( ruleIndex, 1 );
		mw.notify( `Deleted rule: "${ rule.name }"`, { type: 'success' } );
	}

	return {
		rules,
		addRule,
		updateRule,
		deleteRule
	};
}

module.exports = { useRules };
