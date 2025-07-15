const
	Vue = require( 'vue' ),
	App = require( './components/App.vue' );

/**
 * @return {void}
 */
function initApp() {
	const targetElement = document.getElementById( 'ext-rules-app' );
	if ( targetElement === null ) {
		mw.log.warn( '[ext.rules] Unable to mount Vue app: #ext-rules-app element not found' );
		return;
	}

	// @ts-ignore createMwApp is a MediaWiki-specific function
	const app = Vue.createMwApp( App, {} );
	app.mount( targetElement );
}

initApp();

module.exports = { initApp };
