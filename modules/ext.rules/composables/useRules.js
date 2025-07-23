const { ref } = require( 'vue' );

/** @typedef {import('../types.js').Rule} Rule */

/**
 * @typedef {object} RulesComposable
 * @property {import('vue').Ref<boolean>} saving
 * @property {import('vue').Ref<Rule[]>} rules
 * @property {( rule: Rule ) => Rule} addRule
 * @property {( originalRule: Rule, updatedRule: Rule ) => Rule | null} updateRule
 * @property {( rule: Rule ) => Rule | null} deleteRule
 * @property {() => Promise<any>} saveRules
 */

/**
 * @param {Rule[]} [initialRules]
 * @return {RulesComposable}
 */
function useRules( initialRules = [] ) {
	const saving = ref( false );
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
	 * @return {Promise<any>}
	 */
	async function saveRules() {
		saving.value = true;
		try {
			const content = JSON.stringify( { rules: rules.value } );
			const api = new mw.Api();
			return await api.postWithToken( 'csrf', {
				action: 'edit',
				title: mw.config.get( 'wgPageName' ),
				text: content
			} );
		} finally {
			saving.value = false;
		}
	}

	return {
		saving,
		rules,
		addRule,
		updateRule,
		deleteRule,
		saveRules
	};
}

module.exports = { useRules };
