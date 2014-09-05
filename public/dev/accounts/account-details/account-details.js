;
(function () {
    "use strict";

    /*
     @ngInject
     */
    function AccountDetailsCtrl($stateParams, banksService) {
        var self = this;

        this.params = $stateParams;
        console.log($stateParams);

        this.transactions = [];
        banksService.transactions($stateParams.id).get().$promise.then(function (results) {
                var transactions = _.each(results.data, function (item) {
                    console.log(item);
                    var created = moment(item.created_at);
                    var diff = created.diff(moment(), 'days');
                    console.log(diff);
                    item.created = Math.abs(diff) > 7 ? created.format('L') : created.fromNow();
                    return item;
                });
                self.transactions = transactions;
            }
        );

        banksService.users($stateParams.id).get().$promise.then(function(results) {
            console.log(results);
            self.user = results.users[0];
        });
    }

    angular.module('jrbank').controller('AccountDetailsCtrl', AccountDetailsCtrl);
})();