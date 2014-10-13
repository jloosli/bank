(function() {
    /**
     * @ngInject
     * @param $scope
     */
    function manageBank($rootScope, $scope, $state, banksService){
        "use strict";

        banksService.bank($rootScope.currentUser.bank_id).get().$promise.then(function(results) {
            $scope.bank = results.bank;
        });

        this.save = function() {
            banksService.bank($rootScope.currentUser.bank_id).update($scope.bank).$promise.then(function(results) {
                if(results.success) {
                    $state.go('^');
                }
            });
        };
    }
    angular.module('jrbank').controller('ManageBankCtrl', manageBank);
})();

