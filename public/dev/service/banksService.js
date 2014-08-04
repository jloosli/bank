(function () {

    /*
    @ngInject
     */
    function banksService ($resource, API_URL) {
        "use strict";
        var svc = {},
            currentBank = null;

        svc.bank = function () {
            return $resource(API_URL + ':bank_id');
        };

        svc.user = function () {
            return $resource(API_URL + currentBank + '/users/:user_id');
        };

        svc.envelopes = function (user_id) {
            return $resource(API_URL + currentBank + '/users/' + user_id + '/envelopes/:envelope_id');
        };

        svc.setBank = function (bank_id) {
            currentBank = bank_id;
        };

        svc.clearBank = function() {
            currentBank = null;
        };



        return svc;
    }
    angular.module('jrbank').factory('banksService',banksService);
})();