// Console spam: Added non-passive event listener to a scroll-blocking 'touchstart' event...
// https://stackoverflow.com/questions/46094912/added-non-passive-event-listener-to-a-scroll-blocking-touchstart-event
/*
jQuery.event.special.touchstart = {
    setup: function( _, ns, handle ){
        if ( ns.includes("noPreventDefault") ) {
            this.addEventListener("touchstart", handle, { passive: false });
        } else {
            this.addEventListener("touchstart", handle, { passive: true });
        }
    }
};
*/
// return Device Variant
function getDV(w) {
    var v = 'd';

    if (w >= 768 && w <= 991) {
        v = 't';
    } else if (w <= 767) {
        v = 'm';
    }

    return v;
}

var wWidth = $(window).width();
var dv = getDV(wWidth);

// Header Fixed
function hFix() {
    var sTop        = $(window).scrollTop();
    var sTopFix     = $('.top-in').outerHeight();
    var hmH         = $('.header-in').outerHeight() + $('#menu').outerHeight()
    var pTop        = hmH;

    if ($('.header-in').hasClass('common-home') && $(window).width() > 991) {
        logoH       = $('.h-logo').outerHeight();
        sTopFix     = logoH + sTopFix;
        pTop        = logoH + hmH;
    }

    if (sTop > sTopFix) {
        //if (!$('#cart').hasClass('open')) {
            $('header').addClass('fixed');
            $('body').css({'paddingTop': pTop});
        //}
    } else {
        $('header').removeClass('fixed');
        $('body').css({'paddingTop': 0});
    }
}

document.addEventListener('scroll', hFix, { passive: true });

function owl_pp_i(dv) {
    if (typeof owl_pp_i_init == 'function') {
        if (dv == 'm') {
            owl_pp_i_init();
        } else {
            $('.images.owl-carousel').owlCarousel('destroy');
        }
    }
}

function drag(el) {
    if (!$(el)[0]) return false;

    var elW = $(el).width();
    var elSW = $(el)[0].scrollWidth;

    var elW = $(el).width();
    var elSW = $(el)[0].scrollWidth;

    if (elSW > elW) {
        if ($(el).data('ui-draggable')) {
            $(el).draggable('destroy').removeAttr("style");
        }

        var startPosition = 0;
        var left_stop = elW - elSW;

        $(el).draggable({ axis: 'x',
            start: function( event, ui ) {
                startPosition = ui.position.left;
            },
            drag: function( event, ui ) {
                if(ui.position.left > 0)
                    ui.position.left = 0;
                if(ui.position.left < left_stop)
                    ui.position.left = left_stop;
                startPosition = ui.position.left;
            }
        });
    } else {
        if ($(el).data('ui-draggable')) {
            $(el).draggable('destroy').removeAttr("style");
        }
    }
}

// Window Resize
$(window).resize(function () {
    var _wWidth = $(window).width();

    if (wWidth != _wWidth) {
        wWidth = _wWidth;

        clearTimeout(window.resizedFinished);

        window.resizedFinished = setTimeout(function () {
            dv = getDV(wWidth);

            drag('.breadcrumb');

            if (dv == 'm') {
                $('#cart .dropdown-menu').width(wWidth)
            }

            if ($('#product-product').hasClass('container')) {
                p_stycky();
            }

            owl_pp_i(dv);
        }, 250);

    }
});

function quickOrder(product_id=0) {
    $('#modal-quick-order[data-type="success"]').remove();
    var sw=(window.innerWidth-$(window).width());

    //$('.top_in').attr('style', 'width: calc(100% + ' + sw + 'px)' );
    //$('.top_in').attr('data-in', '1' );
     $('body .top_in').css({ 'width' : '200px' });
    // $('html, body').animate({scrollTop: 0}, 'slow');

    if ($('#modal-quick-order').length) {
        quickOrderTotal();
        $('#modal-quick-order').modal('show');
    } else {
        $.get('index.php?route=checkout/quick_order/modal&product_id=' + product_id, function(data) {
            $('body').append(data);
            quickOrderTotal();
            $('#modal-quick-order').modal('show');
        });
    }
}

function stockByStore(p_id) {
    $('#modal-ms-stock [data-pov-id]').removeClass('active');

    var pov_id = $('#product [type="radio"]:checked').val() | 0;

    $('body').append('<div class="lds-ellipsis ms-stock"><div></div><div></div><div></div><div></div></div><div class="lds-ellipsis-backdrop"></div>');

    if ($('#modal-ms-stock').length) {
        $('#modal-ms-stock [data-pov-id="' + pov_id + '"]').addClass('active');
        $('.lds-ellipsis, .lds-ellipsis-backdrop').remove();
        $('#modal-ms-stock').modal('show');
    } else {
        $.get('index.php?route=product/ms_stock/modal&p_id=' + p_id + 'pov_id' + pov_id, function (data) {
            $('body').append(data);
            $('#modal-ms-stock [data-pov-id="' + pov_id + '"]').addClass('active');
            $('.lds-ellipsis, .lds-ellipsis-backdrop').remove();
            $('#modal-ms-stock').modal('show');
        });
    }
}

function quickOrderTotal(type) {
    $('#modal-quick-order .modal-total > div:last-child').text($('#cart .cart-total > div:last-child').text());
}

$(document).ready(function() {
    dv = getDV(wWidth);

    drag('.breadcrumb');
    drag('.pp-nav-tabs');

    owl_pp_i(dv);

    if (dv == 'm') {
        $('#cart .dropdown-menu').width(wWidth)

        $('body').delegate('.i-cart', 'click', function(e) {
        });

        $('body').delegate('#cart', 'show.bs.dropdown', function(e) {
            // $('header').addClass('fixed modal-open');
            $('body').addClass('modal-open');
        });

        $('body').delegate('#cart', 'hide.bs.dropdown', function(e) {
            /*$('header').removeClass('fixed');*/
            $('body').removeClass('modal-open');
        });

        if (typeof owlImages == 'function') {
            owlImages();
        }
    } else {
        if (typeof owlImages == 'function') {
            $('.images.owl-carousel').owlCarousel('destroy');
        }
    }

    // Menu
    if (dv == 'd') {
        $('#menu .dropdown').on('click.bs.dropdown', function(e) {
            if ($(this).hasClass('clickable')) {
                location = $(this).find('a[data-toggle="dropdown"]').attr('href');
            }
        });
    } else {
        $('#menu .dropdown.clickable').on('click.bs.dropdown', function(e) {
            if (typeof(e.target.href) != 'undefined') {
                e.preventDefault();
                e.stopPropagation();
                location = e.target.href;
            } else {
                var id = $(this).closest('li').data('id');
                var name = $(this).closest('li').find('.menu-nav-link > a').text();

                if (!$('.menu-child').hasClass('.menu-child' + id)) {
                    $.get('index.php?route=common/menu/getChildren&id=' + id, function (data) {
                        var html = '<div class="menu-child menu-child' + id + '">'
                        html += '<div class="menu-child-name d-f ai-c"><svg width="14" height="14"><use xlink:href="image/icons.svg#menu-back" xmlns:xlink="http://www.w3.org/1999/xlink"></use></svg>' + name + '</div>'
                        html += data;
                        html += '</div>';

                        $('#menu > div > ul.nav').stop().css('border-right', '1px solid #000').animate({width: '0px'}, 700,
                            function () {
                                $(this).hide();
                                $('#menu > div > ul.nav').after(html);
                            });
                    });
                } else {
                    $('#menu > div > ul.nav').stop().css('border-right', '1px solid #000').animate({width: '0px'}, 700,
                        function () {
                            $(this).hide();
                            $('.menu-child' + id).show();
                        });
                }
            }
        });

        $('#menu').delegate('.menu-child-name', 'click', function() {
            $('#menu .menu-child').hide();
            $('#menu > div > ul.nav').css({'width': 'auto', 'border-right': 'unset'}).show();
        });

        $('.menu-store-li').on('click', function(e) {
            if ($('.menu-store').hasClass('hidden')) {
                $('.menu-store').removeClass('hidden');
                $(this).addClass('open');
            } else {
                $(this).removeClass('open');
                $('.menu-store').addClass('hidden');
            }
        });
    }

    $('#menu .dropdown').on('mouseenter', function () {
        $('.menu-backdrop').addClass('show');
    });

    $('#menu .dropdown').on('mouseleave', function () {
        $('.menu-backdrop').removeClass('show');
    });

    $('#menu').on('show.bs.collapse', function () {
        $('body').addClass('modal-open');
        $(this).addClass('fixed');
        $('.menu-backdrop').addClass('show');

    });

    $('#menu').on('hidden.bs.collapse', function () {
        $(this).removeClass('fixed');
        $('.menu-backdrop').removeClass('show');
        $('body').removeClass('modal-open');
    });

    $('#menu').on('mouseenter', function (e) {
        if ($.trim($('#menu .brands-inner').html()) == '') {
            $.get('index.php?route=product/manufacturer/get', function(data) {
                $('#menu .brands-inner').html(data);
            });
        }
    });

    /* Search */
    $('.h-search .i-search').on('click', function(e) {
        if (!$('.h-icons').hasClass('active')) {
            if (wWidth <= 991) {
                $('.i-search').attr('class', 'i-owl-arrow2').attr('width', '27').attr('height', '27').find('use').attr('xlink:href', 'image/icons.svg#i-owl-arrow2');
            }

            if (wWidth <= 767) {
                $('body').addClass('modal-open');
            }

            var strLength = $('#search input').val().length * 2;

            $('.h-icons').addClass('active').find('input').focus();

            $('#search input')[0].setSelectionRange(strLength, strLength);
        } else {
            var url = $('base').attr('href') + 'index.php?route=product/search';

            var value = $('header #search input[name=\'search\']').val();

            if (value) {
                url += '&search=' + encodeURIComponent(value);
            }

            location = url;
        }
    });

    $('.btn-search-close').on('click', function(e) {
        $('body').removeClass('modal-open');
        $('.h-icons').removeClass('active');

        if ($('.h-search > svg').attr('class') == 'i-owl-arrow2') {
            $('.h-search > svg').attr('class', 'i-search').attr('width', '24').attr('height', '24').find('use').attr('xlink:href', 'image/icons.svg#i-search');
        }
    });

    $('#search input[name=\'search\']').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('.h-search .i-search, .h-search .i-owl-arrow2').trigger('click');
        }
    });

    // Cart Mini
    /*
    $('body').delegate('.i-cart', 'click', function(e) {
        if ($('#cart').hasClass('open')) {
            $('.menu-backdrop').removeClass('show');
        }
    });
     */


    $('.navbar-header').on('click', function(e) {
        $('.wrap_account_top .h-account').removeClass('active');
        $('.wrap_account_top .account_menu').hide();
    });
    $('.wrap_account_top .h-account').on('click', function(e) {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('.wrap_account_top .account_menu').hide();
            $('body').removeClass('modal-open');
            $('.menu-backdrop').removeClass('show');
            $('.navbar').removeClass('fixed');
            $('.navbar .navbar-collapse').attr('class','navbar-collapse navbar-menu-collapse collapse');
        }else{
            $(this).addClass('active');
            $('.wrap_account_top .account_menu').show();
            $('body').addClass('modal-open');
            $('.menu-backdrop').addClass('show');

        }
    });

    $('.account-breadcrumb ul.breadcrumb > li:nth-child(3)').on('click', function(e) {
        e.preventDefault();
        $('.wrap_account_top .h-account').trigger('click');
    });

        // product thumb swap image
    $('body').delegate('.product-thumb .image img', 'mouseenter mouseleave', function(e) {
        var el = $(this), img = el.attr('src'), alt = el.attr('data-alt'), id = el.closest('.product-thumb').data('id');

        if (typeof(id) == 'undefined') {
            return false;
        }

        if (e.type=='mouseenter') {
            if (typeof(alt) == 'undefined' || alt == '') {
                $.get('index.php?route=product/product_thumb_swap_image&id=' + el.closest('.product-thumb').data('id') + '&w=' + el.attr('width') + '&h=' + el.attr('height'), function(json) {
                    if (json['image'] && el.hasClass('hover') === false) {
                        el.attr('src', json['image']).attr('data-alt', img).addClass('hover');
                    }
                });
            } else {
                if (el.hasClass('hover') === false) {
                    el.attr('src', alt).attr('data-alt', img).addClass('hover');
                }
            }

        } else if (e.type=='mouseleave') {
            if (typeof(alt) != 'undefined' && alt != '' && el.hasClass('hover') === true) {
                el.attr('src', alt).attr('data-alt', img).removeClass('hover');
            }
        }
    });

    // footer mobile
    if (dv != 'd') {
        $('footer .row > div:first-child h5').on('click', function (e) {
            if ($(this).hasClass('open')) {
                $(this).removeClass('open');
            } else {
                $(this).addClass('open');
            }
        });
    }

    /* start Bootsrap moadal padding-right FIX */
    $('body').on('show.bs.modal', function () {
        var scrollbarWidth = (window.innerWidth - $(window).width());

        if (scrollbarWidth > 0) {
            $('header.fixed').css('padding-right', scrollbarWidth + 'px');
            $('.top-in').css({'padding-right' : scrollbarWidth + 'px', 'margin-right' : '-' + (scrollbarWidth * 2) + 'px', 'margin-left' : '-' + scrollbarWidth + 'px' });
        }
    });

    $('body').on('hidden.bs.modal', function () {
        $('header.fixed, .top-in').css('padding-right', '');
        $('.top-in').attr('style', '');
    });
    /* end Bootsrap moadal padding-right FIX */
});



