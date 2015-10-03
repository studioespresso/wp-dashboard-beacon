(function( $ ) {
	'use strict';

    var formId = hsb_settings.formId;
    var beaconOptions = hsb_settings.beaconOptions;
    var selectedIcon = hsb_settings.icon;
    var formInstructions = hsb_settings.formInstructions;
    var beaconColour = hsb_settings.colour;

    if(hsb_settings.allowAttachments === '1') { var allowedAttachments = true; } else { var allowedAttachments = false; }
    if(hsb_settings.credits === '1') { var poweredBy = false; } else { var poweredBy = true; }

    if(selectedIcon === '') {
        if(beaconOptions === 'contact') {
            selectedIcon = 'message';
        }
        if(beaconOptions === 'docs') {
            selectedIcon = 'search';
        }
        if(beaconOptions === 'contact_docs') {
            selectedIcon = 'beacon'
        }
    }
    if(beaconOptions === 'docs' || beaconOptions === 'contact_docs') { var enableDocs = 1; } else { var enableDocs = 0; }
    if(beaconOptions === 'contact' || beaconOptions === 'contact_docs') { var enableContact = 1; } else { var enableContact = 0; }
    if(hsb_settings.subDomain != '') {
        var subDomain = 'http://' + hsb_settings.subDomain + '.helpscoutdocs.com';
    } else {
        enableDocs = 0;
        subDomain = '';
    }

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
            };
        var r = e.getElementsByTagName("script")[0], c = e.createElement("script");
        c.type = "text/javascript", c.async = !0, c.src = "https://djtflbt20bdde.cloudfront.net/",
            r.parentNode.insertBefore(c, r)
    }(document, window.HSCW || {}, window.HS || {}
    );
    HS.beacon.config({
        modal: false,
        icon: selectedIcon,
        color: beaconColour,
        attachment: allowedAttachments,
        instructions: formInstructions,
        poweredBy: false,
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
    });
})( jQuery );
