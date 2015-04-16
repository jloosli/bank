angular.module('jrbank').directive('copyTransaction', function (banksService) {
    return {
        restrict:    'E',
        replace:     true,
        scope:       {
            current:     '@',
            transaction: '='
        },
        templateUrl: 'directive/copy-transaction/copy-transaction.html',
        link:        function (scope, element, attrs, fn) {
            scope.showMe = false;
            banksService.users().get().$promise.then(function (results) {
                scope.otherAccountHolders = _.filter(results.users, function (user) {
                    var addToList = parseInt(user.id) !== parseInt(scope.current) && user.user_type === 'user';
                    return addToList;
                });
                console.log(scope.otherAccountHolders);
                if(scope.otherAccountHolders.length > 1) {
                    scope.showMe = true;
                }
            });
            scope.queryString = function () {
                return 'description=' + encodeURIComponent(scope.transaction.description) + '&' +
                    'amount=' + scope.transaction.amount;
            };
        }
    };
});
