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
/******/ 	return __webpack_require__(__webpack_require__.s = 52);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/Form.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/user/Form.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'UserForm',
  props: ['id'],
  data: function data() {
    return {
      item: {
        roles: [],
        projects: []
      },
      selectedRoles: [],
      selectedProjects: [],
      errors: {},
      select2RolesOptions: this.getSelect2Settings({
        url: route('api.select2.roles'),
        field_name: 'name',
        placeholder: 'Chọn vai trò...',
        term_name: 'search_option[keyword]',
        multiple: true
      }),
      select2ProjectsOptions: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
        multiple: true
      }),
      imgUrl: '',
      uploadImg: null
    };
  },
  created: function created() {
    var _this = this;

    if (this.id !== undefined) {
      axios.get(route('api.users.show', this.id)).then(function (res) {
        _this.item = res.data.item;
        _this.selectedRoles = _this.item.roles.map(function (role) {
          return {
            id: role.id,
            text: role.name
          };
        });
        _this.item['roles'] = _this.item.roles.map(function (role) {
          return role.id;
        });
        _this.selectedProjects = _this.item.projects.map(function (project) {
          return {
            id: project.id,
            text: project.name
          };
        });
        _this.item['projects'] = _this.item.projects.map(function (project) {
          return project.id;
        });
        _this.imgUrl = Ziggy.baseUrl + 'storage/images/avatars/' + _this.item.image;
      });
    }
  },
  methods: {
    filesChange: function filesChange(fileList) {
      if (!fileList.length) return; // For preview

      var reader = new FileReader();
      var vm = this;

      reader.onload = function (e) {
        vm.imgUrl = e.target.result;
      };

      reader.readAsDataURL(fileList[0]);
      this.uploadImg = fileList[0];
    },
    submitAdd: function submitAdd() {
      var _this2 = this;

      if (this.item.password !== this.item.password_confirm) {
        this.errors = {
          user: ['Mật khẩu xác nhận không đúng']
        };
        return;
      }

      var form_data = new FormData();

      for (var key in this.item) {
        if (key !== 'image' && key !== 'roles' && key !== 'projects') {
          form_data.append(key, this.item[key]);
        }

        if (key === 'roles' || key === 'projects') {
          form_data.append(key, JSON.stringify(this.item[key]));
        }
      }

      if (this.uploadImg) {
        form_data.append('image', this.uploadImg);
      }

      form_data.append('current_project_id', this.currentProjectId);
      var config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      };
      this.errors = {};
      axios.post(route('api.users.store'), form_data, config).then(function (res) {
        var result = res;

        if (result.code === 2) {
          console.dir(result.data.errors);
          _this2.errors = result.data.errors;
        }

        if (result.code === 0) {
          _this2.$swal('', 'Tạo tài khoản thành công!', 'success').then(function () {
            window.location.href = _this2.currentProjectId ? route('admin.projects.users.index', _this2.currentProjectId) : route('admin.users.index');
          });
        }
      });
    },
    submitEdit: function submitEdit() {
      var _this3 = this;

      var form_data = new FormData();

      for (var key in this.item) {
        if (key !== 'image' && key !== 'roles' && key !== 'projects') {
          form_data.append(key, this.item[key]);
        }

        if (key === 'roles' || key === 'projects') {
          form_data.append(key, JSON.stringify(this.item[key]));
        }
      }

      if (this.uploadImg) {
        form_data.append('image', this.uploadImg);
      }

      form_data.append('current_project_id', this.currentProjectId);
      form_data.append('_method', 'PUT');
      var config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      };
      this.errors = {};
      axios.post(route('api.users.update', this.id), form_data, config).then(function (res) {
        var result = res;

        if (result.code === 2) {
          _this3.errors = result.data.errors;
        }

        if (result.code === 0) {
          _this3.$swal('', 'Sửa tài khoản thành công!', 'success').then(function () {
            window.location.href = _this3.currentProjectId ? route('admin.projects.users.index', _this3.currentProjectId) : route('admin.users.index');
          });
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/List.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/user/List.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
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
      searchOption: {},
      imgBaseUrl: ''
    };
  },
  created: function created() {
    this.getItems();
    this.select2ProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn dự án...'
    });
    this.select2RoleOptions = this.getSelect2Settings({
      url: route('api.select2.roles'),
      field_name: 'name',
      placeholder: 'Chọn vai trò...'
    });
    this.imgBaseUrl = Ziggy.baseUrl + 'storage/images/avatars/';
  },
  methods: {
    getItems: function getItems() {
      var _this = this;

      this.searchOption.project_id = this.currentProjectId;
      var params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page
      };
      axios.get(route('api.users.index'), {
        params: params
      }).then(function (res) {
        _this.items = res.data;
        _this.role_action = res.role_action;
      });
    },
    deleteItem: function deleteItem(id) {
      var _this2 = this;

      this.confirmDelete().then(function (result) {
        if (result.value) {
          axios["delete"](route('api.users.destroy', id)).then(function (res) {
            if (res.code == 0) {
              _this2.getItems();
            }
          });
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/Form.vue?vue&type=template&id=6fb788a1&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/user/Form.vue?vue&type=template&id=6fb788a1& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
      _vm.id === undefined
        ? _c("div", { staticClass: "caption" }, [
            _c("i", { staticClass: "fa fa-plus font-green-haze" }),
            _vm._v(" "),
            _c(
              "span",
              { staticClass: "caption-subject font-green-haze bold uppercase" },
              [_vm._v("Tạo tài khoản")]
            )
          ])
        : _c("div", { staticClass: "caption" }, [
            _c("i", { staticClass: "fa fa-pencil font-green-haze" }),
            _vm._v(" "),
            _c(
              "span",
              { staticClass: "caption-subject font-green-haze bold uppercase" },
              [_vm._v("Sửa tài khoản")]
            )
          ])
    ]),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "portlet-body form" },
      [
        _c("vue-error-message", { attrs: { errors: _vm.errors } }),
        _vm._v(" "),
        _c(
          "form",
          {
            staticClass: "form-horizontal form-row-seperated form-label-left",
            attrs: { action: "#" }
          },
          [
            _c("div", { staticClass: "form-body" }, [
              _c("div", { staticClass: "form-group" }, [
                _c("label", { staticClass: "control-label col-md-3" }, [
                  _vm._v("Tên *")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-9" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.item.name,
                        expression: "item.name"
                      }
                    ],
                    staticClass: "form-control",
                    attrs: { type: "text", placeholder: "Tên Tài Khoản" },
                    domProps: { value: _vm.item.name },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.item, "name", $event.target.value)
                      }
                    }
                  })
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-body" }, [
              _c("div", { staticClass: "form-group" }, [
                _c("label", { staticClass: "control-label col-md-3" }, [
                  _vm._v("Email *")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-9" }, [
                  _vm.id === undefined
                    ? _c("div", [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.item.email,
                              expression: "item.email"
                            }
                          ],
                          staticClass: "form-control",
                          attrs: { type: "email", placeholder: "Email" },
                          domProps: { value: _vm.item.email },
                          on: {
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.$set(_vm.item, "email", $event.target.value)
                            }
                          }
                        })
                      ])
                    : _c("div", [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.item.email,
                              expression: "item.email"
                            }
                          ],
                          staticClass: "form-control",
                          attrs: {
                            type: "email",
                            placeholder: "Email",
                            disabled: ""
                          },
                          domProps: { value: _vm.item.email },
                          on: {
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.$set(_vm.item, "email", $event.target.value)
                            }
                          }
                        })
                      ])
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-body" }, [
              _c("div", { staticClass: "form-group" }, [
                _c("label", { staticClass: "control-label col-md-3" }, [
                  _vm._v("Vai trò")
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-9" },
                  [
                    _c("select2", {
                      attrs: {
                        settings: _vm.select2RolesOptions,
                        selected: _vm.selectedRoles
                      },
                      model: {
                        value: _vm.item.roles,
                        callback: function($$v) {
                          _vm.$set(_vm.item, "roles", $$v)
                        },
                        expression: "item.roles"
                      }
                    })
                  ],
                  1
                )
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-body" }, [
              _c("div", { staticClass: "form-group" }, [
                _c("label", { staticClass: "control-label col-md-3" }, [
                  _vm._v("Mật khẩu")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-9" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.item.password,
                        expression: "item.password"
                      }
                    ],
                    staticClass: "form-control",
                    attrs: { type: "password" },
                    domProps: { value: _vm.item.password },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.item, "password", $event.target.value)
                      }
                    }
                  })
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-body" }, [
              _c("div", { staticClass: "form-group" }, [
                _c("label", { staticClass: "control-label col-md-3" }, [
                  _vm._v("Xác nhận mật khẩu")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-9" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.item.password_confirm,
                        expression: "item.password_confirm"
                      }
                    ],
                    staticClass: "form-control",
                    attrs: { type: "password" },
                    domProps: { value: _vm.item.password_confirm },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.item,
                          "password_confirm",
                          $event.target.value
                        )
                      }
                    }
                  })
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-body" }, [
              _c("div", { staticClass: "form-group" }, [
                _c("label", { staticClass: "control-label col-md-3" }, [
                  _vm._v("Ảnh")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-9" }, [
                  _vm.item.image
                    ? _c("img", {
                        staticClass: "img-thumbnail",
                        attrs: { src: _vm.imgUrl, alt: "" }
                      })
                    : _c("img", {
                        staticClass: "img-thumbnail",
                        attrs: {
                          src:
                            "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",
                          alt: ""
                        }
                      }),
                  _vm._v(" "),
                  _c("input", {
                    staticClass: "form-control",
                    attrs: {
                      type: "file",
                      placeholder: "Ảnh",
                      accept: "image/*"
                    },
                    on: {
                      change: function($event) {
                        return _vm.filesChange($event.target.files)
                      }
                    }
                  })
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "form-actions" }, [
              _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "col-md-offset-3 col-md-9" }, [
                  _vm.id === undefined
                    ? _c(
                        "button",
                        {
                          staticClass: "btn green",
                          attrs: { type: "button" },
                          on: {
                            click: function($event) {
                              return _vm.submitAdd()
                            }
                          }
                        },
                        [
                          _c("i", { staticClass: "fa fa-plus" }),
                          _vm._v(" Tạo\n            ")
                        ]
                      )
                    : _c(
                        "button",
                        {
                          staticClass: "btn green",
                          attrs: { type: "button" },
                          on: {
                            click: function($event) {
                              return _vm.submitEdit()
                            }
                          }
                        },
                        [
                          _c("i", { staticClass: "fa fa-pencil" }),
                          _vm._v(" Sửa\n            ")
                        ]
                      ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "btn default",
                      attrs: {
                        href: _vm.currentProjectId
                          ? _vm.route(
                              "admin.projects.users.index",
                              _vm.currentProjectId
                            )
                          : _vm.route("admin.users.index")
                      }
                    },
                    [_vm._v("Hủy")]
                  )
                ])
              ])
            ])
          ]
        )
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/List.vue?vue&type=template&id=b89ea10a&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/user/List.vue?vue&type=template&id=b89ea10a&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
                  value: _vm.searchOption.date_from,
                  callback: function($$v) {
                    _vm.$set(_vm.searchOption, "date_from", $$v)
                  },
                  expression: "searchOption.date_from"
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
                  value: _vm.searchOption.date_till,
                  callback: function($$v) {
                    _vm.$set(_vm.searchOption, "date_till", $$v)
                  },
                  expression: "searchOption.date_till"
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
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "portlet-input input-inline input-small" },
          [
            _c("select2", {
              attrs: { settings: _vm.select2RoleOptions },
              on: {
                select: function($event) {
                  return _vm.getItems()
                }
              },
              model: {
                value: _vm.searchOption.role,
                callback: function($$v) {
                  _vm.$set(_vm.searchOption, "role", $$v)
                },
                expression: "searchOption.role"
              }
            })
          ],
          1
        )
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "actions" }, [
        _c(
          "a",
          {
            staticClass: "btn btn-success",
            attrs: {
              href: _vm.currentProjectId
                ? _vm.route("admin.projects.users.create", _vm.currentProjectId)
                : _vm.route("admin.users.create")
            }
          },
          [
            _c("i", { staticClass: "fa fa-plus" }),
            _vm._v(" Tạo tài khoản\n      ")
          ]
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
                  _c("td", [
                    _vm._v(
                      _vm._s(
                        key +
                          1 +
                          (_vm.items.meta.current_page - 1) *
                            _vm.items.meta.per_page
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c("td", [
                    item.image
                      ? _c("img", {
                          staticClass: "img-thumbnail",
                          attrs: { src: _vm.imgBaseUrl + item.image, alt: "" }
                        })
                      : _c("img", {
                          staticClass: "img-thumbnail",
                          attrs: {
                            src:
                              "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",
                            alt: ""
                          }
                        })
                  ]),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(item.name))]),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(item.email))]),
                  _vm._v(" "),
                  _c(
                    "td",
                    _vm._l(item.roles, function(role, roleKey) {
                      return _c(
                        "div",
                        { key: roleKey, staticClass: "margin-bottom-10" },
                        [
                          _c("div", { staticClass: "label label-danger" }, [
                            _vm._v(
                              "\n                  " +
                                _vm._s(role.name) +
                                "\n                "
                            )
                          ])
                        ]
                      )
                    }),
                    0
                  ),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(item.created_at))]),
                  _vm._v(" "),
                  _c("td", [
                    _c("div", [
                      _vm.role_action.can_update
                        ? _c(
                            "a",
                            {
                              staticClass: "btn blue btn-xs btn-outline",
                              attrs: {
                                href: _vm.currentProjectId
                                  ? _vm.route("admin.projects.users.edit", {
                                      projectId: _vm.currentProjectId,
                                      user: item.id
                                    })
                                  : _vm.route("admin.users.edit", item.id)
                              }
                            },
                            [_c("i", { staticClass: "fa fa-pencil" })]
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.role_action.can_delete
                        ? _c(
                            "button",
                            {
                              staticClass: "btn red btn-xs btn-outline",
                              attrs: { type: "button" },
                              on: {
                                click: function($event) {
                                  return _vm.deleteItem(item.id)
                                }
                              }
                            },
                            [_c("i", { staticClass: "fa fa-trash" })]
                          )
                        : _vm._e()
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
          { staticClass: "pull-right", staticStyle: { "font-weight": "bold" } },
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
        _c("th", [_vm._v(" #")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Ảnh")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Tên")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Email")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Vai trò")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Ngày tạo")]),
        _vm._v(" "),
        _c("th", [_vm._v(" Hành động")])
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

/***/ "./resources/assets/js/components/user/Form.vue":
/*!******************************************************!*\
  !*** ./resources/assets/js/components/user/Form.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Form_vue_vue_type_template_id_6fb788a1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Form.vue?vue&type=template&id=6fb788a1& */ "./resources/assets/js/components/user/Form.vue?vue&type=template&id=6fb788a1&");
/* harmony import */ var _Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Form.vue?vue&type=script&lang=js& */ "./resources/assets/js/components/user/Form.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Form_vue_vue_type_template_id_6fb788a1___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Form_vue_vue_type_template_id_6fb788a1___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/js/components/user/Form.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/js/components/user/Form.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/assets/js/components/user/Form.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Form.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/Form.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/js/components/user/Form.vue?vue&type=template&id=6fb788a1&":
/*!*************************************************************************************!*\
  !*** ./resources/assets/js/components/user/Form.vue?vue&type=template&id=6fb788a1& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_template_id_6fb788a1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Form.vue?vue&type=template&id=6fb788a1& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/Form.vue?vue&type=template&id=6fb788a1&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_template_id_6fb788a1___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_template_id_6fb788a1___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/js/components/user/List.vue":
/*!******************************************************!*\
  !*** ./resources/assets/js/components/user/List.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _List_vue_vue_type_template_id_b89ea10a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./List.vue?vue&type=template&id=b89ea10a&scoped=true& */ "./resources/assets/js/components/user/List.vue?vue&type=template&id=b89ea10a&scoped=true&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List.vue?vue&type=script&lang=js& */ "./resources/assets/js/components/user/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_b89ea10a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _List_vue_vue_type_template_id_b89ea10a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "b89ea10a",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/js/components/user/List.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/js/components/user/List.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/assets/js/components/user/List.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/js/components/user/List.vue?vue&type=template&id=b89ea10a&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/assets/js/components/user/List.vue?vue&type=template&id=b89ea10a&scoped=true& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_b89ea10a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=template&id=b89ea10a&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/user/List.vue?vue&type=template&id=b89ea10a&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_b89ea10a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_b89ea10a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/js/modules/user.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/modules/user.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_user_Form_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/user/Form.vue */ "./resources/assets/js/components/user/Form.vue");
/* harmony import */ var _components_user_List__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/user/List */ "./resources/assets/js/components/user/List.vue");


Vue.component('user-list', _components_user_List__WEBPACK_IMPORTED_MODULE_1__["default"]);
Vue.component('user-form', _components_user_Form_vue__WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ }),

/***/ 52:
/*!***************************************************!*\
  !*** multi ./resources/assets/js/modules/user.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\pmmo\resources\assets\js\modules\user.js */"./resources/assets/js/modules/user.js");


/***/ })

/******/ });