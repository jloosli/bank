/*global ACCESS_LEVELS:true */
angular.module('jrbank', ['ui.bootstrap', 'ui.utils', 'ui.router', 'ngAnimate']);

angular.module('jrbank')
    .constant('ACCESS_LEVELS', {
        pub: 0,
        user: 1,
        parent: 2,
        super: 3
    })
    .config(function ($stateProvider, $urlRouterProvider) {
    'use strict';
    /* Add New States Above */
    $urlRouterProvider.otherwise('/home');

});

angular.module('jrbank').run(function ($rootScope) {
    'use strict';

    $rootScope.safeApply = function (fn) {
        var phase = $rootScope.$$phase;
        if (phase === '$apply' || phase === '$digest') {
            if (fn && (typeof(fn) === 'function')) {
                fn();
            }
        } else {
            this.$apply(fn);
        }
    };

});

/*
Trying to follow http://toddmotto.com/opinionated-angular-js-styleguide-for-teams/
 */
