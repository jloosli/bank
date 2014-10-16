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
            banksService.users().get().$promise.then(function (results) {
                scope.otherAccountHolders = _.filter(results.users, function (user) {
                    return parseInt(user.id) !== parseInt(scope.current);
                });
            });
            scope.queryString = function () {
                return 'description=' + encodeURIComponent(scope.transaction.description) + '&' +
                    'amount=' + scope.transaction.amount;
            };
        }
    };
});
