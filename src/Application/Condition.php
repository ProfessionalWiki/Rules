<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

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
		foreach ( $presentCategories as $category ) {
			if ( in_array( $category, $this->categoryNames, true ) ) {
				return true;
			}
		}

		return $this->categoryNames === [];
	}

}
