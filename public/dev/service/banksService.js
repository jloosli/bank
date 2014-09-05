(function () {

    /*
    @ngInject
     */
    function banksService ($resource, $auth, $rootScope, API_URL) {
        "use strict";
        var svc = {};

        function currentBank () {
            if($auth.isAuthenticated()) {
                var user = $rootScope.currentUser;
                return user.bank_id;
            }
            return 0;
        }

        svc.bank = function () {
            return $resource(API_URL + ':bank_id');
        };

        svc.users = function (user_id) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id', {
                "user_id": user_id || ''
            });
        };

        svc.transactions = function(user_id) {
            return $resource(API_URL  + 'banks/' + currentBank() + '/users/:user_id/transactions', {
                "user_id": user_id
            })
        };

        svc.envelopes = function (user_id) {
            return $resource(API_URL + currentBank() + '/users/' + user_id + '/envelopes/:envelope_id');
        };

        return svc;
    }
    angular.module('jrbank').factory('banksService',banksService);
})();