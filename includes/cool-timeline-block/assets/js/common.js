

jQuery(document).ready(function(){
  const removeEmptyElement=()=>{
    const imageBlocks=jQuery('.wp-block-cp-timeline-content-timeline-block-child .story-content .wp-block-image img[src=""]');
    const headingBlocks=jQuery('.wp-block-cp-timeline-content-timeline-block-child .story-content .wp-block-heading:empty');
    const paragraphBlocks=jQuery('.wp-block-cp-timeline-content-timeline-block-child .story-content>p:empty');
    imageBlocks?.closest('.story-content')?.addClass('cp-block-image-not');
    imageBlocks?.closest('figure')?.remove();
    headingBlocks?.remove();
    paragraphBlocks?.remove();
  };
  removeEmptyElement();
})