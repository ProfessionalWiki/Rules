const { ref } = require( 'vue' );
const { RuleActionType, RuleConditionType } = require( '../types.js' );

/** @typedef {import('../types.js').Rule} Rule */

/**
 * @typedef {object} RulesComposable
 * @property {import('vue').Ref<Rule[]>} rules
 * @property {( rule: Rule ) => Rule} addRule
 * @property {( originalRule: Rule, updatedRule: Rule ) => Rule | null} updateRule
 * @property {( rule: Rule ) => Rule | null} deleteRule
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
 * @param {Rule[]} [initialRules]
 * @return {RulesComposable}
 */
function useRules( initialRules ) {
	// TODO: Refactor the rules injection when the API is ready
	/** @type {import('vue').Ref<Rule[]>} */
	const rules = ref( [ ...( initialRules || placeholderRules ) ] );

	/**
	 * @param {Rule} rule
	 * @return {Rule}
	 */
	function addRule( rule ) {
		// TODO: Save to API
		rules.value.push( rule );
		return rule;
	}

	/**
	 * @param {Rule} originalRule
	 * @param {Rule} updatedRule
	 * @return {Rule | null}
	 */
	function updateRule( originalRule, updatedRule ) {
		// TODO: Save to API
		const ruleIndex = rules.value.findIndex( ( r ) => r.name === originalRule.name );

		if ( ruleIndex !== -1 ) {
			rules.value[ ruleIndex ] = updatedRule;
			return updatedRule;
		}

		return null;
	}

	/**
	 * @param {Rule} rule
	 * @return {Rule | null}
	 */
	function deleteRule( rule ) {
		// TODO: Delete from API
		const ruleIndex = rules.value.findIndex( ( r ) => r.name === rule.name );

		if ( ruleIndex === -1 ) {
			return null;
		}

		rules.value.splice( ruleIndex, 1 );
		return rule;
	}

	return {
		rules,
		addRule,
		updateRule,
		deleteRule
	};
}

module.exports = { useRules };
