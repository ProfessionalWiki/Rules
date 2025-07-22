<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

use MediaWiki\Parser\ParserOutput;

class ApplyRulesUseCase {

	public function __construct(
		private readonly RuleListLookup $ruleListLookup,
	) {
	}

	public function applyToPage( ParserOutput $parserOutput ): void {
		$rules = $this->ruleListLookup->getAllRules();

		$presentCategories = $parserOutput->getCategoryNames();

		$categoriesToAdd = $rules->run( $presentCategories );

		foreach ( $categoriesToAdd as $category ) {
			$parserOutput->addCategory( $category );
		}
	}

}
