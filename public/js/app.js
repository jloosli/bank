'use strict';

// Declare app level module which depends on filters, and services
var jrBankApp = angular.module('jrBank', [
        'ngRoute',
//        'ngSanitize',
//            'ngAnimate',
        'ui.bootstrap',
        'jrBank.filters',
        'jrBank.services',
        'jrBank.directives',
        'jrBank.controllers'
    ])
    .constant('ACCESS_LEVELS', {
        pub: 0,
        user: 1,
        parent: 2,
        super: 3
    })
    .config(['$routeProvider', 'ACCESS_LEVELS', function ($routeProvider, ACCESS_LEVELS) {
        $routeProvider.when('/accounts', {
            templateUrl: 'partials/list.html',
            controller: 'listCtrl',
            access_level: ACCESS_LEVELS.user
        });
        $routeProvider.when('/accounts/edit', {
            templateUrl: 'partials/list.html',
            controller: 'listCtrl',
            access_level: ACCESS_LEVELS.user
        });
        $routeProvider.when('/accounts/:userId', {
            templateUrl: 'partials/user.html',
            controller: 'userCtrl',
            access_level: ACCESS_LEVELS.user
        });
        $routeProvider.when('/accounts/:userId/envelopes', {
            templateUrl: 'partials/envelopes.html',
            controller: 'envelopesCtrl',
            access_level: ACCESS_LEVELS.user
        });
        $routeProvider.when('/login', {
            templateUrl: '/partials/login.html',
            controller: 'loginCtrl',
            access_level: ACCESS_LEVELS.pub
        });
        $routeProvider.otherwise({redirectTo: '/accounts'});
    }])
    .config(['$httpProvider', function ($httpProvider) {

        // Adding the following since
        $httpProvider.defaults.headers.common["JrBank"] = "true";

        var interceptor = function ($q, $rootScope, Auth) {
            return {
                'response': function (resp) {
                    if (resp.config.url == '/api/v1/auth') {
                        if (resp.data.token) {
                            var user = resp.data.user;
                            user.token = resp.data.token;
                            Auth.setUser(user);
                            $rootScope.$broadcast('auth:loggedIn');
                        }
                    }
                    return resp || $q.when(resp);
                },
                'responseError': function (rejection) {
                    // Handle errors
                    switch (rejection.status) {
                        case 401:
                            if (rejection.config.url !== '/api/v1/auth') {
                                $rootScope.$broadcast('auth:loginRequired');
                            }
                            break;
                        case 403:
                            $rootScope.$broadcast('auth:forbidden');
                            break;
                        case 404:
                            $rootScope.$broadcast('page:notFound');
                            break;
                        case 500:
                            if(rejection.data.error && rejection.data.error.message == "Not Authorized") {
                                Auth.logout();
                                $rootScope.$broadcast('auth:loginRequired');
                            } else {
                                $rootScope.$broadcast('server:error');
                            }
                            break;
                        default:
                            console.log(rejection);
                    }
                    return $q.reject(rejection);
                },
                'request' : function(config) {
                    if (Auth.getToken()) {
                        config.headers['X-Auth-Token'] = Auth.getToken();
                    }
                    return config;
                }
            }
        };
        $httpProvider.interceptors.push(interceptor);
    }])
    .run(function ($rootScope, $location, Auth) {
        // Set a watch on the $routeChangeStart
        $rootScope.$on('$routeChangeStart', function (evt, next, curr) {
            if (!Auth.isAuthorized(next.access_level)) {
                if (Auth.isLoggedIn()) {
                    $location.path('/accounts');
                } else {
                    $location.path('/login');
                }
            }
        })
    });
