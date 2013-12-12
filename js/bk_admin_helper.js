function add_form( element_id ) {
    var element_display = document.getElementById( "new_" + element_id + "_block" ).style.display;

    if ( element_display == 'none' ) {
        $( "#new_" + element_id + "_block" ).show();
    } else {
        $( "#new_" + element_id + "_block" ).hide();
    }
}

function serialize( mixed_value ) {
    var _getType = function ( inp ) {
        var type = typeof inp, match;
        var key;
        if ( type == 'object' && !inp ) {
            return 'null';
        }
        if ( type == "object" ) {
            if ( !inp.constructor ) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match( /(\w+)\(/ );
            if ( match ) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for ( key in types ) {
                if ( cons == types[key] ) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType( mixed_value );
    var val, ktype = '';

    switch( type ) {
        case "function":
            val = "";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round( mixed_value ) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            mixed_value = this.utf8_encode( mixed_value );
            val = "s:" + encodeURIComponent( mixed_value ).replace( /%../g, 'x' ).length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
             if (type == "object") {
             var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
             if (objname == undefined) {                    return;
             }
             objname[1] = this.serialize(objname[1]);
             val = "O" + objname[1].substring(1, objname[1].length - 1);
             }            */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for ( key in mixed_value ) {
                ktype = _getType( mixed_value[key] );
                if ( ktype == "function" ) {
                    continue;
                }
                okey = (key.match( /^[0-9]+$/ ) ? parseInt( key, 10 ) : key);
                vals += this.serialize( okey ) +
                        this.serialize( mixed_value[key] );
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP            val = "N";
            break;
    }
    if ( type != "object" && type != "array" ) {
        val += ";";
    }
    return val;
}

function utf8_encode( argString ) {
    // Encodes an ISO-8859-1 string to UTF-8  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/utf8_encode    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'    
    var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "";
    var start, end;
    var stringl = 0;
    start = end = 0;
    stringl = string.length;
    for ( var n = 0; n < stringl; n++ ) {
        var c1 = string.charCodeAt( n );
        var enc = null;

        if ( c1 < 128 ) {
            end++;
        } else if ( c1 > 127 && c1 < 2048 ) {
            enc = String.fromCharCode( (c1 >> 6) | 192 ) + String.fromCharCode( (c1 & 63) | 128 );
        } else {
            enc = String.fromCharCode( (c1 >> 12) | 224 ) + String.fromCharCode( ((c1 >> 6) & 63) | 128 ) + String.fromCharCode( (c1 & 63) | 128 );
        }
        if ( enc !== null ) {
            if ( end > start ) {
                utftext += string.substring( start, end );
            }
            utftext += enc;
            start = end = n + 1;
        }
    }

    if ( end > start ) {
        utftext += string.substring( start, string.length );
    }

    return utftext;
}

function dump( obj, step ) {
    if ( typeof step == 'undefined' ) {
        step = -1;
    }
    step++;
    var pad = new Array( 2 * step ).join( '   ' );
    var str = typeof(obj) + ":\n";
    for ( var p in obj ) {
        if ( typeof obj[p] == 'object' ) {
            str += pad + '   [' + p + '] = ' + dump( obj[p], step );
        } else {
            str += pad + '   [' + p + '] = ' + obj[p] + "\n";
        }
    }
    return str;
}

function change_price_value( item_id, flag ) {
    if ( flag != undefined && flag != "" ) {
        if ( flag == 'change' ) {
            var priceType = $( '#price_select_change_' + item_id ).val();
            var uahVal = $( '#cr_val_' + item_id ).val();

            var usd = parseFloat( $( '#cr_usd' ).text() );
            var eur = parseFloat( $( '#cr_eur' ).text() );

            if ( priceType != undefined && priceType != '' ) {
                var price_uah;
                var price_usd;
                var price_eur;

                switch( priceType ) {
                    case "uah":
                        price_uah = js_round( uahVal );
                        price_usd = js_round( uahVal / usd );
                        price_eur = js_round( uahVal / eur );
                        break;

                    case "usd":
                        price_uah = js_round( uahVal * usd );
                        price_usd = js_round( uahVal );
                        price_eur = js_round( ( uahVal * usd ) / eur );
                        break;

                    case "eur":
                        price_uah = js_round( uahVal * eur );
                        price_usd = js_round( ( uahVal * eur ) / usd );
                        price_eur = js_round( uahVal );
                        break;
                }
            }

            $( '#cr_uah_' + item_id ).text( price_uah );
            $( '#cr_usd_' + item_id ).text( price_usd );
            $( '#cr_eur_' + item_id ).text( price_eur );

        } else if ( flag == 'display' ) {
            var price_name = $( "#price_select_" + item_id + " option:selected" ).val();
            var price = js_round( parseInt( $( '#item_price_' + item_id ).val() ) * parseFloat( $( '#cr_' + price_name ).text() ) );
            $( '#price_item_' + item_id ).text( price );

        } else if ( flag == 'hs_set' ) {
            $( '#cr_val_' + item_id ).val( $( '#item_price_' + item_id ).val() );
            change_price_value( item_id, 'change' );
        }
    }
}

function js_round( number ) {
    var number_arr = number.toString().split( "." );
    if ( number_arr[1] != undefined ) number = number_arr[0] + "." + number_arr[1].substr( 0, 3 );
    else number = number_arr[0];
    return number;
}
