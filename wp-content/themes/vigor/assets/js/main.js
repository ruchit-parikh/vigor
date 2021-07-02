let markers = [];

$(document).ready(function() {
    let topMenu = $("#navbar");

    $(window).scroll(function() {
        let fromTop = $(this).scrollTop();
    
        if (fromTop > 100) {
            topMenu.addClass('scrolled vg-bg-primary');
        } else {
            topMenu.removeClass('scrolled vg-bg-primary');
        }
    });

    // Ajax add to cart
    var $wrapFragmentRefresh = {
        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
        type: 'POST',
        success: function(data) {
            if (data && data.fragments) {
    
                $.each(data.fragments, function(key, value) {
                    $(key).replaceWith(value);
                });
    
                $(document.body).trigger('wc_fragments_refreshed');
            }
        }
    };
    
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();

        let $this = $(this);
    
        let productUrl = $this.attr('href');

        let data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: $this.attr('data-product_id'),
            product_sku: $this.attr('data-product_sku'),
            quantity: $this.attr('data-quantity'),
            variation_id: $this.attr('data-variation_id'),
        };
    
        $.post(productUrl, JSON.stringify(data) + '&_wp_http_referer=' + productUrl, function (result) {
            $.ajax($wrapFragmentRefresh);
        });
    });

    //initialize google map
    initializeMap();
});

/**
 * Initialize google map with markers
 * All required data will be loaded through wordpress hook from backend
 */
function initializeMap() {
    let infowindow = new google.maps.InfoWindow({
        content: ''
    });

    let mapOptions = {
        scrollwheel: false,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        streetViewControl: false
    };

    let canvas = document.querySelector('#map-canvas');

    if (canvas) {
        let map = new google.maps.Map(canvas, mapOptions);

        loadMarkers(map, infowindow);

        //close info window when click on map
        google.maps.event.addListener(map, "click", function(event) {
            infowindow.close();

            resetMarkers();
        });

        //reset markers when close button is clicked
        google.maps.event.addListener(infowindow, 'closeclick', resetMarkers);
    }
}

/**
 * Load markers on given map
 * 
 * @param {Object} map
 * @param {Object} infowindow
 */
function loadMarkers(map, infowindow) {
    let bounds = new google.maps.LatLngBounds();

    for(let office of vg_map_data.locations) {
        let pos = new google.maps.LatLng(office.location.lat, office.location.lng);

        let mapPin = new google.maps.Marker({
            position: pos,
            map: map,
            icon: vg_map_data.marker
        });

        markers.push(mapPin);

        // Marker click listener
        google.maps.event.addListener(mapPin, 'click', function () {
            resetMarkers();

            mapPin.setIcon(vg_map_data.active_marker);

            infowindow.setContent(office.content);
            infowindow.open({
                anchor: mapPin,
                map,
            });

            map.setZoom(13);
        });

        bounds.extend(mapPin.position);
    }

    map.fitBounds(bounds);
}

function resetMarkers() {
    for(marker of markers) {
        marker.setIcon(vg_map_data.marker);
    }
}