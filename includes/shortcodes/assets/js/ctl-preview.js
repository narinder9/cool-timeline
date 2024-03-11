/* eslint-disable prettier/prettier */
class CtlPreview {
    constructor(iframeLoaded) {
        this.iframeWrp = jQuery('#ctl_preview')[0];
        // Run function After Iframe Window Load
        if (iframeLoaded) {
            this.CtlPreviewShortcode();
            this.CtlPreviewInit();
        } else {
            this.iframeWrp.contentWindow.onload = () => {
                this.CtlPreviewShortcode();
                this.CtlPreviewInit();
            };
        }
    }

    // Preivew Iframe Initialize and added script
    CtlPreviewInit() {
        this.iframe =
            this.iframeWrp.contentDocument ||
            this.iframeWrp.contentWindow.document;
        // eslint-disable-next-line no-undef
        const fieldWrp = this.iframeWrp.closest('.csf-field-content');
        const ModelWrp =
            this.iframeWrp.closest('.csf-modal-content');
        fieldWrp.style = `height: calc(${ModelWrp.clientHeight}px - 108px); overflow: hidden`;

        const jqueryScript = this.iframe.createElement('script');
        jqueryScript.src = 'https://code.jquery.com/jquery-3.6.4.min.js';
        this.iframe.head.appendChild(jqueryScript);
        const preloaderUrl = jQuery(this.iframeWrp).data('preloader');
        jQuery(this.iframe.body).append(`<img id="ctl-preview-preloader" src="${preloaderUrl}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(3)">`);
        this.ctlPreviewAjax();
    }

    // Get Timeline Shortcode Attribute
    CtlPreviewShortcode() {
        // Create an object to store the shortcode values
        const shortcodeObject = {
            shortcodeType: 'cool-timeline',
        };

        // Select the relevant input and select elements
        const layoutInput = jQuery(
            '.csf-fieldset select , .csf-fieldset input:checked ,.csf-fieldset input:text , .csf-fieldset .csf-siblings .csf--active input:checked'
        );

        // Loop through the selected inputs and selects
        layoutInput.each(function () {
            const inputKey = jQuery(this).attr('data-depend-id');
            const inputValue = jQuery(this).val();

            // Include input and select values in the object
            if (inputKey && inputValue) {
                shortcodeObject[inputKey] = inputValue;
            }
        });

        this.shortcodeObject = shortcodeObject;
        return shortcodeObject;
    }

    // Timeline Preview Ajax Request
    ctlPreviewAjax() {
        jQuery.ajax({
            type: 'POST',
            // eslint-disable-next-line no-undef
            url: myAjax.ajaxurl, // Set this using wp_localize_script
            data: {
                action: 'get_shortcode_preview',
                shortcode: this.shortcodeObject,
                // eslint-disable-next-line no-undef
                nonce: myAjax.nonce,
            },
            success: (response) => {
                setTimeout(() => {
                    jQuery(this.iframe.body).html('');
                    this.CtlPreviewAjaxSuccess(response);
                }, 1000)
            },
            error: (xhr, status, error) => {
                console.log(xhr.responseText);
                console.log(error);
                console.log(status);
            },
        });
    }

    // Ajax Success Function
    CtlPreviewAjaxSuccess(response) {
        // Added Timeline Style Files in iframe
        let styleFiles = '';
        if (['yes','Yes','YES'].includes(this.shortcodeObject.icons)) {
            styleFiles += `<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css?ver=4.5.6" media="all">`;
            styleFiles += `<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/v4-shims.min.css" media="all">`;
        };
        response.data.assets.style.map((file) => {
            styleFiles += `<link rel="stylesheet" href="${file}" media="all">`;
        });

        // Added Timeline Script Files in iframe
        let scriptFiles = '';
        response.data.assets.script.map((file) => {
            scriptFiles += `<script type="text/javascript" src="${file}"></script>`;
        });
        if ('compact' === this.shortcodeObject.layout) {
            scriptFiles += this.CtlCompactReInit();
        }

        // Create a custom style element for iframe
        const styleElement = this.iframe.createElement('style');
        styleElement.textContent = response.data.assets.custom_style;

        // Append the style files to the head of the iframe's content document
        jQuery(this.iframe).find('head').append(styleFiles);
        // Append the Customstyle element to the head of the iframe's content document
        this.iframe.head.appendChild(styleElement);
        // Append the timeline html to the body of the iframe's content document
        jQuery(this.iframe).find('body').prepend(response.data.shortcode);
        // Append the script files to the head of the iframe's content document
        jQuery(this.iframe).find('body').append(scriptFiles);

        jQuery(this.iframe)
            .find('a')
            .on('click', (e) => {
                e.preventDefault();
            });
    }

    CtlCompactReInit() {
        const script = `<script type="text/javascript">const initializeCompactMasonry=()=>{const wrapper=jQuery(".ctl-compact-wrapper .ctl-timeline-container"),animation=wrapper.data("animation");ctlCompactMasonry(wrapper,animation)};const ctlCompactMasonry=(grids,animation)=>{let grid="",leftReminder=0,rightReminder=0;grid=grids.masonry({itemSelector:".ctl-story",initLayout:!1}),grid.one("layoutComplete",()=>{let leftPos=0,topPosDiff;grid.find(".ctl-story").each((index,element)=>{if(leftPos=jQuery(element).position().left,leftPos<=0){const extraCls=leftReminder%2==0?"ctl-left-odd":"ctl-left-even",prevCls="ctl-left-odd"===extraCls?"ctl-left-even":"ctl-left-odd";jQuery(element).removeClass("ctl-story-right").removeClass("ctl-right-even").removeClass("ctl-right-odd").removeClass(prevCls).addClass("ctl-story-left").addClass(extraCls),leftReminder++}else{const extraCls=rightReminder%2==0?"ctl-right-odd":"ctl-right-even",prevCls="ctl-right-odd"===extraCls?"ctl-right-even":"ctl-right-odd";jQuery(element).removeClass("ctl-story-left").removeClass("ctl-left-odd").removeClass("ctl-left-even").removeClass(prevCls).addClass("ctl-story-right").addClass(extraCls),rightReminder++}topPosDiff=jQuery(element).position().top-jQuery(element).prev().position().top,topPosDiff<40&&(jQuery(element).removeClass("ctl-compact-up").addClass("ctl-compact-down"),jQuery(element).prev().removeClass("ctl-compact-down").addClass("ctl-compact-up"))}),jQuery(".ctl-icon").addClass("showit"),jQuery(".ctl-title").addClass("showit-after"),"none"!==animation&&AOS.refreshHard()})};setTimeout(()=>{initializeCompactMasonry(); jQuery('.ctl-compact-wrapper').css('padding','0px');},200)</script>`;
        return script;
    }
}

(function () {
    // Set up a click event for the third tab
    let shortcodePreview = false;
    let iframeLoaded = true;

    const iframeReload = () => {
        shortcodePreview = false;
        iframeLoaded = false;
        const iframeWrp = jQuery('#ctl_preview')[0];
        if (iframeWrp) {
            const iframeWrp = jQuery('#ctl_preview')[0];
            const iframeAttr = iframeWrp.getAttributeNames();
            const newIframe = document.createElement('iframe');
            Object.values(iframeAttr).map(val => {
                const attrValue = iframeWrp.getAttribute(val);
                if (attrValue && '' !== iframeAttr) {
                    newIframe.setAttribute(val, attrValue);
                }
            });
            iframeWrp.replaceWith(newIframe);

            newIframe.contentWindow.onload = () => {
                iframeLoaded = true;
            };
        }
    };

    jQuery(document).on('click', '.csf-tabbed-nav a:nth-child(2)', (e) => {
        // Update the shortcode before rendering and submit
        if (!shortcodePreview) {
            iframeReload();
            new CtlPreview(iframeLoaded);
            shortcodePreview = true;
        }
    });

    jQuery(document).on('click', '.csf-modal-inner', (e) => {
        e.stopPropagation();
    });

    // Set up a click event for the Second tab
    jQuery(document).on(
        'click',
        '.csf-tabbed-nav a:not(:nth-child(2)), .csf-modal-close , .csf-modal-overlay',
        () => {
            iframeReload();
        }
    );

    jQuery(document).on('click', '.csf-shortcode-button', () => {
        const activeTab = jQuery('.csf-tabbed-nav .csf-tabbed-active').text();
        if ('Preview' === activeTab) {
            const primaryTab = jQuery('.csf-tabbed-nav a').not('.csf-tabbed-active');
            primaryTab.trigger('click');
        }
        iframeReload();
    });

    jQuery(document).on('change', '.csf-modal-header select', () => {
        iframeReload();
    })
})(jQuery);