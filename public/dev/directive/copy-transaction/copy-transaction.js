angular.module('jrbank').directive('copyTransaction', function() {
	return {
		restrict: 'E',
		replace: true,
		scope: {

		},
		templateUrl: 'directive/copy-transaction/copy-transaction.html',
		link: function(scope, element, attrs, fn) {


		}
	};
});
