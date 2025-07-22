<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

use Iterator;
use MediaWiki\Title\Title;
use WikiPage;

class ApplyRulesUseCase {

	public function __construct(
		private readonly RuleListLookup $ruleListLookup,
	) {
	}

	public function applyToPage( WikiPage $wikiPage ): void {
		$rules = $this->ruleListLookup->getAllRules();

		$presentCategories = $this->titleArrayObjectToStringArray( $wikiPage->getCategories() );

		$categoriesToAdd = $rules->run( $presentCategories );

		shuffle( $categoriesToAdd ); // TODO: add categories to page
	}

	/**
	 * @param Iterator<Title> $titles
	 * @return string[]
	 */
	private function titleArrayObjectToStringArray( Iterator $titles ): array {
		return array_map(
			fn( Title $title ) => $title->getText(),
			iterator_to_array( $titles )
		);
	}

}
