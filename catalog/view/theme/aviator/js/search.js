function doLiveSearch( ev, keywords ) {

    if( ev.keyCode == 38 || ev.keyCode == 40 ) {
        return false;
    }

    $('#search-r').remove();
    updown = -1;

    if( keywords == '' || keywords.length < 2 ) {
        return false;
    }

    keywords = encodeURI(keywords);

    $.ajax({
        url: $('base').attr('href') + 'index.php?route=extension/module/search/ajax&keyword=' + keywords,
        dataType: 'json',
        success: function(json) {

            if( (json['products'] && json['products'].length) || (json['catgories'] && json['catgories'].length) ) {
                var eDiv = document.createElement('div');
                eDiv.id = 'search-r';

                var eListElem;
                var eLink;
                var eImage;

                products = json['products'];
                catgories = json['categories'];

                if (products.length > 0) {
                    eListElem = document.createElement('div');

                    var eList = document.createElement('ul');
                    eList.className = 's-product';

                    eListElem = document.createElement('li');
                    eLink = document.createElement('a');

                    var textNode = document.createTextNode(json['text_all_search']);
                    eLink.appendChild(textNode);

                    eLink.href = $('base').attr('href') + 'index.php?route=product/search&search=' + keywords;
                    eListElem.appendChild(eLink);
                    eList.appendChild(eListElem);

                    eDiv.appendChild(eList);

                    for (var i in products) {
                        eListElem = document.createElement('li');
                        eLink = document.createElement('a');

                        if ((products[i].thumb) != '') {
                            var eIm = document.createElement('div');
                            eIm.className = 's-img';
                            eImage = document.createElement('img');
                            eImage.src = products[i].thumb;
                            eIm.appendChild(eImage);
                            eLink.appendChild(eIm);
                        }

                        var eCap = document.createElement('div');
                        eCap.className = 's-cap';

                        var el_span = document.createElement('div');
                        el_span.className = 's-name';

                        var textNode = document.createTextNode(products[i].name);
                        eCap.appendChild(el_span);
                        el_span.appendChild(textNode);
                        eLink.appendChild(eCap);

                        if (typeof (products[i].href) != 'undefined') {
                            eLink.href = products[i].href;
                        } else {
                            eLink.href = $('base').attr('href') + 'index.php?route=product/product&product_id=' + products[i].product_id + '&keyword=' + keywords;
                        }

                        eListElem.appendChild(eLink);

                        if ((products[i].price) != '') {
                            var el_span = document.createElement('div');
                            el_span.className = 'price';

                            if (products[i].special != '') {
                                el_span.innerHTML += '<div class="price-new">' + products[i].special +'</div> <div class="price-old">' + products[i].price + '</div>'
                                eCap.appendChild(el_span);
                                eLink.appendChild(eCap);
                            } else {
                                var textNode = document.createTextNode(products[i].price);
                                eCap.appendChild(el_span);
                                el_span.appendChild(textNode);
                                eLink.appendChild(eCap);
                            }
                        }

                        eList.appendChild(eListElem);
                    }

                    eDiv.appendChild(eList);
                }

                if (catgories.length > 0) {
                    eListElem = document.createElement('div');
                    eListElem.className = 's-text';

                    var textNode = document.createTextNode(json['text_go_to_category']);
                    eListElem.appendChild(textNode);
                    eDiv.appendChild(eListElem);

                    var eList = document.createElement('ul');
                    eList.className = 's-category';

                    for (var i in catgories) {
                        eListElem = document.createElement('li');
                        eLink = document.createElement('a');

                        var svgElem = document.createElementNS('http://www.w3.org/2000/svg', 'svg'),
                            useElem = document.createElementNS('http://www.w3.org/2000/svg', 'use');

                        useElem.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', 'image/icons.svg#i-grid');
                        svgElem.appendChild(useElem);
                        svgElem.classList.add('i-grid');
                        svgElem.setAttribute('width', '16');
                        svgElem.setAttribute('height', '16');
                        eLink.appendChild(svgElem);

                        var el_span = document.createElement('div');
                        el_span.className = 's-name';
                        el_span.innerHTML += catgories[i].name
                        eLink.appendChild(el_span);
                        eLink.href = catgories[i].href;

                        eListElem.appendChild(eLink);
                        eList.appendChild(eListElem);
                    }

                    eDiv.appendChild(eList);
                }

                if ($('#search-r').length > 0) {
                    $('#search-r').remove();
                }

                $('#search').append(eDiv);

                // $('#search-r').addClass('scroll').css('maxHeight', '400px');
                $('#search-r').addClass('scroll');

                /*
                $('#search-r').mCustomScrollbar({
                    theme: "dark",
                    scrollbarPosition: "inside",
                    mouseWheel: {
                        preventDefault: !0,
                    },
                    callbacks: {
                        onInit: function () {
                            $('#search-r .mCSB_container').css('margin-right', '8px');
                        }
                    }
                });
                 */
            }
        }});

    return true;
}

function upDownEvent( ev ) {
    var elem = document.getElementById('search-r');
    var fkey = $('#search').find('[name=search]').first();


    if( elem ) {
        var length = elem.childNodes.length - 1;

        if( updown != -1 && typeof(elem.childNodes[updown]) != 'undefined' ) {
            $(elem.childNodes[updown]).removeClass('highlighted');
        }

        // Up
        if( ev.keyCode == 38 ) {
            updown = ( updown > 0 ) ? --updown : updown;
        }
        else if( ev.keyCode == 40 ) {
            updown = ( updown < length ) ? ++updown : updown;
        }

        if( updown >= 0 && updown <= length ) {
            $(elem.childNodes[updown]).addClass('highlighted');

            var text = elem.childNodes[updown].childNodes[0].text;
            if( typeof(text) == 'undefined' ) {
                text = elem.childNodes[updown].childNodes[0].innerText;
            }

        }
    }

    return false;
}

var updown = -1;

$(document).ready(function(){
    $('#search').find('[name=search]').attr('autocomplete', 'off');

    $('#search').find('[name=search]').first().keyup(function(ev){
        doLiveSearch(ev, this.value);
    }).focus(function(ev){
        $('.place_line__compare').css({ 'border-left-color' : 'transparent' });
        doLiveSearch(ev, this.value);
    }).keydown(function(ev){
        //upDownEvent( ev );
    }).blur(function(){
        //-- $('.place_line__compare').css({ 'border-left-color' : '' });
        //window.setTimeout("$('#search-r').remove();updown=0;", 1500);
    });
    $(document).bind('keydown', function(ev) {
        try {
            if( ev.keyCode == 13 && $('.highlighted').length > 0 ) {
                document.location.href = $('.highlighted').find('a').first().attr('href');
            }
        }
        catch(e) {}
    });
});
