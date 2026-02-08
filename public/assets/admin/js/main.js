(function ($) {
    "use strict";
  
    // ==========================================
    //      Start Document Ready function
    // ==========================================
    $(document).ready(function () {
      // ============== Bootstrap Tooltip Enable Start ========
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
      tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
      });
      // =============== Bootstrap Tooltip Enable End =========
  
      // ========================== Add Attribute For Bg Image Js Start =====================
      $(".bg-img").css("background-image", function () {
        var bg = "url(" + $(this).data("background-image") + ")";
        return bg;
      });
      // ========================== Add Attribute For Bg Image Js End =====================
  
      // ================== Password Show Hide Js Start ==========
      $(".toggle-password").on("click", function () {
        $(this).toggleClass("ti-eye ti-eye-off");
        var input = $($(this).attr("id"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
      $('.show-password-btn').on('click', function(){
        var input = $(this).attr('data-input');
        if($('#' + input).attr('type') === 'password') {
          $(this).html('<i class="ti ti-eye-off"></i>');
          $('#' + input).attr('type', 'text');
        } else {
          $(this).html('<i class="ti ti-eye"></i>');
          $('#' + input).attr('type', 'password');
        }
      });
      // =============== Password Show Hide Js End =================
  
      // ========================= Change Password Modal Start ==========
      $('.modal-close').on('click', function(){
        $('.change-password-modal').removeClass('active');
      });
      $('.change-password-modal').on('click', function(e){
        if ($(e.target).is('.change-password-modal *') === false) {
          $('.change-password-modal').removeClass('active');
        }
      });
      // ========================= Change Password Modal End ==========
  
      // ========================= For Th In Small Devices Start ==========
      if ($('th').length) {
        Array.from(document.querySelectorAll('table')).forEach(table => {
          let heading = table.querySelector('thead') ? table.querySelectorAll('thead tr th') : null;
          Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
            Array.from(row.querySelectorAll('td')).forEach((column, i) => {
              if (heading && heading[i]) {
                column.setAttribute('data-label', heading[i].innerText);
              }
            });
          });
        });
      }
      // ========================= For Th In Small Devices End ==========
      
      $('.header__search__input').on('input', function () {
            var search = $(this).val().toLowerCase();
            var search_result_pane = $('.search-list');
            $(search_result_pane).html('');
            if (search.length == 0) {
            $('.search-list').addClass('d-none');
                return;
            }
            $('.search-list').removeClass('d-none');
        
            // search
            var match = $('.sidebar-menu .sidebar-link:not(.has-sub)').filter(function (idx, elem) {
                return $(elem).text().trim().toLowerCase().indexOf(search) >= 0 ? elem : null;
            }).sort();
        
            // search not found
            if (match.length == 0) {
                $(search_result_pane).append('<li class="text--muted pl-5">No search result found.</li>');
                return;
            }
        
            // search found
            match.each(function (idx, elem) {
            var parent = $(elem).parents('.sidebar-item').find('.has-sub').find('.sidebar-txt').first().text();
            if (!parent) {
                parent = `Main Menu`
            }
            parent = `<small class="d-block">${parent}</small>`
            var item_url = $(elem).attr('href') || $(elem).data('default-url');
            var item_text = $(elem).text().replace(/(\d+)/g, '').trim();
            $(search_result_pane).append(`
                <li>
                ${parent}
                <a href="${item_url}" class="fw-bold text-color--3 d-block">${item_text}</a>
                </li>
            `);
            });
        });

        var len = 0;
        var clickLink = 0;
        var search = null;
        var process = false;
        $('#searchInput').on('keydown', function(e){
          var length = $('.search-list li').length;
          if(search != $(this).val() && process){
              len = 0;
              clickLink = 0;
              $(`.search-list li:eq(${len}) a`).focus();
              $(`#searchInput`).focus();
          }
          //Down
          if(e.keyCode == 40 && length){
              process = true;
              var contra = false;
              if(len < clickLink && clickLink < length){
                  len += 2;
              }
              $(`.search-list li[class="active"]`).removeClass('active');
              $(`.search-list li a[class="text--white"]`).removeClass('text--white');
              $(`.search-list li:eq(${len}) a`).focus().addClass('text--white');
              $(`.search-list li:eq(${len})`).addClass('active');
              $(`#searchInput`).focus();
              clickLink = len;
              if(!$(`.search-list li:eq(${clickLink}) a`).length){
                  $(`.search-list li:eq(${len})`).addClass('text--white');
              }
              len += 1;
              if(length == Math.abs(clickLink)){
                  len = 0;
              }
          }
          //Up
          else if(e.keyCode == 38 && length){
              process = true;
              if(len > clickLink && len != 0){
                  len -= 2;
              }
              $(`.search-list li[class="active"]`).removeClass('active');
              $(`.search-list li a[class="text--white"]`).removeClass('text--white');
              $(`.search-list li:eq(${len}) a`).focus().addClass('text--white');
              $(`.search-list li:eq(${len})`).addClass('active');
              $(`#searchInput`).focus();
              clickLink = len;
              if(!$(`.search-list li:eq(${clickLink}) a`).length){
                  $(`.search-list li:eq(${len})`).addClass('text--white');
              }
              len -= 1;
              if(length == Math.abs(clickLink)){
                  len = 0;
              }
          }
          //Enter
          else if(e.keyCode == 13){
              e.preventDefault();
              if($(`.search-list li:eq(${clickLink}) a`).length && process){
                  $(`.search-list li:eq(${clickLink}) a`)[0].click();
              }
          }
          //Retry
          else if(e.keyCode == 8){
              len = 0;
              clickLink = 0;
              $(`.search-list li:eq(${len}) a`).focus();
              $(`#searchInput`).focus();
          }
          search = $(this).val();
        });
      
      // ========================= Overlay Scrollbar Js Start =====================
      if($('.scroll').length) {
        $('.scroll').overlayScrollbars({});
      }
      // ========================= Overlay Scrollbar Js End =====================

      // ========================= Sidebar Menu Js Start =========================
      $('.has-sub').on('click', function(){
        if ($('.sidebar-link').hasClass('has-sub')){
          $(this).toggleClass('show')
          $(this).siblings('.sidebar-dropdown-menu').slideToggle(300).parent('.sidebar-item')
          $(this).parent('.sidebar-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
          $(this).parent('.sidebar-dropdown-item').siblings().children('.sidebar-link').removeClass('show')
          $(this).parent('.sidebar-dropdown-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
        }
      });

      $(".sidebar-link").each(function() {
        var pageUrl = window.location.href.split(/[?#]/)[0];
        if (this.href == pageUrl) {
             $(this).addClass("active");
             $(this).parents(".sidebar-dropdown-menu").siblings('.sidebar-link').addClass("active");
        }
      });
      $(".sidebar-menu .active").parent().parents(".sidebar-dropdown-menu").show().siblings().addClass("show");
      $('.sidebar-toggler').on('click', function(){
        if($(window).width() > 1499) {
          $('body').toggleClass('nav-collapsed');
          $('header').toggleClass('nav-collapsed')
          $('.main-sidebar').toggleClass('collapsed');
          $('.sidebar-overlay-2').toggleClass('show');
        } else {
          $('.main-sidebar').toggleClass('expanded');
        }
      });
      $(document).on('click', function(e){
        if ($(e.target).is('.main-sidebar, .main-sidebar *, .sidebar-toggler, .sidebar-toggler *') === false) {
          $('.main-sidebar').removeClass('expanded');
        }
      });
      $('.main-sidebar').on('mouseenter', function(){
        if($('body').hasClass('nav-collapsed')) {
          $('.main-sidebar').removeClass('collapsed');
        }
      });
      $('.main-sidebar').on('mouseleave', function(){
        if($('body').hasClass('nav-collapsed')) {
          $('.main-sidebar').addClass('collapsed');
        }
      });
      $('.sidebar-link.show').siblings('.sidebar-dropdown-menu').show();

      if ($('.main-sidebar').length) {
        var activeLink = $('.sidebar-link.active:not(.has-sub)');
        var menuHeight = $('.sidebar-menu .os-viewport').height();
          if (activeLink.length) {
              $('.sidebar-menu .os-viewport').animate({
                    scrollTop: activeLink.offset().top - menuHeight / 2
              }, 0);
          }
        }
      // ========================= Sidebar Menu Js End =========================

       // ========================= Select2 Js Start =====================
        $(".select-2").select2({
          containerCssClass: ":all:",
          dropdownAutoWidth: true,
        });
        if($("select").prop('multiple') === true) {
          $(".select-2").select2({
            containerCssClass: ":all:",
            dropdownAutoWidth: true,
            tags: true,
          });
        }
        if($(".select-2").parents('.modal').length > 0) {
          var selectParent = $('.select-2').parent();
          $(".select-2").select2({
            containerCssClass: ":all:",
            dropdownParent: selectParent
          });
        }
        // ========================= Select2 Js End =====================
      
      // ========================= Image Upload With Preview Start ==========
      function updatePreviewLogo(input, file) {
        var $preview = $(input).siblings('.image-preview');
        
        if (file) {
          var reader = new FileReader();
          reader.onload = function (e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            $preview.html(img);
            $preview.addClass('active');
          }
          reader.readAsDataURL(file);
        } else {
          $preview.html('<i class="ti ti-photo-up"></i>');
          $preview.removeClass('active');
        }
      }
      $('.image-upload').on('change', function () {
        updatePreviewLogo(this, this.files[0]);
        $(this).siblings('.custom-file-input-clear').removeClass('d-none');
      });
      $('.custom-file-input-clear').on('click', function () {
        var $input = $(this).siblings('.image-upload');
        $input.val('');
        $(this).addClass('d-none');
        updatePreviewLogo($input, null);
      });
      // ========================= Image Upload With Preview End ==========

      var tr_elements = $('.custom-data-table tbody tr');

    $(document).on('input','[name=search_table]',function(){
        var search = $(this).val().toUpperCase();
        var match = tr_elements.filter(function (idx, elem) {
            return $(elem).text().trim().toUpperCase().indexOf(search) >= 0 ? elem : null;
        }).sort();
        var table_content = $('.custom-data-table tbody');
        if (match.length == 0) {
            table_content.html(`<tr><td colspan="100%" class="text-center">No data found</td></tr>`);
        }else{
            table_content.html(match);
        }
    });
    });
    // ==========================================
    //      End Document Ready function
    // ==========================================
  
    //============================ Scroll To Top Icon Js Start =========
    var btn = $(".scroll-top");
  
    $(window).scroll(function () {
      if ($(window).scrollTop() > 300) {
        btn.addClass("show");
      } else {
        btn.removeClass("show");
      }
    });
  
    btn.on("click", function (e) {
      e.preventDefault();
      $("html, body").animate({ scrollTop: 0 }, "300");
    });
    //========================= Scroll To Top Icon Js End ======================
  })(jQuery);