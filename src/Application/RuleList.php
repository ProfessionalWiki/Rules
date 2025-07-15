<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

class RuleList {

	public function __construct(
		/**
		 * @var Rule[]
		 */
		private readonly array $rules,
	) {
	}

	/**
	 * @param string[] $presentCategories
	 * @return string[]
	 */
	public function run( array $presentCategories ): array {
		$categoriesToAdd = array_map(
			fn( Rule $rule ) => $rule->run( $presentCategories ),
			$this->rules
		);

		return array_unique( array_merge( ...$categoriesToAdd ) );
	}

}
