<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests\Maintenance;

use MediaWiki\Maintenance\Maintenance;
use MediaWikiIntegrationTestCase;
use ProfessionalWiki\Rules\Maintenance\ApplyRules;

/**
 * @covers \ProfessionalWiki\Rules\Maintenance\ApplyRules
 * @group Database
 */
class ApplyRulesTest extends MediaWikiIntegrationTestCase {

	private Maintenance $maintenance;

	protected function setUp(): void {
		parent::setUp();

		$this->maintenance = new ApplyRules();
		$this->maintenance->checkRequiredExtensions();
	}

	protected function tearDown(): void {
		$this->maintenance->cleanupChanneled();
		parent::tearDown();
	}

	public function testTODO(): void {
		$this->maintenance->execute();

		$this->assertTrue( true ); // TODO
	}

}
