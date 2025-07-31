<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\TitleValue;

class ApplyRulesUseCase {

	public function __construct(
		private readonly RuleListLookup $ruleListLookup,
	) {
	}

	public function applyToPage( ParserOutput $parserOutput ): void {
		$rules = $this->ruleListLookup->getAllRules();

		$presentCategories = $this->normalizeCategories( $parserOutput->getCategoryNames() );

		$categoriesToAdd = $rules->run( $presentCategories );

		foreach ( $categoriesToAdd as $category ) {
			$parserOutput->addCategory( $category );
		}
	}

	/**
	 * @param string[] $categories
	 * @return string[]
	 */
	private function normalizeCategories( array $categories ): array {
		return array_map(
			fn( string $category ) => TitleValue::tryNew( NS_CATEGORY, $category )?->getText() ?? '',
			$categories
		);
	}

}
