<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use ProfessionalWiki\Rules\RulesExtension;

class RulesHooks implements BeforePageDisplayHook, ContentHandlerDefaultModelForHook {

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

	public function onContentHandlerDefaultModelFor( $title, &$model ): void {
		if ( $this->isRulesPage( $title ) ) {
			$model = CONTENT_MODEL_JSON;
		}
	}

	private function isRulesPage( Title $title ): bool {
		return $this->titleFactory->newFromText( RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI )
			?->equals( $title ) ?? false;
	}

}
