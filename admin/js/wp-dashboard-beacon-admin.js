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

    // beacon, buoy, message, question or search.

    var formId = hsb_settings.formId;
    var subDomain = 'http://' + hsb_settings.subDomain + '.helpscoutdocs.com';
    var beaconOptions = hsb_settings.beaconOptions;
    console.log(beaconOptions);
    if(beaconOptions === 'docs' || beaconOptions === 'contact_docs') { var enableDocs = 1; } else { var enableDocs = 0; }
    if(beaconOptions === 'contact' || beaconOptions === 'contact_docs') { var enableContact = 1; } else { var enableContact = 0; }
    if (formId) {
        !function (e, o, n) {
            window.HSCW = o, window.HS = n, n.beacon = n.beacon || {};
            var t = n.beacon;
            t.userConfig = {}, t.readyQueue = [], t.config = function (e) {
                this.userConfig = e
            },
                t.ready = function (e) {
                    this.readyQueue.push(e)
                },
                o.config = {
                    docs: {enabled: enableDocs, baseUrl: subDomain},
                    contact: {enabled: enableContact, formId: formId},
                    color: '#cacaca',
                    translation: {
                        'searchLabel': hsb_settings.strings.searchLabel,
                        'searchErrorLabel': hsb_settings.strings.searchErrorLabel,
                        'noResultsLabel': hsb_settings.strings.noResultsLabel,
                        'contactLabel': hsb_settings.strings.contactLabel,
                        'attachFileLabel': hsb_settings.strings.attachFileLabel,
                        'attachFileError': hsb_settings.strings.attachFileError,
                        'nameLabel': hsb_settings.strings.nameLabel,
                        'nameError': hsb_settings.strings.nameError,
                        'emailLabel': hsb_settings.strings.emailLabel,
                        'emailError': hsb_settings.strings.emailError,
                        'topicLabel': hsb_settings.strings.topicLabel,
                        'topicError': hsb_settings.strings.topicError,
                        'subjectLabel': hsb_settings.strings.subjectLabel,
                        'subjectError': hsb_settings.strings.subjectError,
                        'messageLabel': hsb_settings.strings.messageLabel,
                        'messageError': hsb_settings.strings.messageError,
                        'sendLabel': hsb_settings.strings.sendLabel,
                        'contactSuccessLabel': hsb_settings.strings.contactSuccessLabel,
                        'contactSuccessDescription': hsb_settings.strings.contactSuccessDescription,
                    },
                };
            var r = e.getElementsByTagName("script")[0], c = e.createElement("script");
            c.type = "text/javascript", c.async = !0, c.src = "https://djtflbt20bdde.cloudfront.net/",
                r.parentNode.insertBefore(c, r)
        }(document, window.HSCW || {}, window.HS || {}
        );
    }
})( jQuery );
