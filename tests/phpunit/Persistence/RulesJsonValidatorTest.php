<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Persistence;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\Rules\Persistence\RulesJsonValidator;
use ProfessionalWiki\Rules\Tests\Valid;
use ProfessionalWiki\Rules\RulesExtension;

/**
 * This test covers the combination of RulesJsonValidator and rules-schema.json.
 *
 * @covers \ProfessionalWiki\Rules\Persistence\RulesJsonValidator
 * @covers \ProfessionalWiki\Rules\RulesExtension
 */
class RulesJsonValidatorTest extends TestCase {

	private function newValidator(): RulesJsonValidator {
		return RulesExtension::getInstance()->newRulesJsonValidator();
	}

	public function testEmptyJsonFailsValidation(): void {
		$this->assertFalse(
			$this->newValidator()->validate( '{}' )
		);
	}

	public function testValidJsonPassesValidation(): void {
		$validator = $this->newValidator();
		$success = $validator->validate( Valid::configJson() );

		$this->assertSame( [], $validator->getErrors() );
		$this->assertTrue( $success );
	}

	public function testStructurallyInvalidJsonFailsValidation(): void {
		$this->assertFalse(
			$this->newValidator()->validate( '}{' )
		);
	}

	public function testMissingRuleNameFails(): void {
		$validator = $this->newValidator();

		$validator->validate( '
	{
		"rules": [
			{
				"notName": "Foo",
				"conditions": [],
				"actions": []
			}
		]
	}
		' );

		$this->assertSame(
			[ '/rules/0' => 'The required properties (name) are missing' ],
			$validator->getErrors()
		);
	}

	public function testMissingConditionTypeFails(): void {
		$validator = $this->newValidator();

		$validator->validate( '
	{
		"rules": [
			{
				"name": "Foo",
				"conditions": [
					{
						"notType": "inCategory",
						"categories": [ "Foo" ]
					}
				],
				"actions": [
					{
						"type": "addCategory",
						"category": "Bar"
					}
				]
			}
		]
	}
		' );

		$this->assertSame(
			[ '/rules/0/conditions/0' => 'The required properties (type) are missing' ],
			$validator->getErrors()
		);
	}

	public function testMissingConditionCategoriesFails(): void {
		$validator = $this->newValidator();

		$validator->validate( '
	{
		"rules": [
			{
				"name": "Foo",
				"conditions": [
					{
						"type": "inCategory",
						"notCategories": [ "Foo" ]
					}
				],
				"actions": [
					{
						"type": "addCategory",
						"category": "Bar"
					}
				]
			}
		]
	}
		' );

		$this->assertSame(
			[ '/rules/0/conditions/0' => 'The required properties (categories) are missing' ],
			$validator->getErrors()
		);
	}

	public function testEmptyConditionCategoriesFails(): void {
		$validator = $this->newValidator();

		$validator->validate( '
	{
		"rules": [
			{
				"name": "Foo",
				"conditions": [
					{
						"type": "inCategory",
						"categories": []
					}
				],
				"actions": [
					{
						"type": "addCategory",
						"category": "Bar"
					}
				]
			}
		]
	}
		' );

		$this->assertSame(
			[ '/rules/0/conditions/0/categories' => 'Array should have at least 1 items, 0 found' ],
			$validator->getErrors()
		);
	}

	public function testMissingActionTypeFails(): void {
		$validator = $this->newValidator();

		$validator->validate( '
    {
        "rules": [
            {
                "name": "Foo",
                "conditions": [
                    {
                        "type": "inCategory",
                        "categories": [ "Foo" ]
                    }
                ],
                "actions": [
                    {
                        "notType": "addCategory",
                        "category": "Bar"
                    }
                ]
            }
        ]
    }
        ' );

		$this->assertSame(
			[ '/rules/0/actions/0' => 'The required properties (type) are missing' ],
			$validator->getErrors()
		);
	}

}
