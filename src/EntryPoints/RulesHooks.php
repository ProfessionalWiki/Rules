<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use ProfessionalWiki\Rules\RulesExtension;

class RulesHooks implements BeforePageDisplayHook {

	public function __construct(
		private TitleFactory $titleFactory
	) {
	}

	public function onBeforePageDisplay( $out, $skin ): void {
		$title = $out->getTitle();

		if (
			$title === null ||
			$out->getActionName() !== 'view' ||
			!$this->isRulesPage( $title )
		) {
			return;
		}

		// Add entry point for the Vue app
		$out->addHTML( '<div id="ext-rules-app"></div>' );

		$out->addModules( 'ext.rules' );
	}

	private function isRulesPage( Title $title ): bool {
		$rulesTitle = $this->titleFactory->newFromText( RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI );
		if ( $rulesTitle === null ) {
			return false;
		}
		return $rulesTitle->equals( $title );
	}

}
