<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Maintenance;

use Maintenance;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';
require_once $basePath . '/maintenance/Maintenance.php';

class ApplyRules extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->requireExtension( 'Rules' );
		$this->addDescription( 'Applies rules on all pages' );
	}

	public function execute() {
		// TODO: Implement execute() method.
	}

}

$maintClass = ApplyRules::class;
require_once RUN_MAINTENANCE_IF_MAIN;
