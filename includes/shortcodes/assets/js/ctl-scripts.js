class ctlCommonScript {

	// constructor
	constructor(){
		this.callBack();
	}

	// Lightbox Custom Html
	CtlLightBoxHtml = (type) => {
		// Lightbox Dynamic container class
		const layoutClass = type === 'minimal' ? ' minimal-layout' : '';

		return `<div id="glightbox-body" class="glightbox-container">
            <div class="gloader visible"></div>
            <div class="goverlay"></div>
            <div class="gcontainer ctl_glightbox_container${layoutClass}">
            <div id="glightbox-slider" class="gslider"></div>
            <button class="gnext gbtn" tabindex="0" aria-label="Next" data-customattribute="example">{nextSVG}</button>
            <button class="gprev gbtn" tabindex="1" aria-label="Previous">{prevSVG}</button>
            <button class="gclose gbtn" tabindex="2" aria-label="Close">{closeSVG}</button>
            </div>
        </div>`;
	};

	// Lightbox Custom Svg Icons and HTML
	CtlLightBoxSettings = (type) => {
		const Lightboxsvg = {
			close: '<i class="ctl_glightbox_close_btn"></i>',
			next: '<i class="ctl_glightbox_hidden"></i>',
			prev: '<i class="ctl_glightbox_hidden"></i>',
		};

		const attributes = {
			lightboxHTML: this.CtlLightBoxHtml(type),
			svg: Lightboxsvg,
			touchNavigation: type === 'minimal' ? false : true,
		};

		return GLightbox(attributes);
	};

	// Default Lightbox for single image
	CtlLighBox = (ele) => {
		ele.preventDefault();
		const element = ele.currentTarget;
		const lightbox = this.CtlLightBoxSettings('default');

		const targetHref = element.getAttribute('href');
		const title = element.getAttribute('data-glightbox');
		lightbox.setElements([{ href: targetHref, title }]);
		lightbox.open();
	};

	// Lightbox For minimal Layout
	CtlLighBoxMinimal = (ele) => {
		ele.preventDefault();
		// const element = ele.currentTarget;
		const popUpContainerID = jQuery(ele.currentTarget).data('popup-id');
		const popUpConatiner = jQuery(popUpContainerID);

		const lightbox = this.CtlLightBoxSettings('minimal');
		const img = popUpConatiner
			.find('.ctl_popup_media .ctl_popup_img')
			.data('popup-image');
		const date = popUpConatiner.find('.ctl_popup_date').html();
		const title = popUpConatiner.find('.ctl_popup_title').html();
		const desc = popUpConatiner.find('.ctl_popup_desc').html();

		const elements = [];

		if (img !== undefined) {
			elements.push({
				href: img,
				type: 'image',
				description: `<div class='ctl_glightbox_content'><h2 class='ctl_glightbox_title'>${title}</h2><div class='ctl_glightbox_date'>${date}</div><div class="ctl_glightbox_desc">${desc}</div></div>`,
				height: '200px',
			});
		}else {
			elements.push({
				content: `<h2 class='ctl_glightbox_title'>${title}</h2><div class='ctl_glightbox_content'><div class='ctl_glightbox_date'>${date}</div><div class="ctl_glightbox_desc">${desc}</div></div>`,
				'max-width': '50vw',
				height: 'auto',
			});
		}

		lightbox.setElements(elements);
		lightbox.open();
	};

	ctlResponsiveDevice = () => {
        const screenWidth = jQuery(window).width();

        const yearLabelOverflowCls=(isMobile)=>{
            const vrDateLabels=jQuery('.ctl-wrapper .ctl-vertical-wrapper').not('.ctl-compact-wrapper').find('.ctl-labels');
            if(vrDateLabels.length>0){
                if(isMobile){
                    vrDateLabels.each((_,ele)=>{
                        const contentWidth=jQuery(ele).closest('.ctl-story').find('.ctl-content')[0].scrollWidth;
                        const labelWidth=jQuery(ele)[0].scrollWidth;
                        if(labelWidth > contentWidth){
                            ele.classList.add('ctl-label-overflow');
                        }
                    })
                }else{
                    vrDateLabels.removeClass('ctl-label-overflow');
                }
            }
        }

        if (screenWidth < 380) {
            yearLabelOverflowCls(true);
        } else {
            yearLabelOverflowCls(false);
        }  
    };

	btnHoverEffect = () =>{
        const eleClasses='.ctl-button-next:not(".swiper-button-disabled"), .ctl-button-prev:not(".swiper-button-disabled"), .ctl-pagination a.page-numbers';

        jQuery(document).on('mousedown',eleClasses,(e)=>{
            const ele=jQuery(e.currentTarget);

            ele.addClass('ctl-btn-click-effect')
				setTimeout(
		            function() {
		                ele.removeClass('ctl-btn-click-effect');
		        },
		    500);
        });
    }

    callBack = () => {

		const eachTimeline=jQuery('.ctl-wrapper .cool-timeline-wrapper');
		eachTimeline.each((index,element) => {
			const timeline=jQuery(element);
			if(timeline.hasClass('ctl-horizontal-timeline')){
				timeline.find('.ctl-story');
			}else{
				const animation=timeline.find(".ctl-timeline-container").attr("data-animation");
				if('none' !== animation){
					AOS.init();
				}
			}
		});

        // Default Lightbox for single image
        jQuery(document).on(
            'click',
            '.ctl-wrapper .ctl_glightbox',
            (ele) => {
                this.CtlLighBox(ele);
            }
        );

        // Lightbox for minimal Layout
        jQuery(document).on(
            'click',
            '.ctl-wrapper .minimal_glightbox',
            (ele) => {
                this.CtlLighBoxMinimal(ele);
            }
        );

		this.ctlResponsiveDevice();

        jQuery(window).on('resize', () => {
            this.ctlResponsiveDevice();
        });

		// Button hover effect
		this.btnHoverEffect();
    };
}

new ctlCommonScript;
