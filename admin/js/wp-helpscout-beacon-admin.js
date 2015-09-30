(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
    var formId = hsb_settings.formId;
    if(formId) {
        !function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};
            var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},
                t.ready=function(e){this.readyQueue.push(e)},
                o.config={docs:{enabled:!1,baseUrl:""},
                    contact:{enabled:!0,formId:formId}};
            var r=e.getElementsByTagName("script")[0],c=e.createElement("script");
            c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",
                r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{}
        );
    }
})( jQuery );
