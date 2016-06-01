( function( $, window ) {
window.wsu_analytics.wsuglobal.events = [
    {
        element:"#wsu-actions-tabs button",
        options:{
            action:function( ele ) {
                return "Action tab " + ( ele.closest( "li" ).is( ".opened" ) ? "opening" : "closing" );
            },
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return " " + $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-actions a",
        options:{
            action:"Action tab Content Click",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return $( ele ).text() + " - " + $( ele ).attr( "href" );
            },
            overwrites:"true"
        }
    },
    {
        element:"#spine nav li.parent > a",
        options:{
            action:function( ele ) {
                return "Couplets " + ( ele.closest( ".parent" ).is( ".opened" ) ? "opening" : "closing" );
            },
            eventTracked:"click",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return " " + $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-search input[type=text]",
        options:{
            action:"searching",
            eventTracked:"autocompletesearch",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return "" + $( ele ).val();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-social-channels a",
        options:{
            action:"social channel visited",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return "" + $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-global-links a",
        options:{
            action:"WSU global link visited",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return "" + $( ele ).text() + " - " + $( ele ).attr( "href" );
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-signature",
        options:{
            action:"WSU global logo clicked",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return $( ele ).attr( "href" );
            },
            overwrites:"true"
        }
    },
    {
        element:"#shelve",
        options:{
            action:"mobile menu icon clicked",
            category:"Spine Framework interactions",
            value:null,
            label:function() {
                return $( "#spine" ).is( ".shelved" ) ? "closed" : "opened" ;
            },
            overwrites:"true"
        }
    }
];
window.wsu_analytics.app.events    = [
    {
        element: "#wsu-actions-tabs button",
        options: {
            action:function( ele ) {
                return "Action tab tapped " + ( ele.closest( "li" ).hasClass( "opened" ) ? "closed" : "open" );
            },
            eventTracked: "touchend mouseup",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element: "#wsu-actions-tabs button",
        options: {
            action:function( ele ) {
                return "Action tab clicked " + ( ele.closest( "li" ).hasClass( "opened" ) ? "open" : "closed" );
            },
            eventTracked: "click",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-actions a",
        options:{
            action:"Action tab link followed",
            category:"Spine Framework interactions",
            value:null,
            eventTracked: "click",
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#spine nav li.parent > a",
        options:{
            action:function( ele ) {
                return "Couplet clicked " + ( ele.closest( ".parent" ).hasClass( "opened" ) ? "open" : "closed" );
            },
            eventTracked:"click",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#spine nav li.parent > a",
        options:{
            action:function( ele ) {
                return "Couplet tapped " + ( ele.closest( ".parent" ).hasClass( "opened" ) ? "closed" : "open" );
            },
            eventTracked:"touchend",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-search input[type=text]",
        options:{
            action:"searching",
            eventTracked:"autocompletesearch",
            category:"Spine Framework interactions",
            value:null,
            label:function( ele ) {
                return "" + $( ele ).val();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-social-channels a",
        options:{
            action:"Social channel link followed",
            category:"Spine Framework interactions",
            eventTracked: "click",
            value:null,
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-global-links a",
        options:{
            action:"WSU global link followed",
            category:"Spine Framework interactions",
            value:null,
            eventTracked: "click",
            label:function( ele ) {
                return $( ele ).text();
            },
            overwrites:"true"
        }
    },
    {
        element:"#wsu-signature",
        options:{
            action:"WSU global logo clicked",
            category:"Spine Framework interactions",
            value:null,
            eventTracked: "click",
            label:function( ele ) {
                return $( ele ).attr( "href" );
            },
            overwrites:"true"
        }
    },
    {
        element:"#shelve",
        options:{
            action:"Mobile menu icon tapped",
            eventTracked: "touchend",
            category:"Spine Framework interactions",
            value:null,
            label:function() {
                if ( $( "html" ).hasClass( "spine-mobile-open" ) ) {
                    return "close";
                } else {
                    return "open";
                }
            },
            overwrites:"true"
        }
    },
    {
        element: "#shelve",
        options: {
            action: "Mobile menu icon clicked",
            eventTracked: "click",
            category: "Spine Framework interactions",
            value:null,
            label:function() {
                if ( $( "html" ).hasClass( "spine-mobile-open" ) ) {
                    return "close";
                } else {
                    return "open";
                }
            },
            overwrites:"true"
        }
    }
];
window.wsu_analytics.site.events   = [
    {
        element:"a[href^='http']:not([href*='wsu.edu']), .track.outbound",
        options:{
            mode:"event",
            category:"outbound",
            action:"click"
        }
    },
    {
        element:"a[href*='wsu.edu']:not([href*='**SELF_DOMAIN**']), .track.internal",
        options:{
            skip_internal:"true",
            mode:"event",
            category:"internal",
            action:"click"
        }
    },
    {
        element:"a[href*='zzusis.wsu.edu'], " +
                "a[href*='portal.wsu.edu'], " +
                "a[href*='applyweb.com/public/inquiry'], " +
                "a[href*='www.mme.wsu.edu/people/faculty/faculty.html'], " +
                "a[href*='puyallup.wsu.edu'], " +
                ".track.internal.query_intolerant",
        options:{
            skip_internal:"true",
            overwrites:"true",
            mode:"event",
            category:"internal-query-intolerant",
            action:"click"

        }
    },

    // Externals that are known to be url query intolerant.
    {
        element:"a[href*='tinyurl.com']," +
                "a[href*='ptwc.weather.gov'], " +
                "a[href*='www.atmos.washington.edu'], " +
                ".track.outbound.query_intolerant",
        options:{
            skip_internal:"true",
            overwrites:"true",
            mode:"event",
            category:"outbound-query-intolerant",
            action:"click"

        }
    },
    {
        element:".youtube,.youtube2",
        options:{
            action:"youtube",
            category:"videos",
            label:function( ele ) {
                return ( ( $( ele ).attr( "title" ) !== "" && typeof( $( ele ).attr( "title" ) ) !== "undefined" ) ? $( ele ).attr( "title" ) : $( ele ).attr( "href" ) );
            },
            overwrites:"true"
        }
    },
    {
        element: "a[href*='.jpg'], a[href*='.zip'], a[href*='.tiff'], a[href*='.tif'], " +
                 "a[href*='.bin'], a[href*='.Bin'], a[href*='.eps'], a[href*='.gif'], " +
                 "a[href*='.png'], a[href*='.ppt'], a[href*='.pdf'], a[href*='.doc'], " +
                 "a[href*='.docx'], " +
                 ".track.jpg, .track.zip, .track.tiff, .track.tif, " +
                 ".track.bin, .track.Bin, .track.eps, .track.gif, " +
                 ".track.png, .track.ppt, .track.pdf, .track.doc, " +
                 " .track.docx",
        options:{
            action:function( ele ) {
                var href_parts = $( ele ).attr( "href" ).split( "." );
                return href_parts[ href_parts.length - 1 ];
            },
            category:"download",
            label:function( ele ) {
                return ( ( $( ele ).attr( "title" ) !== "" && typeof( $( ele ).attr( "title" ) ) !== "undefined" ) ? $( ele ).attr( "title" ) : $( ele ).attr( "href" ) );
            },
            overwrites:"true"
        }
    },

    //This should be built on which are loading in the customizer
    {
        element:"a[href*='facebook.com']",
        options:{
            category:"Social",
            action:"Facebook",
            overwrites:"true"
        }
    },
    {
        element:"a[href*='.rss'],.track.rss",
        options:{
            category:"Feed",
            action:"RSS",
            overwrites:"true"
        }
    },
    {
        element:"a[href*='mailto:'],.track.email",
        options:{
            category:"email",
            overwrites:"true"
        }
    },
    //view item
    {
        element:".catalog-category-view .item a.product-image",
        options:{
            category:"Product views",
            action:"viewed product - via image click",
            label:function( ele ) {
                var label = $( ele ).attr( "title" );
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }
                return label;
            },
            overwrites:"true"
        }
    },
    {
        element:".catalog-category-view .item .product-name a",
        options:{
            category:"email",
            action:"viewed product - via name click",
            label:function( ele ) {
                var label = $( ele ).text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }
                return label;
            },
            overwrites:"true"
        }
    },
    //add to cart
    {
        element:".catalog-category-view .item button.btn-cart",
        options:{
            category:"Cart events",
            action:"added to - via category view",
            label:function( ele ) {
                var label = $( ele ).closest(".item").find(".product-name").text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }if( "undefined" === label || "" === label ){
                    label = "**template didn't provide findable name***";
                }
                return label;
            },
            overwrites:"true"
        }
    },
    {
        element:"#product-addtocart-button",
        options:{
            category:"Cart events",
            action:"added to - via product view",
            label:function( ele ) {
                var label = $( ele ).closest(".product-view").find(".product-name").text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }if( "undefined" === label || "" === label ){
                    label = "**template didn't provide findable name***";
                }
                return label;
            },
            overwrites:"true",
            ec:{
                data:{
                    type:"addProduct",
                    data:{
                        id:function(){ //note that we will need to address the sku and variant at the same time
                            var sku = window.optionsPrice.productId;
                            if($('[property="gr:hasStockKeepingUnit"]').length){
                                sku = $('[property="gr:hasStockKeepingUnit"]').attr("content");
                            }
                            return sku;
                        },
                        name:function(){ return $('[property="gr:name"]').attr("content"); },
                        brand:"WSU",
                        category:function(){
                            var cats = [];
                            $('[property="gr:category"]').each(function(){
                                cats.push($(this).attr("content"));
                            });
                            return cats.join("/");
                        },
//variant: to come
                        price:function(){ return $('[property="gr:hasCurrencyValue"]').attr("content"); },
                        quantity:function(){ return $("#qty").val(); },
//coupon	text	No	The coupon code associated with a product (e.g. SUMMER_SALE13). to come

                    }
                },
                action:{
                    type:"add"
                }
            }
        }
    },
    {
        element:".checkout-cart .btn-update",
        options:{
            category:"Cart events",
            action:"updated - via cart view",
            label:function( ele ) {
                var label = $( ele ).closest(".product-view").find(".product-name").text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }if( "undefined" === label || "" === label ){
                    label = "**template didn't provide findable name***";
                }
                return label;
            },
            overwrites:"true"
        }
    },
    {
        element:".checkout-cart-configure .btn-cart",
        options:{
            category:"Cart events",
            action:"updated - via product view",
            label:function( ele ) {
                var label = $( ele ).closest(".product-view").find(".product-name").text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }if( "undefined" === label || "" === label ){
                    label = "**template didn't provide findable name***";
                }
                return label;
            },
            overwrites:"true"
        }
    },
    {
        element:".checkout-cart a[href*='checkout/cart/configure']",
        options:{
            category:"Cart events",
            action:"editing - from cart moving to product view",
            label:function( ele ) {
                var label = $( ele ).closest("tr").find(".product-name").text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }if( "undefined" === label || "" === label ){
                    label = "**template didn't provide findable name***";
                }
                return label;
            },
            overwrites:"true"
        }
    },
    {
        element:".checkout-cart .btn-remove",
        options:{
            category:"Cart events",
            action:"removing item - via cart view",
            label:function( ele ) {
                var label = $( ele ).closest("tr").find(".product-name").text();
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "title" );
                }
                if( "undefined" === label || "" === label ){
                    label = $( ele ).attr( "href" );
                }if( "undefined" === label || "" === label ){
                    label = "**template didn't provide findable name***";
                }
                return label;
            },
            overwrites:"true"
        }
    },






    {
        element:".catalog-category-view .filtering_button",
        options:{
            category:"Filtering",
            action:"via filter block",
            label:function( ele ) {
                var label = "closed";
                if($( ele ).is(".open")){
                    label = "opened";
                }
                return label;
            },
            overwrites:"true"
        }
    },
    {
        element:".catalog-category-view .sorting_button",
        options:{
            category:"Filtering",
            action:"via sorting block",
            label:function( ele ) {
                var label = "closed";
                if($( ele ).is(".open")){
                    label = "opened";
                }
                return label;
            },
            overwrites:"true"
        }
    },

    {
        element: ".more-views a[href*='.jpg']",
        options:{
            action:"Opened image",
            category:"Product Details",
            label:function( ele ) {
                var href_parts = $( ele ).attr( "href" ).split( "." );
                return href_parts[ href_parts.length - 1 ];
            },
            overwrites:"true"
        }
    },

];
} )( jQuery, window );
