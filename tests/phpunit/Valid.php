<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests;

class Valid {

	public static function configJsonForNoRules(): string {
		return '{ "rules": [] }';
	}

	public static function configJson(): string {
		return <<<'JSON'
{
	"rules": [
		{
			"name": "Valid Rule",
			"conditions": [
				{
					"type": "inCategory",
					"categories": [ "ConditionCategory" ]
				}
			],
			"actions": [
				{
					"type": "addCategory",
					"category": "ActionCategory"
				}
			]
		},
		{
			"name": "Valid Rule With Spaces",
			"conditions": [
				{
					"type": "inCategory",
					"categories": [ "Condition Category" ]
				}
			],
			"actions": [
				{
					"type": "addCategory",
					"category": "Action Category"
				}
			]
		}
	]
}
JSON;
	}

}
