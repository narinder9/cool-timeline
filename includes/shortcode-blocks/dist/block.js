/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/block.js":
/*!**********************!*\
  !*** ./src/block.js ***!
  \**********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./icons */ "./src/icons.js");
/* harmony import */ var _layout_type__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./layout-type */ "./src/layout-type.js");
/* harmony import */ var _images_timeline_png__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./images/timeline.png */ "./src/images/timeline.png");
/**
 * Block dependencies
 */




/**
 * Internal block libraries
 */
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var baseURL = ctlUrl;
var LayoutImgPath = baseURL + '/includes/shortcode-blocks/layout-images';
var _wp = wp,
  apiFetch = _wp.apiFetch;
var _wp$blockEditor = wp.blockEditor,
  RichText = _wp$blockEditor.RichText,
  InspectorControls = _wp$blockEditor.InspectorControls,
  BlockControls = _wp$blockEditor.BlockControls;
var _wp$element = wp.element,
  Fragment = _wp$element.Fragment,
  useEffect = _wp$element.useEffect;
var _wp$components = wp.components,
  PanelBody = _wp$components.PanelBody,
  PanelRow = _wp$components.PanelRow,
  TextareaControl = _wp$components.TextareaControl,
  TextControl = _wp$components.TextControl,
  Dashicon = _wp$components.Dashicon,
  Toolbar = _wp$components.Toolbar,
  ButtonGroup = _wp$components.ButtonGroup,
  Button = _wp$components.Button,
  SelectControl = _wp$components.SelectControl,
  Tooltip = _wp$components.Tooltip,
  RangeControl = _wp$components.RangeControl,
  TabPanel = _wp$components.TabPanel,
  Card = _wp$components.Card,
  CardBody = _wp$components.CardBody,
  Panel = _wp$components.Panel;

/**
 * Register block

 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (registerBlockType('cool-timleine/shortcode-block', {
  // Block Title
  title: __('Cool Timeline Shortcode'),
  // Block Description
  description: __('Cool Timeline Shortcode Generator.'),
  // Block Category
  category: 'layout',
  // Block Icon
  icon: _icons__WEBPACK_IMPORTED_MODULE_0__["default"],
  // Block Keywords
  keywords: [__('cool timeline'), __('timeline shortcode'), __('cool timeline block')],
  attributes: {
    layout: {
      type: 'string',
      "default": 'default'
    },
    skin: {
      type: 'string',
      "default": 'default'
    },
    postperpage: {
      type: 'string',
      "default": 10
    },
    slideToShow: {
      type: 'string',
      "default": ''
    },
    dateformat: {
      type: 'string',
      "default": 'F j'
    },
    icons: {
      type: 'string',
      "default": 'NO'
    },
    animation: {
      type: 'string',
      "default": 'none'
    },
    storycontent: {
      type: 'string',
      "default": 'short'
    },
    order: {
      type: 'string',
      "default": 'DESC'
    },
    isPreview: {
      type: 'boolean',
      "default": false
    }
  },
  // Defining the edit interface
  edit: function edit(props) {
    useEffect(function () {
      '' === props.attributes.slideToShow && props.setAttributes({
        slideToShow: '4'
      });
    }, []);
    var skinOptions = [{
      value: 'default',
      label: __('Default')
    }, {
      value: 'clean',
      label: __('Clean')
    }];
    // const iconOptions = [
    //     { value: 'NO', label: __( 'NO' ) },
    //     { value: 'YES', label: __( 'YES' ) }
    // ];
    var DfromatOptions = [{
      value: "F j",
      label: "F j"
    }, {
      value: "F j Y",
      label: "F j Y"
    }, {
      value: "Y-m-d",
      label: "Y-m-d"
    }, {
      value: "m/d/Y",
      label: "m/d/Y"
    }, {
      value: "d/m/Y",
      label: "d/m/Y"
    }, {
      value: "F j Y g:i A",
      label: "F j Y g:i A"
    }, {
      value: "Y",
      label: "Y"
    }];
    var layoutOptions = [{
      value: 'default',
      label: __('Vertical')
    }, {
      value: 'horizontal',
      label: __('Horizontal')
    }, {
      value: 'one-side',
      label: __('One Side Layout')
    }, {
      value: 'simple',
      label: __('Simple Layout')
    }, {
      value: 'compact',
      label: __('Compact Layout')
    }];
    var animationOptions = [{
      value: 'none',
      label: __('None')
    }, {
      value: 'fade-up',
      label: __('fadeInUp')
    }];
    var contentSettings = [{
      label: "Summary",
      value: "short"
    }, {
      label: "Full Text",
      value: "full"
    }];
    var general_settings = React.createElement(Panel, {
      className: "ctl_shortcode_setting_panel"
    }, React.createElement(PanelBody, {
      title: __('Timeline General Settings')
    }, React.createElement(SelectControl, {
      label: __('Select Layout'),
      description: __('Vertical/Horizontal'),
      options: layoutOptions,
      value: props.attributes.layout,
      onChange: function onChange(value) {
        return props.setAttributes({
          layout: value
        });
      },
      __nextHasNoMarginBottom: true
    }), props.attributes.layout != "horizontal" && React.createElement(Fragment, null, React.createElement("div", {
      className: "ctl_shortcode-button-group_label"
    }, __("Skin")), React.createElement(ButtonGroup, {
      className: "ctl_shortcode-button-group"
    }, React.createElement(Button, {
      onClick: function onClick(e) {
        props.setAttributes({
          skin: 'default'
        });
      },
      className: props.attributes.skin == 'default' ? 'active' : '',
      isSmall: true
    }, "Default"), React.createElement(Button, {
      onClick: function onClick(e) {
        props.setAttributes({
          skin: 'clean'
        });
      },
      className: props.attributes.skin == 'clean' ? 'active' : '',
      isSmall: true
    }, "Clean")), React.createElement("p", null, "Create Light, Dark or Colorful Timeline")), React.createElement(PanelRow, {
      className: "ctl_shortcode_setting_row"
    }, React.createElement(SelectControl, {
      label: __('Stories Description?'),
      options: contentSettings,
      value: props.attributes.storycontent,
      onChange: function onChange(value) {
        return props.setAttributes({
          storycontent: value
        });
      }
    })), props.attributes.storycontent == 'short' ? React.createElement("p", null, React.createElement("strong", null, "Summary"), ":- Short description") : React.createElement("p", null, React.createElement("strong", null, "Full"), ":- All content with formated text."), React.createElement(RangeControl, {
      label: __('Display Pers Page?'),
      value: parseInt(props.attributes.postperpage),
      onChange: function onChange(value) {
        return props.setAttributes({
          postperpage: value.toString()
        });
      },
      min: 1,
      max: 50,
      step: 1
    }), 'horizontal' === props.attributes.layout && React.createElement(RangeControl, {
      label: __('Slide To Show?'),
      value: parseInt(props.attributes.slideToShow),
      onChange: function onChange(value) {
        return props.setAttributes({
          slideToShow: value.toString()
        });
      },
      min: 1,
      max: 10,
      step: 1
    })));
    var advanced_settings = React.createElement(PanelBody, {
      title: __('Timeline Advanced Settings')
    }, React.createElement("div", {
      className: "ctl_shortcode-button-group_label"
    }, __("Stories Order?")), React.createElement(ButtonGroup, {
      className: "ctl_shortcode-button-group"
    }, React.createElement(Button, {
      onClick: function onClick(e) {
        props.setAttributes({
          order: 'ASC'
        });
      },
      className: props.attributes.order == 'ASC' ? 'active' : '',
      isSmall: true
    }, "ASC"), React.createElement(Button, {
      onClick: function onClick(e) {
        props.setAttributes({
          order: 'DESC'
        });
      },
      className: props.attributes.order == 'DESC' ? 'active' : '',
      isSmall: true
    }, "DESC")), React.createElement("p", null, "For Ex :- ", props.attributes.order == 'DESC' ? 'DESC(2017-1900)' : 'ASC(1900-2017)'), React.createElement(SelectControl, {
      label: __('Date Formats'),
      description: __('yes/no'),
      options: DfromatOptions,
      value: props.attributes.dateformat,
      onChange: function onChange(value) {
        return props.setAttributes({
          dateformat: value
        });
      }
    }), React.createElement("div", {
      className: "ctl_shortcode-button-group_label"
    }, __("Display Icons (By default Is Dot)")), React.createElement(ButtonGroup, {
      className: "ctl_shortcode-button-group"
    }, React.createElement(Button, {
      onClick: function onClick(e) {
        props.setAttributes({
          icons: 'YES'
        });
      },
      className: props.attributes.icons == 'YES' ? 'active' : '',
      isSmall: true
    }, "Icons"), React.createElement(Button, {
      onClick: function onClick(e) {
        props.setAttributes({
          icons: 'NO'
        });
      },
      className: props.attributes.icons == 'NO' ? 'active' : '',
      isSmall: true
    }, "Dot")), props.attributes.layout != "horizontal" && React.createElement(SelectControl, {
      label: __('Animation'),
      description: __('yes/no'),
      options: animationOptions,
      value: props.attributes.animation,
      onChange: function onChange(value) {
        return props.setAttributes({
          animation: value
        });
      }
    }));
    return [!!props.isSelected && React.createElement(InspectorControls, {
      key: "inspector"
    }, React.createElement(TabPanel, {
      className: "ctl_shortcode-tab-settings",
      activeClass: "active-tab",
      tabs: [{
        name: 'general_settings',
        title: 'General',
        className: 'ctl-settings_tab-one',
        content: general_settings
      }, {
        name: 'advanced_settings',
        title: 'Advanced',
        className: 'ctl-settings_tab-two',
        content: advanced_settings
      }]
    }, function (tab) {
      return React.createElement(Card, null, tab.content);
    }), React.createElement(CardBody, {
      className: "ctl-shortcode-block-demo-button"
    }, React.createElement("a", {
      target: "_blank",
      className: "button button-primary",
      href: "https://cooltimeline.com/demo/cool-timeline-pro/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=demo&utm_content=ctl_shortcode"
    }, "View Demos"), React.createElement("a", {
      target: "_blank",
      className: "button button-primary",
      href: "https://cooltimeline.com/plugin/cool-timeline-pro/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=ctl_shortcode"
    }, "Buy Pro"))), props.attributes.isPreview ? React.createElement("img", {
      src: _images_timeline_png__WEBPACK_IMPORTED_MODULE_2__["default"]
    }) : React.createElement("div", {
      className: props.className,
      key: props.clientId
    }, React.createElement(_layout_type__WEBPACK_IMPORTED_MODULE_1__["default"], {
      LayoutImgPath: LayoutImgPath,
      attributes: props.attributes
    }), React.createElement("div", {
      className: "ctl-block-shortcode"
    }, props.attributes.layout === 'horizontal' ? /*#__PURE__*/React.createElement(React.Fragment, null, "[cool-timeline layout=\"", props.attributes.layout, "\" show-posts=\"", props.attributes.postperpage, "\" items=\"", props.attributes.slideToShow, "\" date-format=\"", props.attributes.dateformat, "\" icons=\"", props.attributes.icons, "\" story-content=\"", props.attributes.storycontent, "\" order=\"", props.attributes.order, "\" ]") : /*#__PURE__*/React.createElement(React.Fragment, null, "[cool-timeline layout=\"", props.attributes.layout, "\" skin=\"", props.attributes.skin, "\" show-posts=\"", props.attributes.postperpage, "\" date-format=\"", props.attributes.dateformat, "\" icons=\"", props.attributes.icons, "\" animation=\"", props.attributes.animation, "\" story-content=\"", props.attributes.storycontent, "\" order=\"", props.attributes.order, "\" ]")))];
  },
  // Defining the front-end interface
  save: function save() {
    // Rendering in PHP
    return null;
  },
  example: {
    attributes: {
      isPreview: true
    }
  }
}));

/***/ }),

/***/ "./src/icons.js":
/*!**********************!*\
  !*** ./src/icons.js ***!
  \**********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var CtlIcon = function CtlIcon() {
  return React.createElement("svg", {
    width: "100%",
    height: "100%",
    viewBox: "0 0 62 62",
    version: "1.1",
    xmlns: "http://www.w3.org/2000/svg",
    xmlnsXlink: "http://www.w3.org/1999/xlink",
    xmlSpace: "preserve",
    xmlnsserif: "http://www.serif.com/",
    style: {
      fillRule: "evenodd",
      clipRule: "evenodd",
      strokeLinecap: "round",
      strokeLinejoin: "round",
      strokeMiterlimit: 1.5
    }
  }, React.createElement("g", {
    id: "icon-only",
    serifid: "icon only"
  }, React.createElement("g", {
    id: "icon-only1",
    serifid: "icon-only"
  }, React.createElement("g", {
    id: "icon"
  }, React.createElement("rect", {
    x: "29.146",
    y: "-0.042",
    width: "3.149",
    height: "61.44"
  }), React.createElement("path", {
    d: "M18.433,21.461l-0.007,-4.311l5.77,-4.905l-5.766,-4.923l0.003,-4.293l-18.433,-0l-0,18.432l18.433,0",
    style: {
      fill: "#f12945"
    }
  }), React.createElement("circle", {
    cx: "30.72",
    cy: "12.245",
    r: "3.046",
    style: {
      fill: "#fff",
      stroke: "#000",
      strokeWidth: 2.18 + "px"
    }
  }), React.createElement("path", {
    d: "M43.007,21.461l0.007,4.312l-5.77,4.905l5.766,4.922l-0.003,4.294l18.433,-0l0,-18.433l-18.433,0",
    style: {
      fill: "#01c5bd"
    }
  }), React.createElement("circle", {
    cx: "30.72",
    cy: "30.678",
    r: "3.046",
    style: {
      fill: "#fff",
      stroke: "#000",
      strokeWidth: 2.18 + "px"
    }
  }), React.createElement("path", {
    d: "M18.433,58.326l-0.007,-4.311l5.77,-4.905l-5.766,-4.923l0.003,-4.293l-18.433,-0l-0,18.432l18.433,0",
    style: {
      fill: "#f12945"
    }
  }), React.createElement("circle", {
    cx: "30.72",
    cy: "49.11",
    r: "3.046",
    style: {
      fill: "#fff",
      stroke: "#000",
      strokeWidth: 2.18 + "px"
    }
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CtlIcon);

/***/ }),

/***/ "./src/images/timeline.png":
/*!*********************************!*\
  !*** ./src/images/timeline.png ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__webpack_require__.p + "images/27d3f3c782dabdb08d424daa7396b72e.png");

/***/ }),

/***/ "./src/layout-type.js":
/*!****************************!*\
  !*** ./src/layout-type.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./icons */ "./src/icons.js");

var CtlLayoutType = function CtlLayoutType(props) {
  if (!props.attributes.layout) {
    return null;
  }
  if (props.attributes.layout == "horizontal") {
    var horizontal_img = props.LayoutImgPath + "/cool-horizontal-timeline.jpg";
    var divStyle = {
      color: 'white',
      backgroundImage: 'url(' + horizontal_img + ')',
      height: '300px',
      width: '100%'
    };
    // return <div style={divStyle} className="ctl-block-image">
    return React.createElement("div", {
      className: "ctl-block-image"
    }, React.createElement("div", {
      className: "ctl-block-icon"
    }, React.createElement(_icons__WEBPACK_IMPORTED_MODULE_0__["default"], null)), React.createElement("p", null, "Story Timeline Shortcode"));
  } else {
    var vertical_img = props.LayoutImgPath + "/cool-vertical-timeline.jpg";
    var divStylev = {
      color: 'white',
      backgroundImage: 'url(' + vertical_img + ')',
      height: '300px',
      width: '100%'
    };
    // return <div style={divStylev} className="ctl-block-image">
    return React.createElement("div", {
      className: "ctl-block-image"
    }, React.createElement("div", {
      className: "ctl-block-icon"
    }, React.createElement(_icons__WEBPACK_IMPORTED_MODULE_0__["default"], null)), React.createElement("p", null, "Story Timeline Shortcode"));
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CtlLayoutType);

/***/ }),

/***/ "./src/style.scss":
/*!************************!*\
  !*** ./src/style.scss ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		var scriptUrl;
/******/ 		if (__webpack_require__.g.importScripts) scriptUrl = __webpack_require__.g.location + "";
/******/ 		var document = __webpack_require__.g.document;
/******/ 		if (!scriptUrl && document) {
/******/ 			if (document.currentScript && document.currentScript.tagName.toUpperCase() === 'SCRIPT')
/******/ 				scriptUrl = document.currentScript.src;
/******/ 			if (!scriptUrl) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				if(scripts.length) {
/******/ 					var i = scripts.length - 1;
/******/ 					while (i > -1 && (!scriptUrl || !/^http(s?):/.test(scriptUrl))) scriptUrl = scripts[i--].src;
/******/ 				}
/******/ 			}
/******/ 		}
/******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
/******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
/******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
/******/ 		scriptUrl = scriptUrl.replace(/^blob:/, "").replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
/******/ 		__webpack_require__.p = scriptUrl + "../";
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./src/style.scss");
/* harmony import */ var _block_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block.js */ "./src/block.js");
// Include stylesheet


// Import Click to Tweet Block

})();

/******/ })()
;
//# sourceMappingURL=block.js.map