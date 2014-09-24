angular.module('jrbank').controller('newTransactionCtrl', function() {

});

angular.module('jrbank').directive('newTransaction', function() {
	return {
		restrict: 'E',
		replace: true,
		scope: {
            envelopes: '='
		},
		templateUrl: 'directive/new-transaction/new-transaction.html',
		link: function(scope, element, attrs, fn) {


		},
        controller: newTransactionCtrl
	};
});

angular.module('jrbank').service('newTransactionSvc', function() {
    'use strict';
    var svc = {},
        envelopes = [];

    svc.calc = function() {

    };



    return svc;
});


/*
 @ngInject
 */
function newTransactionCtrl (newTransactionSvc) {

}


