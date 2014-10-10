(function () {
    /*
     @ngInject
     */

    function AccountEnvelopesCtrl($state, $stateParams, $q, banksService) {
        var params = $stateParams,
            self = this;

        this.envelopes = {default: '', data: []};

        banksService.users(params.id).get().$promise.then(function (results) {
            if (results.users.length) {
                self.envelopes.data = results.users[0].envelopes;
            }
        });
            banksService.defaultEnvelope(params.id).then(function (env) {
                self.envelopes['default'] = env.id;
            });

        this.add = function () {
            var env = {
                id:           _.uniqueId('new-'),
                name:         'New Envelope',
                "user_id":    params.id,
                "deleted_at": null,
                balance:      0
            };
            self.envelopes.data.push(env);
        };

        this.save = function () {
            var theEnvelopes = _.map(self.envelopes.data, function (env) {
                console.log(env);
                env.default_spend = (env.id === self.envelopes.default) ? 1 : 0;
                if (typeof env.id === 'string' && env.id.indexOf('new-') === 0) {
                    delete(env.id);
                }
                if(env.deleted_at === '1') {
                    delete(env.deleted_at);
                    env['to_inactivate'] = true;
                }
                return env;
            });
            banksService.envelopes(params.id).save({envelopes:theEnvelopes});
            $state.go('^');
        };

        this.totals = function () {
            return {
                percent: _.reduce(self.envelopes.data, function (oldsum, env) {
                    return oldsum + (parseFloat(env.percent) || 0);
                }, 0),
                goals: _.reduce(self.envelopes.data, function (oldsum, env) {
                    return oldsum + (parseFloat(env.goal) || 0);
                }, 0),
                balance: _.reduce(self.envelopes.data, function (oldsum, env) {
                    return oldsum + (parseFloat(env.balance) || 0);
                }, 0)
            };
        };

    }

    angular.module('jrbank').controller('AccountEnvelopesCtrl', AccountEnvelopesCtrl);
})();
