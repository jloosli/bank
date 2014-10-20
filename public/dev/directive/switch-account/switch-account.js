angular.module('jrbank').directive('switchAccount', function(banksService) {
	return {
		restrict: 'E',
		replace: true,
		scope: {
            current: '@'
		},
		templateUrl: 'directive/switch-account/switch-account.html',
		link: function(scope, element, attrs, fn) {
            scope.accountHolders = [];
            banksService.users().get().$promise.then(function(results) {
                scope.otherAccountHolders = _.filter(results.users, function(user) {
                    return user.id !== parseInt(scope.current) && user.user_type === 'user';
                });
            });
		}
	};
});
