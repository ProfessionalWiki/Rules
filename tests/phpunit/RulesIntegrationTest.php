<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests;

use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;
use ProfessionalWiki\Rules\RulesExtension;
use WikiPage;

class RulesIntegrationTest extends MediaWikiIntegrationTestCase {

	protected function editConfigPage( string $config ): void {
		$this->editPage(
			Title::newFromText( RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI ),
			$config
		);
	}

	protected function createPageWithText( string $text = 'Whatever wikitext' ): WikiPage {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $this->createUniqueTitle() );

		$this->editPage( $page, $text );

		return $page;
	}

	private function createUniqueTitle(): Title {
		static $pageCounter = 0;
		return Title::newFromText( 'RulesTestPage' . ++$pageCounter );
	}

	/**
	 * @param string[] $categories
	 */
	public function assertPageHasCategories( Title $title, array $categories ): void {
		$parserOutput = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )->getParserOutput();

		// Normalize to "text form" to make assertions consistent.
		$normalizedCategories = array_map(
			fn( string $category ) => TitleValue::tryNew( NS_CATEGORY, $category )?->getText() ?? '',
			$parserOutput === false ? [] : $parserOutput->getCategoryNames()
		);

		$this->assertSame(
			$normalizedCategories,
			$categories
		);
	}

}
