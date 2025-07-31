<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Application;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\Rules\Application\Condition;
use ProfessionalWiki\Rules\Application\Rule;

/**
 * @covers \ProfessionalWiki\Rules\Application\Rule
 */
class RuleTest extends TestCase {

	public function testCategoriesNotReturnedWhenConditionFails(): void {
		$rule = new Rule( [ new Condition( [ 'required' ] ) ], [ 'categoryToAdd' ] );

		$this->assertSame( [], $rule->run( [ 'wrong' ] ) );
	}

	public function testCategoriesAreReturnedWhenConditionMatches(): void {
		$rule = new Rule( [ new Condition( [ 'required' ] ) ], [ 'categoryToAdd1', 'categoryToAdd2' ] );

		$this->assertSame( [ 'categoryToAdd1', 'categoryToAdd2' ], $rule->run( [ 'required' ] ) );
	}

	public function testCategoriesNotReturnedWhenOneConditionMismatches(): void {
		$rule = new Rule(
			[
				new Condition( [ 'required1' ] ),
				new Condition( [ 'required2' ] ),
				new Condition( [ 'required3' ] ),
			],
			[ 'categoryToAdd' ]
		);

		$this->assertSame( [], $rule->run( [ 'required1', 'AnotherValue', 'required3' ] ) );
	}

	public function testCategoriesAreReturnedWhenAllConditionsMatch(): void {
		$rule = new Rule(
			[
				new Condition( [ 'required1' ] ),
				new Condition( [ 'required2' ] ),
				new Condition( [ 'required3' ] ),
			],
			[ 'categoryToAdd' ]
		);

		$this->assertSame( [ 'categoryToAdd' ], $rule->run( [ 'required2', 'required1', 'required3' ] ) );
	}

	/**
	 * @dataProvider provideMatchingCategoriesWhenNormalized
	 */
	public function testConditionCategoriesAreNormalized( string $conditionCategory, string $presentCategory ): void {
		$rule = new Rule(
			[
				new Condition( [ $conditionCategory ] ),
			],
			[ 'categoryToAdd' ]
		);

		$this->assertSame( [ 'categoryToAdd' ], $rule->run( [ $presentCategory ] ) );
	}

	public function provideMatchingCategoriesWhenNormalized(): iterable {
		// $conditionCategory, $presentCategory
		yield [ 'Foo Bar', 'Foo Bar' ];
		yield [ 'Foo Bar', 'Foo_Bar' ];
		yield [ 'Foo_Bar', 'Foo Bar' ];
		yield [ 'Foo_Bar', 'Foo_Bar' ];
	}

}
