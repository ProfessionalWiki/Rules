<template>
	<cdx-table
		v-model:sort="sort"
		:caption="$i18n( 'rules-table-caption' ).text()"
		:columns="tableData.length ? columns : []"
		:pending="saving"
		:data="tableData"
		:use-row-headers="true"
		@update:sort="onSort"
	>
		<template #header>
			<cdx-button @click="$emit( 'add-rule' )">
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
				@update:selected="onMenuSelect( $event, row )"
			>
				<cdx-icon :icon="cdxIconEllipsis"></cdx-icon>
			</cdx-menu-button>
		</template>
	</cdx-table>
</template>

<script>
const { defineComponent, ref, computed } = require( 'vue' );
const { CdxButton, CdxIcon, CdxMenuButton, CdxTable } = require( '../../codex.js' );
const { cdxIconAdd, cdxIconEdit, cdxIconEllipsis, cdxIconTrash } = require( '../icons.json' );
const { ruleToTableRow } = require( '../utils/ruleTransformers.js' );

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
	name: 'RulesTable',
	components: {
		CdxButton,
		CdxIcon,
		CdxMenuButton,
		CdxTable
	},
	props: {
		rules: {
			/** @type {import('vue').PropType<import('../types.js').Rule[]>} */
			type: Array,
			required: true
		}
	},
	emits: [ 'add-rule', 'edit-rule', 'delete-rule' ],
	setup( props, { emit } ) {
		const selection = ref( null );
		const sort = ref( { name: 'none' } );

		/** @type {import( '@wikimedia/codex' ).TableColumn[]} */
		const columns = [
			{ id: 'name', label: mw.msg( 'rules-table-column-name' ), allowSort: true },
			{ id: 'conditions', label: mw.msg( 'rules-table-column-conditions' ) },
			{ id: 'actions', label: mw.msg( 'rules-table-column-actions' ) },
			{ id: 'more' }
		];

		/** @type {import('vue').ComputedRef<import( '@wikimedia/codex' ).TableRow[]>} */
		const tableData = computed( () => {
			const data = props.rules.map( ruleToTableRow );
			const sortOrder = sort.value.name;

			if ( sortOrder === 'asc' || sortOrder === 'desc' ) {
				data.sort( ( a, b ) => {
					const multiplier = sortOrder === 'asc' ? 1 : -1;
					return multiplier * ( a.name.localeCompare( b.name ) );
				} );
			}

			return data;
		} );

		/**
		 * @param {string} newSelection
		 * @param {import( '@wikimedia/codex' ).TableRow} row
		 */
		function onMenuSelect( newSelection, row ) {
			const rule = props.rules.find( ( r ) => r.name === row.name );

			if ( !rule ) {
				return;
			}

			if ( newSelection === 'edit-rule' ) {
				emit( 'edit-rule', rule );
			} else if ( newSelection === 'delete-rule' ) {
				emit( 'delete-rule', rule );
			}

			selection.value = null;
		}

		/**
		 * @param {import( '@wikimedia/codex' ).TableSort} newSort
		 */
		function onSort( newSort ) {
			if ( newSort.name === 'none' ) {
				sort.value = { name: 'asc' };
				return;
			}

			sort.value = { name: newSort.name || 'asc' };
		}

		return {
			sort,
			selection,
			columns,
			tableData,
			menuItems,
			cdxIconAdd,
			cdxIconEllipsis,
			onMenuSelect,
			onSort
		};
	}
} );
</script>
