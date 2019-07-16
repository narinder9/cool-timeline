/**
 * All Types Meta Box Class JS
 *
 * JS used for the custom metaboxes and other form items.
 *
 * Copyright 2011 - 2013 Ohad Raz (admin@bainternet.info)
 * @since 1.0
 */

var c =jQuery.noConflict();

var e_d_count = 0;
var Ed_array = Array;
//fix editor on window resize
jQuery(document).ready(function($) {
/*------------ end here---------------*/
  $("#post").validate({
    rules: {
      startDate: {
        required: true,
        dpDate: true
      }
    }
  });

  //editor rezise fix
  $(window).resize(function() {
    $.each(Ed_array, function() {
      var ee = this;
      $(ee.getScrollerElement()).width(100); // set this low enough
      width = $(ee.getScrollerElement()).parent().width();
      $(ee.getScrollerElement()).width(width); // set it to
      ee.refresh();
    });
  });
});

function update_repeater_fields(){
  _metabox_fields.init();
}
//metabox fields object
var _metabox_fields = {
  oncefancySelect: false,
  init: function(){
    if (!this.oncefancySelect){
      this.fancySelect();
      this.oncefancySelect = true;
    }
    this.load_code_editor();
    this.load_conditinal();
    this.load_time_picker();
    this.load_date_picker();
    this.load_color_picker();

    // repater Field
    c(".at-re-toggle").live('click', function() {
      c(this).parent().find('.repeater-table').toggle('slow');
    });
    // repeater sortable
    c('.repeater-sortable').sortable({
      opacity: 0.6,
      revert: true,
      cursor: 'move',
      handle: '.at_re_sort_handle',
      placeholder: 'at_re_sort_highlight'
    });
    //c('.repeater-sortable').sortable( "option", "handle", ".at_re_sort_handle" );
  },
  fancySelect: function(){
    // if (c().select2){
    c(".at-select, .at-posts-select, .at-tax-select").each(function (){
      if(! c(this).hasClass('no-fancy'))
        c(this).select2();
    });
    //}
  },
  get_query_var: function(name){
    var match = RegExp('[?&]' + name + '=([^&#]*)').exec(location.href);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
  },
  load_code_editor: function(){
    c(".code_text").each(function() {

      // if a code editor is already present, do nothing... #94
      if(c(this).next('.CodeMirror').length) return;

      var lang = c(this).attr("data-lang");
      //php application/x-httpd-php
      //css text/css
      //html text/html
      //javascript text/javascript
      switch(lang){
        case 'php':
          lang = 'application/x-httpd-php';
          break;
        case 'css':
          lang = 'text/css';
          break;
        case 'html':
          lang = 'text/html';
          break;
        case 'javascript':
          lang = 'text/javascript';
          break;
        default:
          lang = 'application/x-httpd-php';
      }
      var theme  = c(this).attr("data-theme");
      switch(theme){
        case 'default':
          theme = 'default';
          break;
        case 'light':
          theme = 'solarizedLight';
          break;
        case 'dark':
          theme = 'solarizedDark';;
          break;
        default:
          theme = 'default';
      }

      var editor = CodeMirror.fromTextArea(document.getElementById(c(this).attr('id')), {
        lineNumbers: true,
        matchBrackets: true,
        mode: lang,
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
      });
      editor.setOption("theme", theme);
      c(editor.getScrollerElement()).width(100); // set this low enough
      width = c(editor.getScrollerElement()).parent().width();
      c(editor.getScrollerElement()).width(width); // set it to
      editor.refresh();
      Ed_array[e_d_count] = editor;
      e_d_count++;
    });
  },
  load_conditinal: function(){
    c(".conditinal_control").click(function(){
      if(c(this).is(':checked')){
        c(this).next().show('fast');
      }else{
        c(this).next().hide('fast');
      }
    });
  },
  load_time_picker: function(){
    c('.at-time').each( function() {

      var cthis   = c(this),
          format   = cthis.attr('rel'),
          aampm    = cthis.attr('data-ampm');
      if ('true' == aampm)
        aampm = true;
      else
        aampm = false;

      cthis.timepicker( { showSecond: true, timeFormat: format, ampm: aampm } );

    });
  },
  load_date_picker: function() {
    c('.at-date').each( function() {

      var cthis  = c(this),
          format = cthis.attr('rel');

      cthis.datetimepicker( { showButtonPanel: true,
        dateFormat:'mm/dd/yy',
        timeFormat: "hh:mm tt",
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date(),
        yearRange:'1970:2025'
      } ).attr("placeholder", "mm/dd/yy hh:mm").attr('class','dpDate');


    });
  },
  load_color_picker: function(){
    if (c('.at-color-iris').length>0)
      c('.at-color-iris').wpColorPicker();
  },
};
//call object init in delay
window.setTimeout('_metabox_fields.init();',2000);

//upload fields handler
var simplePanelmedia;
jQuery(document).ready(function(c){
  var simplePanelupload =(function(){
    var inited;
    var file_id;
    var file_url;
    var file_type;
    function init (){
      return {
        image_frame: new Array(),
        file_frame: new Array(),
        hooks:function(){
          c(document).on('click','.simplePanelimageUpload,.simplePanelfileUpload', function( event ){
            event.preventDefault();
            if (c(this).hasClass('simplePanelfileUpload'))
              inited.upload(c(this),'file');
            else
              inited.upload(c(this),'image');
          });

          c('.simplePanelimageUploadclear,.simplePanelfileUploadclear').live('click', function( event ){
            event.preventDefault();
            inited.set_fields(c(this));
            c(inited.file_url).val("");
            c(inited.file_id).val("");
            if (c(this).hasClass('simplePanelimageUploadclear')){
              inited.set_preview('image',false);
              inited.replaceImageUploadClass(c(this));
            }else{
              inited.set_preview('file',false);
              inited.replaceFileUploadClass(c(this));
            }
          });
        },
        set_fields: function (el){
          inited.file_url = c(el).prev();
          inited.file_id = c(inited.file_url).prev();
        },
        upload:function(el,utype){
          inited.set_fields(el)
          if (utype == 'image')
            inited.upload_Image(c(el));
          else
            inited.upload_File(c(el));
        },
        upload_File: function(el){
          // If the media frame already exists, reopen it.
          var mime = c(el).attr('data-mime_type') || '';
          var ext = c(el).attr("data-ext") || false;
          var name = c(el).attr('id');
          var multi = (c(el).hasClass("multiFile")? true: false);

          if ( typeof inited.file_frame[name] !== "undefined")  {
            if (ext){
              inited.file_frame[name].uploader.uploader.param( 'uploadeType', ext);
              inited.file_frame[name].uploader.uploader.param( 'uploadeTypecaller', 'my_meta_box' );
            }
            inited.file_frame[name].open();
            return;
          }
          // Create the media frame.

          inited.file_frame[name] = wp.media({
            library: {
              type: mime
            },
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
              text: jQuery( this ).data( 'uploader_button_text' ),
            },
            multiple: multi  // Set to true to allow multiple files to be selected
          });


          // When an image is selected, run a callback.
          inited.file_frame[name].on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = inited.file_frame[name].state().get('selection').first().toJSON();
            // Do something with attachment.id and/or attachment.url here
            c(inited.file_id).val(attachment.id);
            c(inited.file_url).val(attachment.url);
            inited.replaceFileUploadClass(el);
            inited.set_preview('file',true);
          });
          // Finally, open the modal

          inited.file_frame[name].open();
          if (ext){
            inited.file_frame[name].uploader.uploader.param( 'uploadeType', ext);
            inited.file_frame[name].uploader.uploader.param( 'uploadeTypecaller', 'my_meta_box' );
          }
        },
        upload_Image:function(el){
          var name = c(el).attr('id');
          var multi = (c(el).hasClass("multiFile")? true: false);
          // If the media frame already exists, reopen it.
          if ( typeof inited.image_frame[name] !== "undefined")  {
            inited.image_frame[name].open();
            return;
          }
          // Create the media frame.
          inited.image_frame[name] =  wp.media({
            library: {
              type: 'image'
            },
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
              text: jQuery( this ).data( 'uploader_button_text' ),
            },
            multiple: multi  // Set to true to allow multiple files to be selected
          });
          // When an image is selected, run a callback.
          inited.image_frame[name].on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = inited.image_frame[name].state().get('selection').first().toJSON();
            // Do something with attachment.id and/or attachment.url here
            c(inited.file_id).val(attachment.id);
            c(inited.file_url).val(attachment.url);
            inited.replaceImageUploadClass(el);
            inited.set_preview('image',true);
          });
          // Finally, open the modal
          inited.image_frame[name].open();
        },
        replaceImageUploadClass: function(el){
          if (c(el).hasClass("simplePanelimageUpload")){
            c(el).removeClass("simplePanelimageUpload").addClass('simplePanelimageUploadclear').val('Remove Image');
          }else{
            c(el).removeClass("simplePanelimageUploadclear").addClass('simplePanelimageUpload').val('Upload Image');
          }
        },
        replaceFileUploadClass: function(el){
          if (c(el).hasClass("simplePanelfileUpload")){
            c(el).removeClass("simplePanelfileUpload").addClass('simplePanelfileUploadclear').val('Remove File');
          }else{
            c(el).removeClass("simplePanelfileUploadclear").addClass('simplePanelfileUpload').val('Upload File');
          }
        },
        set_preview: function(stype,ShowFlag){
          ShowFlag = ShowFlag || false;
          var fileuri = c(inited.file_url).val();
          if (stype == 'image'){
            if (ShowFlag)
              c(inited.file_id).prev().find('img').attr('src',fileuri).show();
            else
              c(inited.file_id).prev().find('img').attr('src','').hide();
          }else{
            if (ShowFlag)
              c(inited.file_id).prev().find('ul').append('<li><a href="' + fileuri + '" target="_blank">'+fileuri+'</a></li>');
            else
              c(inited.file_id).prev().find('ul').children().remove();
          }
        }
      }
    }
    return {
      getInstance :function(){
        if (!inited){
          inited = init();
        }
        return inited;
      }
    }
  })()
  simplePanelmedia = simplePanelupload.getInstance();
  simplePanelmedia.hooks();
});