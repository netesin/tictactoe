'use strict';

/**
 * Requirejs config
 */
requirejs.config(
    {
        
        baseUrl    : 'bundles/app',
        waitSeconds: 30,
        
        /**
         * Modules ids.
         */
        paths: {
            'domReady': 'lib/requirejs-domready/domReady',
            'angular' : 'lib/angular/angular',
            'jquery'  : 'lib/jquery/jquery'
        },
        
        /**
         * for libs that either do not support AMD out of the box, or
         * require some fine tuning to dependency mgt'
         */
        shim: {
            'angular'          : {
                exports: 'angular'
            },
            'angular-ui-router': {
                deps: ['angular']
            }
        },
        
        deps: [
            'js/main'
        ]
        
    }
);