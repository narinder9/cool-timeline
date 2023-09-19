const customLightboxHTML = `<div id="glightbox-body" class="glightbox-container">
    <div class="gloader visible"></div>
    <div class="goverlay"></div>
    <div class="gcontainer ctl_glightbox_container">
    <div id="glightbox-slider" class="gslider"></div>
    <button class="gnext gbtn" tabindex="0" aria-label="Next" data-customattribute="example">{nextSVG}</button>
    <button class="gprev gbtn" tabindex="1" aria-label="Previous">{prevSVG}</button>
    <button class="gclose gbtn" tabindex="2" aria-label="Close">{closeSVG}</button>
</div>
</div>`;

const lightbox = GLightbox({
  lightboxHTML: customLightboxHTML,
  svg: {
            'close':'<i class="ctl_glightbox_close_btn"></i>',
        },
})
const allLightboxTriggers = document.querySelectorAll('.glightbox')
if (allLightboxTriggers) {
  allLightboxTriggers.forEach(function(trigger) {
    trigger.addEventListener('click', function(e) {
      e.preventDefault()
      let targetHref = this.getAttribute('href'),
      title = this.getAttribute('data-glightbox')
      lightbox.setElements([{'href': targetHref,'title':title}])
      lightbox.open()
    })
  })
}
const allHrLightboxTriggers = document.querySelectorAll('.horizontal_glightbox')
if (allHrLightboxTriggers) {
  allHrLightboxTriggers.forEach(function(trigger) {
    trigger.addEventListener('click', function(e) {
        e.preventDefault()
        let id=jQuery(this).attr('href'),
        image=jQuery(id).find('.ctl-popup-content .full-width>img')[0],
        date=jQuery(id).find('.ctl-popup-content .story-posted-date').html(),
        title=jQuery(id).find('.ctl-popup-content>h2').html(),
        desc=jQuery(id).find('.ctl-popup-content .story-content').html(),
        img=undefined;
        if(image != undefined){
            img=image.src;
        }
      lightbox.setElements(
        img!=undefined?[
            {
                'href': img,
                'type': 'image',
                'title': "<h2 class='ctl_glightbox_title'>"+title+"</h2>",
                'description': "<div class='ctl_glightbox_content'><div class='ctl_glightbox_date'>"+date+"</div>"+desc+"</div>",
            },
        ]:
        [
            {
                'content': "<h2 class='ctl_glightbox_title'>"+title+"</h2>"+"<div class='ctl_glightbox_content'><div class='ctl_glightbox_date'>"+date+"</div>"+desc+"</div>",
                'max-width':'50vw',
                'height':'auto'
              },
          ]
      )
      lightbox.open()
    })
  })
}