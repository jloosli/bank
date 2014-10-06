(function () {
    "use strict";

    /*
     @ngInject
     */
    function AccountDetailsCtrl($stateParams, banksService, utilsService) {
        var self = this;

        this.params = $stateParams;

        this.transactions = [];
        banksService.transactions($stateParams.id).get().$promise.then(function (results) {
                self.transactions = _.each(results.data, function (item) {

                    item.created = utilsService.relDate(item.created_at);
                    return item;
                });
            }
        );

        banksService.users($stateParams.id).get().$promise.then(function(results) {
            self.user = results.users[0];
        });
    }

    angular.module('jrbank').controller('AccountDetailsCtrl', AccountDetailsCtrl);
})();