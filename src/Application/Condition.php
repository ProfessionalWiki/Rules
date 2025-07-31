<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

use MediaWiki\Title\Title;

/**
 * Currently we only support the "inCategory" condition type
 * which matches as soon as one of the present categories is in the condition.
 */
class Condition {

	public function __construct(
		/**
		 * @var string[]
		 */
		private readonly array $categoryNames
	) {
	}

	/**
	 * @param string[] $presentCategories
	 */
	public function isFulfilled( array $presentCategories ): bool {
		$normalizedPresentCategories = $this->normalizeCategories( $presentCategories );
		$normalizedConditionCategories = $this->normalizeCategories( $this->categoryNames );

		foreach ( $normalizedPresentCategories as $category ) {
			if ( in_array( $category, $normalizedConditionCategories, true ) ) {
				return true;
			}
		}

		return $this->categoryNames === [];
	}

	/**
	 * @param string[] $categories
	 * @return string[]
	 */
	private function normalizeCategories( array $categories ): array {
		return array_map(
			fn( string $category ) => Title::newFromText( $category, NS_CATEGORY )->getDBkey(),
			$categories
		);
	}
}
