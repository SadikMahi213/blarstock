(function ($) {
  "use strict";

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {
    // ============== Bootstrap Tooltip Enable Start ========
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll("[title], [data-title], [data-bs-title]")
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
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
      $(this).toggleClass("ti-eye ti-eye-off");
      var input = $($(this).attr("id"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    // ========================= Account Setup Key Copy Start ==========
    $(".account-setup-key__copy").on("click", function () {
      var inputElement = $("#accountSetupKey");
      inputElement.select();
      document.execCommand("copy");
      $(".account-setup-key__badge").addClass("show");
      setTimeout(function () {
        $(".account-setup-key__badge").removeClass("show");
      }, 1500);
    });
    // ========================= Account Setup Key Copy End ==========

    // ========================= For Th In Small Devices Start ==========
    if ($("th").length) {
      Array.from(document.querySelectorAll("table")).forEach((table) => {
        let heading = table.querySelector("thead")
          ? table.querySelectorAll("thead tr th")
          : null;
        Array.from(table.querySelectorAll("tbody tr")).forEach((row) => {
          Array.from(row.querySelectorAll("td")).forEach((column, i) => {
            if (heading && heading[i]) {
              column.setAttribute("data-label", heading[i].innerText);
            }
          });
        });
      });
    }
    // ========================= For Th In Small Devices End ==========

    // ========================= Select2 Js Start =====================
    $(".select-2").each(function () {
      var $select = $(this);
      var tags = $select.data("tags") === true;
      var noSearch = $select.data("search") === false;

      $select.select2({
        containerCssClass: ":all:",
        tags: tags,
        templateResult: resultState,
        minimumResultsForSearch: noSearch ? Infinity : 0,
      });
    });
    function resultState(data, container) {
      if (data.element) {
        $(container).addClass($(data.element).attr("class"));
      }
      return data.text;
    }
    // ========================= Select2 Js End =====================

    // ========================= Share Link Copy Start =====================
    $(".share-link__copy").on("click", function () {
      var inputElement = $("#shareLink");
      inputElement.select();
      document.execCommand("copy");
      $(".share-link__badge").addClass("show");
      setTimeout(function () {
        $(".share-link__badge").removeClass("show");
      }, 1500);
    });
    // ========================= Share Link Copy End ==========

    // ========================= Banner Select Menu Start =========================
    $(".banner-content__select__btn").on("click", function () {
      $(this).siblings(".banner-content__select__list").toggleClass("show");
    });
    $(document).on("click", function (e) {
      if (
        !$(e.target).is(
          ".banner-content__select__btn, .banner-content__select__btn *, .banner-content__select__list, .banner-content__select__list *"
        )
      ) {
        $(".banner-content__select__list").removeClass("show");
      }
    });
    $(document).on(
      "change",
      ".banner-content__select__list input",
      function () {
        var filterType = $(this).data("filter");
        if ($(this).is(":checked")) {
          $(this)
            .parent("li")
            .siblings()
            .find("input[data-filter=" + filterType + "]")
            .prop("checked", false);
        }
        var checkedLabels = $(".banner-content__select__list")
          .find("input:not([data-filter=access-level]):checked")
          .siblings("label");

        if (checkedLabels.length > 0) {
          var firstLabel = checkedLabels.eq(0).clone();
          var firstLabelIcon = firstLabel.find("i").prop("outerHTML");
          var firstLabelText = firstLabel.text();

          if (checkedLabels.length > 1) {
            var secondLabel = checkedLabels.eq(1).clone();
            var secondLabelIcon = secondLabel.find("i").prop("outerHTML");
            var secondLabelText = secondLabel.text();

            var resultHtml =
              secondLabelIcon +
              ' <span class="btn-txt">' +
              firstLabelText +
              ", " +
              secondLabelText +
              "</span>";
            $(".banner-content__select__btn__txt").html(resultHtml);
          } else {
            var resultHtml =
              firstLabelIcon +
              ' <span class="btn-txt">' +
              firstLabelText +
              "</span>";
            $(".banner-content__select__btn__txt").html(resultHtml);
          }
        }
      }
    );
    // ========================= Banner Select Menu End =========================

    // ========================= Category Slider Start =========================
    if ($(".category__slider").length) {
      function setEqualHeight(slider) {
        var maxHeight = 0;
        slider.find(".slick-slide").each(function () {
          var slideHeight = $(this).outerHeight();
          if (slideHeight > maxHeight) {
            maxHeight = slideHeight;
          }
        });
        slider.find(".slick-slide").css("height", maxHeight + "px");
      }
      var $slider = $(".category__slider");
      $slider.slick({
        slidesToShow: 8,
        adaptiveHeight: true,
        prevArrow:
          '<button class="slick-prev"><i class="ti ti-arrow-left"></i></button>',
        nextArrow:
          '<button class="slick-next"><i class="ti ti-arrow-right"></i></button>',
        responsive: [
          {
            breakpoint: 1400,
            settings: {
              slidesToShow: 6,
              arrows: false,
            },
          },
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 5,
              arrows: false,
            },
          },
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 4,
              arrows: false,
            },
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 3,
              arrows: false,
            },
          },
        ],
      });
      setEqualHeight($slider);
    }
    // ========================= Category Slider End =========================

    // ========================= Product Grid Start =========================
    var container = $(".product__row:not(.product__row-empty)");
    if (container.length) {
      function setWookmarkOffset() {
        var offset = window.matchMedia("(max-width: 768px)").matches ? 10 : 15;

        container.wookmark({
          align: "left",
          offset: offset,
          outerOffset: 0,
        });
      }

      container.imagesLoaded(function () {
        setWookmarkOffset();
        initAnimations(); // ✅ Run GSAP setup after Wookmark is done
      });

      let resizeTimer;
      $(window).on("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          setWookmarkOffset();
          ScrollTrigger.refresh(); // refresh on layout change
        }, 200);
      });
    }

    // ========================= Product Grid End =========================

    // ========================= Search Filter Color Button Activation Start =========================
    $(".search-result__color-filter-btn").each(function () {
      var colorCode = $(this).attr("data-filter-color");
      $(this).css("background", colorCode);
      $(this)
        .not(".deselect-color")
        .on("click", function () {
          $(this).addClass("active").siblings().removeClass("active");
        });
    });
    $(".search-result__color-filter-btn.deselect-color").on(
      "click",
      function () {
        $(this)
          .siblings(".search-result__color-filter-btn")
          .removeClass("active");
      }
    );
    // ========================= Search Filter Color Button Activation End =========================

    // ========================= Search Filter Card Collapsing Start =========================
    $(".search-result__card__header").on("click", function () {
      $(this)
        .toggleClass("opened")
        .siblings(".search-result__card__body")
        .toggleClass("d-none");
    });
    // ========================= Search Filter Card Collapsing End =========================

    // ========================= Filter Sidebar Collapsing Start =========================
    $(".search-result__sidebar-toggler, .search-result__sidebar__close").on(
      "click",
      function () {
        var viewport = $(window).outerWidth();
        if (viewport > 991) {
          $(".search-result__sidebar").toggleClass("collapsed");
          $(".search-result__items").toggleClass("expanded");
          var container = $(".product__row");
          if (container.length) {
            container.imagesLoaded(function () {
              container.wookmark({
                align: "left",
                offset: 15,
                outerOffset: 0,
              });
            });
          }
        } else {
          $(".search-result__sidebar").toggleClass("expanded");
        }
      }
    );
    // ========================= Filter Sidebar Collapsing End =========================

    // ========================= Search Guide Horizontal Scroll Start =========================
    if ($(".search-result__guide__list").length) {
      var scroller = document.querySelector(".search-result__guide__list");
      var direction = 0;
      var active = false;
      var max = 10;
      var Vx = 0;
      var x = 0.0;
      var prevTime = 0;
      var f = 0.2;
      var prevScroll = 0;
      function physics(time) {
        var diffTime = time - prevTime;
        if (!active) {
          diffTime = 80;
          active = true;
        }
        prevTime = time;

        Vx = (direction * max * f + Vx * (1 - f)) * (diffTime / 20);

        x += Vx;
        var thisScroll = scroller.scrollLeft;
        var nextScroll = Math.floor(thisScroll + Vx);

        if (Math.abs(Vx) > 0.5 && nextScroll !== prevScroll) {
          scroller.scrollLeft = nextScroll;
          requestAnimationFrame(physics);
        } else {
          Vx = 0;
          active = false;
        }
        prevScroll = nextScroll;
      }
      function handleMouseDown(dir) {
        direction = dir;
        if (!active) {
          requestAnimationFrame(physics);
        }
      }
      function handleMouseUp() {
        direction = 0;
      }
      searchGuideLeftArrow.addEventListener("mousedown", function () {
        handleMouseDown(-1);
      });
      searchGuideRightArrow.addEventListener("mousedown", function () {
        handleMouseDown(1);
      });
      window.addEventListener("mouseup", handleMouseUp);
      $(scroller)
        .on("scroll", function () {
          if ($(this).scrollLeft() < 1) {
            $(searchGuideLeftArrow).prop("disabled", true);
          } else {
            $(searchGuideLeftArrow).prop("disabled", false);
          }
          if (
            $(this).scrollLeft() + $(this).innerWidth() >=
            $(this)[0].scrollWidth
          ) {
            $(searchGuideRightArrow).prop("disabled", true);
          } else {
            $(searchGuideRightArrow).prop("disabled", false);
          }
        })
        .trigger("scroll");
      searchGuideLeftArrow.addEventListener("touchstart", function () {
        handleMouseDown(-1);
      });
      searchGuideRightArrow.addEventListener("touchstart", function () {
        handleMouseDown(1);
      });
      window.addEventListener("touchend", handleMouseUp);

      function scrollActiveGuideTypeIntoView() {
        const activeType = document.querySelector(".search-result__guide__type");
        if (activeType) {
          activeType.scrollIntoView({
            behavior: "smooth",
            block: "nearest",
            inline: "center"
          });
        }
      }

      $(scroller).on("click", "a", function () {
        setTimeout(() => {
          scrollActiveGuideTypeIntoView();
        }, 50);
      });

      $(window).on("load", function () {
        setTimeout(() => {
          scrollActiveGuideTypeIntoView();
        }, 100);
      });
    }
    // ========================= Search Guide Horizontal Scroll End =========================

    // ========================= Related Product Slider Start =========================
    class Slider {
      constructor(scrollerSelector, leftArrowId, rightArrowId) {
        this.scroller = document.querySelector(scrollerSelector);
        this.leftArrow = document.getElementById(leftArrowId);
        this.rightArrow = document.getElementById(rightArrowId);
        this.direction = 0;
        this.active = false;
        this.max = 50;
        this.Vx = 0;
        this.x = 0.0;
        this.prevTime = 0;
        this.f = 0.2;
        this.prevScroll = 0;

        this.init();
      }

      init() {
        const start = () => {
          this.direction = -1;
          if (!this.active) {
            requestAnimationFrame((time) => this.physics(time));
          }
        };
        const end = () => {
          this.direction = 0;
        };
        const startRight = () => {
          this.direction = 1;
          if (!this.active) {
            requestAnimationFrame((time) => this.physics(time));
          }
        };

        // Left arrow events
        this.leftArrow.addEventListener("mousedown", start);
        this.leftArrow.addEventListener("touchstart", start);
        this.leftArrow.addEventListener("mouseup", end);
        this.leftArrow.addEventListener("touchend", end);

        // Right arrow events
        this.rightArrow.addEventListener("mousedown", startRight);
        this.rightArrow.addEventListener("touchstart", startRight);
        this.rightArrow.addEventListener("mouseup", end);
        this.rightArrow.addEventListener("touchend", end);

        // Scroll detection (jQuery remains the same)
        $(this.scroller).on("scroll", () => {
          const scrollLeft = $(this.scroller).scrollLeft();
          const scrollRightEdge = scrollLeft + $(this.scroller).innerWidth();
          const maxScroll = $(this.scroller)[0].scrollWidth;

          $(this.leftArrow).prop("disabled", scrollLeft < 1);
          $(this.rightArrow).prop("disabled", scrollRightEdge >= maxScroll);
        });
      }

      physics(time) {
        let diffTime = time - this.prevTime;
        if (!this.active) {
          diffTime = 80;
          this.active = true;
        }
        this.prevTime = time;

        this.Vx =
          (this.direction * this.max * this.f + this.Vx * (1 - this.f)) *
          (diffTime / 20);

        this.x += this.Vx;
        const thisScroll = this.scroller.scrollLeft;
        const nextScroll = Math.floor(thisScroll + this.Vx);

        if (Math.abs(this.Vx) > 0.5 && nextScroll !== this.prevScroll) {
          this.scroller.scrollLeft = nextScroll;
          requestAnimationFrame((time) => this.physics(time));
        } else {
          this.Vx = 0;
          this.active = false;
        }
        this.prevScroll = nextScroll;
      }
    }

    if ($(".related-product-scroll").length) {
      const slider1 = new Slider(
        ".related-product-scroll",
        "relatedProductLeft",
        "relatedProductRight"
      );
    }
    if ($(".author-product-scroll").length) {
      const slider2 = new Slider(
        ".author-product-scroll",
        "authorProductLeft",
        "authorProductRight"
      );
    }

    $(".related-product").each(function () {
      var listWidth = $(this).find("ul").innerWidth();
      if (listWidth < $(this).width()) {
        $(this).find(".related-product__nav").hide();
      }
    });
    // ========================= Author Product Slider End =========================

    // ========================= Dashboard Menu Active Start =========================
    $(".dashboard-navbar a").each(function () {
      var pageUrl = window.location.href.split(/[?#]/)[0];
      if (this.href == pageUrl) {
        $(this).addClass("active");
        if ($(this).parents(".dropdown-menu")) {
          $(this)
            .parents(".dropdown-menu")
            .siblings(".nav-link")
            .addClass("active");
        }
      }
    });
    // ========================= Dashboard Menu Active End =========================

    // ========================= Transaction Filter Toggle Start =========================
    $(".transaciton-filter").on("click", function () {
      $("#transacitonFilterForm").toggleClass("d-none");
    });
    // ========================= Transaction Filter Toggle End =========================

    // ========================= Referral Link Copy Start =====================
    $(".referral-link__copy").on("click", function () {
      var inputElement = $("#referralLink");
      inputElement.select();
      document.execCommand("copy");
      $(this).html('<i class="ti ti-circle-check"></i>');
      setTimeout(function () {
        $(".referral-link__copy").html('<i class="ti ti-copy"></i>');
      }, 1500);
    });
    // ========================= Referral Link Copy End ==========

    // ========================= Custom Dropzone Start =====================
    var currentFileIndex = 0;
    var uploadedFiles = [];
    
    function updatePreview(input, files) {
      var $preview = $(input).siblings(".dropzone").find(".dropzone__preview");
      var $carousel = $('#filePreviewCarousel');
      var $slidesContainer = $('#previewSlides');
      var $indicatorsContainer = $('#slideIndicators');
      var $fileCount = $('#fileCount');
      
      $preview.html(""); // Clear previous previews
      $slidesContainer.html(""); // Clear carousel slides
      $indicatorsContainer.html(""); // Clear indicators
      uploadedFiles = [];
      
      if (files && files.length > 0) {
        uploadedFiles = Array.from(files);
        $fileCount.text(files.length);
        $carousel.removeClass('d-none');
        
        // Create slides for each file
        uploadedFiles.forEach(function(file, index) {
          var slideHtml = '<div class="slide-item' + (index === 0 ? ' active' : '') + '" data-index="' + index + '">';
          
          if (file.type.startsWith("image/")) {
            var reader = new FileReader();
            reader.onload = function(e) {
              slideHtml += '<div class="file-preview-image">';
              slideHtml += '<img src="' + e.target.result + '" alt="' + file.name + '" class="preview-image">';
              slideHtml += '<div class="file-info">';
              slideHtml += '<div class="file-name">' + file.name + '</div>';
              slideHtml += '<div class="file-size">' + formatFileSize(file.size) + '</div>';
              slideHtml += '</div>';
              slideHtml += '</div>';
              slideHtml += '</div>';
              $slidesContainer.append(slideHtml);
            };
            reader.readAsDataURL(file);
          } else if (file.type.startsWith("video/")) {
            var reader = new FileReader();
            reader.onload = function(e) {
              slideHtml += '<div class="file-preview-video">';
              slideHtml += '<video src="' + e.target.result + '" class="preview-video" controls></video>';
              slideHtml += '<div class="file-info">';
              slideHtml += '<div class="file-name">' + file.name + '</div>';
              slideHtml += '<div class="file-size">' + formatFileSize(file.size) + '</div>';
              slideHtml += '</div>';
              slideHtml += '</div>';
              slideHtml += '</div>';
              $slidesContainer.append(slideHtml);
            };
            reader.readAsDataURL(file);
          } else {
            // For other file types, show icon
            slideHtml += '<div class="file-preview-other">';
            slideHtml += '<div class="file-icon-large"><i class="ti ti-file"></i></div>';
            slideHtml += '<div class="file-info">';
            slideHtml += '<div class="file-name">' + file.name + '</div>';
            slideHtml += '<div class="file-size">' + formatFileSize(file.size) + '</div>';
            slideHtml += '<div class="file-type">' + file.type.split('/')[1].toUpperCase() + '</div>';
            slideHtml += '</div>';
            slideHtml += '</div>';
            slideHtml += '</div>';
            $slidesContainer.append(slideHtml);
          }
          
          // Create indicator
          var indicatorHtml = '<button type="button" class="indicator-btn' + (index === 0 ? ' active' : '') + '" data-slide-to="' + index + '"></button>';
          $indicatorsContainer.append(indicatorHtml);
        });
        
        // Show first file preview in dropzone
        var firstFile = files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
          if (firstFile.type.startsWith("image/")) {
            var img = document.createElement("img");
            img.src = e.target.result;
            $preview.html(img);
          } else if (firstFile.type.startsWith("video/")) {
            var video = document.createElement("video");
            video.src = e.target.result;
            $preview.html(video);
          } else {
            // For other file types, show icon
            $preview.html('<div class="file-icon"><i class="ti ti-file"></i></div>');
          }
          $preview.addClass("active");
        };
        reader.readAsDataURL(firstFile);
        
      } else {
        $preview.html("");
        $preview.removeClass("active");
        $carousel.addClass('d-none');
      }
    }
    
    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      var k = 1024;
      var sizes = ['Bytes', 'KB', 'MB', 'GB'];
      var i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function showSlide(index) {
      if (index >= 0 && index < uploadedFiles.length) {
        $('.slide-item').removeClass('active');
        $('.indicator-btn').removeClass('active');
        $('.slide-item[data-index="' + index + '"]').addClass('active');
        $('.indicator-btn[data-slide-to="' + index + '"]').addClass('active');
        currentFileIndex = index;
      }
    }
    // Carousel navigation
    $('#prevFile').on('click', function() {
      if (currentFileIndex > 0) {
        showSlide(currentFileIndex - 1);
      }
    });
    
    $('#nextFile').on('click', function() {
      if (currentFileIndex < uploadedFiles.length - 1) {
        showSlide(currentFileIndex + 1);
      }
    });
    
    // Indicator click events
    $(document).on('click', '.indicator-btn', function() {
      var slideTo = $(this).data('slide-to');
      showSlide(slideTo);
    });
    
    // Keyboard navigation
    $(document).on('keydown', function(e) {
      if (uploadedFiles.length > 0) {
        if (e.key === 'ArrowLeft') {
          $('#prevFile').click();
        } else if (e.key === 'ArrowRight') {
          $('#nextFile').click();
        }
      }
    });
    
    $("#fileInput").on("change", function () {
      updatePreview(this, this.files);
      updateTypeField(this.files);
    });
    
    function updateTypeField(files) {
      if (files && files.length > 0) {
        // Check the first file to determine the type
        const firstFile = files[0];
        let assetType = 1; // Default to image
        
        if (firstFile.type.startsWith('video/')) {
          assetType = 2; // Video type
        } else if (firstFile.type.startsWith('image/')) {
          assetType = 1; // Image type
        } else if (firstFile.type.includes('vector') || firstFile.name.match(/\.(svg|ai|eps)$/i)) {
          assetType = 1; // Vector treated as image type
        } else if (firstFile.name.match(/\.(obj|fbx|blend|ma|max)$/i)) {
          assetType = 1; // 3D objects treated as image type
        } else if (firstFile.name.match(/\.(pdf)$/i)) {
          assetType = 1; // Documents treated as image type
        }
        
        $('#assetTypeHidden').val(assetType);
      }
    }
    $(".dropzone").on("dragover dragleave drop", function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (e.type === "dragover") {
        $(this).addClass("dragging");
      } else if (e.type === "dragleave" || e.type === "drop") {
        $(this).removeClass("dragging");
      }

      if (e.type === "drop") {
        var files = Array.from(e.originalEvent.dataTransfer.files);
        var fileInput = $("#fileInput")[0];
        var accept = fileInput.getAttribute("accept");
        var acceptedTypes = accept
          ? accept.split(",").map((type) => type.trim())
          : [];

        var invalidFiles = files.filter((file) => {
          var fileType = file.type;
          var fileName = file.name.toLowerCase();
          return !acceptedTypes.some((type) => {
            return (
              (type.startsWith(".") && fileName.endsWith(type)) ||
              fileType === type
            );
          });
        });
        if (invalidFiles.length > 0) {
          alert(
            "Some files are not allowed. Please check the accepted file types."
          );
          return;
        }
        if (!fileInput.multiple && files.length > 1) {
          alert("This input only allows one file.");
          return;
        }
        var dt = new DataTransfer();
        files.forEach(function (file) {
          dt.items.add(file);
        });
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event("change"));
      }
    });
    
    // Also update type when files are dropped
    $(".dropzone").on("drop", function (e) {
      setTimeout(function() {
        var fileInput = $("#fileInput")[0];
        if (fileInput.files.length > 0) {
          updateTypeField(fileInput.files);
        }
      }, 10); // Small delay to ensure files are set
    });
    
    // ========================= Custom Dropzone End ==========
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



  // all gsap

  // GSAP & ScrollTrigger setup
  document.addEventListener("DOMContentLoaded", () => {
    if (typeof gsap === "undefined") return; // skip if gsap not loaded

    if (gsap.registerPlugin) {
      gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);
    }

    gsap.config({ nullTargetWarn: false });

    if (typeof ScrollTrigger !== "undefined") {
      ScrollTrigger.config({ autoRefreshEvents: "DOMContentLoaded,load" });
    }
  });

  // GSAP animation initialization
  function initAnimations() {
    ScrollTrigger.getAll().forEach((trigger) => trigger.kill());

    gsap.set(".fade-bottom", { y: 70, opacity: 0, immediateRender: false });

    setupTextAnimations();
    setupCardAnimations();
    setupFadeAnimations();
    initImageAnimation();

    setTimeout(() => {
      ScrollTrigger.refresh();
    }, 200);
  }

  // =============== TEXT ANIMATIONS ===============
  function setupTextAnimations() {
    document.querySelectorAll(".slide-to-left").forEach((element) => {
      const originalText = element.textContent.trim();

      element.innerHTML = originalText
        .split(/\s+/)
        .map(
          (word) =>
            `<span class="word">${word}</span><span class="space">&nbsp;</span>`
        )
        .join("");

      gsap.from(element.querySelectorAll(".word"), {
        duration: 0.8,
        x: 100,
        opacity: 0,
        stagger: 0.05,
        ease: "power2.out",
        scrollTrigger: {
          trigger: element,
          start: "top 80%",
          end: "bottom 20%",
          toggleActions: "play none none none",
          markers: false,
        },
      });
    });
  }

  // =============== CARD ANIMATIONS ===============
  function setupCardAnimations() {
    gsap.utils.toArray(".box-container").forEach((container) => {
      const boxes = container.querySelectorAll(".box");
      let rowTop = null;
      let delayCounter = 0;

      boxes.forEach((box) => {
        const rect = box.getBoundingClientRect();
        if (rowTop === null || Math.abs(rect.top - rowTop) > 5) {
          rowTop = rect.top;
          delayCounter = 0;
        }

        gsap.from(box, {
          duration: 0.6,
          scale: 0.8,
          opacity: 0,
          ease: "back.out(1.2)",
          delay: delayCounter * 0.15,
          scrollTrigger: {
            trigger: box,
            start: "top 70%",
            toggleActions: "play none none none",
            markers: false,
          },
        });

        gsap.from(box.querySelectorAll(".box__content"), {
          duration: 0.8,
          x: -30,
          opacity: 0,
          stagger: 0.1,
          ease: "power2.out",
          scrollTrigger: {
            trigger: box,
            start: "top 70%",
            toggleActions: "play none none none",
            markers: false,
          },
        });

        delayCounter++;
      });
    });
  }

  // =============== FADE-IN ANIMATIONS ===============
  function setupFadeAnimations() {
    gsap.utils.toArray(".fade-bottom").forEach((item) => {
      gsap.from(item, {
        y: 50,
        opacity: 0,
        duration: 1,
        ease: "power2.out",
        scrollTrigger: {
          trigger: item,
          start: "top 85%",
          toggleActions: "play none none none",
          markers: false,
        },
      });
    });
  }

  // =============== IMAGE SCALE ANIMATION ===============
  let imageTl;
  function initImageAnimation() {
    if (imageTl) {
      imageTl.kill();
    }

    const bigImage = document.querySelector(".big-image");
    const bigImageImg = bigImage?.querySelector("img");
    if (!bigImage || !bigImageImg) return;

    ScrollTrigger.getAll().forEach((trigger) => {
      if (trigger.trigger === bigImage) {
        trigger.kill();
      }
    });

    const runAnimation = () => {
      if (window.innerWidth > 767) {
        gsap.set(bigImageImg, { scale: 1 });

        imageTl = gsap.timeline({
          scrollTrigger: {
            trigger: bigImage,
            start: "top center",
            end: "bottom top",
            scrub: true,
            markers: false,
          },
        });

        imageTl.to(bigImageImg, {
          scale: 1.3,
          ease: "none",
        });

        ScrollTrigger.refresh();
      }
    };

    if (bigImageImg.complete) {
      runAnimation();
    } else {
      bigImageImg.addEventListener("load", runAnimation, { once: true });
    }
  }

  window.addEventListener("load", initImageAnimation);

  let resizeTimer;
  $(window).on("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      initImageAnimation();
    }, 200);
  });



})(jQuery);