'use strict';

global.mw = require( '@wikimedia/mw-node-qunit/src/mockMediaWiki.js' )();

// Missing from mockMediaWiki.js
global.mw.log.error = jest.fn();
