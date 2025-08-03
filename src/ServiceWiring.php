<?php

use MediaWiki\MediaWikiServices;
use ProfessionalWiki\Rules\RulesExtension;

/** @phpcs-require-sorted-array */
return [
	'Rules.RulesExtension' => static function ( MediaWikiServices $services ): RulesExtension {
		return new RulesExtension(
			$services->getTitleFactory(),
			$services->getWikiPageFactory(),
		);
	}
];
