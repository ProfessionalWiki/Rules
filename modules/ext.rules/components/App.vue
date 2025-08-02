<template>
	<div v-if="errorMessage" class="error-banner">
  		{{ errorMessage }}
	</div>
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
const { defineComponent, ref, watch, provide } = require( 'vue' );
const EditRule = require( './EditRule.vue' );
const RulesTable = require( './RulesTable.vue' );
const { useRules } = require( '../composables/useRules.js' );
const { getInitialRules } = require( '../utils/rulePage.js' );

/**
 * @typedef {import('../types.js').Rule} Rule
 */

module.exports = defineComponent( {
	name: 'App',
	components: {
		EditRule,
		RulesTable
	},
	setup() {
		const isFormVisible = ref( false );
		/** @type {import('vue').Ref<Rule | null>} */
		const ruleToEdit = ref( null );
		const { rules, addRule, updateRule, deleteRule, saveRules } = useRules( getInitialRules() );
		const errorMessage = ref('');
		const api = new mw.Api();
		provide( 'api', api );
		const title = mw.config.get( 'wgPageName' );

		function onAddRule() {
			ruleToEdit.value = null;
			isFormVisible.value = true;
		}

		/**
		 * @param {Rule} rule
		 */
		function onEditRule( rule ) {
			ruleToEdit.value = rule;
			isFormVisible.value = true;
		}

		/**
		 * @param {Rule} rule
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
		 * @param {Rule} rule
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

		// Watch for changes in rules and save them to the API
		watch( rules, async () => {
			errorMessage.value = '';
			try {
				await saveRules( api, title );
			} catch ( error ) {
				console.error( 'Save failed:', error );
				errorMessage.value = 'Failed to save rules. Please try again.';
				mw.notify( errorMessage.value, { type: 'error' } );
			}
		}, { deep: true } );

		
		return {
			rules,
			isFormVisible,
			ruleToEdit,
			onAddRule,
			onEditRule,
			onDeleteRule,
			onSaveRule,
			errorMessage
		};
	}
} );
</script>



<style scoped>
.error-banner {
  background-color: #ffe6e6;
  color: #cc0000;
  border: 1px solid #ffcccc;
  padding: 12px 16px;
  border-radius: 6px;
  margin: 12px 0;
  font-weight: 500;
  font-family: sans-serif;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

