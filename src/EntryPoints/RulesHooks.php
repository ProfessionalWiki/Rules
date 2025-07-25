<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Html\Html;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Hook\ShowMissingArticleHook;
use MediaWiki\Content\Hook\ContentAlterParserOutputHook;
use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use ProfessionalWiki\Rules\RulesExtension;


class RulesHooks implements BeforePageDisplayHook, ContentAlterParserOutputHook, ContentHandlerDefaultModelForHook, ShowMissingArticleHook {

	public function onBeforePageDisplay( $out, $skin ): void {
		$title = $out->getTitle();

		if (
			$title === null ||
			$out->getActionName() !== 'view' ||
			!RulesExtension::getInstance()->isRulesPage( $title )
		) {
			return;
		}

		$out->addHTML( '<div id="ext-rules-app"></div>' );
		$out->addModules( 'ext.rules' );
	}

	public function onContentHandlerDefaultModelFor( $title, &$model ): void {
		if ( RulesExtension::getInstance()->isRulesPage( $title ) ) {
			$model = RulesExtension::RULES_CONTENT_MODEL;
		}
	}

	public function onShowMissingArticle( $article ): void {
		$rulesExtension = RulesExtension::getInstance();
		$title = $article->getTitle();

		if ( !$rulesExtension->isRulesPage( $title ) ) {
			return;
		}

		// Fill the rules page with the default content
		$this->createRulesPage( $rulesExtension, $title );
		// Render a message to prompt the user to refresh the page
		// Because the fillParserOutput method is not called when the page is first created
		$this->renderRefreshMessage( $article->getContext()->getOutput() );
	}

	private function createRulesPage( RulesExtension $rulesExtension, Title $title ): void {
		$pageUpdater = $rulesExtension->getRulesPageUpdater( $title );
		$pageUpdater->setContent( SlotRecord::MAIN, new RulesContent() );
		$pageUpdater->saveRevision( CommentStoreComment::newUnsavedComment( $rulesExtension->getEditCommentForRulesPageInit() ) );
	}

	private function renderRefreshMessage( OutputPage $output ): void {
		$output->addHTML( Html::noticeBox( $output->msg( 'rules-refresh-message' )->text(), [] ) );
	}

	public function onContentAlterParserOutput( $content, $title, $parserOutput ) {
		RulesExtension::getInstance()->newApplyRulesUseCase()->applyToPage( $parserOutput );
	}

}
