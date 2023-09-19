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
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
  /* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icons */ "./src/icons.js");
  /* harmony import */ var _layout_type__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./layout-type */ "./src/layout-type.js");
  /* harmony import */ var _images_timeline_png__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./images/timeline.png */ "./src/images/timeline.png");
  
  /**
   * Block dependencies
   */
  
  
  
  
  
  /**
   * Internal block libraries
   */
  const {
    __
  } = wp.i18n;
  const {
    registerBlockType
  } = wp.blocks;
  const baseURL = ctlUrl;
  const LayoutImgPath = baseURL + '/includes/shortcode-blocks/layout-images';
  const {
    apiFetch
  } = wp;
  const {
    RichText,
    InspectorControls,
    BlockControls
  } = wp.editor;
  const {
    Fragment
  } = wp.element;
  const {
    PanelBody,
    PanelRow,
    TextareaControl,
    TextControl,
    Dashicon,
    Toolbar,
    ButtonGroup,
    Button,
    SelectControl,
    Tooltip,
    RangeControl,
    TabPanel,
    Card,
    CardBody,
    Panel
  } = wp.components;
  
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
    icon: _icons__WEBPACK_IMPORTED_MODULE_1__["default"],
    // Block Keywords
    keywords: [__('cool timeline'), __('timeline shortcode'), __('cool timeline block')],
    attributes: {
      layout: {
        type: 'string',
        default: 'default'
      },
      skin: {
        type: 'string',
        default: 'default'
      },
      postperpage: {
        type: 'string',
        default: 10
      },
      slideToShow: {
        type: 'string',
        default: 4
      },
      dateformat: {
        type: 'string',
        default: 'F j'
      },
      icons: {
        type: 'string',
        default: 'NO'
      },
      animation: {
        type: 'string',
        default: 'none'
      },
      storycontent: {
        type: 'string',
        default: 'short'
      },
      order: {
        type: 'string',
        default: 'DESC'
      },
      isPreview: {
        type: 'boolean',
        default: false
      }
    },
    // Defining the edit interface
    edit: props => {
      const skinOptions = [{
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
      const DfromatOptions = [{
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
      const layoutOptions = [{
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
      const animationOptions = [{
        value: 'none',
        label: __('None')
      }, {
        value: 'fade-up',
        label: __('fadeInUp')
      }];
      const contentSettings = [{
        label: "Summary",
        value: "short"
      }, {
        label: "Full Text",
        value: "full"
      }];
      const general_settings = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Panel, {
        className: "ctl_shortcode_setting_panel"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Timeline General Settings')
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
        label: __('Select Layout'),
        description: __('Vertical/Horizontal'),
        options: layoutOptions,
        value: props.attributes.layout,
        onChange: value => props.setAttributes({
          layout: value
        }),
        __nextHasNoMarginBottom: true
      }), props.attributes.layout != "horizontal" && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "ctl_shortcode-button-group_label"
      }, __("Skin")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ButtonGroup, {
        className: "ctl_shortcode-button-group"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
        onClick: e => {
          props.setAttributes({
            skin: 'default'
          });
        },
        className: props.attributes.skin == 'default' ? 'active' : '',
        isSmall: true
      }, "Default"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
        onClick: e => {
          props.setAttributes({
            skin: 'clean'
          });
        },
        className: props.attributes.skin == 'clean' ? 'active' : '',
        isSmall: true
      }, "Clean")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "Create Light, Dark or Colorful Timeline")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, {
        className: "ctl_shortcode_setting_row"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
        label: __('Stories Description?'),
        options: contentSettings,
        value: props.attributes.storycontent,
        onChange: value => props.setAttributes({
          storycontent: value
        })
      })), props.attributes.storycontent == 'short' ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, "Summary"), ":- Short description") : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, "Full"), ":- All content with formated text."), props.attributes.layout != "horizontal" ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(RangeControl, {
        label: __('Display Pers Page?'),
        value: parseInt(props.attributes.postperpage),
        onChange: value => props.setAttributes({
          postperpage: value.toString()
        }),
        min: 1,
        max: 50,
        step: 1
      }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(RangeControl, {
        label: __('Slide To Show?'),
        value: parseInt(props.attributes.slideToShow),
        onChange: value => props.setAttributes({
          slideToShow: value.toString()
        }),
        min: 1,
        max: 10,
        step: 1
      })));
      const advanced_settings = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Timeline Advanced Settings')
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "ctl_shortcode-button-group_label"
      }, __("Stories Order?")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ButtonGroup, {
        className: "ctl_shortcode-button-group"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
        onClick: e => {
          props.setAttributes({
            order: 'ASC'
          });
        },
        className: props.attributes.order == 'ASC' ? 'active' : '',
        isSmall: true
      }, "ASC"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
        onClick: e => {
          props.setAttributes({
            order: 'DESC'
          });
        },
        className: props.attributes.order == 'DESC' ? 'active' : '',
        isSmall: true
      }, "DESC")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "For Ex :- ", props.attributes.order == 'DESC' ? 'DESC(2017-1900)' : 'ASC(1900-2017)'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
        label: __('Date Formats'),
        description: __('yes/no'),
        options: DfromatOptions,
        value: props.attributes.dateformat,
        onChange: value => props.setAttributes({
          dateformat: value
        })
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "ctl_shortcode-button-group_label"
      }, __("Display Icons (By Default Is Dot)")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ButtonGroup, {
        className: "ctl_shortcode-button-group"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
        onClick: e => {
          props.setAttributes({
            icons: 'YES'
          });
        },
        className: props.attributes.icons == 'YES' ? 'active' : '',
        isSmall: true
      }, "Yes"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
        onClick: e => {
          props.setAttributes({
            icons: 'NO'
          });
        },
        className: props.attributes.icons == 'NO' ? 'active' : '',
        isSmall: true
      }, "No")), props.attributes.layout != "horizontal" && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
        label: __('Animation'),
        description: __('yes/no'),
        options: animationOptions,
        value: props.attributes.animation,
        onChange: value => props.setAttributes({
          animation: value
        })
      }));
      return [!!props.isSelected && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(InspectorControls, {
        key: "inspector"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(TabPanel, {
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
      }, tab => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Card, null, tab.content)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __("View Timeline Demos", "timeline-block"),
        initialOpen: false
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CardBody, {
        className: "ctl-shortcode-block-demo-button"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
        target: "_blank",
        class: "button button-primary",
        href: "https://cooltimeline.com/demo/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=demo&utm_content=timeline_block"
      }, "View Demos"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
        target: "_blank",
        class: "button button-primary",
        href: "https://cooltimeline.com/buy-cool-timeline-pro/"
      }, "Buy Pro")))), props.attributes.isPreview ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: _images_timeline_png__WEBPACK_IMPORTED_MODULE_3__
      }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: props.className
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_layout_type__WEBPACK_IMPORTED_MODULE_2__["default"], {
        LayoutImgPath: LayoutImgPath,
        attributes: props.attributes
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "ctl-block-shortcode"
      }, "[cool-timeline layout=\"", props.attributes.layout, "\" skin=\"", props.attributes.skin, "\" show-posts=\"", props.attributes.postperpage, "\" date-format=\"", props.attributes.dateformat, "\" icons=\"", props.attributes.icons, "\" animation=\"", props.attributes.animation, "\" story-content=\"", props.attributes.storycontent, "\" order=\"", props.attributes.order, "\" ]"))];
    },
    // Defining the front-end interface
    save() {
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
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
  
  const CtlIcon = () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
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
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    id: "icon-only",
    serifid: "icon only"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    id: "icon-only1",
    serifid: "icon-only"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    id: "icon"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
    x: "29.146",
    y: "-0.042",
    width: "3.149",
    height: "61.44"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M18.433,21.461l-0.007,-4.311l5.77,-4.905l-5.766,-4.923l0.003,-4.293l-18.433,-0l-0,18.432l18.433,0",
    style: {
      fill: "#f12945"
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
    cx: "30.72",
    cy: "12.245",
    r: "3.046",
    style: {
      fill: "#fff",
      stroke: "#000",
      strokeWidth: 2.18 + "px"
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M43.007,21.461l0.007,4.312l-5.77,4.905l5.766,4.922l-0.003,4.294l18.433,-0l0,-18.433l-18.433,0",
    style: {
      fill: "#01c5bd"
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
    cx: "30.72",
    cy: "30.678",
    r: "3.046",
    style: {
      fill: "#fff",
      stroke: "#000",
      strokeWidth: 2.18 + "px"
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M18.433,58.326l-0.007,-4.311l5.77,-4.905l-5.766,-4.923l0.003,-4.293l-18.433,-0l-0,18.432l18.433,0",
    style: {
      fill: "#f12945"
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
    cx: "30.72",
    cy: "49.11",
    r: "3.046",
    style: {
      fill: "#fff",
      stroke: "#000",
      strokeWidth: 2.18 + "px"
    }
  })))));
  /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CtlIcon);
  
  /***/ }),
  
  /***/ "./src/index.js":
  /*!**********************!*\
    !*** ./src/index.js ***!
    \**********************/
  /***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
  
  __webpack_require__.r(__webpack_exports__);
  /* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./src/style.scss");
  /* harmony import */ var _block_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block.js */ "./src/block.js");
  // Include stylesheet
  
  
  // Import Click to Tweet Block
  
  
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
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
  /* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icons */ "./src/icons.js");
  
  
  const CtlLayoutType = props => {
    if (!props.attributes.layout) {
      return null;
    }
    if (props.attributes.layout == "horizontal") {
      const horizontal_img = props.LayoutImgPath + "/cool-horizontal-timeline.jpg";
      const divStyle = {
        color: 'white',
        backgroundImage: 'url(' + horizontal_img + ')',
        height: '300px',
        width: '100%'
      };
      // return <div style={divStyle} className="ctl-block-image">
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "ctl-block-image"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "ctl-block-icon"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_icons__WEBPACK_IMPORTED_MODULE_1__["default"], null)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "Story Timeline Shortcode"));
    } else {
      const vertical_img = props.LayoutImgPath + "/cool-vertical-timeline.jpg";
      const divStylev = {
        color: 'white',
        backgroundImage: 'url(' + vertical_img + ')',
        height: '300px',
        width: '100%'
      };
      // return <div style={divStylev} className="ctl-block-image">
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "ctl-block-image"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "ctl-block-icon"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_icons__WEBPACK_IMPORTED_MODULE_1__["default"], null)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "Story Timeline Shortcode"));
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
  
  
  /***/ }),
  
  /***/ "./src/images/timeline.png":
  /*!*********************************!*\
    !*** ./src/images/timeline.png ***!
    \*********************************/
  /***/ ((module, __unused_webpack_exports, __webpack_require__) => {
  
  module.exports = __webpack_require__.p + "images/timeline.27d3f3c7.png";
  
  /***/ }),
  
  /***/ "@wordpress/element":
  /*!*********************************!*\
    !*** external ["wp","element"] ***!
    \*********************************/
  /***/ ((module) => {
  
  module.exports = window["wp"]["element"];
  
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
  /******/ 	// expose the modules object (__webpack_modules__)
  /******/ 	__webpack_require__.m = __webpack_modules__;
  /******/ 	
  /************************************************************************/
  /******/ 	/* webpack/runtime/chunk loaded */
  /******/ 	(() => {
  /******/ 		var deferred = [];
  /******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
  /******/ 			if(chunkIds) {
  /******/ 				priority = priority || 0;
  /******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
  /******/ 				deferred[i] = [chunkIds, fn, priority];
  /******/ 				return;
  /******/ 			}
  /******/ 			var notFulfilled = Infinity;
  /******/ 			for (var i = 0; i < deferred.length; i++) {
  /******/ 				var [chunkIds, fn, priority] = deferred[i];
  /******/ 				var fulfilled = true;
  /******/ 				for (var j = 0; j < chunkIds.length; j++) {
  /******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
  /******/ 						chunkIds.splice(j--, 1);
  /******/ 					} else {
  /******/ 						fulfilled = false;
  /******/ 						if(priority < notFulfilled) notFulfilled = priority;
  /******/ 					}
  /******/ 				}
  /******/ 				if(fulfilled) {
  /******/ 					deferred.splice(i--, 1)
  /******/ 					var r = fn();
  /******/ 					if (r !== undefined) result = r;
  /******/ 				}
  /******/ 			}
  /******/ 			return result;
  /******/ 		};
  /******/ 	})();
  /******/ 	
  /******/ 	/* webpack/runtime/compat get default export */
  /******/ 	(() => {
  /******/ 		// getDefaultExport function for compatibility with non-harmony modules
  /******/ 		__webpack_require__.n = (module) => {
  /******/ 			var getter = module && module.__esModule ?
  /******/ 				() => (module['default']) :
  /******/ 				() => (module);
  /******/ 			__webpack_require__.d(getter, { a: getter });
  /******/ 			return getter;
  /******/ 		};
  /******/ 	})();
  /******/ 	
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
  /******/ 			if (document.currentScript)
  /******/ 				scriptUrl = document.currentScript.src
  /******/ 			if (!scriptUrl) {
  /******/ 				var scripts = document.getElementsByTagName("script");
  /******/ 				if(scripts.length) scriptUrl = scripts[scripts.length - 1].src
  /******/ 			}
  /******/ 		}
  /******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
  /******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
  /******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
  /******/ 		scriptUrl = scriptUrl.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
  /******/ 		__webpack_require__.p = scriptUrl;
  /******/ 	})();
  /******/ 	
  /******/ 	/* webpack/runtime/jsonp chunk loading */
  /******/ 	(() => {
  /******/ 		// no baseURI
  /******/ 		
  /******/ 		// object to store loaded and loading chunks
  /******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
  /******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
  /******/ 		var installedChunks = {
  /******/ 			"index": 0,
  /******/ 			"./style-index": 0
  /******/ 		};
  /******/ 		
  /******/ 		// no chunk on demand loading
  /******/ 		
  /******/ 		// no prefetching
  /******/ 		
  /******/ 		// no preloaded
  /******/ 		
  /******/ 		// no HMR
  /******/ 		
  /******/ 		// no HMR manifest
  /******/ 		
  /******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
  /******/ 		
  /******/ 		// install a JSONP callback for chunk loading
  /******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
  /******/ 			var [chunkIds, moreModules, runtime] = data;
  /******/ 			// add "moreModules" to the modules object,
  /******/ 			// then flag all "chunkIds" as loaded and fire callback
  /******/ 			var moduleId, chunkId, i = 0;
  /******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
  /******/ 				for(moduleId in moreModules) {
  /******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
  /******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
  /******/ 					}
  /******/ 				}
  /******/ 				if(runtime) var result = runtime(__webpack_require__);
  /******/ 			}
  /******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
  /******/ 			for(;i < chunkIds.length; i++) {
  /******/ 				chunkId = chunkIds[i];
  /******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
  /******/ 					installedChunks[chunkId][0]();
  /******/ 				}
  /******/ 				installedChunks[chunkId] = 0;
  /******/ 			}
  /******/ 			return __webpack_require__.O(result);
  /******/ 		}
  /******/ 		
  /******/ 		var chunkLoadingGlobal = globalThis["webpackChunkshrotcode_block_free"] = globalThis["webpackChunkshrotcode_block_free"] || [];
  /******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
  /******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
  /******/ 	})();
  /******/ 	
  /************************************************************************/
  /******/ 	
  /******/ 	// startup
  /******/ 	// Load entry module and return exports
  /******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
  /******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/index.js")))
  /******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
  /******/ 	
  /******/ })()
  ;