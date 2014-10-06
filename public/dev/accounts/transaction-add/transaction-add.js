angular.module('jrbank').controller('TransactionAddCtrl',function($scope, $stateParams, banksService){
    $scope.envelopes = [];
    var params = $stateParams;
    $scope.user = params.id;

    banksService.envelopes(params.id).get().$promise.then(function(result) {
        $scope.envelopes = result.envelopes;
    });
});