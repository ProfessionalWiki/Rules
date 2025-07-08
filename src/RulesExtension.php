<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules;

class RulesExtension {

	public const RULES_PAGE_TITLE = 'Rules';

	public static function getInstance(): self {
		/** @var ?RulesExtension $instance */
		static $instance = null;
		$instance ??= new self();
		return $instance;
	}

}
