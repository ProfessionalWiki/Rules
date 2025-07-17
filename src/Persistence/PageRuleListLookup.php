<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Persistence;

use InvalidArgumentException;
use JsonContent;
use JsonException;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Title\TitleFactory;
use ProfessionalWiki\Rules\Application\RuleList;
use ProfessionalWiki\Rules\Application\RuleListLookup;
use ProfessionalWiki\Rules\RulesExtension;

class PageRuleListLookup implements RuleListLookup {

	public function __construct(
		private readonly TitleFactory $titleFactory,
		private readonly WikiPageFactory $wikiPageFactory,
		private readonly RulesDeserializer $deserializer
	) {
	}

	public function getAllRules(): RuleList { // TODO: test (currently not even manually tested)
		try {
			return $this->deserializer->deserialize( $this->getPageContent() );
		}
		catch ( InvalidArgumentException | JsonException ) {
			return new RuleList( [] );
		}
	}

	private function getPageContent(): string { // TODO: test (currently not even manually tested)
		$title = $this->titleFactory->newFromText( RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI );

		if ( $title === null ) {
			return '';
		}

		$content = $this->wikiPageFactory->newFromTitle( $title )->getContent();

		if ( $content instanceof JsonContent ) { // TODO/note: might need to do https://github.com/ProfessionalWiki/Rules/issues/9 before can test
			return $content->getText();
		}

		return '';
	}

}
