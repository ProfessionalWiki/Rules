<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests;

use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use ProfessionalWiki\Rules\RulesExtension;

class RulesIntegrationTest extends MediaWikiIntegrationTestCase {

	protected function editConfigPage( string $config ): void {
		$this->editPage(
			Title::newFromText( RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI ),
			$config
		);
	}

}
