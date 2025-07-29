<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Maintenance;

use MediaWiki\Maintenance\Maintenance;
use ProfessionalWiki\Rules\Maintenance\ApplyRules;
use ProfessionalWiki\Rules\Tests\RulesIntegrationTest;
use ProfessionalWiki\Rules\Tests\Valid;

/**
 * @covers \ProfessionalWiki\Rules\Maintenance\ApplyRules
 * @group Database
 */
class ApplyRulesTest extends RulesIntegrationTest {

	private Maintenance $maintenance;

	protected function setUp(): void {
		parent::setUp();

		$this->maintenance = new ApplyRules();
		$this->maintenance->checkRequiredExtensions();
	}

	protected function tearDown(): void {
		$this->maintenance->cleanupChanneled();
		parent::tearDown();
	}

	public function testAppliesNothingIfThereAreNoPages() {
		$this->maintenance->execute();

		$this->expectOutputRegex( '/Applied rules on 0 pages/' );
	}

	public function testAppliesRulesOnAllPagesInAllNamespaces() {
		$this->editConfigPage( Valid::configJson() );
		$this->insertPage( 'Page One', '[[Category:ConditionCategory]]' );
		$this->insertPage( 'Category One', '', NS_CATEGORY );
		$this->insertPage( 'Page Two', '' );
		$this->insertPage( 'SomeConfig', '', NS_MEDIAWIKI );
		$this->insertPage( 'Page Three', '[[Category:ConditionCategory]]' );

		$this->maintenance->execute();

		$this->expectOutputRegex( '/Applied rules on 6 pages/' );
	}

	public function testNewCategoryIsAdded() {
		$this->editConfigPage( Valid::configJsonForNoRules() );
		$pageOne = $this->createPageWithText();
		$pageTwo = $this->createPageWithText( '[[Category:ConditionCategory]]' );
		$pageThree = $this->createPageWithText();
		$this->editConfigPage( Valid::configJson() );

		$this->maintenance->execute();

		$this->assertPageHasCategories( $pageOne->getTitle(), [] );
		$this->assertPageHasCategories( $pageTwo->getTitle(), [ 'ConditionCategory', 'ActionCategory' ] );
		$this->assertPageHasCategories( $pageThree->getTitle(), [] );
	}

	public function testOldCategoryIsRemoved() {
		$this->editConfigPage( Valid::configJson() );
		$pageOne = $this->createPageWithText();
		$pageTwo = $this->createPageWithText( '[[Category:ConditionCategory]]' );
		$pageThree = $this->createPageWithText();
		$this->editConfigPage( Valid::configJsonForNoRules() );

		$this->maintenance->execute();

		$this->assertPageHasCategories( $pageOne->getTitle(), [] );
		$this->assertPageHasCategories( $pageTwo->getTitle(), [ 'ConditionCategory' ] );
		$this->assertPageHasCategories( $pageThree->getTitle(), [] );
	}

}
