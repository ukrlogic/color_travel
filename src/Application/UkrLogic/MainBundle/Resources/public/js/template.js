function search() {
    $(".slide_search").trigger('click');
}

$(function () {


    if ($(".wallpaperq").length != 0) {
        $(".wallpaperq").wallpaper({
            source: {
                // poster: "video/wallpaper.jpg",
                // ogg:    "video/wallpaper.ogv",
                // webm:   "video/wallpaper.webm",
                mp4: "video/wallpaper.mp4"
            }
        });
    }

    e404 = (parseFloat($('header').css('height')) + parseFloat($('footer').css('height')) + parseFloat($('nav').css('height')));
    $('.e404, .e404>div').css('height', $(window).height() - e404 - 60);


    $(window).on('load', function () {
        w_w = $(document).width() + 17;

        if (w_w < 3000) {
            $('.masonry-news>a.box.news').removeClass('invisible');
            $('.masonry-news>a.box.news:gt(5)').addClass('invisible');
            $('.masonry-news').masonry('reload');
        }
        if (w_w < 1921) {
            $('.masonry-news>a.box.news').removeClass('invisible');
            $('.masonry-news>a.box.news:gt(6)').addClass('invisible');
            $('.masonry-news').masonry('reload');
            console.log('1920');
        }
        if (w_w < 1601) {
            $('.masonry-news>a.box.news').removeClass('invisible');
            $('.masonry-news>a.box.news:gt(5)').addClass('invisible');
            $('.masonry-news').masonry('reload');
            console.log('<1599');
        }
        if (w_w < 1281) {
            $('.masonry-news>a.box.news').removeClass('invisible');
            $('.masonry-news>a.box.news:gt(4)').addClass('invisible');
            $('.masonry-news').masonry('reload');
            console.log('<1279');
        }
        if (w_w < 1026) {
            $('.masonry-news>a.box.news').removeClass('invisible');
            $('.masonry-news>a.box.news:gt(3)').addClass('invisible');
            $('.masonry-news').masonry('reload');
            console.log('<1023');
        }


    });


    $(document).tooltip();

    /**** проверка на placeholder ****/
    if (!Modernizr.input.placeholder) {
        $('input:not(.nonplace)').each(function () {
            value = $(this).attr('placeholder');
            $(this).val(value);
            $(this).on('focus', function () {
                $(this).val('');
            })
        })
    }
    /**** кнопка вверх ****/
    $(window).scroll(function () {
        if ($(window).scrollTop() > 100) {
            $('.scrolltop').fadeIn();
        } else {
            $('.scrolltop').fadeOut();
        }
    });
    $('.scrolltop').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 600);
    });

    /**** Плитка *****/

        // Masonry corner stamp modifications
    $.Mason.prototype.resize = function () {
        this._getColumns();
        this._reLayout();
    };

    $.Mason.prototype._reLayout = function (callback) {
        var freeCols = this.cols;
        if (this.options.cornerStampSelector) {
            var $cornerStamp = this.element.find(this.options.cornerStampSelector),
                cornerStampX = $cornerStamp.offset().left -
                    (this.element.offset().left + this.offset.x + parseInt($cornerStamp.css('marginLeft')));
            freeCols = Math.floor(cornerStampX / this.columnWidth);
        }
        // reset columns
        var i = this.cols;
        this.colYs = [];
        while (i--) {
            this.colYs.push(this.offset.y);
        }

        for (i = freeCols; i < this.cols; i++) {
            this.colYs[i] = this.offset.y + $cornerStamp.outerHeight(true);
        }

        // apply layout logic to all bricks
        this.layout(this.$bricks, callback);
    };

    windowWidth = $(window).width() + 17;

    if (windowWidth >= 1920) {
        ColumnWidth = 171.5;
    }
    if (windowWidth <= 1919) {
        ColumnWidth = 168;
    }
    if (windowWidth <= 1599) {
        ColumnWidth = 160.5;
    }
    if (windowWidth <= 1365) {
        ColumnWidth = 171.5;
    }
    if (windowWidth <= 1279) {
        ColumnWidth = 158.5;
    }


    masonryOptions = {
        itemSelector: '.box',
        gutterWidth: 5,
        columnWidth: ColumnWidth,
        isAnimated: true,
        transitionDuration: 0
    };
    masonryOptionsNews = {
        itemSelector: '.box:not(.invisible)',
        gutterWidth: 5,
        columnWidth: ColumnWidth,
        isAnimated: true,
        cornerStampSelector: '.m-header',
        stamp: ".stamp"
    };
    $('.masonry').masonry(masonryOptions);
    $('.masonry-news').masonry(masonryOptionsNews);


    //$('.flip-container').mouseover(function () {
    //    $(this).find('.flipper .back').fadeIn(200);
    //}).mouseleave(function () {
    //    $(this).find('.flipper .back').stop().fadeOut(200);
    //});


    /**** Регистрация/Вход ****/

    jQuery.fn.fadeToggle = function (speed, easing, callback) {
        return this.animate({
            opacity: 'toggle'
        }, speed, easing, callback);
    };
    $('#signup').click(function () {
        $(this).toggleClass('active');
        $('.signup').fadeToggle(200, 'linear', function () {
            $('.signup>div').hide();
            $('.login').show();
        });

    });
    $('.forget').click(function () {
        $('.login').hide();
        $('.recovery').fadeIn(200);
    });
    $('.registr').click(function () {
        $('.login').hide();
        $('.registration').fadeIn(200);
    });

    function signup(event) {
        event.preventDefault();

        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function (response) {
            console.log(response);
            if (response.redirect) {
                window.location = response.redirect
            } else {
                $form.parent().replaceWith(response);
                $form.parent().show();

                $('.signup form').submit(signup);
            }
        });
    }

    $('.signup form').submit(signup);


    /***** заказать тур *****/

    $('#order').click(function () {
        $('.order_tour').toggleClass('show');
        return false;
    });
    $('#addComment').click(function () {
        $('.add_comment').toggleClass('show');
        return false;
    });
    /***** Вариант тура ******/

    //$('.filter-variant .button:first').click(function () {
    //    $('.filter-variant .button').removeClass('active');
    //    $(this).addClass('active');
    //    $('input[type="checkbox"].is_avia').attr('checked', false);
    //    $('input[type="checkbox"].is_bus').attr('checked', true);
    //    $('.hide-on-bus').hide();
    //    //$('#container').masonry('reload');
    //});
    //$('.filter-variant .button:last').click(function () {
    //    $('.filter-variant .button').removeClass('active');
    //    $(this).addClass('active');
    //    $('input[type="checkbox"].is_avia').attr('checked', true);
    //    $('input[type="checkbox"].is_bus').attr('checked', false);
    //    $('.hide-on-bus').show();
    //    //$('#container').masonry('reload');
    //
    //});

    $('.filter-variant .button').click(function (ev) {
        ev.preventDefault();
        console.log(this);
        var dataClass = $(this).data('class');
        var checkboxes = $('input[type="checkbox"].' + dataClass);
        checkboxes.attr("checked", !checkboxes.attr("checked"));
        $('form[name="tour_form"]').submit();
    });

    if (! $('.filter-variant .button[data-class="is_bus"]').hasClass('active')) {
        $('.hide-on-bus').hide();
    }

    /**** Календарь ****/
    $('.rangeInlinePicker').datepick({
        rangeSelect: true,
        monthsToShow: [1, 1],
        firstDay: 1,
        onSelect: function (dates) {
            $('input.date_from').val($.datepicker.formatDate('yy-mm-dd', dates[0]));
            $('input.date_to').val($.datepicker.formatDate('yy-mm-dd', dates[1]));
        }
    });

    /**** Слайдер выбора продолжительности тура ****/
    $('.slider-day').slider({
        min: 1,
        max: 30,
        step: 1,
        value: 5,
        range: true,
        values: [5, 15],
        change: function (event, ui) {
            $('input.days_from').val(ui.values[0]);
            $('input.days_to').val(ui.values[1]);
        },
        slide: function (event, ui) {
            // $("#slider-day .ui-slider-handle").attr('data-content', ui.value + ' дней');

            $(".slider-day .ui-slider-handle:first").attr('data-content', ui.values[0] + ' дней');
            $(".slider-day .ui-slider-handle:last").attr('data-content', ui.values[1] + ' дней');
        }
    });
    $(".slider-day .ui-slider-handle:first").attr('data-content', '5 дней');
    $(".slider-day .ui-slider-handle:last").attr('data-content', '15 дней');


    /**** Слайдер выбора стоимости тура ****/
    $('.slider-cost').slider({
        min: 100,
        max: 10000,
        step: 50,
        value: 100,
        range: true,
        values: [500, 3500],
        change: function (event, ui) {
            $('input.price_from').val(ui.values[0]);
            $('input.price_to').val(ui.values[1]);
        },
        slide: function (event, ui) {
            $(".slider-cost .ui-slider-handle:first").attr('data-content', ui.values[0]);
            $(".slider-cost .ui-slider-handle:last").attr('data-content', ui.values[1]);
        }
    });
    $(".slider-cost .ui-slider-handle:first").attr('data-content', 500);
    $(".slider-cost .ui-slider-handle:last").attr('data-content', 3500);

    $('.cost').on('keyup', function () {
        $(this).val($(this).val().replace(/\D/, ''));
    });


    /**** Категория отеля (звезды) ****/
    $('.stars .star:not(.active)').mouseenter(function () {
        count = $(this).index() + 1;
        $('.stars > :lt(' + count + ')').stop().css('opacity', '0.8');
    }).mouseleave(function () {
        $('.stars .star:not(.active)').stop().css('opacity', '0.5');
    }).click(function () {
        $('.stars .star').removeClass('active');
        $('.intext>div').removeClass('active');
        count = $(this).index() + 1;
        $('.stars > :gt(' + (count - 1) + ')').css('opacity', '0.5');
        $('.stars > :lt(' + count + ')').addClass('active');
        $('input.hotel_rate').val($(this).data('value'))
    });
    $('.intext>div').click(function () {
        $(this).addClass('active');
        $('.stars .star').removeClass('active').css('opacity', 0.5);
        $('input.hotel_rate').val($(this).data('value'));
    });

    /**** Селекты выбора количества туристов ****/
    $("#adult.selectordie").selectOrDie({
        placeholder: "Взрослых"
    });
    $("#child.selectordie").selectOrDie({
        placeholder: "Детей"
    });
    $(".selectordie").selectOrDie({});

    /**** Кастомные скролы ****/
    $(".customscroll").mCustomScrollbar({
        theme: "light-3"
    });
    $(".customscroll-dark").mCustomScrollbar({
        theme: "dark-3"
    });

    /**** Аккордеон в фильтре ****/
    $(".filter-section").accordion({
        collapsible: true,
        heightStyle: 'content',
        create: function () {
            //          fh = $('.filter').height();
            // $('.filter').css('height', fh);
            $('#container').masonry(masonryOptions);
        },
        activate: function (event, ui) {
            $('.filter').addClass('flex');
            console.log('activ');

            $('#container').masonry(masonryOptions);
        }
    });

    /*** ajax загрузка отзывов ****/
    $('#more-reviews').click(function () {
        $(this).find('h2').hide();
        $(this).find('img').show();
        $.ajax({
            url: 'send.php',
            method: 'post'
        }).done(function () {

            function lazy() {
                $('#more-reviews').find('img').hide();
                $('#more-reviews').find('h2').show();
            }

            setInterval(lazy, 5000);

        });
    })

    /*** развернуть текст отзыва ****/
    $(function () {
        $('.more_button').click(function () {
            $(this).prev('.more_content').slideToggle(250);
            $(this).toggleClass('open');
        })
    });


    $('.europe-image').velocity({
        scale: 0.2
    });

    $('.europe-image').click(function () {
        // $(this).fadeOut();
        $('.europe-image').show().velocity({
            scale: 0.5,
            opacity: 0,
            top: 0
        });
        $('.content-flags.europe').velocity({
            scale: 0.5,
            opacity: 0
        }).velocity("fadeOut", {duration: 50});
        $('.world-image, .content-flags.world').show().velocity({
            opacity: 1,
            scale: 1
        });
    })
    $('.europe-flag').click(function () {
        $('.world-image, .content-flags.world').velocity({
            opacity: 0,
            scale: 3
        }).velocity("fadeOut", {duration: 50});
        $('.europe-image').velocity("fadeIn", {duration: 50}).velocity({
            opacity: 1,
            top: 0,
            scale: 1
        });
        $('.content-flags.europe').show().velocity({
            scale: 1,
            opacity: 1
        });


    })


    $(".slide_search").click(function () {
        $(this).toggleClass('hide');
        if ($(this).hasClass('hide')) {
            $(".box.filter").velocity({
                left: '-500px',
                width: 0,
                opacity: 0
            }, 300, function () {
                $('#container').masonry(masonryOptions);
            });
        } else {
            if ($(window).width() <= 1) {
                $(".box.filter").css({
                    width: ColumnWidth,
                    opacity: 1
                });
            } else {
                $(".box.filter").css({
                    width: (ColumnWidth * 2) + 5,
                    opacity: 1
                });
            }
            $('#container').masonry(masonryOptions);
        }
    });


    /* вызов оверлея  */

    function showOverlay() { //показать
        $('.plzwait').text('Пожалуйста, подождите');
        $('.dots').show();
        $('.loadingoverlay').velocity("fadeIn", {duration: 200});
    }

    function hideOverlay() { // скрыть
        $('.loadingoverlay').velocity("fadeOut", {duration: 200});
    }

    $('form[name="tour_form"]').submit(function (ev) {
        ev.preventDefault();
        showOverlay();
        $form = $(this);
        $.post($form.attr('action'), $form.serialize(), function (data) {
            if (!data) {
                $('.plzwait').text('Туров не найдено').click(hideOverlay);
                $('.dots').fadeOut();
            } else {
                if (! $form.data('append')) {
                    $('#container a.masonry-brick').remove();
                } else {
                    $form.data('append', false);
                }
                $('#container').append(data);
                $('#container').masonry('reload');

                hideOverlay();
            }


        })
    });

    //подгрузка туров
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            var page = $('form[name="tour_form"] input.page').val();
            $('form[name="tour_form"] input.page').val(page++);

            $('form[name="tour_form"]').data('append', 'append').submit();
        }
    });

    $('.from-group input[type="checkbox"]').click(function () {
        $(this).parents('.mCSB_container').find('input[type="checkbox"]').attr('checked', false);
        $(this).attr('checked', true);
        $('form[name="tour_form"]').submit();
    });


    $('p.close').on('click', function () {
        $(this).parent().parent().removeClass('show');
    });

    //$('#avia-switcher').click();

    //$('form[name="tour_form"]').submit(showOverlay);


    $(".vertical-gallery").jCarouselLite({
        btnNext: '.next',
        btnPrev: '.prev',
        mouseWheel: true,
        vertical: true,
        visible: 4,
        circular: true,
        scroll: 1
    });

    //phone mask
    $('input.phone-mask').mask('(999) 999-99-99');
    $('input.phone-mask').on("blur", function () {
        var last = $(this).val().substr($(this).val().indexOf("-") + 1);

        if (last.length == 3) {
            var move = $(this).val().substr($(this).val().indexOf("-") - 1, 1);
            var lastfour = move + last;

            var first = $(this).val().substr(0, 9);

            $(this).val(first + '-' + lastfour);
        }
    });

});