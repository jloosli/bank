var LoginReminder = function () {
    var self= this;
    this.submitted = false;
    this.email = 'bob@bob.com';

    this.submitEmail = function() {
        self.email = '';
        self.submitted=true;
    }
};
angular.module('jrbank').controller('LoginReminderCtrl', LoginReminder);