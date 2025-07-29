<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Maintenance;

use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Maintenance\Maintenance;
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
		$pageRecords = $services->getPageStore()->newSelectQueryBuilder()->fetchPageRecords();
		$wikiPageFactory = $services->getWikiPageFactory();
		$pageCount = 0;

		foreach ( $pageRecords as $pageRecord ) {
			/** @var PageRecord $pageRecord */
			$page = $wikiPageFactory->newFromTitle( $pageRecord );
			$categoryCountBefore = count( $page->getCategories() );

			// Get ParserOutput without cache to trigger onContentAlterParserOutput
			( new LinksUpdate( $page->getTitle(), $page->getParserOutput( noCache: true ) ) )->doUpdate();
			$page->doPurge();

			$titleText = $page->getTitle()->getPrefixedText();
			$categoryCountAfter = count( $page->getCategories() );

			$this->output( "Applied rules on {$titleText} ($categoryCountBefore -> $categoryCountAfter)\n" );

			$pageCount += 1;
		}

		$this->output( "Done. Applied rules on $pageCount pages\n" );
	}

}

$maintClass = ApplyRules::class;
require_once RUN_MAINTENANCE_IF_MAIN;
