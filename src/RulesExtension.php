<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use ProfessionalWiki\Rules\Application\ApplyRulesUseCase;
use ProfessionalWiki\Rules\Application\RuleListLookup;
use ProfessionalWiki\Rules\Persistence\RulesJsonValidator;
use ProfessionalWiki\Rules\Persistence\PageRuleListLookup;
use ProfessionalWiki\Rules\Persistence\RulesDeserializer;
use RuntimeException;

class RulesExtension {

	public const RULES_PAGE_TITLE = 'Rules';

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

	public function newApplyRulesUseCase(): ApplyRulesUseCase {
		return new ApplyRulesUseCase(
			ruleListLookup: $this->newPageRuleListLookup()
		);
	}

	private function newPageRuleListLookup(): RuleListLookup {
		return new PageRuleListLookup(
			titleFactory: MediaWikiServices::getInstance()->getTitleFactory(),
			wikiPageFactory: MediaWikiServices::getInstance()->getWikiPageFactory(),
			deserializer: $this->newRulesDeserializer()
		);
	}

	private function newRulesDeserializer(): RulesDeserializer {
		return new RulesDeserializer(
			$this->newRulesJsonValidator()
		);
	}

	public function newRulesJsonValidator(): RulesJsonValidator {
		$json = file_get_contents( __DIR__ . '/rules-schema.json' );

		if ( !is_string( $json ) ) {
			throw new RuntimeException( 'Could not obtain JSON Schema' );
		}

		$schema = json_decode( $json );

		if ( !is_object( $schema ) ) {
			throw new RuntimeException( 'Failed to deserialize JSON Schema' );
		}

		return new RulesJsonValidator( $schema );
	}

}
