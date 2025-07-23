const { ref } = require( 'vue' );

/** @typedef {import('../types.js').Rule} Rule */

/**
 * @typedef {object} RulesComposable
 * @property {import('vue').Ref<Rule[]>} rules
 * @property {( rule: Rule ) => Rule} addRule
 * @property {( originalRule: Rule, updatedRule: Rule ) => Rule | null} updateRule
 * @property {( rule: Rule ) => Rule | null} deleteRule
 */

/**
 * @param {Rule[]} [initialRules]
 * @return {RulesComposable}
 */
function useRules( initialRules = [] ) {
	/** @type {import('vue').Ref<Rule[]>} */
	const rules = ref( [ ...initialRules ] );

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
