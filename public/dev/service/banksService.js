(function () {

    /*
     @ngInject
     */
    function banksService($resource, $auth, $rootScope, $q, API_URL) {
        "use strict";
        var svc = {};

        function currentBank() {
            if ($auth.isAuthenticated()) {
                var user = $rootScope.currentUser;
                return user.bank_id;
            }
            return 0;
        }

        svc.bank = function () {
            return $resource(API_URL + ':bank_id', {cache: true});
        };

        svc.users = function (user_id) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id', {
                "user_id": user_id || '',
                cache: true
            });
        };

        svc.transactions = function (user_id) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id/transactions', {
                "user_id": user_id,
                cache:     true
            });
        };

        svc.envelopes = function (user_id) {
            return $resource(API_URL + currentBank() + '/users/' + user_id + '/envelopes/:envelope_id', {
                "envelope_id": '',
                cache:         true
            });
        };

        svc.defaultEnvelope = function (user_id) {
            var deferred = $q.defer();
            svc.users(user_id).get().$promise.then(function (results) {
                var envelopes = results.users[0].envelopes;
                var default_envelope = _.find(envelopes, function (env) {
                    return env.default_spend === '1';
                });
                deferred.resolve(default_envelope);
            });

            return deferred.promise;
        };

        return svc;
    }

    angular.module('jrbank').factory('banksService', banksService);
})();