'use strict';

const { config } = require( '@vue/test-utils' );
const mockMediaWiki = require( '@wikimedia/mw-node-qunit/src/mockMediaWiki.js' );

// Mock global mw object
global.mw = mockMediaWiki();

// Mock Vue plugins
config.global.mocks = {
	$i18n: ( ...messageKeyAndParams ) => ( {
		text: () => '(' + messageKeyAndParams.join( ', ' ) + ')',
		parse: () => '(' + messageKeyAndParams.join( ', ' ) + ')'
	} )
};
config.global.directives = {
	'i18n-html': ( el, binding ) => {
		el.innerHTML = `${ binding.arg } (${ binding.value })`;
	}
};
