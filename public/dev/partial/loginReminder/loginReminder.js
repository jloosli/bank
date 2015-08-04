var LoginReminder = function (usersService) {
    var self= this;
    this.submitted = false;
    this.email = '';

    this.submitEmail = function() {
        usersService.submitPasswordReminder(self.email)
            .then(function (result) {
                self.submitted = true;
                console.log(result);
                //self.email = '';
            });

    };
};
angular.module('jrbank').controller('LoginReminderCtrl', LoginReminder);