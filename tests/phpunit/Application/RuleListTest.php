<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Application;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\Rules\Application\Condition;
use ProfessionalWiki\Rules\Application\Rule;
use ProfessionalWiki\Rules\Application\RuleList;

/**
 * @covers \ProfessionalWiki\Rules\Application\RuleList
 */
class RuleListTest extends TestCase {

	public function testEmptyListResultsInNoCategories(): void {
		$rules = new RuleList( [] );
		$this->assertSame( [], $rules->run( [ 'required' ] ) );
	}

	public function testReturnsOnlyCategoriesOfMatchingRules(): void {
		$rules = new RuleList( [
			new Rule( [ new Condition( [ 'required' ] ) ], [ 'categoryToAdd1', 'categoryToAdd2' ] ),
			new Rule( [ new Condition( [ 'alsoRequired' ] ) ], [ 'categoryToAdd3', 'categoryToAdd4' ] ),
			new Rule( [ new Condition( [ 'thirdRequired' ] ) ], [ 'categoryToAdd5', 'categoryToAdd6' ] ),
		] );

		$this->assertSame(
			[ 'categoryToAdd1', 'categoryToAdd2', 'categoryToAdd5', 'categoryToAdd6' ],
			$rules->run( [ 'wrong', 'thirdRequired', 'required', 'alsoWrong' ] )
		);
	}

	public function testDeduplicatesCategories(): void {
		$rules = new RuleList( [
			new Rule( [ new Condition( [ 'required' ] ) ], [ 'categoryToAdd1', 'categoryToAdd2' ] ),
			new Rule( [ new Condition( [ 'required' ] ) ], [ 'categoryToAdd1', 'categoryToAdd3' ] ),
		] );

		$this->assertSame(
			[ 'categoryToAdd1', 'categoryToAdd2', 'categoryToAdd3' ],
			array_values( $rules->run( [ 'required' ] ) )
		);
	}

}
