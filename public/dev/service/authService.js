(function () {
    /*
     @ngInject
     */
    function authService($http, $q, API_URL) {

        var svc = {};

        svc.init = function () {
            if(!!this.getToken()) {
                $http.defaults.headers.common.Authorization = this.getAuthString();
            } else {
                delete $http.defaults.headers.common.Authorization;
            }
        };

        svc.getToken = function () {
            "use strict";
            return localStorage.getItem('auth_token');
        };

        svc.setToken = function (newToken) {
            "use strict";
            localStorage.setItem('auth_token', newToken);
            this.init();
        };

        svc.clearToken = function () {
            "use strict";
            localStorage.removeItem('auth_token');
            localStorage.removeItem('current_user');
            console.log("Cleared token");
            this.init();
        };

        svc.getAuthString = function () {
            "use strict";
            var theToken = this.getToken();
            if (!!theToken) {
                return 'Basic ' + btoa(theToken + ":");
            }
            return null;
        };

        svc.getCurrentUser = function() {
            "use strict";
            var me = this;
            var user = localStorage.getItem('current_user');
            if(!!user) {
                return $q.when(JSON.parse(user));
            } else {
                return $http({
                    method: 'GET',
                    url: API_URL+ 'users/me'
                }).then(function (user) { // success (codes 200-299)
                    /*
                    Response format:
                     {"user":{"id":1,"username":"first_user","name":"First User","email":"first@example.com","slug":"first_user","bank_id":1,"user_type":"user","balance":0,"created_at":"2014-07-30 21:23:32","updated_at":"2014-07-30 21:23:32"}}
                     */
                    localStorage.setItem('current_user', JSON.stringify(user.data.user));
                    return me.getCurrentUser();
                },function(data) { // failure
                    if(data.status === 403) { // Only clear the token if my server sends back unauthorized. If it's a 404 error, keep the token.
                        me.clearToken();
                    }
                    return $q.when(null);
                });
            }
        };
        return svc;
    }


    angular.module('jrbank').factory('authService', authService);
})();