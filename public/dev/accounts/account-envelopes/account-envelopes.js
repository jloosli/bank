(function() {
    /*
     @ngInject
     */

function AccountEnvelopesCtrl($scope, $stateParams, $q,  banksService) {
    var params = $stateParams,
        self = this;

    this.envelopes = {default: '', data:[]};

    banksService.users(params.id).get().$promise.then(function(results) {
        if(results.users.length) {
            self.envelopes.data = results.users[0].envelopes;
        }
    });

    banksService.defaultEnvelope(params.id).then(function (env) {
        self.envelopes['default'] = env.id;
    });

    this.add = function() {
        var env = {
            name:'New Envelope',
            "deleted_at": null,
            balance: 0
        };
        self.envelopes.data.push(env);
    };

    this.update = function() {

    };

}

angular.module('jrbank').controller('AccountEnvelopesCtrl', AccountEnvelopesCtrl);
})();
