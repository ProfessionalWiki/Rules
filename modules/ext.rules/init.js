function init() {
	const targetElement = document.getElementById( 'ext-rules-app' );
	if ( targetElement === null ) {
		mw.log.error( '[ext.rules] No target element found' );
		return;
	}

	mw.log.warn( '[ext.rules] Target element found for Vue app' );
}

init();
