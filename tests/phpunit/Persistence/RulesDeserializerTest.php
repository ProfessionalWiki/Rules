<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Persistence;

use InvalidArgumentException;
use JsonException;
use PHPUnit\Framework\TestCase;
use ProfessionalWiki\Rules\Application\Condition;
use ProfessionalWiki\Rules\Application\Rule;
use ProfessionalWiki\Rules\Application\RuleList;
use ProfessionalWiki\Rules\Persistence\RulesDeserializer;
use ProfessionalWiki\Rules\RulesExtension;

/**
 * @covers \ProfessionalWiki\Rules\Persistence\RulesDeserializer
 */
class RulesDeserializerTest extends TestCase {

	public function testDeserializeEmptyList(): void {
		$deserializer = $this->newRulesDeserializer();
		$json = '{"rules": []}';

		$result = $deserializer->deserialize( $json );

		$this->assertEquals( new RuleList( [] ), $result );
	}

	private function newRulesDeserializer(): RulesDeserializer {
		return new RulesDeserializer(
			RulesExtension::getInstance()->newRulesJsonValidator()
		);
	}

	public function testDeserializeThrowsExceptionForInvalidJson(): void {
		$deserializer = $this->newRulesDeserializer();
		$invalidJson = '{"rules": [}';

		$this->expectException( InvalidArgumentException::class );
		$deserializer->deserialize( $invalidJson );
	}

	public function testDeserializeThrowsExceptionForMissingRulesArray(): void {
		$deserializer = $this->newRulesDeserializer();
		$json = '{"other": "data"}';

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid JSON: The required properties (rules) are missing' );
		$deserializer->deserialize( $json );
	}

	public function testDeserializeThrowsExceptionWhenRulesIsNotArray(): void {
		$deserializer = $this->newRulesDeserializer();
		$json = '{"rules": "not an array"}';

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid JSON: The data (string) must match the type: array' );
		$deserializer->deserialize( $json );
	}

	public function testDeserializeMultipleRules(): void {
		$deserializer = $this->newRulesDeserializer();
		$json = <<<'JSON'
{
	"rules": [
		{
			"name": "Shorthair cats",
			"conditions": [
				{
					"type": "inCategory",
					"categories": [ "Siamese", "British Shorthair", "Burmese" ]
				},
				{
					"type": "inCategory",
					"categories": [ "Cat" ]
				}
			],
			"actions": [
				{
					"type": "addCategory",
					"category": "Shorthair cats"
				}
			]
		},
		{
			"name": "Longhair cats",
			"conditions": [
				{
					"type": "inCategory",
					"categories": [ "Persian", "Maine Coon", "Norwegian Forest Cat" ]
				},
				{
					"type": "inCategory",
					"categories": [ "Cat" ]
				}
			],
			"actions": [
				{
					"type": "addCategory",
					"category": "Shorthair cats"
				}
			]
		}
	]
}
JSON;

		$expectedRuleList = new RuleList( [
			new Rule(
				conditions: [
					new Condition( [ 'Siamese', 'British Shorthair', 'Burmese' ] ),
					new Condition( [ 'Cat' ] )
				],
				categoriesToAdd: [ 'Shorthair cats' ]
			),
			new Rule(
				conditions: [
					new Condition( [ 'Persian', 'Maine Coon', 'Norwegian Forest Cat' ] ),
					new Condition( [ 'Cat' ] )
				],
				categoriesToAdd: [ 'Shorthair cats' ]
			)
		] );

		$this->assertEquals( $expectedRuleList, $deserializer->deserialize( $json ) );
	}

	public function testDeserializeEmptyRule(): void {
		$json = <<<'JSON'
{
	"rules": [
		{
			"name": "Shorthair cats"
		}
	]
}
JSON;

		$expectedRuleList = new RuleList( [
			new Rule(
				conditions: [],
				categoriesToAdd: []
			)
		] );

		$this->assertEquals(
			$expectedRuleList,
			( $this->newRulesDeserializer() )->deserialize( $json )
		);
	}

	public function testIgnoresActionsWithoutCategory(): void {
		$json = <<<'JSON'
{
	"rules": [
		{
			"name": "Shorthair cats",
			"conditions": [
				{
					"type": "inCategory",
					"categories": [ "Cat" ]
				}
			],
			"actions": [
				{
					"type": "addCategory",
					"category": "Shorthair cats"
				},
				{
					"type": "addCategory"
				},
				{
					"type": "addCategory",
					"category": "Longhair cats"
				}
			]
		}
	]
}
JSON;

		$expectedRuleList = new RuleList( [
			new Rule(
				conditions: [
					new Condition( [ 'Cat' ] )
				],
				categoriesToAdd: [ 'Shorthair cats', 'Longhair cats' ]
			)
		] );

		$this->assertEquals(
			$expectedRuleList,
			( $this->newRulesDeserializer() )->deserialize( $json )
		);
	}
}
