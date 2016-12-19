/**
 * Created by Ludovic on 18-11-16.
 */
window.$ = window.jQuery = require('jquery');
//window.$ = $.extend(require('jquery-ui-bundle'));

$(document).ready(function() {

    var STATIONS = "";

    $.ajax({
        type: "POST",
        url: './api/mixed/stations',
        data: "",
        success: function(data) {
            STATIONS = data;
        }
    });



    $('form .station-autocomplete').autocomplete({
        source: function( request, response ) {
            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
            response( $.grep( STATIONS, function( item ){
                return matcher.test( item );
            }) );
        },
        autoFocus: true,
        delay: 500,
//        source: 'test'
    });
});