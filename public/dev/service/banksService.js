(function () {

    /*
     @ngInject
     */
    function banksService($resource, $auth, $rootScope, $q, $cacheFactory, $http, authService, API_URL) {
        "use strict";
        var svc = {};

        function currentBank() {
            if ($auth.isAuthenticated()) {
                var user = authService.getCurrentUser();
                return user.bank_id;
            }
            return 0;
        }

        svc.bank = function (bank_id) {
            bank_id = bank_id || currentBank();
            return $resource(API_URL + 'banks/:bank_id', {"bank_id": bank_id}, {
                //get:    {cache: true},
                update: {method: 'PUT'}
            });
        };

        svc.users = function (user_id) {
            //debugger;
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id',
                {"user_id": user_id || ''},
                {
                    //get:    {cache: true},
                    update: {method: 'PUT'}
                }
            );
        };

        svc.transactions = function (user_id, page) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/:user_id/transactions',
                {"user_id": user_id, page: page || 1},
                {
                    //get: {cache: true}
                }
            );
        };

        svc.envelopes = function (user_id) {
            return $resource(API_URL + 'banks/' + currentBank() + '/users/' + user_id + '/envelopes/:envelope_id',
                {"envelope_id": ''},
                {
                    //get: {cache: true}
                }
            );
        };

        svc.flush = function (service, user_id) {
            var key = API_URL + 'banks/' + currentBank() + '/users/' + user_id;
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
            console.log('cleared: ', key);
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

        svc.getBanks = function (ids) {
            return $q(function (resolve, reject) {
                $http.get(API_URL + 'banks')
                    .success(function (data) {
                        console.log(data);
                        resolve(data.banks);
                    })
                    .error(function (data) {
                        reject('Could not get banks');
                    });
            });
        };

        svc.createBank = function (bank) {
            return $q(function (resolve, reject) {
                $http.post(API_URL + 'banks', bank)
                    .success(function (data) {
                        resolve(data.data);
                    })
                    .error(function (data) {
                        reject(data.message);
                    });
            });
        };

        return svc;
    }

    angular.module('jrbank').factory('banksService', banksService);
})();