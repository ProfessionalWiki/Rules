const { ref } = require( 'vue' );

/** @typedef {import('../types.js').Rule} Rule */

/**
 * @typedef {object} RulesComposable
 * @property {import('vue').Ref<boolean>} saving
 * @property {import('vue').Ref<string|null>} saveError
 * @property {import('vue').Ref<Rule[]>} rules
 * @property {( rule: Rule ) => Rule} addRule
 * @property {( originalRule: Rule, updatedRule: Rule ) => Rule | null} updateRule
 * @property {( rule: Rule ) => Rule | null} deleteRule
 * @property {( api: MwApi, title: string ) => Promise<any>} saveRules
 */

/**
 * @param {Rule[]} [initialRules]
 * @return {RulesComposable}
 */
function useRules( initialRules = [] ) {
	const saving = ref( false );
	const saveError = ref(' ');
	/** @type {import('vue').Ref<Rule[]>} */
	const rules = ref( [ ...initialRules ] );

	/**
	 * @param {Rule} rule
	 * @return {Rule}
	 */
	function addRule( rule ) {
		rules.value.push( rule );
		return rule;
	}

	/**
	 * @param {Rule} originalRule
	 * @param {Rule} updatedRule
	 * @return {Rule | null}
	 */
	function updateRule( originalRule, updatedRule ) {
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
		const ruleIndex = rules.value.findIndex( ( r ) => r.name === rule.name );

		if ( ruleIndex === -1 ) {
			return null;
		}

		rules.value.splice( ruleIndex, 1 );
		return rule;
	}

	/**
	 * @param {MwApi} api
	 * @param {string} title
	 * @return {Promise<any>}
	 */
	async function saveRules(api, title) {
	saving.value = true;
	saveError.value = null;

	try {
		const content = JSON.stringify({ rules: rules.value });
		return await api.postWithToken('csrf', {
			action: 'edit',
			title,
			text: content
		});
	} catch (error) {
		console.error('Failed to save rules:', error);
		saveError.value = error;
		throw error;
	} finally {
		saving.value = false;
	}
}


	return {
		saving,
		saveError,
		rules,
		addRule,
		updateRule,
		deleteRule,
		saveRules
	};
}

module.exports = { useRules };
