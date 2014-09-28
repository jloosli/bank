angular.module('jrbank').directive('newTransactionEnvelope', function() {
	return {
		restrict: 'E',
		replace: true,
		scope: {
            envelope: '=',
            transaction: '='
		},
		templateUrl: 'directive/new-transaction-envelope/new-transaction-envelope.html',
		link: function(scope, element, attrs, newTransactionCtrl) {
            newTransactionCtrl.addEnvelope(scope.envelope);
            var pct = scope.envelope.percent;

            scope.transaction.amount = scope.transaction.amount || 0;

            scope.total = (scope.transaction.amount || 0) * pct;
		},
        require: '^newTransaction',
        controller: function() {

        }
	};
});
