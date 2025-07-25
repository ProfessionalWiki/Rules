/**
 * @return {import('../types.js').Rule[] | undefined}
 */
function getInitialRules() {
	// eslint-disable-next-line es-x/no-optional-chaining
	return mw.config.get( 'rules' )?.value?.rules;
}

/**
 * @return {boolean}
 */
function isPageEditable() {
	return mw.config.get( 'wgIsProbablyEditable' ) || false;
}

module.exports = {
	getInitialRules,
	isPageEditable
};
