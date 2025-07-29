<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Integration;

use MediaWiki\Title\Title;
use ProfessionalWiki\Rules\Tests\RulesIntegrationTest;
use ProfessionalWiki\Rules\Tests\Valid;

/**
 * @covers \ProfessionalWiki\Rules\Application\ApplyRulesUseCase
 * @covers \ProfessionalWiki\Rules\EntryPoints\RulesHooks::onContentAlterParserOutput
 * @group Database
 */
class ApplyRulesUseCaseIntegrationTest extends RulesIntegrationTest {

	protected function setUp(): void {
		parent::setUp();
		$this->editConfigPage( Valid::configJson() );
	}

	public function testCategoryGetsAddedIfRuleMatchesOnPageCreation(): void {
		$title = $this->newTestPage();

		$this->insertPage( $title, '[[Category:ConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'ConditionCategory', 'ActionCategory' ]
		);
	}

	private function newTestPage(): Title {
		return Title::newFromText( 'Test Page' );
	}

	public function testCategoryDoesNotGetAddedIfNoRuleMatchesOnPageCreation(): void {
		$title = $this->newTestPage();

		$this->insertPage( $title, '[[Category:NotConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'NotConditionCategory' ]
		);
	}

	public function testCategoryGetsAddedIfRuleMatchesOnPageEdit(): void {
		$title = $this->newTestPage();
		$this->insertPage( $title, '[[Category:NotConditionCategory]]' );

		$this->editPage( $title, '[[Category:NotConditionCategory]] [[Category:ConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'NotConditionCategory', 'ConditionCategory', 'ActionCategory' ]
		);
	}

	public function testCategoryDoesNotGetAddedIfNoRuleMatchesOnPageEdit(): void {
		$title = $this->newTestPage();
		$this->insertPage( $title, '[[Category:NotConditionCategory]]' );

		$this->editPage( $title, '[[Category:NotConditionCategory]] [[Category:StillNotConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'NotConditionCategory', 'StillNotConditionCategory' ]
		);
	}

	public function testPreviouslyAddedRuleCategoryIsKeptWhenNonMatchingCategoryIsAdded(): void {
		$title = $this->newTestPage();
		$this->insertPage( $title, '[[Category:ConditionCategory]]' );

		$this->editPage( $title, '[[Category:ConditionCategory]] [[Category:NotConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'ConditionCategory', 'NotConditionCategory', 'ActionCategory' ]
		);
	}

	public function testPreviouslyAddedRuleCategoryIsRemovedWhenPreviouslyMatchingCategoryIsRemoved(): void {
		$title = $this->newTestPage();
		$this->insertPage( $title, '[[Category:ConditionCategory]]' );

		$this->editPage( $title, '[[Category:NotConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'NotConditionCategory' ]
		);
	}

	public function testPreviouslyAddedRuleCategoryIsNotRemovedWhenPageIsNotEditedAfterConfigChange(): void {
		$title = $this->newTestPage();
		$this->insertPage( $title, '[[Category:ConditionCategory]]' );

		$this->editConfigPage( Valid::configJsonForNoRules() );

		$this->assertPageHasCategories(
			$title,
			[ 'ConditionCategory', 'ActionCategory' ]
		);
	}

	public function testPreviouslyAddedRuleCategoryIsRemovedWhenPageIsEditedAfterConfigChange(): void {
		$title = $this->newTestPage();
		$this->insertPage( $title, '[[Category:ConditionCategory]]' );

		$this->editConfigPage( Valid::configJsonForNoRules() );
		$this->editPage( $title, '[[Category:ConditionCategory]]' );

		$this->assertPageHasCategories(
			$title,
			[ 'ConditionCategory' ]
		);
	}

}
