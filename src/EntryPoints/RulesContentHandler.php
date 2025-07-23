<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Content\Content;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\JsonContentHandler;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;
use ProfessionalWiki\Rules\RulesExtension;

class RulesContentHandler extends JsonContentHandler {

	public function __construct( $modelId = RulesExtension::RULES_CONTENT_MODEL ) {
		parent::__construct( $modelId );
	}

	protected function getContentClass(): string {
		return RulesContent::class;
	}

	public function makeEmptyContent() {
		return new RulesContent( RulesExtension::RULES_DEFAULT_CONFIG );
	}

	public function canBeUsedOn( Title $title ) {
		return RulesExtension::getInstance()->isRulesPage( $title );
	}

	public function supportsDirectEditing(): bool {
		// TODO: Need to decide if we want to allow direct editing
		return true;
	}

	// So that it can be edited by the Vue app
	public function supportsDirectApiEditing(): bool {
		return true;
	}

	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	): void {
		/** @var RulesContent $content */
		$output->setJsConfigVar( 'rules', $content->getData() );
		$output->setRawText( '<div id="ext-rules-app"></div>' );
		$output->addModules( [ 'ext.rules' ] );
	}
}
