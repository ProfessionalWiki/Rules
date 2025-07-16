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

/**
 * @covers \ProfessionalWiki\Rules\Persistence\RulesDeserializer
 */
class RulesDeserializerTest extends TestCase {

	public function testDeserializeEmptyList(): void {
		$deserializer = new RulesDeserializer();
		$json = '{"rules": []}';

		$result = $deserializer->deserialize( $json );

		$this->assertEquals( new RuleList( [] ), $result );
	}

	public function testDeserializeThrowsExceptionForInvalidJson(): void {
		$deserializer = new RulesDeserializer();
		$invalidJson = '{"rules": [}';

		$this->expectException( JsonException::class );
		$deserializer->deserialize( $invalidJson );
	}

	public function testDeserializeThrowsExceptionForMissingRulesArray(): void {
		$deserializer = new RulesDeserializer();
		$json = '{"other": "data"}';

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid JSON structure: missing rules array' );
		$deserializer->deserialize( $json );
	}

	public function testDeserializeThrowsExceptionWhenRulesIsNotArray(): void {
		$deserializer = new RulesDeserializer();
		$json = '{"rules": "not an array"}';

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid JSON structure: missing rules array' );
		$deserializer->deserialize( $json );
	}

	public function testDeserializeMultipleRules(): void {
		$deserializer = new RulesDeserializer();
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
			( new RulesDeserializer() )->deserialize( $json )
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
			( new RulesDeserializer() )->deserialize( $json )
		);
	}
}
