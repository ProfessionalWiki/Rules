const { mount } = require( '@vue/test-utils' );
const RulesTable = require( '../../../../modules/ext.rules/components/RulesTable.vue' );
const { createRule } = require( '../helpers.js' );
const { CdxTable, CdxButton, CdxMenuButton } = require( '../../../../modules/codex.js' );

const mockMw = {
	msg: jest.fn( ( key ) => key ),
	util: {
		getUrl: jest.fn( ( title ) => `/wiki/${ title }` )
	}
};

global.mw = mockMw;

const mountRulesTable = ( props = { canEdit: false, rules: [] } ) => mount( RulesTable, {
	props
} );

describe( 'RulesTable.vue', () => {
	beforeEach( () => {
		mockMw.msg.mockClear();
	} );

	it( 'renders the table', () => {
		const wrapper = mountRulesTable();
		expect( wrapper.findComponent( CdxTable ).exists() ).toBe( true );
	} );

	it( 'shows empty state message when there are no rules', () => {
		const wrapper = mountRulesTable( { rules: [], canEdit: false } );
		expect( wrapper.text() ).toContain( 'rules-table-empty-state' );
	} );

	it( 'shows "add rule" button if canEdit is true', () => {
		const wrapper = mountRulesTable( { rules: [], canEdit: true } );
		expect( wrapper.findComponent( CdxButton ).exists() ).toBe( true );
	} );

	it( 'does not show "add rule" button if canEdit is false', () => {
		const wrapper = mountRulesTable( { rules: [], canEdit: false } );
		expect( wrapper.findComponent( CdxButton ).exists() ).toBe( false );
	} );

	it( 'emits "add-rule" when add button is clicked', async () => {
		const wrapper = mountRulesTable( { rules: [], canEdit: true } );
		await wrapper.findComponent( CdxButton ).trigger( 'click' );
		expect( wrapper.emitted( 'add-rule' ) ).toHaveLength( 1 );
	} );

	describe( 'with rules', () => {
		const rules = [
			createRule( { name: 'Rule B' } ),
			createRule( { name: 'Rule A' } )
		];

		it( 'renders a row for each rule', () => {
			const wrapper = mountRulesTable( { rules, canEdit: false } );
			expect( wrapper.findComponent( CdxTable ).props( 'data' ) ).toHaveLength( 2 );
		} );

		it( 'shows menu button if canEdit is true', () => {
			const wrapper = mountRulesTable( { rules, canEdit: true } );
			expect( wrapper.findComponent( CdxMenuButton ).exists() ).toBe( true );
		} );

		it( 'does not show menu button if canEdit is false', () => {
			const wrapper = mountRulesTable( { rules, canEdit: false } );
			expect( wrapper.findComponent( CdxMenuButton ).exists() ).toBe( false );
		} );

		it( 'emits "edit-rule" when edit is selected from menu', async () => {
			const wrapper = mountRulesTable( { rules, canEdit: true } );
			const rowData = wrapper.vm.tableData[ 0 ];

			wrapper.vm.onMenuSelect( 'edit-rule', rowData );
			await wrapper.vm.$nextTick();

			expect( wrapper.emitted( 'edit-rule' ) ).toHaveLength( 1 );
			expect( wrapper.emitted( 'edit-rule' )[ 0 ][ 0 ] ).toEqual( rules[ 0 ] );
		} );

		it( 'emits "delete-rule" when delete is selected from menu', async () => {
			const wrapper = mountRulesTable( { rules, canEdit: true } );
			const rowData = wrapper.vm.tableData[ 1 ];

			wrapper.vm.onMenuSelect( 'delete-rule', rowData );
			await wrapper.vm.$nextTick();

			expect( wrapper.emitted( 'delete-rule' ) ).toHaveLength( 1 );
			expect( wrapper.emitted( 'delete-rule' )[ 0 ][ 0 ] ).toEqual( rules[ 1 ] );
		} );

		it( 'does not emit if rule is not found on menu select', async () => {
			const wrapper = mountRulesTable( { rules, canEdit: true } );
			const fakeRowData = { name: 'Non-existent rule' };

			wrapper.vm.onMenuSelect( 'edit-rule', fakeRowData );
			await wrapper.vm.$nextTick();

			expect( wrapper.emitted( 'edit-rule' ) ).toBeUndefined();
		} );

		describe( 'sorting', () => {
			it( 'does not sort data by default', () => {
				const wrapper = mountRulesTable( { rules, canEdit: false } );
				const tableData = wrapper.findComponent( CdxTable ).props( 'data' );
				expect( tableData[ 0 ].name ).toBe( 'Rule B' );
				expect( tableData[ 1 ].name ).toBe( 'Rule A' );
			} );

			it( 'sorts data ascending when column is sorted', async () => {
				const wrapper = mountRulesTable( { rules, canEdit: false } );
				const cdxTable = wrapper.findComponent( CdxTable );

				await cdxTable.vm.$emit( 'update:sort', { name: 'asc' } );

				const tableData = cdxTable.props( 'data' );
				expect( tableData[ 0 ].name ).toBe( 'Rule A' );
				expect( tableData[ 1 ].name ).toBe( 'Rule B' );
			} );

			it( 'sorts data descending when column is sorted', async () => {
				const wrapper = mountRulesTable( { rules, canEdit: false } );
				const cdxTable = wrapper.findComponent( CdxTable );
				await cdxTable.vm.$emit( 'update:sort', { name: 'asc' } );
				await cdxTable.vm.$emit( 'update:sort', { name: 'desc' } );

				const tableData = cdxTable.props( 'data' );
				expect( tableData[ 0 ].name ).toBe( 'Rule B' );
				expect( tableData[ 1 ].name ).toBe( 'Rule A' );
			} );

			it( 'sorts data ascending when sort is set to "none"', async () => {
				const wrapper = mountRulesTable( { rules, canEdit: false } );
				const cdxTable = wrapper.findComponent( CdxTable );
				// Go from descending to 'none' to see a change
				await cdxTable.vm.$emit( 'update:sort', { name: 'desc' } );

				await cdxTable.vm.$emit( 'update:sort', { name: 'none' } );

				const tableData = cdxTable.props( 'data' );
				expect( tableData[ 0 ].name ).toBe( 'Rule A' );
				expect( tableData[ 1 ].name ).toBe( 'Rule B' );
			} );
		} );
	} );
} );
