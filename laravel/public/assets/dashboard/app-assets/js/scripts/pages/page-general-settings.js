/*=========================================================================================
    File Name: blog-edit.js
    Description: Blog edit field select2 and quill editor JS
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
(function (window, document, $) {
    'use strict';




    var lightLogoImage = $('#light-logo-feature-image');
    var lightLogoImageText = document.getElementById('light-logo-image-text');
    var lightLogoImageInput = $('#lightLogoCustomFile');

    // Change featured image
    if (lightLogoImageInput.length) {
        $(lightLogoImageInput).on('change', function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (lightLogoImage.length) {
                    lightLogoImage.attr('src', reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
            lightLogoImageText.innerHTML = lightLogoImageInput.val();
        });
    }


    var darkLogoImage = $('#dark-logo-feature-image');
    var darkLogoImageText = document.getElementById('dark-logo-image-text');
    var darkLogoImageInput = $('#darkLogoCustomFile');

    // Change featured image
    if (darkLogoImageInput.length) {
        $(darkLogoImageInput).on('change', function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (darkLogoImage.length) {
                    darkLogoImage.attr('src', reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
            darkLogoImageText.innerHTML = darkLogoImageInput.val();
        });
    }


    var faviconImage = $('#favicon-feature-image');
    var faviconImageText = document.getElementById('favicon-image-text');
    var faviconImageInput = $('#faviconCustomFile');

    // Change featured image
    if (faviconImageInput.length) {
        $(faviconImageInput).on('change', function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (faviconImage.length) {
                   faviconImage.attr('src', reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
           faviconImageText.innerHTML =faviconImageInput.val();
        });
    }


    var dashboardLogoImage = $('#dashboard-logo-feature-image');
    var dashboardLogoImageText = document.getElementById('dashboard-logo-image-text');
    var dashboardLogoImageInput = $('#dashboardLogoCustomFile');

    // Change featured image
    if (dashboardLogoImageInput.length) {
        $(dashboardLogoImageInput).on('change', function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (dashboardLogoImage.length) {
                    dashboardLogoImage.attr('src', reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
            dashboardLogoImageText.innerHTML = dashboardLogoImageInput.val();
        });
    }

    var metaImageImage = $('#meta-image-feature-image');
    var metaImageImageText = document.getElementById('meta-image-image-text');
    var metaImageImageInput = $('#metaImageCustomFile');

    // Change featured image
    if (metaImageImageInput.length) {
        $(metaImageImageInput).on('change', function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (metaImageImage.length) {
                    metaImageImage.attr('src', reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
            metaImageImageText.innerHTML = metaImageImageInput.val();
        });
    }


    

})(window, document, jQuery);
