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
			"conditions": [
				{
					"type": "inCategory",
					"categories": [ "ConditionCategory" ]
				}
			],
			"actions": [
				{
					"category": "ActionCategory"
				}
			]
		}
	]
}
JSON;
	}

}
