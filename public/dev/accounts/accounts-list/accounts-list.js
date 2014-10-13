(function () {
    "use strict";

    /*
    @ngInject
     */
    function AccountsListCtrl ($state, banksService) {
        var self = this;
        banksService.users().get().$promise.then(function(results) {
            results.users = _.forEach(results.users, function(item, idx, orig) {
                item['default_spend'] = _.filter(item['envelopes'],function(env) {
                    return env.default_spend;
                })[0];
                return item;
            });
            self.accountsList = results.users;
        });

        this.accountDetails = function(id) {
            $state.go('root.account-details',
                {id: id}
            );
        };

    }
    angular.module('jrbank').controller('AccountsListCtrl', AccountsListCtrl);
})();
