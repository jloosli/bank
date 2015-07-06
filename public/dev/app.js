/* @ngInject */
(function () {
    angular.module('jrbank', ['ui.bootstrap', 'ui.utils', 'ui.router', 'ngAnimate', 'ngResource', 'ngMessages', 'ngSanitize', 'satellizer']);

    var hostparts = window.location.hostname.split('.'),
        API_URL = window.location.protocol + '//' + hostparts[hostparts.length - 2] + '.' + hostparts[hostparts.length - 1] + '/';
    console.log(API_URL);
    angular.module('jrbank')
        .constant('ACCESS_LEVELS', {
            pub:    0,
            user:   1,
            parent: 2,
            super:  3
        })
        .constant('API_URL', API_URL + 'api/')
        .config(function ($authProvider) {
            $authProvider.google({
                clientId:    '554520232798-g78ah8n025muphdv0tddf6de3baje83k.apps.googleusercontent.com',
                redirectUri: window.location.origin,
                url:         API_URL + 'auth/google'
            });
            $authProvider.github({
                clientId:    'ef17c21a425f8310c1ab',
                redirectUri: window.location.origin,
                url:         API_URL + '/auth/github'
            });
            $authProvider.loginUrl = API_URL + 'auth/login';
            $authProvider.signupUrl = API_URL + 'auth/signup';
            $authProvider.withCredentials = false;
        })
        .config(function ($stateProvider, $urlRouterProvider, $httpProvider, ACCESS_LEVELS) {
            'use strict';
            $stateProvider
                .state('root', {
                    url:      '',
                    abstract: true,
                    views:    {
                        header: {
                            templateUrl: 'partial/bank-header/bank-header.html',
                            controller:  'BankHeaderCtrl as bankHeader'
                        },
                        footer: {
                            templateUrl: 'partial/bank-footer/bank-footer.html',
                            controller:  'BankFooterCtrl as bankFooter'
                        }
                    },
                    data:     {
                        access: ACCESS_LEVELS.pub
                    }
                })
                .state('root.accounts', {
                    url:   '/accounts/',
                    views: {
                        "container@": {
                            templateUrl: 'accounts/accounts-list/accounts-list.html',
                            controller:  'AccountsListCtrl as accounts'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.account-details', {
                    url:   '/accounts/:id/',
                    views: {
                        "container@": {
                            templateUrl: 'accounts/account-details/account-details.html',
                            controller:  'AccountDetailsCtrl as accountDetails'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.account-details.transaction-add', {
                    url:   'add/',
                    views: {
                        "popins": {
                            templateUrl: 'accounts/transaction-add/transaction-add.html',
                            controller:  'TransactionAddCtrl as transactionAdd'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.account-details.envelopes', {
                    url:   'envelopes/',
                    views: {
                        "popins": {
                            templateUrl: 'accounts/account-envelopes/account-envelopes.html',
                            controller:  'AccountEnvelopesCtrl as accountEnvelopes'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                }).state('root.manage', {
                    url:   '/manage/',
                    views: {
                        "container@": {
                            templateUrl: 'partial/manage/main/manage.html',
                            controller:  'ManageCtrl as AccountManagement'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.manage.manage-bank', {
                    url:   'bank/',
                    views: {
                        manage: {
                            templateUrl: 'partial/manage/manage-bank/manage-bank.html',
                            controller:  'ManageBankCtrl as ManageBank'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.manage.manage-users', {
                    url:     'users/',
                    views:   {
                        manage: {
                            templateUrl: 'partial/manage/manage-users/manage-users.html',
                            controller:  'ManageUsersCtrl as manageUsers'
                        }
                    },
                    resolve: {
                        banksService: 'banksService',
                        users:        function (banksService) {
                            return banksService.users().get().$promise;
                        }
                    },
                    data:    {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.manage.manage-users.manage-users-edit', {
                    url:   'edit/:id/',
                    views: {
                        edit: {
                            templateUrl: 'partial/manage/manage-users-edit/manage-users-edit.html',
                            controller:  'ManageUsersEditCtrl as editUser'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.user
                    }
                })
                .state('root.login', {
                    url:   '/user/login/',
                    views: {
                        "container@": {
                            templateUrl: 'partial/login/login.html',
                            controller:  'LoginCtrl as login'
                        }
                    },
                    data:  {
                        access: ACCESS_LEVELS.pub
                    }
                });


            $stateProvider.state('super', {
                url:         '/super',
                templateUrl: 'partial/super/super.html'
            });
            /* Add New States Above */
            $urlRouterProvider.otherwise('/user/login/'); // This needs to point to a public url

            // Always use slashes at the end
            $urlRouterProvider.rule(function ($injector, $location) {
                var path = $location.url();
                // check to see if the path already has a slash where it should be
                if (path[path.length - 1] === '/' || path.indexOf('/?') > -1) {
                    return;
                }

                if (path.indexOf('?') > -1) {
                    return path.replace('?', '/?');
                }

                return path + '/';
            });

            // Asynchronous $digest (see http://blog.thoughtram.io/angularjs/2015/01/14/exploring-angular-1.3-speed-up-with-applyAsync.html)
            $httpProvider.useApplyAsync(true);

            $httpProvider.interceptors.push(function ($injector, $q) {
                var isApp = /^app/.test(window.location.host);
                return {
                    'request':     function (config) {
                        //if(!isApp) {
                        //    config.params = config.params || {};
                        //    config.params['XDEBUG_SESSION_START'] = 'PHPSTORM';
                        //}
                        return config;
                    },
                    response:      function (response) {
                        if (response.status === 401) {
                            console.log("Response 401");
                        }
                        return response || $q.when(response);
                    },
                    responseError: function (rejection) {
                        if (rejection.status >= 400 || rejection.status < 500) {
                            // Seems like 401 and 409 should remove token, but removing anything 4## for now
                            console.log("Response Error " + rejection.status, rejection);
                            $injector.get('$auth').removeToken();
                            $injector.get('$state').go('root.login', {}, {reload: true});
                        }

                        return $q.reject(rejection);
                    }
                };
            });

        });

    angular.module('jrbank').run(function ($rootScope, $http, $auth, $state, $window, $location, authService) {
        'use strict';

        $http.defaults.withCredentials = false;

        authService.init();

        $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            //debugger;
            if (!(_.has(toState, "data") && _.has(toState['data'], 'access'))) {
                $rootScope.error = "Access undefined for this state";
                console.log($rootScope.error + ": " + toState.name);
                event.preventDefault();
            } else if (!authService.checkAccess(toState.data.access)) {
                $rootScope.error = "Seems like you tried accessing a route you don't have access to...";
                event.preventDefault();
            } else if (toState.name === 'root.login' && $auth.isAuthenticated()) {
                $auth.removeToken();
                event.preventDefault();
                $state.go('root.accounts');
            } else if (toState.name !== 'root.login' && (fromState.url === '^' || fromState.url === '')) {
                if ($auth.isAuthenticated()) {
                    console.log('Already logged in. Going to accounts.');
                    $state.go('root.accounts');
                } else {
                    event.preventDefault();
                    $auth.removeToken();
                    console.log("Going to login page.");
                    $rootScope.error = null;
                    $state.go('root.login');
                }
            }
        });

        $rootScope
            .$on('$stateChangeSuccess',
            function (event) {

                if (!$window.ga) {
                    return;
                }

                $window.ga('send', 'pageview', {page: $location.path()});
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

        if ($window.ga && $auth.isAuthenticated()) {
            // Add analytics user trackin if user is logged in
            $window.ga('set', '&uid', authService.getCurrentUser().id);
        }

    });
})();
/*
 Trying to follow @link http://toddmotto.com/opinionated-angular-js-styleguide-for-teams/
 */
