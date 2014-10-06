
angular.module('jrbank').directive('newTransactionEnvelope', function() {
	return {
		restrict: 'E',
		replace: true,
		scope: {
            envelope: '=',
            balanced: '='
		},
		templateUrl: 'directive/new-transaction-envelope/new-transaction-envelope.html',
		link: function(scope, element, attrs, newTransactionCtrl) {
            scope.clearEnvelope = newTransactionCtrl.clearEnvelope;
            scope.calcEnvelope = newTransactionCtrl.calcEnvelope;
            scope.envelopeChange = newTransactionCtrl.onValueChange;
		},
        require: '^newTransaction',
        controllerAs: 'newTransaction'
	};
});
