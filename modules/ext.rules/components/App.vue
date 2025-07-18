<template>
	<edit-rule
		v-if="screen === 'edit-rule'"
		:rule="ruleToEdit"
		@back="showRulesList"
		@save="onSaveRule"
	></edit-rule>

	<rules-table
		v-if="screen === 'rules-list'"
		:rules="rules"
		@add-rule="onAddRule"
		@edit-rule="onEditRule"
		@delete-rule="deleteRule"
	></rules-table>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const EditRule = require( './EditRule.vue' );
const RulesTable = require( './RulesTable.vue' );
const { useRules } = require( '../composables/useRules.js' );

module.exports = defineComponent( {
	name: 'App',
	components: {
		EditRule,
		RulesTable
	},
	setup() {
		const screen = ref( 'rules-list' );
		const { rules, addRule, updateRule, deleteRule } = useRules();
		/** @type {import('vue').Ref<import('../types.js').Rule | null>} */
		const ruleToEdit = ref( null );

		function onAddRule() {
			ruleToEdit.value = null;
			screen.value = 'edit-rule';
		}

		/**
		 * @param {import('../types.js').Rule} rule
		 */
		function onEditRule( rule ) {
			ruleToEdit.value = rule;
			screen.value = 'edit-rule';
		}

		function showRulesList() {
			screen.value = 'rules-list';
		}

		/**
		 * @param {import('../types.js').Rule} rule
		 */
		function onSaveRule( rule ) {
			const ruleBeingEdited = ruleToEdit.value;
			if ( ruleBeingEdited ) {
				updateRule( ruleBeingEdited, rule );
			} else {
				addRule( rule );
			}

			showRulesList();
		}

		return {
			rules,
			screen,
			ruleToEdit,
			onAddRule,
			onEditRule,
			deleteRule,
			onSaveRule,
			showRulesList
		};
	}
} );
</script>
