<template>
	<div class="ext-rules-form-section">
		<div class="ext-rules-form-section-title">
			{{ title }}
		</div>
		<div class="ext-rules-form-section-content">
			<slot></slot>
		</div>
	</div>
</template>

<script>
const { defineComponent } = require( 'vue' );

module.exports = defineComponent( {
	name: 'FormSection',
	props: {
		title: {
			type: String,
			required: true
		}
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.ext-rules-form-section {
	&-title {
		font-size: @font-size-large;
		font-weight: @font-weight-bold;
		// Fallback for pre-Codex 2.0 MediaWiki versions
		line-height: var( --line-height-large, 1.75rem );
	}

	&-content {
		margin-top: @spacing-75;
	}
}
</style>
