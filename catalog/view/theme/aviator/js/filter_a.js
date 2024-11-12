function fiaQ(fia, f = false) {
    var lq = String(document.location).split('?');
    var query = '';
    var filtered = '';

    lq[0] = lq[0].replace(/\/+$/, '');

    console.log(lq);

    if (fia == 'CA') {
        // lq[1] = '';
    } else if (fia == 'P') {
        if (!f) {
            var min = parseInt($('#fia-P-min').val().replace(/\ /, ''));
            var max = parseInt($('#fia-P-max').val().replace(/\ /, ''));
        } else {
            var min = 0;
            var max = 0;
        }

        filtered = min + ',' + max;
    } else {
        $('.fia-' + fia + ' [data-filter]').each(function () {
            if ($(this).is('.active')) {
                filtered += $(this).data('filter') + ',';
            }
        });
    }

    var data = {
        'fia'       : fia,
        'filtered'  : filtered.replace(/\,$/, ''),
        'query'     : lq[1] ? lq[1] : ''
    }

    console.log(data);

    $.ajax({
        url: 'index.php?route=extension/module/filter_a/query',
        type: 'post',
        dataType: 'json',
        data: data,
        beforeSend: function() {
            fia_beforeSend();
        },
        complete: function() {
            fia_complete();
        },
        success: function(json) {
            console.log(json);
            if (json['query']) {
                location = lq[0] + '/?' + json['query'];
            } else {
                location = lq[0];
            }
        }
    });
}

function applyB(fia, apply) {
    if (apply) {
        $('#filter-a > .row').addClass('mb60');
        $('.fia-apply').attr('data-fia', fia).removeClass('hidden');
    } else {
        $('#filter-a > .row').removeClass('mb60');
        $('.fia-apply').addClass('hidden');
    }
}

function fiaGrid(n, loc = true) {
    $.ajax({
        url: 'index.php?route=extension/module/filter_a/fiaGrid',
        type: 'post',
        dataType: 'json',
        data: { 'fiaGrid' : n },
        beforeSend: function() {
            if (loc) {
                fia_beforeSend();
            }
        },
        complete: function() {
            fia_complete();
        },
        success: function(json) {
            if (json['success'] && loc) {
                location = String(document.location);
            }
        }
    });
}

function fia_beforeSend() {
    if ($(window).width() > 991) {
        $('#filter-a .fia-in').removeClass('open');
    }

    $('#content').attr('style', 'opacity: 0.9');
    $('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>').prependTo($('#content'));
}

function fia_complete() {
    setTimeout(function() {
        $('#content').attr('style', '');
        $('.lds-ellipsis').remove();
    }, 250);
}

$(document).ready(function() {
    $('.fia-P-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        fiaQ('P');
    });

    if ($(window).width() > 991) {
        $('.fia-G-o, .fia-C-o, .fia-M-o, .fia-S-o').on('click', function (e) {
            if ($(this).hasClass('disabled')) {
                return false;
            }

            var fia = $(this).closest('[data-fia]').data('fia');

            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }

            fiaQ(fia);
        });
    } else {
        $('.fia-G-o, .fia-C-o, .fia-M-o, .fia-S-o').on('click', function () {
            if ($(this).hasClass('disabled')) {
                return false;
            }

            var fia     = $(this).closest('[data-fia]').data('fia');
            var apply   = false;
            var active  = false;

            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
                prefix = '+';
            }

            if ($(this).hasClass('apply')) {
                $(this).removeClass('apply');
            } else {
                $(this).addClass('apply');
            }

            if ($('.fia-' + fia).find('[data-filter].active').length > 0) {
                active = true;
            }

            $('.fia-' + fia + ' [data-filter]').each(function() {
                var prefix = '';

                var count = $(this).children(':nth-child(2)').text().replace(/\(|\+|\)/g, '');

                if (active && $(this).is('.active') && !$(this).is('.apply')) {
                    prefix = '';
                }

                if (active && $(this).is('.active') && $(this).is('.apply')) {
                    prefix = '+';
                }

                if (active && !$(this).is('.active')) {
                    prefix = '+';
                }

                $(this).children(':nth-child(2)').text('(' + prefix + count + ')');

                if ($(this).is('.apply')) {
                    apply = true;
                }
            });

            applyB(fia, apply);
        });
    }

    $('.fia-mobile').on('click', function(e) {
        $('body').addClass('modal-open');
        $('#filter-a').show();
    });

    $('.fia-i-close').on('click', function(e) {
        $('body').removeClass('modal-open');
        $('#filter-a').hide();
    });

    jQuery.fn.scrollTo = function(elem, speed) {
        $(this).animate({
            scrollTop:  $(this).scrollTop() - $(this).offset().top + $(elem).offset().top - 60
        }, speed == undefined ? 1000 : speed);

        return this;
    };

    $('#filter-a [data-toggle="dropdown"]').on('click', function() {
        var el      = $(this);
        var fia     = $(this).closest('[data-fia]').data('fia');
        var apply   = false;

        setTimeout(function() {

            $('.fia-in.open [data-filter]').each(function() {
                if ($(this).is('.apply')) {
                    apply = true;
                }
            });

            applyB(fia, apply);

            $('#filter-a').scrollTo(el, 500);
        }, 250);
    });

    $('.fia-grid-n > div').on('click', function() {
        fiaGrid($(this).find('span').length);
    });

    $('#filter-a-r .fia-i-clear').on('click', function() {
        var part = $(this).closest('[data-clear]').data('clear').split('=');

        if (part[0] == 'P' && part[1]) {
            fiaQ('P', '1')
        } else {
            $('.fia-' + part[0] + ' [data-filter="' + part[1] + '"]').removeClass('active');
            fiaQ(part[0]);
        }
    });

    var filtered = '';

    $('.fia-G .active').each(function() {
        filtered += $.trim($(this).text()) + ', ';
    });

    $('.fia-G .filtered').text(filtered.replace(/\,\ $/g, ''));

    var filtered = '';

    $('.fia-C .active').each(function() {
        filtered += $.trim($(this).text()) + ', ';
    });

    $('.fia-C .filtered').text(filtered.replace(/\,\ $/g, ''));

    var filtered = '';

    $('.fia-M .active').each(function() {
        filtered += $.trim($(this).text()) + ', ';
    });

    $('.fia-M .filtered').text(filtered.replace(/\,\ $/g, ''));

    var filtered = '';

    $('.fia-S .active').each(function() {
        filtered += $.trim($(this).text()) + ', ';
    });

    $('.fia-S .filtered').text(filtered.replace(/\,\ $/g, ''));

    $('.fia-sort .filtered').text($('.fia-sort .active').text());

    // Price Slider
    var fiaMinPrice = document.getElementById('fia-P-min');
    var fiaMaxPrice = document.getElementById('fia-P-max');

    fiaP.noUiSlider.on('update', function (values, handle) {
        var value = values[handle];

        if (!handle) {
            fiaMinPrice.value = value;
        } else {
            fiaMaxPrice.value = value;
        }
    });

    // Stop Close Dropdown
    $('#filter-a .dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
});
