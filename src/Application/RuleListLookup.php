<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\Application;

interface RuleListLookup {

	public function getAllRules(): RuleList;

}
