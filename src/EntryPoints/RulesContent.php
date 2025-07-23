<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\Rules\EntryPoints;

use MediaWiki\Content\JsonContent;
use ProfessionalWiki\Rules\RulesExtension;

class RulesContent extends JsonContent {

	public function __construct( $text, $modelId = RulesExtension::RULES_CONTENT_MODEL ) {
		parent::__construct( $text, $modelId );
	}
}
