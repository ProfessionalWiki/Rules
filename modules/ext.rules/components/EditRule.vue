<template>
	<cdx-dialog
		:open="open"
		:title="dialogTitle"
		:use-close-button="true"
		:show-dividers="true"
		:primary-action="{
			label: $i18n( 'rules-edit-rule-save' ).text(),
			actionType: 'progressive'
		}"
		@primary="onSaveClick"
		@update:open="$emit( 'update:open', $event )"
	>
		<form
			class="ext-rules-editrule-form"
		>
			<div class="ext-rules-editrule-form-section">
				<cdx-field>
					<cdx-text-input
						v-model="name"
					></cdx-text-input>

					<template #label>
						{{ $i18n( 'rules-edit-rule-name-label' ).text() }}
					</template>
				</cdx-field>
			</div>

			<div class="ext-rules-editrule-form-section">
				<rule-conditions v-model:conditions="conditions"></rule-conditions>
			</div>

			<div class="ext-rules-editrule-form-section">
				<rule-actions :actions="actions"></rule-actions>
			</div>
		</form>
	</cdx-dialog>
</template>

<script>
/**
 * @typedef {import('../types.js').Rule} Rule
 */
const { defineComponent, reactive, toRefs, markRaw, computed, watch } = require( 'vue' );
const { CdxDialog, CdxField, CdxTextInput } = require( '../../codex.js' );
const MultiTextInput = require( './MultiTextInput.vue' );
const TypeaheadTextInput = require( './TypeaheadTextInput.vue' );
const RuleConditions = require( './RuleConditions.vue' );
const RuleActions = require( './RuleActions.vue' );
const { RuleActionType, RuleConditionType } = require( '../types.js' );
const { formStateToRule } = require( '../utils/ruleTransformers.js' );

module.exports = defineComponent( {
	name: 'EditRule',
	components: {
		CdxDialog,
		CdxField,
		CdxTextInput,
		RuleConditions,
		RuleActions
	},
	props: {
		rule: {
			type: Object,
			default: null
		},
		open: {
			type: Boolean,
			default: false
		}
	},
	emits: [ 'save', 'update:open' ],
	setup( props, { emit } ) {
		const formState = reactive( createDefaultFormState() );

		const dialogTitle = computed( () => (
			props.rule ?
				mw.msg( 'rules-table-edit-rule' ) :
				mw.msg( 'rules-table-add-rule' )
		) );

		watch( () => props.open, ( isOpen ) => {
			if ( isOpen && !props.rule ) {
				resetFormState();
			}
		} );

		watch( () => props.rule, ( newRule ) => {
			if ( newRule ) {
				formState.name = newRule.name;
				formState.conditions = newRule.conditions.map(
					( /** @type {import('../types.js').RuleCondition} */ c ) => ( {
						...c,
						inputComponent: markRaw( MultiTextInput ),
						isFieldset: true
					} )
				);
				formState.actions = newRule.actions.map(
					( /** @type {import('../types.js').RuleAction} */ a ) => ( {
						...a,
						inputComponent: markRaw( TypeaheadTextInput )
					} )
				);

				if ( formState.actions.length === 0 ) {
					formState.actions = createDefaultFormState().actions;
				}
			}
		}, { immediate: true } );

		function createDefaultFormState() {
			return {
				name: '',
				conditions: [
					{
						type: RuleConditionType.IN_CATEGORY,
						categories: [ '' ],
						inputComponent: markRaw( MultiTextInput ),
						isFieldset: true
					}
				],
				actions: [
					{
						type: RuleActionType.ADD_CATEGORY,
						category: '',
						inputComponent: markRaw( TypeaheadTextInput )
					}
				]
			};
		}

		function resetFormState() {
			const defaultState = createDefaultFormState();
			formState.name = defaultState.name;
			formState.conditions = defaultState.conditions;
			formState.actions = defaultState.actions;
		}

		function onSaveClick() {
			emit( 'save', formStateToRule( formState ) );
			emit( 'update:open', false );
		}

		return {
			...toRefs( formState ),
			dialogTitle,
			onSaveClick
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.ext-rules-editrule {
	&-form {
		margin-top: @spacing-150;

		&-section {
			padding-block: @spacing-150;

			&:first-child {
				padding-top: 0;
			}

			&:last-child {
				padding-bottom: 0;
			}

			+ .ext-rules-editrule-form-section {
				border-top: @border-subtle;
			}
		}
	}
}
</style>
