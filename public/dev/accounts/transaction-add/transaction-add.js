angular.module('jrbank').controller('TransactionAddCtrl',function($scope){
    $scope.envelopes = [
        {
            id: 1,
            user_id: '1',
            name: "Spending",
            percent: 30,
            default_spend: true,
            is_active: 1
        },
        {
            id: 2,
            user_id: '1',
            name: "Tithing",
            percent: 10,
            default_spend: false,
            is_active: 1
        },
        {
            id: 3,
            user_id: '1',
            name: "Savings",
            percent: 60,
            default_spend: false,
            is_active: 1
        }
    ];

});