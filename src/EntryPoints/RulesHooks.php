<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;
use ProfessionalWiki\Rules\RulesExtension;

class RulesHooks implements ContentHandlerDefaultModelForHook, LoadExtensionSchemaUpdatesHook {

	public function onContentHandlerDefaultModelFor( $title, &$model ): void {
		if ( RulesExtension::getInstance()->isRulesPage( $title ) ) {
			$model = RulesExtension::RULES_CONTENT_MODEL;
		}
	}

	public function onLoadExtensionSchemaUpdates( $updater ): void {
		$rulesExtension = RulesExtension::getInstance();
		$title = $rulesExtension->getRulesPageTitle();

		if ( $title === null || $title->exists() ) {
			return;
		}

		$pageUpdater = $rulesExtension->getRulesPageUpdater( $title );

		$pageUpdater->setContent( SlotRecord::MAIN, new RulesContent( RulesExtension::RULES_DEFAULT_CONFIG ) );
		$pageUpdater->saveRevision( CommentStoreComment::newUnsavedComment( $rulesExtension->getEditCommentForRulesPageInit() ) );
	}

}
