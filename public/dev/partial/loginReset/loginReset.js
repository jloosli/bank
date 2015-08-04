var LoginResetCtrl = function (usersService, $state) {
    var self = this;
    this.submitted = false;
    this.credentials = {};
    this.states = {
        init:     0,
        checking: 1,
        success:  2
    };
    this.state = this.states.init;

    this.submitPassword = function () {
        self.state = self.states.checking;
        usersService.submitPasswordReset(self.credentials)
            .then(function (result) {
                console.log(result);
                self.state = self.states.success;
                $state.go('root.login');
            })
            .catch(function (result) {
                self.state = self.states.init;
            });

    };


};
angular.module('jrbank').controller('LoginResetCtrl', LoginResetCtrl);