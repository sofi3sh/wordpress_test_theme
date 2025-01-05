jQuery(document).ready(function ($) {
    let $slider = $(".slider"),
        $slides = $(".slide"),
        $prevBtn = $(".slider-prev"),
        $nextBtn = $(".slider-next"),
        $dotsContainer = $(".slider-dots"),
        index = 0,
        slidesPerRow = 4,
        totalSlides = $slides.length,
        maxIndex = totalSlides - slidesPerRow,
        interval;

    for (let i = 0; i <= maxIndex; i++) {
        $dotsContainer.append('</span><span class="dot" data-index="' + i + '"></span>');
    }
    let $dots = $(".dot");
    $dots.first().addClass("active");

    function updateSlider() {
        let shift = index * (100 / slidesPerRow);
        $slider.css("transform", "translateX(-" + shift + "%)");
        $dots.removeClass("active").eq(index).addClass("active");
    }

    function nextSlide() {
        if (index < maxIndex) {
            index++;
        } else {
            index = 0;
        }
        updateSlider();
    }

    function prevSlide() {
        if (index > 0) {
            index--;
        } else {
            index = maxIndex;
        }
        updateSlider();
    }

    function startSlider() {
        interval = setInterval(nextSlide, 5000);
    }

    function stopSlider() {
        clearInterval(interval);
    }

    $nextBtn.on("click", function () {
        nextSlide();
        stopSlider();
        startSlider();
    });

    $prevBtn.on("click", function () {
        prevSlide();
        stopSlider();
        startSlider();
    });

    $dots.on("click", function () {
        index = $(this).data("index");
        updateSlider();
        stopSlider();
        startSlider();
    });

    startSlider();

    $(".slider-container").on("mouseenter", stopSlider).on("mouseleave", startSlider);

    $slides.on("click", function () {
        let postId = $(this).data("id");
        if (postId && !isNaN(postId)) {
            fetchSliderContent(postId);
        } else {
            console.error("Помилка: некоректний ID слайда", postId);
        }
    });

    function fetchSliderContent(postId) {
        $.ajax({
            type: "POST",
            url: ajax_object.ajaxurl,
            data: {
                action: "get_slider_content",
                id: postId
            },
            success: function (response) {
                if (response.success) {
                    $("#modal-description").html(response.data.content);
                    $("#sliderModal").fadeIn();
                } else {
                    console.error("Помилка: ", response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Ajax помилка:", status, error, xhr.responseText);
            }
        });
    }

    $(".close").on("click", function () {
        $("#sliderModal").fadeOut();
    });
    $(window).on("load", function () {
        $("#sliderModal").hide();
    });
});
