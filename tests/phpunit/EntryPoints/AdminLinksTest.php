<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\EntryPoints;

use MediaWiki\Context\RequestContext;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * @covers \ProfessionalWiki\Rules\EntryPoints\RulesHooks
 * @group Database
 */
class AdminLinksTest extends \MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( !class_exists( \ALItem::class ) ) {
			$this->markTestSkipped( 'AdminLinks is not enabled – skipping.' );
		}
	}

	public function testRulesLinkAppearsOnAdminLinksPage(): void {
		$this->assertStringContainsString(
			'Special:Rules',
			$this->getAdminLinksPageHtml(),
			'AdminLinks page should contain a link to Special:Rules'
		);
	}

	private function getAdminLinksPageHtml(): string {
		$context = new RequestContext();
		$context->setTitle( SpecialPage::getTitleFor( 'AdminLinks' ) );

		$specialPage = $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'AdminLinks' );
		$specialPage->setContext( $context );
		$specialPage->execute( null );

		return $context->getOutput()->getHTML();
	}

}
