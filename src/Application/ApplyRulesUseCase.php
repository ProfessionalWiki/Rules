<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

class ApplyRulesUseCase {

	public function __construct(
		private readonly RuleListLookup $ruleListLookup,
	) {
	}

	public function applyToPage( /* TODO: some page identifier */ ): void {
		$rules = $this->ruleListLookup->getAllRules();

		$presentCategories = []; // TODO: get categories from page

		$categoriesToAdd = $rules->run( $presentCategories );

		shuffle( $categoriesToAdd ); // TODO: add categories to page
	}

}
