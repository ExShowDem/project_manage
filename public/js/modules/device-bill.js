/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/device/bill/List.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/device/bill/List.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'List',
  data: function data() {
    return {
      items: {
        data: [],
        meta: {}
      },
      role_action: {
        can_approve: false,
        can_create: false,
        can_delete: false,
        can_update: false,
        is_admin: false
      },
      searchOption: {}
    };
  },
  created: function created() {
    this.getItems();
    this.select2ProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn dự án...'
    });
  },
  methods: {
    getItems: function getItems() {
      var _this = this;

      var params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
        'project_id': this.currentProjectId
      };
      axios.get(route('api.devices.bill.index'), {
        params: params
      }).then(function (res) {
        _this.items = res.data;
        _this.role_action = res.role_action;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/device/bill/List.vue?vue&type=template&id=537a1638&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/device/bill/List.vue?vue&type=template&id=537a1638& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "portlet light bordered" }, [
    _c("div", { staticClass: "portlet-title" }, [
      _c("div", { staticClass: "caption" }, [
        _c("div", { staticClass: "portlet-input input-inline input-small" }, [
          _c("div", { staticClass: "input-icon right" }, [
            _c("i", {
              staticClass: "icon-magnifier",
              on: {
                click: function($event) {
                  return _vm.getItems()
                }
              }
            }),
            _vm._v(" "),
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.searchOption.keyword,
                  expression: "searchOption.keyword"
                }
              ],
              staticClass: "form-control",
              attrs: { type: "text", placeholder: "Tìm kiếm..." },
              domProps: { value: _vm.searchOption.keyword },
              on: {
                keyup: function($event) {
                  if (
                    !$event.type.indexOf("key") &&
                    _vm._k($event.keyCode, "enter", 13, $event.key, "Enter")
                  ) {
                    return null
                  }
                  return _vm.getItems()
                },
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(_vm.searchOption, "keyword", $event.target.value)
                }
              }
            })
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "portlet-input input-inline input-small" }, [
          _c(
            "div",
            { staticClass: "input-icon right" },
            [
              _c("i", { staticClass: "fa fa-calendar" }),
              _vm._v(" "),
              _c("date-picker", {
                attrs: { config: _vm.datepickerOptions },
                on: {
                  "dp-change": function($event) {
                    return _vm.getItems()
                  }
                },
                model: {
                  value: _vm.searchOption.from_date,
                  callback: function($$v) {
                    _vm.$set(_vm.searchOption, "from_date", $$v)
                  },
                  expression: "searchOption.from_date"
                }
              })
            ],
            1
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "portlet-input input-inline input-small" }, [
          _c(
            "div",
            { staticClass: "input-icon right" },
            [
              _c("i", { staticClass: "fa fa-calendar" }),
              _vm._v(" "),
              _c("date-picker", {
                attrs: { config: _vm.datepickerOptions },
                on: {
                  "dp-change": function($event) {
                    return _vm.getItems()
                  }
                },
                model: {
                  value: _vm.searchOption.till_date,
                  callback: function($$v) {
                    _vm.$set(_vm.searchOption, "till_date", $$v)
                  },
                  expression: "searchOption.till_date"
                }
              })
            ],
            1
          )
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "portlet-input input-inline input-small" },
          [
            _c("select2", {
              attrs: { settings: _vm.select2ProjectOptions },
              on: {
                select: function($event) {
                  return _vm.getItems()
                }
              },
              model: {
                value: _vm.searchOption.project,
                callback: function($$v) {
                  _vm.$set(_vm.searchOption, "project", $$v)
                },
                expression: "searchOption.project"
              }
            })
          ],
          1
        )
      ])
    ]),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "portlet-body" },
      [
        _c("div", { staticClass: "table-scrollable" }, [
          _c("table", { staticClass: "table table-hover" }, [
            _vm._m(0),
            _vm._v(" "),
            _c(
              "tbody",
              _vm._l(_vm.items.data, function(item, key) {
                return _c("tr", { key: key }, [
                  _c("td", [_vm._v(_vm._s(key + 1))]),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(item.device_code))]),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(item.device_name))]),
                  _vm._v(" "),
                  _c("td", [
                    _vm._v(
                      _vm._s(
                        _vm._f("thousand_seperator")(
                          item.total_quantity.toFixed(1)
                        )
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c("td", [
                    _c("table", { staticClass: "table table-hover" }, [
                      _vm._m(1, true),
                      _vm._v(" "),
                      _c(
                        "tbody",
                        [
                          _vm._l(
                            item.transfers ? item.transfers.projects : [],
                            function(project, key1) {
                              return _c("tr", { key: key1 }, [
                                _c("td", [_vm._v(_vm._s(project.name))]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "table",
                                    { staticClass: "table table-hover" },
                                    [
                                      _vm._m(2, true),
                                      _vm._v(" "),
                                      _c(
                                        "tbody",
                                        _vm._l(project.info, function(
                                          info,
                                          key2
                                        ) {
                                          return _c("tr", { key: key2 }, [
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(info.type_label) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " + _vm._s(info.unit) + " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " + _vm._s(info.date) + " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(info.days_used) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("to_ndp")(
                                                        info.quantity
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("to_ndp")(
                                                        info.unit_price
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("round_whole")(
                                                        info.total_price
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ])
                                          ])
                                        }),
                                        0
                                      )
                                    ]
                                  )
                                ])
                              ])
                            }
                          ),
                          _vm._v(" "),
                          _vm._l(
                            item.inputs ? item.inputs.projects : [],
                            function(project, key1) {
                              return _c("tr", { key: key1 }, [
                                _c("td", [_vm._v(_vm._s(project.name))]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "table",
                                    { staticClass: "table table-hover" },
                                    [
                                      _vm._m(3, true),
                                      _vm._v(" "),
                                      _c(
                                        "tbody",
                                        _vm._l(project.info, function(
                                          info,
                                          key2
                                        ) {
                                          return _c("tr", { key: key2 }, [
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(info.type_label) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " + _vm._s(info.unit) + " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " + _vm._s(info.date) + " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(info.days_used) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("to_ndp")(
                                                        info.quantity
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("to_ndp")(
                                                        info.unit_price
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("round_whole")(
                                                        info.total_price
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ])
                                          ])
                                        }),
                                        0
                                      )
                                    ]
                                  )
                                ])
                              ])
                            }
                          ),
                          _vm._v(" "),
                          _vm._l(
                            item.deletes ? item.deletes.projects : [],
                            function(project, key1) {
                              return _c("tr", { key: key1 }, [
                                _c("td", [_vm._v(_vm._s(project.name))]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "table",
                                    { staticClass: "table table-hover" },
                                    [
                                      _vm._m(4, true),
                                      _vm._v(" "),
                                      _c(
                                        "tbody",
                                        _vm._l(project.info, function(
                                          info,
                                          key2
                                        ) {
                                          return _c("tr", { key: key2 }, [
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(info.type_label) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " + _vm._s(info.unit) + " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " + _vm._s(info.date) + " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(info.days_used) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("to_ndp")(
                                                        info.quantity
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("to_ndp")(
                                                        info.unit_price
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ]),
                                            _vm._v(" "),
                                            _c("td", [
                                              _vm._v(
                                                " " +
                                                  _vm._s(
                                                    _vm._f(
                                                      "thousand_seperator"
                                                    )(
                                                      _vm._f("round_whole")(
                                                        info.total_price
                                                      )
                                                    )
                                                  ) +
                                                  " "
                                              )
                                            ])
                                          ])
                                        }),
                                        0
                                      )
                                    ]
                                  )
                                ])
                              ])
                            }
                          )
                        ],
                        2
                      )
                    ])
                  ])
                ])
              }),
              0
            )
          ])
        ]),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass: "pull-right",
            staticStyle: { "font-weight": "bold", color: "red" }
          },
          [_c("p", [_vm._v(_vm._s(_vm.items.meta.total) + " kết quả")])]
        ),
        _vm._v(" "),
        _c("vue-pagination", {
          attrs: { pagination: _vm.items.meta },
          on: {
            paginate: function($event) {
              return _vm.getItems()
            }
          }
        })
      ],
      1
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v(" Stt")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Mã thiết bị")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Tên thiết bị")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Tổng Số lượng")]),
        _vm._v(" "),
        _c("th")
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [_c("th", [_vm._v(" Dự án")]), _vm._v(" "), _c("th")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v(" Loại phát sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Đơn vị tính")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Ngày phát sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Số ngày sử dụng")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Số lượng")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Đơn giá")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Thành tiền")])
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v(" Loại phát sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Đơn vị tính")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Ngày phát sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Số ngày sử dụng")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Số lượng")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Đơn giá")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Thành tiền")])
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v(" Loại phát sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Đơn vị tính")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Ngày phát sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Số ngày sử dụng")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Số lượng")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Đơn giá")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Thành tiền")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/assets/js/components/device/bill/List.vue":
/*!*************************************************************!*\
  !*** ./resources/assets/js/components/device/bill/List.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _List_vue_vue_type_template_id_537a1638___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./List.vue?vue&type=template&id=537a1638& */ "./resources/assets/js/components/device/bill/List.vue?vue&type=template&id=537a1638&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List.vue?vue&type=script&lang=js& */ "./resources/assets/js/components/device/bill/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_537a1638___WEBPACK_IMPORTED_MODULE_0__["render"],
  _List_vue_vue_type_template_id_537a1638___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/js/components/device/bill/List.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/js/components/device/bill/List.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/assets/js/components/device/bill/List.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/device/bill/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/js/components/device/bill/List.vue?vue&type=template&id=537a1638&":
/*!********************************************************************************************!*\
  !*** ./resources/assets/js/components/device/bill/List.vue?vue&type=template&id=537a1638& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_537a1638___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=template&id=537a1638& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/device/bill/List.vue?vue&type=template&id=537a1638&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_537a1638___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_537a1638___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/js/modules/device-bill.js":
/*!****************************************************!*\
  !*** ./resources/assets/js/modules/device-bill.js ***!
  \****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_device_bill_List_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/device/bill/List.vue */ "./resources/assets/js/components/device/bill/List.vue");

Vue.component('device-bill-list', _components_device_bill_List_vue__WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ }),

/***/ 10:
/*!**********************************************************!*\
  !*** multi ./resources/assets/js/modules/device-bill.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\pmmo\resources\assets\js\modules\device-bill.js */"./resources/assets/js/modules/device-bill.js");


/***/ })

/******/ });