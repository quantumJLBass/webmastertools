( function( $, window, analytics ) {
    var rendered_accounts = [];

    // Track WSU global analytics for front end requests only.
    if ( analytics.app.view_type === "FrontEnd" || analytics.app.view_type === "unknown" ) {
        if ( analytics.wsuglobal.ga_code !== false ) {
            rendered_accounts = jQuery.merge( rendered_accounts, [ {
                id:analytics.wsuglobal.ga_code,
                settings:{
                    namedSpace:"WSUGlobal",
                    cookieDomain:analytics.defaults.cookieDomain,
                    dimension:[
                        { "name":"dimension1", "val": window.location.protocol },//Protocol <string> (http: / https:)
                        { "name":"dimension2", "val": analytics.wsuglobal.campus },//Campus <string>
                        { "name":"dimension3", "val": analytics.wsuglobal.college },//College <string>
                        { "name":"dimension4", "val": analytics.wsuglobal.unit },//Unit <string>
                        { "name":"dimension5", "val": analytics.wsuglobal.subunit },//Subunit <string>
                        { "name":"dimension6", "val": "" + analytics.app.is_editor },//Editor <bool>(as string)
                        { "name":"dimension7", "val": window.location.hostname },//Base site url <string>(as string)
                        { "name":"dimension8", "val": analytics.wsuglobal.unit_type }//Unit type <string>
                    ],
                    events: analytics.wsuglobal.events
                }
            } ] );
        }
    }

    // Track app level analytics for front end and admin requests.
    if ( analytics.app.ga_code !== false ) {
        rendered_accounts = jQuery.merge( rendered_accounts, [ {
            id: analytics.app.ga_code,
            settings:{
                namedSpace:"appScope",
                cookieDomain:analytics.defaults.cookieDomain,
                dimension:[
                    { "name":"dimension1", "val": analytics.app.is_editor },     // Front end or admin page view type
                    { "name":"dimension2", "val": window.location.hostname }, // Authenticated or non-authenticated user
                    { "name":"dimension3", "val": analytics.app.server_protocol },         // HTTP or HTTPS
                    { "name":"dimension4", "val": analytics.app.view_type }
                ],
                events: analytics.app.events
            }
        } ] );
    }

    // Track site level analytics for front end requests only.
    if ( analytics.app.view_type === "FrontEnd" || analytics.app.view_type === "unknown" ) {
        if ( analytics.site.ga_code !== false ) {
            rendered_accounts = jQuery.merge( rendered_accounts, [ {
                id: analytics.site.ga_code,
                settings:{
                    namedSpace:"siteScope",
                    cookieDomain:analytics.defaults.cookieDomain,
                    dimension:[
                        { "name":"dimension1", "val": "" + analytics.app.is_editor }//Editor <bool>(as string)
                    ],
                    ec: analytics.site.ec,
                    events: analytics.site.events
                }
            } ] );
        }
    }

    // Fire tracking on all merged accounts and events with jTrack.
    jQuery.jtrack( {
        analytics:{
            ga_name:"_wsuGA",
            accounts: rendered_accounts
        }
    } );
} )( jQuery, window, window.wsu_analytics );
