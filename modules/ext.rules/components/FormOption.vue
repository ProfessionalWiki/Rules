<template>
	<div class="ext-rules-form-option">
		<div class="ext-rules-form-option-header">
			<div class="ext-rules-form-option-header-label">
				<cdx-label>
					{{ label }}
				</cdx-label>
			</div>
			<div class="ext-rules-form-option-header-remove">
				<cdx-button
					v-if="showRemove"
					class="ext-rules-form-option-remove-button"
					weight="quiet"
					:aria-label="$i18n( 'rules-multi-text-input-remove-item' ).text()"
					@click="$emit( 'remove' )"
				>
					<cdx-icon :icon="cdxIconTrash"></cdx-icon>
				</cdx-button>
			</div>
		</div>
		<div class="ext-rules-form-option-content">
			<cdx-field
				:is-fieldset="isFieldset"
				:hide-label="true"
			>
				<component
					:is="inputComponent"
					:model-value="modelValue"
					@update:model-value="$emit( 'update:modelValue', $event )"
				>
				</component>
			</cdx-field>
		</div>
	</div>
</template>

<script>
const { defineComponent } = require( 'vue' );
const { CdxButton, CdxField, CdxIcon, CdxLabel } = require( '../../codex.js' );
const MultiTextInput = require( './MultiTextInput.vue' );
const { cdxIconTrash } = require( '../icons.json' );

module.exports = defineComponent( {
	name: 'FormOption',
	components: {
		CdxButton,
		CdxField,
		CdxIcon,
		CdxLabel,
		MultiTextInput
	},
	props: {
		label: {
			type: String,
			required: true
		},
		inputComponent: {
			type: Object,
			required: true
		},
		isFieldset: {
			type: Boolean,
			default: false
		},
		modelValue: {
			type: [ String, Array ],
			required: true
		},
		showRemove: {
			type: Boolean,
			default: false
		}
	},
	emits: [ 'update:modelValue', 'remove' ],
	setup() {
		return {
			cdxIconTrash
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.ext-rules-form-option {
	border: @border-subtle;
	border-radius: @border-radius-base;
	padding: @spacing-75;
	position: relative;

	&-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: @spacing-75;
		min-height: @min-size-interactive-pointer; // Reserve space for the remove button

		.cdx-label {
			padding-bottom: 0; // Reset
		}
	}

	&-content {
		margin-top: @spacing-75;
	}

	&-add-button {
		margin-top: @spacing-75;
		width: 100%;
		max-width: none;
	}

	.cdx-field {
		margin-top: 0;
	}
}
</style>
