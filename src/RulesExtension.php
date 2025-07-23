<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

class RulesExtension {

	public const RULES_PAGE_TITLE = 'Rules';
	public const RULES_CONTENT_MODEL = 'rules';

	public const RULES_DEFAULT_CONFIG = '{
	"rules": []
}';

	public static function getInstance(): self {
		/** @var ?RulesExtension $instance */
		static $instance = null;
		$instance ??= new self();
		return $instance;
	}

	public function isRulesPage( Title $title ): bool {
		return $this->getRulesPageTitle()?->equals( $title ) ?? false;
	}

	public function getRulesPageTitle(): ?Title {
		return MediaWikiServices::getInstance()->getTitleFactory()
			->newFromText( self::RULES_PAGE_TITLE, NS_MEDIAWIKI );
	}

}
