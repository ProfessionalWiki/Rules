/**
 * @typedef {import('@wikimedia/codex').MenuItemData} MenuItemData
 */

/**
 * Helper to get a nested property from an object.
 *
 * @param {Object} obj The object.
 * @param {string} path The path to the property (e.g. 'foo.bar').
 * @return {any} The property value or undefined.
 */
function getNestedProperty( obj, path ) {
	// @ts-expect-error
	return path.split( '.' ).reduce( ( o, k ) => o?.[ k ], obj );
}

/**
 * Fetches data from the MediaWiki API.
 *
 * @param {MwApi} api The MediaWiki API instance.
 * @param {Object} params API parameters.
 * @param {string} resultPath Path to the array of results in the API response.
 * @return {Promise<any[]>}
 */
async function fetchApiData( api, params, resultPath ) {
	const result = await api.get( params );
	return getNestedProperty( result, resultPath ) || [];
}

/**
 * Maps an array of items to menu item data.
 *
 * @param {any[]} items The array of items.
 * @param {(item: any) => MenuItemData} mapper Function to map an item to a MenuItemData object.
 * @return {MenuItemData[]}
 */
function mapDataToMenuItems( items, mapper ) {
	return items.map( mapper );
}

/**
 * Searches for categories and maps them to menu items.
 *
 * @param {MwApi} api The MediaWiki API instance.
 * @param {string} query
 * @return {Promise<MenuItemData[]>}
 */
async function searchCategories( api, query ) {
	const data = await fetchApiData(
		api,
		{
			action: 'query',
			list: 'allcategories',
			acprefix: query,
			aclimit: 50
		},
		'query.allcategories'
	);
	return mapDataToMenuItems( data, ( item ) => ( {
		label: item[ '*' ],
		value: item[ '*' ]
	} ) );
}

module.exports = {
	fetchApiData,
	mapDataToMenuItems,
	searchCategories
};
