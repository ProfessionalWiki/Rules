<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Content\JsonContent;
use MediaWiki\Content\Hook\ContentAlterParserOutputHook;
use MediaWiki\Html\Html;
use MediaWiki\Page\Hook\ShowMissingArticleHook;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use ProfessionalWiki\Rules\RulesExtension;

class RulesHooks implements ContentAlterParserOutputHook, ContentHandlerDefaultModelForHook, ShowMissingArticleHook {

	public function onContentAlterParserOutput( $content, $title, $parserOutput ) {
		if ( !RulesExtension::getInstance()->isRulesPage( $title ) ) {
			RulesExtension::getInstance()->newApplyRulesUseCase()->applyToPage( $parserOutput );
		} else {
			/** @var JsonContent $content */
			$parserOutput->setJsConfigVar( 'rules', $content->getData() );
			$parserOutput->setRawText( $this->getRulesPageHtml() );
			$parserOutput->addModuleStyles( [ 'ext.rules.styles' ] );
			$parserOutput->addModules( [ 'ext.rules' ] );
		}
	}

	public function onContentHandlerDefaultModelFor( $title, &$model ): void {
		if ( RulesExtension::getInstance()->isRulesPage( $title ) ) {
			$model = CONTENT_MODEL_JSON;
		}
	}

	public function onShowMissingArticle( $article ): void {
		if ( RulesExtension::getInstance()->isRulesPage( $article->getTitle() ) ) {
			$output = $article->getContext()->getOutput();
			$output->addHtml( $this->getRulesPageHtml() );
			$output->addModuleStyles( [ 'ext.rules.styles' ] );
			$output->addModules( [ 'ext.rules' ] );
		}
	}

	private function getRulesPageHtml(): string {
		return Html::element( 'div', [ 'id' => 'ext-rules-app' ] ) .
			Html::rawElement( 'noscript', [], Html::noticeBox( wfMessage( 'rules-noscript-message' )->text(), [] ) );
	}

}
