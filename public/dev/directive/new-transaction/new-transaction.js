/*
 @ngInject
 */
function newTransactionCtrl($scope, $location, banksService, utilsService) {
    var self = this;
    //this.envelopes = $$scope.envelopes;
    this.sumEnvelopes = 0;
    this.submitted = false;
    this.lastTransaction = {};

    this.onTransChange = _.debounce(function (amount) {
        amount = amount || 0;
        $scope.$apply(function () {
            $scope.envelopes = _.map($scope.envelopes, function (env) {
                if (amount >= 0) {
                    env.amount = Math.round(env.percent * amount) / 100;
                } else {
                    env.amount = env.default_spend ? amount : 0;
                }
                return env;
            });
            self.onValueChange();
        });
    }, 250);

    this.onValueChange = _.debounce(function () {
        $scope.$apply(function () {
            $scope.balanced = checkDiff() === 0;
        });
    });

    $scope.balanced = true;
    $scope.trans = {
        description: $location.search().description || '',
        amount:      parseFloat($location.search().amount) || 0
    };

    var checkDiff = function () {
        var amount = $scope.trans.amount || 0;
        return Math.round((amount - getEnvelopeSum()) * 100) / 100;
    };

    var getEnvelopeSum = function () {
        return Math.round(_.reduce($scope.envelopes, function (sum, env) {
                return parseFloat(sum) + parseFloat(env['amount']);
            }, 0) * 100) / 100;
    };

    this.clearEnvelope = function (id) {
        $scope.envelopes = _.map($scope.envelopes, function (env) {
            if (env.id === id) {
                env.amount = 0;
            }
            return env;
        });
        self.onValueChange();
    };

    this.calcEnvelope = function (id) {
        var diff = checkDiff();
        if (diff !== 0) {
            $scope.envelopes = _.map($scope.envelopes, function (env) {
                if (env.id === id) {
                    env.amount = Math.round((env.amount + diff) * 100) / 100;
                }
                return env;
            });
            self.onValueChange();
        }
    };

    this.canSubmit = function () {
        var canSub = $scope.balanced && !!$scope.trans.description && (!isNaN($scope.trans.amount) || $scope.trans.amount === '');
        return canSub;
    };

    this.submitTransaction = function () {
        var transaction = {
            'transaction': {
                description:           $scope.trans.description,
                amount:                $scope.trans.amount || 0,
                envelope_transactions: _.map($scope.envelopes, function (env) {
                    return {amount: env.amount, envelope_id: env.id};
                })
            }
        };
        banksService.transactions($scope.user).save(transaction).$promise.then(function (result) {

            var newTransaction = result.transaction;
            newTransaction.envelope_transactions = transaction.envelope_transactions;
            newTransaction.created = utilsService.relDate(newTransaction.created_at);

            self.lastTransaction = transaction.transaction;

            $scope.transactions.push(newTransaction);
            $scope.$parent.$parent.accountDetails.user.balance += parseFloat(transaction.transaction.amount);

            $scope.envelopes = _.map($scope.envelopes, function (env) {
                env.balance += _.find(transaction.transaction.envelope_transactions, function (et) {
                    return parseInt(et.envelope_id) === parseInt(env.id);
                }).amount;
                return env;
            });
            banksService.flush('transactions', newTransaction.user_id);
            $scope.trans = {};
            $scope.addTransaction.$setPristine(true);
            $scope.addTransaction.$setUntouched(true);
            self.submitted = true;
        });
    };
}

angular.module('jrbank').directive('newTransaction', function () {
    return {
        restrict:     'E',
        replace:      true,
        scope:        {
            envelopes:    '=',
            user:         '@',
            transactions: '='
        },
        templateUrl:  'directive/new-transaction/new-transaction.html',
        link:         function (scope, element, attrs, transactionCtrl) {
            scope.$watch('trans.amount', transactionCtrl.onTransChange);
            // @todo Hack to run transaction change once envelopes are loaded. Should do something more eloquent.
            var once = scope.$watch('envelopes', function (env) {
                if (env.length > 0) {
                    transactionCtrl.onTransChange(scope.trans.amount);
                    once();
                }
            });
        },
        controller:   newTransactionCtrl,
        controllerAs: 'transactionCtrl'
    };
});

