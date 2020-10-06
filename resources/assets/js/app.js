
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
if (typeof App === 'object') {

  App.showProgress = (message) => {
    var messageBar = jQuery('#loading');
    messageBar.removeClass('hide');
    var messageHTML='';
    if(message !== undefined) {
      messageHTML = '<div class="message"><span>'+message+'</span></div>';
    }
    messageBar.html('<div style="text-align:center;position:fixed;top:50%;left:40%;"><img src="/assets/admin/images/loading2.gif">'+ messageHTML +'</div>');
  }

  App.hideProgress = () => {
    var messageBar = jQuery('#loading');
    messageBar.addClass('hide');
  }

  App.autoActiveMenu = () => {
    let currentUrl = window.location.href;
    $('.page-sidebar-menu > .nav-item').each(function (index) {
      let $root = $(this);
      if ($root.children('ul.sub-menu').length) {
        $root.find('ul.sub-menu > .nav-item').each(function () {
          if (currentUrl.includes($(this).children('a').attr('href'))) {
            $(this).addClass('active');
            $root.addClass('active open');
            $root.find('.nav-link > span.arrow').addClass('open')
          }
        })
      } else {
        if (currentUrl.includes($(this).children('a').attr('href'))) {
          $(this).addClass('active');
        }
      }
    });
  }

  $(function () {
    App.autoActiveMenu();
  });
}

let access_token = document.head.querySelector('meta[name="token"]');
window.token = access_token.content;
axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.token;
axios.interceptors.request.use(function (config) {
    if (config.dontDisableScreen) {
      
    } else {
      App.showProgress();
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});
axios.interceptors.response.use(function (response) {
  App.hideProgress();
  if (!response.headers['content-disposition']) {
    let data = response.data;
    /*if (data.hasOwnProperty('code') && data.code !== 0 && data.code !== 2) {
      this.alertError('Something went wrong!');
    }*/

    return data;
  }

  return response;
}, function (error) {
  return Promise.reject(error);
});

import qs from 'qs';
axios.defaults.paramsSerializer = function (params) {
  return qs.stringify(params);
};

window.currentUser = $.parseJSON($('input[name=current_user]').val());
