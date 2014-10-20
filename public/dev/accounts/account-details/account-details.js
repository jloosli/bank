(function () {
    "use strict";

    /*
     @ngInject
     */
    function AccountDetailsCtrl($stateParams, banksService, utilsService) {
        var self = this,
            totalPages,
            currentPage;

        this.params = $stateParams;

        this.transactions = [];
        banksService.transactions($stateParams.id).get().$promise.then(function (results) {
                self.transactions = _.each(results.data, function (item) {
                    item.created = utilsService.relDate(item.created_at);
                    // Remove $0.00 transactions
                    _.remove(item.envelope_transaction,function(trans) {
                        return Math.round(parseFloat(trans.amount)*100)/100 === 0;
                    });
                    return item;
                });
                totalPages = results.last_page;
                currentPage = results.current_page;
            }
        );

        this.morePages = function() {
            return parseInt(currentPage) < parseInt(totalPages);
        };

        this.getMore = function() {

            var newPage = currentPage + 1;
            banksService.transactions($stateParams.id, newPage).get().$promise.then(function (results) {
                    self.transactions.push.apply(self.transactions, _.each(results.data, function (item) {
                        item.created = utilsService.relDate(item.created_at);
                        return item;
                    }));
                    currentPage = results.current_page;
                }
            );
        };

        this.getEnvelope = function(envelope_id) {
            return self.user && _.find(self.user.envelopes, function(env) {
                return parseInt(env.id) === parseInt(envelope_id);
            });
        };

        banksService.users($stateParams.id).get().$promise.then(function(results) {
            self.user = results.users[0];
        });
    }

    angular.module('jrbank').controller('AccountDetailsCtrl', AccountDetailsCtrl);
})();