<template>
	<cdx-table
		v-model:sort="sort"
		:caption="$i18n( 'rules-table-caption' ).text()"
		:columns="columns"
		:data="data"
		:use-row-headers="true"
		@update:sort="onSort"
	>
		<template #header>
			<cdx-button @click="onAddRuleClick">
				<cdx-icon :icon="cdxIconAdd"></cdx-icon>
				{{ $i18n( 'rules-table-add-rule' ).text() }}
			</cdx-button>
		</template>

		<template #empty-state>
			{{ $i18n( 'rules-table-empty-state' ).text() }}
		</template>

		<template #item-more="{ row }">
			<cdx-menu-button
				v-model:selected="selection"
				:menu-items="menuItems"
				:aria-label="$i18n( 'rules-table-more-actions' ).text()"
				@update:selected="onMenuSelect( $event, row.id )"
			>
				<cdx-icon :icon="cdxIconEllipsis"></cdx-icon>
			</cdx-menu-button>
		</template>
	</cdx-table>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxButton, CdxIcon, CdxMenuButton, CdxTable } = require( '../../codex.js' );
const { cdxIconAdd, cdxIconEdit, cdxIconEllipsis, cdxIconTrash } = require( '../icons.json' );

/** @type {import( '@wikimedia/codex' ).MenuItemData[]} */
const menuItems = [
	{
		label: mw.msg( 'rules-table-edit-rule' ),
		value: 'edit-rule',
		icon: cdxIconEdit
	},
	{
		label: mw.msg( 'rules-table-delete-rule' ),
		value: 'delete-rule',
		icon: cdxIconTrash,
		// @ts-expect-error action is a valid property for a menu item
		action: 'destructive'
	}
];

module.exports = defineComponent( {
	name: 'App',
	components: {
		CdxButton,
		CdxIcon,
		CdxMenuButton,
		CdxTable
	},
	setup() {
		const selection = ref( null );
		const sort = ref( { name: 'none' } );

		/** @type {import( '@wikimedia/codex' ).TableColumn[]} */
		const columns = [
			{ id: 'name', label: mw.msg( 'rules-table-column-name' ), allowSort: true },
			{ id: 'conditions', label: mw.msg( 'rules-table-column-conditions' ) },
			{ id: 'actions', label: mw.msg( 'rules-table-column-actions' ) },
			{ id: 'more' }
		];

		/** @type {import('vue').Ref<import( '@wikimedia/codex' ).TableRow[]>} */
		const data = ref( [
			{
				id: 'shorthair-cats',
				name: 'Shorthair cats',
				conditions: 'In category: Siamese, British Shorthair, Abyssinian, Burmese',
				actions: 'Add to category: Shorthair cats'
			},
			{
				id: 'mediumhair-cats',
				name: 'Mediumhair cats',
				conditions: 'In category: American Bobtail, Birman, Ragdoll, Siberian',
				actions: 'Add to category: Mediumhair cats'
			},
			{
				id: 'longhair-cats',
				name: 'Longhair cats',
				conditions: 'In category: Persian, Maine Coon, Norwegian Forest Cat, Himalayan',
				actions: 'Add to category: Longhair cats'
			}
		] );

		/**
		 * @param {string} newSelection
		 * @param {string} rowId
		 */
		function onMenuSelect( newSelection, rowId ) {
			if ( newSelection === 'edit-rule' ) {
				mw.notify( `Edit rule: ${ rowId }`, { type: 'success' } );
			} else if ( newSelection === 'delete-rule' ) {
				mw.notify( `Delete rule: ${ rowId }`, { type: 'error' } );
			}

			selection.value = null;
		}

		function onAddRuleClick() {
			mw.notify( 'Add rule', { type: 'success' } );
		}

		/**
		 * @param {import( '@wikimedia/codex' ).TableSort} newSort
		 */
		function onSort( newSort ) {
			const sortOrder = newSort.name;

			/**
			 * @param {import( '@wikimedia/codex' ).TableSortOption} sortDir
			 */
			function sortAlphabetically( sortDir ) {
				data.value.sort( ( a, b ) => {
					const multiplier = sortDir === 'asc' ? 1 : -1;
					return multiplier * ( a.name.localeCompare( b.name ) );
				} );
			}

			if ( sortOrder === 'none' ) {
				sortAlphabetically( 'asc' );
				sort.value = { name: 'asc' };
				return;
			}

			sortAlphabetically( sortOrder || 'asc' );
		}

		return {
			sort,
			selection,
			columns,
			data,
			menuItems,
			cdxIconAdd,
			cdxIconEllipsis,
			onMenuSelect,
			onAddRuleClick,
			onSort
		};
	}
} );
</script>
