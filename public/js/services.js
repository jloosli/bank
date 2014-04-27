'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('jrBank.services', ['ngResource']).
    value('version', '0.1')
    .factory('userFactory', ['$resource', function ($resource) {
        return $resource('/api/v1/users/:userId', {userId: '@id'}, {
            query: {method: 'GET'},
            login: {method: 'POST', params: {username: '@user', password: '@pass'}, url: '/api/v1/auth'},
            logout: {url: '/api/v1/logout'},
            getAll: {method: 'GET', isArray: true}
        });
    }])
    .factory('transactionFactory', ['$resource', '$http', function ($resource, $http) {
        return $resource('/api/v1/transactions/:transactionId', {transactionId: '@id'}, {
            user: {method: 'GET', isArray: true, url: '/api/v1/transactions/user/:userId'}
        });
    }])
    .factory('envelopeFactory', ['$resource', function($resource) {
        return $resource('/api/v1/envelopes/:userId', {userId: '@userId'}, {
            get: {method: 'GET', isArray: true}
        });
    }])
    .factory('Auth', ['ACCESS_LEVELS', '$rootScope', function (ACCESS_LEVELS, $rootScope) {
        // @todo: remove user from localstorage when not authorized
        /* See https://gist.github.com/clouddueling/6191173 */
        var me = this;
        var _user = JSON.parse(localStorage.getItem('user'));
        var setUser = function(user) {
            if(user.user_type ) {
                switch(user.user_type) {
                    case 'user':
                        user.role = ACCESS_LEVELS.user;
                        break;
                    case 'admin':
                        user.role = ACCESS_LEVELS.parent;
                        break;
                    case 'super-admin':
                        user.role = ACCESS_LEVELS.super;
                        break;
                    default:
                        user.role = ACCESS_LEVELS.pub;
                }
            }
            _user = user;
            localStorage.setItem('user', JSON.stringify(_user));
        }
        return {
            isAuthorized: function(lvl) {
                return _user && _user.role >= lvl;
            },
            setUser: setUser,
            isLoggedIn: function() {
                return _user ? true: false;
            },
            getUser: function() {
                return _user;
            },
            getId: function() {
                return _user ? _user.id: null;
            },
            getToken: function() {
                return _user ? _user.token: '';
            },
            logout: function() {
                localStorage.removeItem('user');
                _user = null;
                $rootScope.$broadcast('auth:loggedOut');
            }

        }
    }])
;
