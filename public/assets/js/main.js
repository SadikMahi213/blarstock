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

    // ============== Header Hide Click On Body Js Start ========
    $(".header-button").on("click", function () {
      $(".body-overlay").toggleClass("show");
    });
    $(".body-overlay").on("click", function () {
      $(".header-button").trigger("click");
      $(this).removeClass("show");
    });
    // =============== Header Hide Click On Body Js End =========

    // ========================== Header Hide Scroll Bar Js Start =====================
    $(".navbar-toggler.header-button").on("click", function () {
      $("body").toggleClass("scroll-hide-sm");
    });
    $(".body-overlay").on("click", function () {
      $("body").removeClass("scroll-hide-sm");
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $(".dropdown-item").on("click", function () {
      $(this).closest(".dropdown-menu").addClass("d-block");
    });
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

    // ========================== Add Attribute For Bg Image Js Start =====================
    $(".bg-img").css("background-image", function () {
      var bg = "url(" + $(this).data("background-image") + ")";
      return bg;
    });
    // ========================== Add Attribute For Bg Image Js End =====================

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on("click", function () {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("id"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    // =============== Sidebar Menu Js Start ===============
    $('.has-sub').on('click', function(){
      if ($('.sidebar-link').hasClass('has-sub')){
        $(this).toggleClass('show')
        $(this).siblings('.sidebar-dropdown-menu').slideToggle(300).parent('.sidebar-item')
        $(this).parent('.sidebar-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
        $(this).parent('.sidebar-dropdown-item').siblings().children('.sidebar-link').removeClass('show')
        $(this).parent('.sidebar-dropdown-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
      }
    })
    $(".sidebar-link").each(function() {
      var pageUrl = window.location.href.split(/[?#]/)[0];
        if (this.href == pageUrl) {
          $(this).addClass("active");
          $(this).parents(".sidebar-item").addClass("open")
      }
    })
    $(".sidebar-menu .active").parent().parents(".sidebar-dropdown-menu").show().siblings().addClass("show")
    // =============== Sidebar Menu Js End ===============

    // ========================= Refer Link Copy Start ==========
    $('.refer-link__copy').on('click', function(){
      var inputElement = $('#referLink');
      inputElement.select();
      document.execCommand('copy');
      $('.refer-link__badge').addClass('show');
      setTimeout(function () {
        $('.refer-link__badge').removeClass('show');
      }, 1500);
    });
    // ========================= Refer Link Copy End ==========

    // ========================= Account Setup Key Copy Start ==========
    $('.account-setup-key__copy').on('click', function(){
      var inputElement = $('#accountSetupKey');
      inputElement.select();
      document.execCommand('copy');
      $('.account-setup-key__badge').addClass('show');
      setTimeout(function () {
        $('.account-setup-key__badge').removeClass('show');
      }, 1500);
    });
    // ========================= Account Setup Key Copy End ==========

    // ========================= Image Upload With Preview Start ==========
    function updatePreviewLogo(file) {
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          var img = document.createElement('img');
          img.src = e.target.result;
          $('.image-preview').html(img);
          $('.image-preview').addClass('active');
        }
        reader.readAsDataURL(file);
      } else {
        $('.image-preview').html('+');
        $('.image-preview').removeClass('active');
      }
    }
    $('#imageUpload').change(function () {
      updatePreviewLogo(this.files[0]);
    });
    $('#imageUpload').on('click', '.custom-file-input-clear', function () {
      updatePreviewLogo(null);
    });
    // ========================= Image Upload With Preview End ==========

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

    // ========================= Multistep Form / Wizard Form Start =========================
    $('.next-step').on('click', function () {
      var stepCard = $(this).closest('.step__card');
      var invalidFields = stepCard.find(':required:invalid');

      if (invalidFields.length === 0) {
        stepCard.addClass('hide').removeClass('show');
        setTimeout(function () {
          stepCard.addClass('d-none').next('.step__card').removeClass('d-none');
          var currentStepIndex = stepCard.index();
          $('.step__list li').eq(currentStepIndex + 1).addClass('active');
          stepCard.next('.step__card').addClass('show').removeClass('hide');
        }, 300);
      } else {
        invalidFields[0].reportValidity();
      }
    });
    $('.prev-step').on('click', function(){
      var stepCard = $(this).closest('.step__card');
      stepCard.addClass('show').removeClass('hide');
      setTimeout(function () {
        stepCard.addClass('d-none').prev('.step__card').removeClass('d-none');
        var currentStepIndex = stepCard.index();
        $('.step__list li').eq(currentStepIndex).removeClass('active');
        stepCard.addClass('hide').removeClass('show').prev('.step__card').removeClass('hide').addClass('show');
      }, 300);
    });
    $('[name=buySellSelect]').on('change', function() {
      if ($('#wantToSell').is(':checked')) {
        $('.sell-term').removeClass('d-none');
      } else {
        $('.sell-term').addClass('d-none');
      }
    });
    $('#priceType').on('change', function(){
      if($('[value=fixed]').is(':selected')) {
        $('.price-result').addClass('d-none');
      } else {
        $('.price-result').removeClass('d-none');
      }
    });
    // ========================= Multistep Form / Wizard Form End =========================

    // ========================= Chat Scroll Start =========================
    var scrollableElement = $(".trade-chat__chatbox");
    scrollableElement.scrollTop(scrollableElement.prop("scrollHeight"));
    // ========================= Chat Scroll End =========================

    // ========================= Remaining Character Show of Textarea Start ==========
    $('#feedbackDetails').on('keyup', function(){
      var charCount = $(this).val().length;
      $('#leftCharacter').text(500 - charCount);
    });
    // ========================= Remaining Character Show of Textarea End ==========
    
    // ========================= Countdown Timer Start ==========
    const remainingTimeSpan = $('#remainingTime');
    const remainingMinutes = parseInt(remainingTimeSpan.attr('data-reamining-minute'));
    let remainingSeconds = remainingMinutes * 60;
    function updateTimeDisplay() {
      const minutes = Math.floor(remainingSeconds / 60);
      const seconds = remainingSeconds % 60;
      const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
      remainingTimeSpan.text(formattedTime);
    }
    function updateTimeProgress() {
      const percentageRemaining = (remainingSeconds / (remainingMinutes * 60)) * 100;
      if (percentageRemaining <= 10) {
        remainingTimeSpan.removeClass('bg--warning').addClass('bg--danger');
      } else if (percentageRemaining <= 50) {
        remainingTimeSpan.removeClass('bg--danger').addClass('bg--warning');
      } else {
        remainingTimeSpan.removeClass('bg--warning bg--danger');
      }
    }
    function countdown() {
      remainingSeconds--;
      updateTimeDisplay();
      updateTimeProgress();
      if (remainingSeconds > 0) {
        setTimeout(countdown, 1000);
      }
    }
    updateTimeDisplay();
    updateTimeProgress();
    countdown();
    // ========================= Countdown Timer End ==========
    
    // ========================= Table Filter Panel Collapse Start ==========
    $('.filter-btn').on('click', function(){
      var filterForm = $(this).data('filter-form');
      $('#'+filterForm).slideToggle();
    });
    // ========================= Table Filter Panel Collapse End ==========
    
    // ========================= Bootstrap Dropdown as Select Start ==========
    $('.select-dropdown').each(function(){
      var buttonText = $(this).find('.dropdown-item.active').text();
      $(this).find('.dropdown-toggle').text(buttonText);
    });
    $('.select-dropdown .dropdown-item').on('click', function(){
      $(this).closest('.dropdown-menu').siblings('.dropdown-toggle').text($(this).text());
    });
    // ========================= Bootstrap Dropdown as Select End ==========
    
    // ========================= Height Of Textare For Chatting Start ==========
    $('#messageType').on('input paste', function() {
      adjustRows($(this));
    });
    function adjustRows(textarea) {
      setTimeout(function() {
        const lines = textarea.val().split('\n').length;
        const maxRows = textarea.data('max-rows');
        textarea.prop('rows', lines > maxRows ? maxRows : lines);
      }, 0);
    }
    // ========================= Height Of Textare For Chatting End ==========
    
    // ========================= Attchment With Message Start ==========
    function attachedFile(file) {
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          var isImage = file.type.startsWith('image/');
          var fileExtension = file.name.split('.').pop();
          if (isImage) {
            var img = document.createElement('img');
            img.src = e.target.result;
            $('.trade-chat__attachment').html(img);
            $('.trade-chat__show-attach').addClass('active');
          } else {
            var iconHtml = '<i class="las la-file-alt"></i>';
            var fileDiv = $('<div class="file-container"></div>').html(iconHtml + ' <span class="badge badge--base py-0 px-2">.' + fileExtension + '</span>');
            $('.trade-chat__attachment').html(fileDiv);
            $('.trade-chat__show-attach').addClass('active');
          }
        }
        reader.readAsDataURL(file);
      } else {
        $('.trade-chat__show-attach').removeClass('active');
      }
    }
    $('#attachFile').change(function () {
      attachedFile(this.files[0]);
    });
    $('.trade-chat__clear-attach').on('click', function () {
      attachedFile(null);
      $('#attachFile').val('');
    });
    // ========================= Attchment With Message End ==========

    $('.search-input').on('input', function() {
      var searchTerm = $(this).val().toLowerCase();
      $('.search-results').empty();
      $('.main-sidebar').find('.sidebar-link:not(.open)').removeClass('show').siblings('.sidebar-dropdown-menu').slideUp();
      $('.main-sidebar a').each(function() {
        var content = $(this).text().toLowerCase();
        var itemUrl = $(this).attr('href');
        if (content.includes(searchTerm)) {
          var resultItem = $('<li><a href="' + itemUrl + '" class="result-link">' + content + '</a></li>');
          $('.search-results').removeClass('d-none').append(resultItem);
        }
      });

      if($(this).val() === '') {
        $('.search-results').addClass('d-none').empty();
      }
    });

    $('.sidebar-toggler').on('click', function (){
      if($(window).width() < 992) {
        $('.main-sidebar').toggleClass('active');
      }
    });


    
  });
  // ==========================================
  //      End Document Ready function
  // ==========================================


  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $(".preloader").fadeOut();
  });
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on("scroll", function () {
    if ($(window).scrollTop() >= 300) {
      $(".header").addClass("fixed-header");
    } else {
      $(".header").removeClass("fixed-header");
    }
  });
  // ========================= Header Sticky Js End===================

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