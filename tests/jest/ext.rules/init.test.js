// We mock the vue module to add the createMwApp method.
jest.mock( 'vue', () => {
	const originalVue = jest.requireActual( 'vue' );
	return {
		...originalVue,
		createMwApp: jest.fn( () => ( {
			mount: jest.fn()
		} ) )
	};
} );

const Vue = require( 'vue' );
const init = require( '../../../modules/ext.rules/init.js' );

describe( 'ext.rules/init.js', () => {
	const mockMount = jest.fn();
	Vue.createMwApp.mockReturnValue( { mount: mockMount } );

	beforeEach( () => {
		jest.resetModules();
		Vue.createMwApp.mockClear();
		mockMount.mockClear();
		global.mw.log.error.mockClear();
	} );

	it( 'should mount the Vue app when the target element exists', () => {
		document.body.innerHTML = '<div id="ext-rules-app"></div>';

		init.initApp();

		expect( global.mw.log.error ).not.toHaveBeenCalled();
		expect( Vue.createMwApp ).toHaveBeenCalledTimes( 1 );
		expect( mockMount ).toHaveBeenCalledWith( document.getElementById( 'ext-rules-app' ) );
	} );

	it( 'should log an error when the target element does not exist', () => {
		document.body.innerHTML = '';

		init.initApp();

		expect( global.mw.log.error ).toHaveBeenCalledWith(
			'[ext.rules] Unable to mount Vue app: #ext-rules-app element not found'
		);
		expect( Vue.createMwApp ).not.toHaveBeenCalled();
	} );
} );
