<template>
	<edit-rule
		v-model:open="isFormVisible"
		:rule="ruleToEdit"
		@save="onSaveRule"
	></edit-rule>

	<rules-table
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
		const isFormVisible = ref( false );
		const { rules, addRule, updateRule, deleteRule } = useRules();
		/** @type {import('vue').Ref<import('../types.js').Rule | null>} */
		const ruleToEdit = ref( null );

		function onAddRule() {
			ruleToEdit.value = null;
			isFormVisible.value = true;
		}

		/**
		 * @param {import('../types.js').Rule} rule
		 */
		function onEditRule( rule ) {
			ruleToEdit.value = rule;
			isFormVisible.value = true;
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
		}

		return {
			rules,
			isFormVisible,
			ruleToEdit,
			onAddRule,
			onEditRule,
			onDeleteRule,
			onSaveRule
		};
	}
} );
</script>

<style lang="less">
/**
 * Hide the clear your cache message and JSON code block in default MediaWiki JSON pages.
 * This is put here to ensure that they are only hidden when the Vue app is mounted.
 */
/* stylelint-disable-next-line selector-max-id */
#mw-clearyourcache,
.mw-json {
	display: none;
}
</style>
