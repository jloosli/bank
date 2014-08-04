/*global ACCESS_LEVELS:true */
angular.module('jrbank', ['ui.bootstrap', 'ui.utils', 'ui.router', 'ngAnimate','ngResource', 'directive.g+signin']);

angular.module('jrbank')
    .constant('ACCESS_LEVELS', {
        pub: 0,
        user: 1,
        parent: 2,
        super: 3
    })
    .constant('API_URL', "/api/")
    .config(function ($stateProvider, $urlRouterProvider) {
    'use strict';
        $stateProvider.state('accounts', {
            url: '/accounts',
            templateUrl: 'accounts/accounts-list/accounts-list.html',
            controller: 'AccountsListCtrl as accounts'
        });
    $stateProvider.state('login', {
        url: '/user/login',
        templateUrl: 'partial/login/login.html'
    });
    /* Add New States Above */
    $urlRouterProvider.otherwise('/home');

});

angular.module('jrbank').run(function ($rootScope, $http, authService) {
    'use strict';

    authService.init();


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
Trying to follow @link http://toddmotto.com/opinionated-angular-js-styleguide-for-teams/
 */
