(function () {

    /*
    @ngInject
     */
    function banksService ($resource) {
        "use strict";
        var banksService = {};

        banksService.resource = function () {
            return $resource();
        };

        return banksService;
    }
    angular.module('jrbank').factory('banksService',banksService);
})();