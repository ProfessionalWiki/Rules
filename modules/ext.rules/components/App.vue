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
		@delete-rule="onDeleteRule"
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
		function onDeleteRule( rule ) {
			const deletedRule = deleteRule( rule );
			if ( deletedRule ) {
				mw.notify(
					deletedRule.name,
					{ title: mw.msg( 'rules-notification-deleted-rule' ), type: 'success' }
				);
			}
		}

		/**
		 * @param {import('../types.js').Rule} rule
		 */
		function onSaveRule( rule ) {
			const ruleBeingEdited = ruleToEdit.value;
			if ( ruleBeingEdited ) {
				const updatedRule = updateRule( ruleBeingEdited, rule );
				if ( updatedRule ) {
					mw.notify(
						updatedRule.name,
						{ title: mw.msg( 'rules-notification-updated-rule' ), type: 'success' }
					);
				}
			} else {
				const newRule = addRule( rule );
				mw.notify(
					newRule.name,
					{ title: mw.msg( 'rules-notification-added-rule' ), type: 'success' }
				);
			}

			showRulesList();
		}

		return {
			rules,
			screen,
			ruleToEdit,
			onAddRule,
			onEditRule,
			onDeleteRule,
			onSaveRule,
			showRulesList
		};
	}
} );
</script>
