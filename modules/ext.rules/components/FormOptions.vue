<template>
	<div class="ext-rules-form-options">
		<template
			v-for="( item, index ) in modelValue"
			:key="index"
		>
			<slot
				:item="item"
				:index="index"
				:remove="() => onRemoveItemClick( index )"
			></slot>

			<div
				v-if="index < modelValue.length - 1"
				class="ext-rules-form-options-conjunction"
			>
				<div class="ext-rules-form-options-conjunction-label">
					{{ $i18n( 'rules-operator-and' ).text() }}
				</div>
			</div>
		</template>

		<cdx-button
			class="ext-rules-form-options-add-button"
			type="button"
			@click="onAddItemClick"
		>
			<cdx-icon :icon="cdxIconAdd"></cdx-icon>
			{{ addButtonLabel }}
		</cdx-button>
	</div>
</template>

<script>
const { defineComponent } = require( 'vue' );
const { CdxButton, CdxIcon } = require( '../../codex.js' );
const { cdxIconAdd } = require( '../icons.json' );

module.exports = exports = defineComponent( {
	name: 'FormOptions',

	components: {
		CdxButton,
		CdxIcon
	},

	props: {
		modelValue: {
			type: Array,
			required: true
		},
		newItemFactory: {
			type: Function,
			required: true
		},
		addButtonLabel: {
			type: String,
			required: true
		}
	},

	emits: [ 'update:modelValue' ],

	setup( props, { emit } ) {
		function onAddItemClick() {
			const newValue = [ ...props.modelValue, props.newItemFactory() ];
			emit( 'update:modelValue', newValue );
		}

		/**
		 * @param {number} index
		 */
		function onRemoveItemClick( index ) {
			const newValue = [ ...props.modelValue ];
			newValue.splice( index, 1 );
			emit( 'update:modelValue', newValue );
		}

		return {
			onAddItemClick,
			onRemoveItemClick,
			cdxIconAdd
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.ext-rules-form-options {
	&-conjunction {
		display: flex;
		justify-content: center;
		position: relative;
		padding-block: @spacing-75;

		&::before {
			content: '';
			position: absolute;
			display: block;
			width: 1px;
			top: 0;
			bottom: 0;
			background-color: @border-color-subtle;
			margin-inline: @spacing-100;
			z-index: @z-index-bottom;
		}

		&-label {
			font-weight: @font-weight-bold;
			border: @border-subtle;
			border-radius: @border-radius-base;
			padding: @spacing-25 @spacing-100;
			background-color: @background-color-base;
		}
	}

	&-add-button {
		margin-top: @spacing-75;
		width: 100%;
		max-width: none;
	}
}
</style>
