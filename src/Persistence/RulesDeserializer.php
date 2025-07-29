<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Persistence;

use InvalidArgumentException;
use JsonException;
use ProfessionalWiki\Rules\Application\Condition;
use ProfessionalWiki\Rules\Application\Rule;
use ProfessionalWiki\Rules\Application\RuleList;

class RulesDeserializer {

	public function __construct(
		private readonly RulesJsonValidator $validator
	) {
	}

	/**
	 * @throws JsonException|InvalidArgumentException
	 */
	public function deserialize( string $json ): RuleList {
		if ( !$this->validator->validate( $json ) ) {
			throw new InvalidArgumentException( 'Invalid JSON: ' . implode( ', ', $this->validator->getErrors() ) );
		}

		$data = json_decode( $json, true, 512, JSON_THROW_ON_ERROR );

		$rules = [];

		foreach ( $data['rules'] as $rule ) {
			$rules[] = $this->deserializeRule( $rule );
		}

		return new RuleList( $rules );
	}

	private function deserializeRule( array $rule ): Rule {
		return new Rule(
			conditions: $this->deserializeConditions( $rule['conditions'] ?? [] ),
			categoriesToAdd: $this->deserializeActions( $rule['actions'] ?? [] )
		);
	}

	/**
	 * @return Condition[]
	 */
	private function deserializeConditions( array $conditionsData ): array {
		$conditions = [];

		foreach ( $conditionsData as $conditionData ) {
			if ( ( $conditionData['type'] ?? '' ) === 'inCategory' ) {
				$conditions[] = new Condition( $conditionData['categories'] ?? [] );
			}
		}

		return $conditions;
	}

	/**
	 * @return string[]
	 */
	private function deserializeActions( array $actions ): array {
		$categoriesToAdd = [];

		foreach ( $actions as $action ) {
			if ( array_key_exists( 'category', $action ) ) {
				$categoriesToAdd[] = $action['category'];
			}
		}

		return $categoriesToAdd;
	}

}
