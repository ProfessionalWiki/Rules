<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Application;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\Rules\Application\Condition;
use ProfessionalWiki\Rules\Application\Rule;

/**
 * @covers \ProfessionalWiki\Rules\Application\Condition
 */
class ConditionTest extends TestCase {

	public function testIsFulfilledWhenNoRequiredCategories(): void {
		$condition = new Condition( [] );

		$this->assertTrue( $condition->isFulfilled( [ 'whatever' ] ) );
	}

	public function testNotIsFulfilledWhenNoneOfTheCategoriesArePresent(): void {
		$condition = new Condition( [ 'option1', 'option2', 'option3' ] );

		$this->assertFalse( $condition->isFulfilled( [ 'wrong', 'alsoWrong' ] ) );
	}

	public function testIsFulfilledAsSoonAsOneOfTheCategoriesIsPresent(): void {
		$condition = new Condition( [ 'option1', 'option2', 'option3' ] );

		$this->assertTrue( $condition->isFulfilled( [ 'wrong', 'option2', 'alsoWrong' ] ) );
	}

}
