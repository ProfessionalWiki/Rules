<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Maintenance;

use Maintenance;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageRecord;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';
require_once $basePath . '/maintenance/Maintenance.php';

class ApplyRules extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->requireExtension( 'Rules' );
		$this->addDescription( 'Applies rules on all pages' );
	}

	public function execute() {
		$services = MediaWikiServices::getInstance();
		$wikiPageFactory = $services->getWikiPageFactory();
		$pageRecords = $services->getPageStore()->newSelectQueryBuilder()->fetchPageRecords();

		foreach ( $pageRecords as $pageRecord ) {
			/** @var PageRecord $pageRecord */
			$page = $wikiPageFactory->newFromTitle( $pageRecord );
			$titleText = $page->getTitle()->getPrefixedText();

			if ( $page->doPurge() ) {
				$this->output( "Purged {$titleText}\n" );
			} else {
				$this->error( "Purge failed for {$titleText}" );
			}
		}
	}

}

$maintClass = ApplyRules::class;
require_once RUN_MAINTENANCE_IF_MAIN;
