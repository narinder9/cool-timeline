// Compact layout initalize
class ctlCompact {

	// constructor
	constructor(){
		this.init();
	}

    init(){
        const initializeCompactMasonry=()=> {
            const wrapper = jQuery(
                '.ctl-compact-wrapper .ctl-timeline-container'
            );
            const animation = wrapper.data('animation');
            this.ctlCompactMasonry(wrapper, animation);
        }

        jQuery(document).ready(initializeCompactMasonry);
        jQuery(window).on('load', function () {
            setTimeout(initializeCompactMasonry, 200);
        });
        jQuery(window).on('resize', initializeCompactMasonry);
    }

    ctlCompactMasonry = (grids, animation)=> {
		let grid = '';
		let leftReminder = 0;
		let rightReminder = 0;
		grid = grids.masonry({
			itemSelector: '.ctl-story',
			initLayout: false,
		});

		// layout images after they are loaded
		// grid.imagesLoaded().progress(() => {
		// 	grid.masonry('layout');
		// });

		grid.one('layoutComplete', () => {
			let leftPos = 0;
			let topPosDiff;
			grid.find('.ctl-story').each((index, element) => {
				leftPos = jQuery(element).position().left;
				if (leftPos <= 0) {
					const extraCls = (leftReminder % 2) === 0 ? 'ctl-left-odd' : 'ctl-left-even';
					const prevCls = extraCls === 'ctl-left-odd' ? 'ctl-left-even' : 'ctl-left-odd';
					jQuery(element)
						.removeClass('ctl-story-right')
						.removeClass('ctl-right-even')
						.removeClass('ctl-right-odd')
						.removeClass(prevCls)
						.addClass('ctl-story-left')
						.addClass(extraCls);
						leftReminder++;
				} else {
					const extraCls = (rightReminder % 2) === 0 ? 'ctl-right-odd' : 'ctl-right-even';
					const prevCls = extraCls === 'ctl-right-odd' ? 'ctl-right-even' : 'ctl-right-odd';
					jQuery(element)
					.removeClass('ctl-story-left')
					.removeClass('ctl-left-odd')
					.removeClass('ctl-left-even')
					.removeClass(prevCls)
					.addClass('ctl-story-right')
					.addClass(extraCls);
					rightReminder++;
				}

				topPosDiff =
					jQuery(element).position().top -
					jQuery(element).prev().position().top;
				if (topPosDiff < 40) {
					jQuery(element)
						.removeClass('ctl-compact-up')
						.addClass('ctl-compact-down');
					jQuery(element)
						.prev()
						.removeClass('ctl-compact-down')
						.addClass('ctl-compact-up');
				}
			});
			jQuery('.ctl-icon').addClass('showit');
			jQuery('.ctl-title').addClass('showit-after');
			if (animation !== 'none') {
				AOS.refreshHard();
			}
		});
	};
}

new ctlCompact();