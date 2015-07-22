var LoginReminder = function (usersService) {
    var self= this;
    this.submitted = false;
    this.email = '';

    this.submitEmail = function() {
        //self.submitted=true;
        usersService.submitPasswordReminder(self.email)
            .then(function (result) {
                console.log(result);
                //self.email = '';
            });

    };
};
angular.module('jrbank').controller('LoginReminderCtrl', LoginReminder);