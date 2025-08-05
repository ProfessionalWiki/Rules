<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Content\JsonContent;
use MediaWiki\Content\Hook\ContentAlterParserOutputHook;
use MediaWiki\Hook\EditFilterHook;
use MediaWiki\Html\Html;
use MediaWiki\Page\Hook\ShowMissingArticleHook;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use ProfessionalWiki\Rules\Presentation\RulesJsonErrorFormatter;
use ProfessionalWiki\Rules\RulesExtension;

class RulesHooks implements ContentAlterParserOutputHook, ContentHandlerDefaultModelForHook, ShowMissingArticleHook, EditFilterHook {

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
		return
			Html::rawElement( 'p',
				[ 'id' => 'ext-rules-intro' ],
				wfMessage( 'ext-rules-intro' )->exists() ? wfMessage( 'ext-rules-intro' )->parse() : ''
			) .
			Html::element( 'div', [ 'id' => 'ext-rules-app' ] ) .
			Html::rawElement( 'noscript', [], Html::noticeBox( wfMessage( 'rules-noscript-message' )->text(), '' ) );
	}

	public function onEditFilter( $editor, $text, $section, &$error, $summary ) {
		if ( !RulesExtension::getInstance()->isRulesPage( $editor->getTitle() ) ) {
			return;
		}

		$validator = RulesExtension::getInstance()->newRulesJsonValidator();

		if ( !$validator->validate( $text ) ) {
			$errors = $validator->getErrors();
			$error = Html::errorBox(
				wfMessage( 'rules-config-invalid', count( $errors ) )->escaped() .
				RulesJsonErrorFormatter::format( $errors )
			);
		}
	}

}
