<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use ProfessionalWiki\Rules\RulesExtension;

class RulesHooks implements ContentHandlerDefaultModelForHook {

	public function onContentHandlerDefaultModelFor( $title, &$model ): void {
		if ( RulesExtension::getInstance()->isRulesPage( $title ) ) {
			$model = RulesExtension::RULES_CONTENT_MODEL;
		}
	}

}
