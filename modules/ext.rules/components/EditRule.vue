<template>
	<div class="ext-rules-editrule">
		<cdx-button
			class="ext-rules-editrule-back-button"
			weight="quiet"
			@click="onBackClick"
		>
			<cdx-icon :icon="cdxIconPrevious"></cdx-icon>
			{{ $i18n( 'rules-edit-rule-back' ).text() }}
		</cdx-button>

		<form
			class="ext-rules-editrule-form"
			@submit.prevent="onSaveClick"
		>
			<div class="ext-rules-editrule-form-title">
				{{ $i18n( 'rules-edit-rule-title' ).text() }}
			</div>

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

			<div class="ext-rules-editrule-form-buttons">
				<cdx-button
					type="button"
					@click="onBackClick"
				>
					{{ $i18n( 'rules-edit-rule-cancel' ).text() }}
				</cdx-button>

				<cdx-button
					type="submit"
					action="progressive"
					weight="primary"
				>
					{{ $i18n( 'rules-edit-rule-save' ).text() }}
				</cdx-button>
			</div>
		</form>
	</div>
</template>

<script>
/**
 * @typedef {import('../types.js').Rule} Rule
 */
const { defineComponent, reactive, toRefs, markRaw } = require( 'vue' );
const { CdxButton, CdxField, CdxIcon, CdxTextInput } = require( '../../codex.js' );
const { cdxIconPrevious } = require( '../icons.json' );
const MultiTextInput = require( './MultiTextInput.vue' );
const RuleConditions = require( './RuleConditions.vue' );
const RuleActions = require( './RuleActions.vue' );
const { RuleActionType, RuleConditionType } = require( '../types.js' );
const { formStateToRule } = require( '../utils/ruleTransformers.js' );

module.exports = defineComponent( {
	name: 'EditRule',
	components: {
		CdxButton,
		CdxField,
		CdxIcon,
		CdxTextInput,
		RuleConditions,
		RuleActions
	},
	props: {
		rule: {
			type: Object,
			default: null
		}
	},
	emits: [ 'back', 'save' ],
	setup( props, { emit } ) {
		const formState = reactive( {
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
					inputComponent: markRaw( CdxTextInput )
				}
			]
		} );

		if ( props.rule ) {
			formState.name = props.rule.name;
			formState.conditions = props.rule.conditions.map(
				( /** @type {import('../types.js').RuleCondition} */ c ) => ( {
					...c,
					inputComponent: markRaw( MultiTextInput ),
					isFieldset: true
				} )
			);
			formState.actions = props.rule.actions.map(
				( /** @type {import('../types.js').RuleAction} */ a ) => ( {
					...a,
					inputComponent: markRaw( CdxTextInput )
				} )
			);
		}

		function onBackClick() {
			// TODO: Implement back
			emit( 'back' );
		}

		function onSaveClick() {
			const rule = formStateToRule( formState );
			emit( 'save', rule );
		}

		return {
			...toRefs( formState ),
			onBackClick,
			onSaveClick,
			cdxIconPrevious
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.ext-rules-editrule {
	&-back-button {
		// Align the back button with the left edge of the page
		// @spacing-75 is the padding of CdxButton
		margin-left: -@spacing-75;
	}

	&-form {
		margin-top: @spacing-150;

		&-title {
			font-size: @font-size-x-large;
			font-weight: @font-weight-bold;
			// Fallback for pre-Codex 2.0 MediaWiki versions
			line-height: var( --line-height-x-large, 1.875rem );
		}

		&-section {
			padding-block: @spacing-150;

			+ .ext-rules-editrule-form-section {
				border-top: @border-subtle;
			}
		}

		&-buttons {
			border-top: @border-subtle;
			margin-top: @spacing-100;
			padding-top: @spacing-100;
			display: flex;
			justify-content: space-between;
			gap: @spacing-100;
		}
	}
}
</style>
