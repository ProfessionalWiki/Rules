<template>
	<form-section :title="$i18n( 'rules-edit-rule-action-label' ).text()">
		<form-option
			v-for="( action, index ) in actions"
			:key="index"
			v-model="action.category"
			:input-component="action.inputComponent"
			:label="$i18n( 'rules-edit-rule-action-add-category' ).text()"
		></form-option>
	</form-section>
</template>

<script>
const { defineComponent } = require( 'vue' );
const FormOption = require( './FormOption.vue' );
const FormSection = require( './FormSection.vue' );

module.exports = defineComponent( {
	name: 'RuleActions',
	components: {
		FormOption,
		FormSection
	},
	props: {
		actions: {
			type: Array,
			required: true
		}
	}
} );
</script>
