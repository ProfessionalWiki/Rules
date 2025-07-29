const { inject } = require( 'vue' );
const { searchCategories: searchCategoriesApi } = require( '../utils/api.js' );

/**
 * @typedef {import('@wikimedia/codex').MenuItemData} MenuItemData
 */

/**
 * Composable for category search functionality.
 *
 * @return {{searchCategories: (function(string): Promise<MenuItemData[]>)}}
 */
function useSearchCategories() {
	const api = inject( 'api' );

	/**
	 * @param {string} query
	 * @return {Promise<MenuItemData[]>}
	 */
	function searchCategories( query ) {
		return searchCategoriesApi( api, query );
	}

	return {
		searchCategories
	};
}

module.exports = useSearchCategories;
