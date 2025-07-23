<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use ProfessionalWiki\Rules\RulesExtension;

class SpecialRules extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Rules' );
	}

	public function execute( $subPage ): void {
		parent::execute( $subPage );

		$title = RulesExtension::getInstance()->getRulesPageTitle();

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
