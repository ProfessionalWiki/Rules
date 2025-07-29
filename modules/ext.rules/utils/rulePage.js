/**
 * @return {import('../types.js').Rule[] | undefined}
 */
function getInitialRules() {
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
