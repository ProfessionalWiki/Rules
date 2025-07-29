<template>
	<form-section :title="$i18n( 'rules-edit-rule-condition-label' ).text()">
		<form-options
			:model-value="conditions"
			:add-button-label="$i18n( 'rules-edit-rule-condition-add' ).text()"
			:new-item-factory="createConditionItem"
			@update:model-value="$emit( 'update:conditions', $event )"
		>
			<template #default="{ item, remove }">
				<form-option
					v-model="item.categories"
					:input-component="item.inputComponent"
					:input-props="{ searchMenuItems: searchCategories }"
					:label="$i18n( 'rules-edit-rule-condition-in-category' ).text()"
					:show-remove="conditions.length > 1"
					@remove="remove"
				></form-option>
			</template>
		</form-options>
	</form-section>
</template>

<script>
const { defineComponent, markRaw } = require( 'vue' );
const MultiTextInput = require( './MultiTextInput.vue' );
const FormOption = require( './FormOption.vue' );
const FormOptions = require( './FormOptions.vue' );
const FormSection = require( './FormSection.vue' );
const { RuleConditionType } = require( '../types.js' );
const useSearchCategories = require( '../composables/useSearchCategories.js' );

module.exports = defineComponent( {
	name: 'RuleConditions',
	components: {
		FormOption,
		FormOptions,
		FormSection
	},
	props: {
		conditions: {
			type: Array,
			required: true
		}
	},
	emits: [ 'update:conditions' ],
	setup() {
		const { searchCategories } = useSearchCategories();
		function createConditionItem() {
			return {
				type: RuleConditionType.IN_CATEGORY,
				categories: [ '' ],
				inputComponent: markRaw( MultiTextInput ),
				isFieldset: true
			};
		}

		return {
			createConditionItem,
			searchCategories
		};
	}
} );
</script>
