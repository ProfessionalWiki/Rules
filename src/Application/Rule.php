<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

class Rule {

	public function __construct(
		/**
		 * @var Condition[]
		 */
		private readonly array $conditions,

		/**
		 * @var string[]
		 */
		private readonly array $categoriesToAdd,
	) {
	}

	/**
	 * @param string[] $presentCategories
	 * @return string[]
	 */
	public function run( array $presentCategories ): array {
		if ( $this->allConditionsAreFulfilled( $presentCategories ) ) {
			return $this->categoriesToAdd;
		}

		return [];
	}

	/**
	 * @param string[] $presentCategories
	 */
	private function allConditionsAreFulfilled( array $presentCategories ): bool {
		foreach ( $this->conditions as $condition ) {
			if ( !$condition->isFulfilled( $presentCategories ) ) {
				return false;
			}
		}

		return true;
	}

}
