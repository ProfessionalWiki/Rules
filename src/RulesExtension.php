<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules;

use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use ProfessionalWiki\Rules\Persistence\RulesJsonValidator;
use ProfessionalWiki\Rules\Persistence\RulesDeserializer;
use RuntimeException;

class RulesExtension {

	public const RULES_PAGE_TITLE = 'Rules';

	public function __construct(
		private readonly TitleFactory $titleFactory,
	) {
	}

	public function isRulesPage( Title $title ): bool {
		return $this->getRulesPageTitle()?->equals( $title ) ?? false;
	}

	public function getRulesPageTitle(): ?Title {
		return $this->titleFactory->newFromText( self::RULES_PAGE_TITLE, NS_MEDIAWIKI );
	}

	public function newRulesDeserializer(): RulesDeserializer {
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
