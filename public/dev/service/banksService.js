;(function () {

    /*
     @ngInject
     */
    function banksService($resource, $auth, $rootScope, $q, $cacheFactory, API_URL) {
        "use strict";
        var svc = {};

        function currentBank() {
            if ($auth.isAuthenticated()) {
                var user = $rootScope.currentUser;
                return user.bank_id;
            }
            return 0;
        }

        svc.bank = function (bank_id) {
            bank_id = bank_id || currentBank();
            return $resource(API_URL + 'banks/:bank_id',{"bank_id": bank_id}, {
                cache: true,
                update: {method: 'PUT'}
            });
        };

        svc.users = function (user_id) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id',
                {"user_id": user_id || ''},
                {cache: true,
                update: {method: 'PUT'}
                }
            );
        };

        svc.transactions = function (user_id, page) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id/transactions',
                {"user_id": user_id, page: page || 1},
                {cache: true}
            );
        };

        svc.envelopes = function (user_id) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/' + user_id + '/envelopes/:envelope_id',
                {"envelope_id": ''},
                {cache: true}
            );
        };

        svc.flush = function(service, user_id) {
            var key = API_URL + 'banks' + currentBank() + '/users/' + user_id ;
            switch (service) {
                case 'envelopes':
                    key += '/envelopes/';
                    break;
                case 'transactions':
                    key += '/transactions';
                    break;
                case 'users':
                    break;
                default:
                    key += '';
            }
            console.log($cacheFactory.get('$http').info());
            $cacheFactory.get('$http').remove(key);
            console.log('cleared: ',key);
        };

        svc.defaultEnvelope = function (user_id) {
            var deferred = $q.defer();
            svc.users(user_id).get().$promise.then(function (results) {
                var envelopes = results.users[0].envelopes;
                var default_envelope = _.find(envelopes, function (env) {
                    return env.default_spend;
                });
                deferred.resolve(default_envelope);
            });

            return deferred.promise;
        };

        return svc;
    }

    angular.module('jrbank').factory('banksService', banksService);
})();