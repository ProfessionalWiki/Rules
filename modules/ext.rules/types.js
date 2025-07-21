/**
 * @module types
 * Shared JSDoc type definitions for the Rules extension.
 */

/**
 * Enum for rule action types.
 *
 * @enum {string}
 */
const RuleActionType = Object.freeze( {
	ADD_CATEGORY: 'addCategory'
} );

/**
 * Enum for rule condition types.
 *
 * @enum {string}
 */
const RuleConditionType = Object.freeze( {
	IN_CATEGORY: 'inCategory'
} );

/**
 * @typedef {Object} RuleAction
 * @property {RuleActionType} type The type of action.
 * @property {string} category The category to add.
 */

/**
 * @typedef {Object} RuleCondition
 * @property {RuleConditionType} type The type of condition.
 * @property {string[]} categories The categories to check for.
 */

/**
 * @typedef {Object} Rule
 * @property {string} name The name of the rule.
 * @property {RuleCondition[]} conditions The conditions for the rule.
 * @property {RuleAction[]} actions The actions to perform.
 */

/**
 * @typedef {object} FormCondition
 * @property {RuleConditionType} type
 * @property {string[]} categories
 * @property {import('vue').Component} inputComponent
 * @property {boolean} isFieldset
 */

/**
 * @typedef {object} FormAction
 * @property {RuleActionType} type
 * @property {string} category
 * @property {import('vue').Component} inputComponent
 */

/**
 * @typedef {object} FormState
 * @property {string} name
 * @property {FormCondition[]} conditions
 * @property {FormAction[]} actions
 */

module.exports = {
	RuleActionType,
	RuleConditionType
};
