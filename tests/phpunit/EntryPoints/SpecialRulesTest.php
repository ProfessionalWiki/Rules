<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\EntryPoints;

use ProfessionalWiki\Rules\EntryPoints\SpecialRules;
use ProfessionalWiki\Rules\RulesExtension;
use SpecialPageTestBase;

/**
 * @covers \ProfessionalWiki\Rules\EntryPoints\SpecialRules
 */
class SpecialRulesTest extends SpecialPageTestBase {

	protected function newSpecialPage(): SpecialRules {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Rules' );
	}

	/**
	 * @covers \ProfessionalWiki\Rules\EntryPoints\SpecialRules::execute
	 */
	public function testRedirect(): void {
		$specialRules = $this->newSpecialPage();
		$specialRules->execute( null );
		$this->assertEquals( $this->getRedirectTargetUrl(), $specialRules->getOutput()->getRedirect() );
	}

	private function getRedirectTargetUrl(): string {
		return $this->getServiceContainer()->get( 'TitleFactory' )->newFromText(
			RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI
		)->getFullURL();
	}

}
