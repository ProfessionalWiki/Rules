<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\TitleFactory;
use ProfessionalWiki\Rules\RulesExtension;

class SpecialRules extends SpecialPage {

	public function __construct(
		private TitleFactory $titleFactory
	) {
		parent::__construct( 'Rules' );
	}

	public function execute( $subPage ): void {
		parent::execute( $subPage );

		$title = $this->titleFactory->newFromText( RulesExtension::RULES_PAGE_TITLE, NS_MEDIAWIKI );

		if ( $title !== null ) {
			$this->getOutput()->redirect( $title->getFullURL() );
		}
	}

	public function getGroupName(): string {
		return 'wiki';
	}

	public function getDescription(): Message {
		return $this->msg( 'rules-message' );
	}

}
