<template>
	<div class="ext-rules-typeahead-text-input">
		<cdx-text-input
			ref="input"
			:model-value="selectedValue"
			role="combobox"
			:aria-expanded="expanded"
			:aria-controls="menuId"
			:aria-activedescendant="activeDescendant"
			@update:model-value="selectedValue = $event"
			@click="onClick"
			@blur="expanded = false"
			@keydown="onKeydown"
		></cdx-text-input>
		<cdx-menu
			v-if="searchMenuItems"
			:id="menuId"
			ref="menu"
			v-model:selected="selectedValue"
			v-model:expanded="expanded"
			:menu-items="menuItems"
			:search-query="modelValue"
		></cdx-menu>
	</div>
</template>

<script>
const { defineComponent, ref, computed, watch } = require( 'vue' );
const { CdxMenu, CdxTextInput, useGeneratedId, useFloatingMenu } = require( '../../codex.js' );

module.exports = defineComponent( {
	name: 'TypeaheadTextInput',
	components: {
		CdxMenu,
		CdxTextInput
	},
	props: {
		modelValue: {
			type: String,
			default: ''
		},
		searchMenuItems: {
			type: Function,
			default: null
		}
	},
	emits: [ 'update:modelValue' ],
	setup( props, { emit } ) {
		const input = ref();
		const menu = ref();
		/** @type {import('vue').Ref<import( '@wikimedia/codex' ).MenuItemData[]>} */
		const menuItems = ref( [] );
		const selectedValue = computed( {
			get: () => props.modelValue,
			set: ( value ) => {
				emit( 'update:modelValue', value );
			}
		} );
		const expanded = ref( false );
		const activeDescendant = computed( () => {
			const highlightedItem = menu.value && menu.value.getHighlightedMenuItem();
			return highlightedItem ? highlightedItem.id : undefined;
		} );
		const menuId = useGeneratedId( 'menu' );
		let lastQuery = '';

		useFloatingMenu( input, menu );

		/**
		 * @param {string} query The search query
		 */
		async function performSearch( query ) {
			if ( !props.searchMenuItems ) {
				return;
			}
			lastQuery = query;
			const newMenuItems = await props.searchMenuItems( query );

			if ( query !== lastQuery ) {
				return;
			}

			menuItems.value = newMenuItems;

			if (
				menuItems.value.length === 1 &&
				menuItems.value[ 0 ].value === query
			) {
				// The only suggestion is an exact match of the current input.
				// In this case, we don't show the menu.
				expanded.value = false;
				return;
			}

			expanded.value = menuItems.value.length > 0;
		}

		/** @type {(query: string) => void} */
		const debouncedPerformSearch = mw.util.debounce( performSearch, 250 );

		function clearSearchResults() {
			menuItems.value = [];
			expanded.value = false;
		}

		watch( selectedValue, ( newQuery ) => {
			if ( newQuery ) {
				debouncedPerformSearch( newQuery );
			} else {
				clearSearchResults();
			}
		} );

		/**
		 * Delegate most keydowns on the text input to the Menu component. This
		 * allows the Menu component to enable keyboard navigation of the menu.
		 *
		 * @param {KeyboardEvent} e The keyboard event
		 */
		function onKeydown( e ) {
			if ( e.key === ' ' ) {
				return;
			}

			if ( menu.value ) {
				menu.value.delegateKeyNavigation( e );
			}
		}

		function onClick() {
			// Do not show the menu if the only suggestion is an exact match.
			// This prevents the menu from reappearing if the user clicks in
			// the input after `performSearch` has already hidden it.
			if (
				menuItems.value.length === 1 &&
				menuItems.value[ 0 ].value === selectedValue.value
			) {
				return;
			}

			if ( menuItems.value.length > 0 ) {
				expanded.value = true;
			}
		}

		return {
			input,
			menu,
			selectedValue,
			expanded,
			activeDescendant,
			menuId,
			menuItems,
			onKeydown,
			onClick
		};
	}
} );
</script>

<style lang="less">
.ext-rules-typeahead-text-input {
	// The Menu component is absolutely positioned, so we need `position: relative` here to
	// position the menu relative to this div. This ensures the menu will align with the input.
	position: relative;
}
</style>
