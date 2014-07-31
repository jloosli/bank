(function () {

    /*
    @ngInject
     */
    function banksService ($resource) {
        "use strict";
        var svc = {};

        svc.resource = function () {
            return $resource();
        };

        return svc;
    }
    angular.module('jrbank').factory('banksService',banksService);
})();