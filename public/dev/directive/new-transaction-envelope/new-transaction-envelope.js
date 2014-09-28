
angular.module('jrbank').directive('newTransactionEnvelope', function() {
	return {
		restrict: 'E',
		replace: true,
		scope: {
            envelope: '='
		},
		templateUrl: 'directive/new-transaction-envelope/new-transaction-envelope.html',
		link: function(scope, element, attrs, newTransactionCtrl) {
            newTransactionCtrl.addEnvelope(scope.envelope);
		},
        require: '^newTransaction'
	};
});
