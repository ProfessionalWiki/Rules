const { useRules } = require( '../../../../modules/ext.rules/composables/useRules.js' );
const { createRule, createRules } = require( '../helpers.js' );

describe( 'useRules', () => {
	it( 'adds a rule', () => {
		const initialRules = createRules();
		const { rules, addRule } = useRules( initialRules );
		const newRule = createRule( { name: 'New rule' } );

		addRule( newRule );

		expect( rules.value ).toEqual( [ ...initialRules, newRule ] );
	} );

	it( 'updates a rule', () => {
		const initialRules = createRules();
		const { rules, updateRule } = useRules( initialRules );
		const ruleToUpdate = initialRules[ 1 ];
		const updatedRule = {
			...ruleToUpdate,
			name: 'Updated rule'
		};

		updateRule( ruleToUpdate, updatedRule );

		expect( rules.value ).toEqual( [
			initialRules[ 0 ],
			updatedRule,
			initialRules[ 2 ]
		] );
	} );

	it( 'deletes a rule', () => {
		const initialRules = createRules();
		const { rules, deleteRule } = useRules( initialRules );
		const ruleToDelete = initialRules[ 1 ];

		deleteRule( ruleToDelete );

		expect( rules.value ).toEqual( [
			initialRules[ 0 ],
			initialRules[ 2 ]
		] );
	} );

	describe( 'saveRules', () => {
		it( 'saves rules successfully and resets saving state', async () => {
			const initialRules = createRules();
			const { rules, saveRules, saving } = useRules( initialRules );
			const title = 'My Test Rules';
			const mockApi = {
				postWithToken: jest.fn().mockResolvedValue( { edit: { result: 'Success' } } )
			};

			expect( saving.value ).toBe( false );

			const promise = saveRules( mockApi, title );

			expect( saving.value ).toBe( true );

			const result = await promise;

			expect( result ).toEqual( { edit: { result: 'Success' } } );
			expect( mockApi.postWithToken ).toHaveBeenCalledWith(
				'csrf',
				{
					action: 'edit',
					title,
					text: JSON.stringify( { rules: rules.value } )
				}
			);
			expect( saving.value ).toBe( false );
		} );

		it( 'handles API errors and resets saving state', async () => {
			const initialRules = createRules();
			const { saveRules, saving } = useRules( initialRules );
			const title = 'My Test Rules';
			const error = new Error( 'API Failure' );
			const mockApi = {
				postWithToken: jest.fn().mockRejectedValue( error )
			};

			expect( saving.value ).toBe( false );

			const promise = saveRules( mockApi, title );

			expect( saving.value ).toBe( true );

			await expect( promise ).rejects.toThrow( 'API Failure' );

			expect( saving.value ).toBe( false );
		} );
	} );
} );
