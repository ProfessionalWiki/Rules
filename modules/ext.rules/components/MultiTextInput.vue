<template>
	<div class="ext-rules-multi-text-input">
		<div
			v-for="( item, index ) in modelValue"
			:key="index"
			class="ext-rules-multi-text-input-row"
		>
			<cdx-text-input
				:model-value="item"
				class="ext-rules-multi-text-input-row-input"
				@update:model-value="updateItem( index, $event )"
			></cdx-text-input>
		</div>
	</div>
</template>

<script>
const { defineComponent, watch } = require( 'vue' );
const { CdxTextInput } = require( '../../codex.js' );

module.exports = defineComponent( {
	name: 'MultiTextInput',
	components: {
		CdxTextInput
	},
	props: {
		modelValue: {
			type: Array,
			required: true
		}
	},
	emits: [ 'update:modelValue' ],
	setup( props, { emit } ) {
		watch(
			() => [ ...props.modelValue ],
			( currentModelValue ) => {
				const newModelValue = currentModelValue.filter( ( item ) => item !== '' );

				newModelValue.push( '' );

				if ( newModelValue.length < 2 ) {
					newModelValue.push( '' );
				}

				if ( JSON.stringify( newModelValue ) !== JSON.stringify( currentModelValue ) ) {
					emit( 'update:modelValue', newModelValue );
				}
			},
			{ immediate: true }
		);

		/**
		 * @param {number} index
		 * @param {string} value
		 */
		function updateItem( index, value ) {
			const newModelValue = [ ...props.modelValue ];
			newModelValue[ index ] = value;
			emit( 'update:modelValue', newModelValue );
		}

		return {
			updateItem
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.ext-rules-multi-text-input {
	&-row {
		display: flex;
		gap: @spacing-50;
		align-items: center;

		+ .ext-rules-multi-text-input-row {
			margin-top: @spacing-50;
		}

		&-input {
			flex-grow: 1;
		}
	}
}
</style>
