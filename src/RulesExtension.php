<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules;

use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use RuntimeException;

class RulesExtension {

	public const RULES_PAGE_TITLE = 'Rules';

	public const RULES_CONTENT_MODEL = 'rules';

	public const RULES_SYSTEM_USER = 'Rules Bot';

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

	public function getRulesPageUpdater( Title $title ): PageUpdater {
		$user = $this->getRulesSystemUser();

		if ( $user === null ) {
			throw new RuntimeException( 'Failed to create system user' );
		}

		return MediaWikiServices::getInstance()->getPageUpdaterFactory()
			->newPageUpdater( $title, $user );
	}

	public function getRulesSystemUser(): ?User {
		// TODO: Add i18n for the system user name?
		return User::newSystemUser( self::RULES_SYSTEM_USER, [ 'steal' => true ] );
	}

	public function getEditCommentForRulesPageInit(): Message {
		return new Message( 'rules-bot-comment-init' );
	}

}
