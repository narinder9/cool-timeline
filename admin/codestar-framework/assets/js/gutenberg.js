/**
 *
 * -----------------------------------------------------------
 *
 * Codestar Framework Gutenberg Block
 * A Simple and Lightweight WordPress Option Framework
 *
 * -----------------------------------------------------------
 *
 */
( function( blocks, blockEditor, element, components ) {

  if ( !window.csf_gutenberg_blocks ) { return; }

  Object.values(window.csf_gutenberg_blocks).forEach( function( block ) {

    var registerBlockType = blocks.registerBlockType;
    var PlainText         = blockEditor.PlainText;
    var createElement     = element.createElement;
    var RawHTML           = element.RawHTML;
    var Button            = components.Button;

    let ctl_blockName=block.gutenberg.block_name=='ctl_timeline_shortcode'?'csf/ctl-timeline-shortcode':'ctl-gutenberg-block/block-'+block.hash;
    let ctl_class=block.gutenberg.block_name=='ctl_timeline_shortcode'?'csf-shortcode':'ctl-shortcode';

    registerBlockType(ctl_blockName, {
      title: block.gutenberg.title,
      description: block.gutenberg.description,
      icon: block.gutenberg.icon || 'screenoptions',
      category: block.gutenberg.category || 'widgets',
      keywords: block.gutenberg.keywords,
      supports: {
        html: false,
        className: false,
        customClassName: false,
        inserter: false
      },
      attributes: {
        shortcode: {
          string: 'string',
          source: 'text',
        },
        isPreview:{
          type: 'boolean',
          default: false
        }
      },
      edit: function (props) {
        return (
          props.attributes.isPreview ?
           createElement('img', {src: block.gutenberg.previewImage})
           :
          createElement('div', {className: ctl_class+'-block'},

            createElement(Button, {
              'data-modal-id': block.modal_id,
              'data-gutenberg-id': block.name,
              className: 'is-secondary '+ctl_class+'-button',
              onClick: function () {
                window.csf_gutenberg_props = props;
              },
            }, block.button_title ),

            createElement(PlainText, {
              placeholder: block.gutenberg.placeholder,
              className: 'input-control blocks-shortcode__textarea',
              onChange: function (value) {
                props.setAttributes({
                  shortcode: value
                });
              },
              value: props.attributes.shortcode
            })

          )
        );
      },
      save: function (props) {
        return createElement(RawHTML, {}, props.attributes.shortcode);
      },
      example: {
        attributes: {
          isPreview: true,
        },
      },
    });

  });

})(
  window.wp.blocks,
  window.wp.blockEditor,
  window.wp.element,
  window.wp.components
);
