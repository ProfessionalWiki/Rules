<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Tests;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\Rules\RulesExtension;

/**
 * @covers \ProfessionalWiki\Rules\RulesExtension
 */
class RulesExtensionTest extends TestCase {

	public function testGetInstanceIsSingleton(): void {
		$this->assertSame( RulesExtension::getInstance(), RulesExtension::getInstance() );
	}

}
