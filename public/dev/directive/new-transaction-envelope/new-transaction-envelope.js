angular.module('jrbank').directive('newTransactionEnvelope', function() {
	return {
		restrict: 'E',
		replace: true,
		scope: {
            envelope: '=',
            transaction: '@'
		},
		templateUrl: 'directive/new-transaction-envelope/new-transaction-envelope.html',
		link: function(scope, element, attrs, fn) {


		},
        require: '^newTransaction',
        controller: function() {

        }
	};
});
