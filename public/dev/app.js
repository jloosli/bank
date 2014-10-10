/*global ACCESS_LEVELS:true */
angular.module('jrbank', ['ui.bootstrap', 'ui.utils', 'ui.router', 'ngAnimate', 'ngResource', 'Satellizer']);

angular.module('jrbank')
    .constant('ACCESS_LEVELS', {
        pub:    0,
        user:   1,
        parent: 2,
        super:  3
    })
    .constant('API_URL', "/api/")
    .config(function ($authProvider) {
        $authProvider.google({
            clientId: '554520232798-g78ah8n025muphdv0tddf6de3baje83k.apps.googleusercontent.com',
            redirectUri: window.location.origin + '/dev/index.html',
            url: window.location.origin + '/auth/google'
        });
        $authProvider.github({
            clientId: 'ef17c21a425f8310c1ab',
            redirectUri: window.location.origin + '/dev/index.html',
            url: window.location.origin + '/auth/github'

        });
    })
    .config(function ($stateProvider, $urlRouterProvider, ACCESS_LEVELS) {
        'use strict';
        $stateProvider
            .state('root', {
                url:         '',
                //abstract: true,
                views: {
                    header: {
                        templateUrl: 'partial/bank-header/bank-header.html',
                        controller:  'bankHeaderCtrl as bankHeader'
                    },
                    footer: {
                        templateUrl: 'partial/bank-header/bank-header.html',
                        controller:  'bankHeaderCtrl as bankHeader'
                    }
                }
            })
            .state('root.accounts', {
                url:         '/accounts/',
                views: {
                    "container@": {
                        templateUrl: 'accounts/accounts-list/accounts-list.html',
                        controller:  'AccountsListCtrl as accounts'
                    }
                },
                data:        {
                    access: ACCESS_LEVELS.user
                }
            })
            .state('root.account-details', {
                url:         '/accounts/:id/',
                views: {
                    "container@": {
                templateUrl: 'accounts/account-details/account-details.html',
                controller: 'AccountDetailsCtrl as accountDetails'
                    }
                },
                data: {
                    access: ACCESS_LEVELS.user
                }
            })
            .state('root.account-details.transaction-add', {
                url: 'add/',
                views: {
                    "popins": {
                        templateUrl: 'accounts/transaction-add/transaction-add.html',
                controller: 'TransactionAddCtrl as transactionAdd'
                    }
                },
                data: {
                    access: ACCESS_LEVELS.user
                }
            })
            .state('root.account-details.envelopes', {
                url: 'envelopes/',
                views: {
                    "popins": {
                        templateUrl: 'accounts/account-envelopes/account-envelopes.html',
                controller: 'AccountEnvelopesCtrl as accountEnvelopes'
                    }
                },
                data: {
                    access: ACCESS_LEVELS.user
                }
            })
            .state('root.login', {
                url:         '/user/login/',
                views: {
                    "container@": {
                        templateUrl: 'partial/login/login.html',
                controller:  'LoginCtrl as login'
                    }
                },
                data:        {
                    access: ACCESS_LEVELS.pub
                }
            });
            //.state('root.home', {
            //    url:         '/',
            //    templateUrl: 'partial/home/home.html',
            //    controller:  'HomeCtrl as home',
            //    data:        {
            //        access: ACCESS_LEVELS.pub
            //    }
            //});


        /* Add New States Above */
        $urlRouterProvider.otherwise('/');

        // Always use slashes at the end
        $urlRouterProvider.rule(function($injector, $location) {
            var path = $location.url();
            // check to see if the path already has a slash where it should be
            if (path[path.length - 1] === '/' || path.indexOf('/?') > -1) {
                return ;
            }

            if (path.indexOf('?') > -1) {
                return path.replace('?', '/?');
            }

            return path + '/';
        });

    });

angular.module('jrbank').run(function ($rootScope, $http, $auth, $state, authService) {
    'use strict';

    authService.init();

    $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
        if (!(_.has(toState, "data") && _.has(toState['data'], 'access'))) {
            $rootScope.error = "Access undefined for this state";
            console.log(toState);

            event.preventDefault();
        } else if (!authService.checkAccess(toState.data.access)) {
            $rootScope.error = "Seems like you tried accessing a route you don't have access to...";
            event.preventDefault();

            if (fromState.url === '^') {
                if ($auth.isAuthenticated()) {
                    $state.go('accounts');
                } else {
                    $rootScope.error = null;
                    $state.go('login');
                }
            }
        } else if (toState.name === 'login' && $auth.isAuthenticated()) {
            event.preventDefault();
            $state.go('accounts');
        }
    });


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
