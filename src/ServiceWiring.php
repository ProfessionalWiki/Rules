<?php

use MediaWiki\MediaWikiServices;
use ProfessionalWiki\Rules\Application\ApplyRulesUseCase;
use ProfessionalWiki\Rules\Application\RuleListLookup;
use ProfessionalWiki\Rules\Persistence\PageRuleListLookup;
use ProfessionalWiki\Rules\RulesExtension;

/** @phpcs-require-sorted-array */
return [
	'Rules.ApplyRulesUseCase' => static function ( MediaWikiServices $services ): ApplyRulesUseCase {
		return new ApplyRulesUseCase(
			$services->getService( 'Rules.PageRuleListLookup' )
		);
	},
	'Rules.PageRuleListLookup' => static function ( MediaWikiServices $services ): RuleListLookup {
		/** @var RulesExtension $rulesExtension */
		$rulesExtension = $services->getService( 'Rules.RulesExtension' );

		return new PageRuleListLookup(
			$services->getTitleFactory(),
			$services->getWikiPageFactory(),
			$rulesExtension->newRulesDeserializer()
		);
	},
	'Rules.RulesExtension' => static function ( MediaWikiServices $services ): RulesExtension {
		return new RulesExtension(
			$services->getTitleFactory()
		);
	}
];
