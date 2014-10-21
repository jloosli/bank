angular.module('jrbank').factory('utilsService', function ($auth, $state) {

    var utilsService = {};

    utilsService.relDate = function (theDate) {
        // Get to true ISO format. See https://github.com/moment/moment/issues/1407
        var created = moment(theDate.replace(' ', 'T') + '+0000'),
            diff = created.diff(moment(), 'days'),
            format = 'll'; // e.g. Jan 4, 2014

        if (created.year() === moment().year()) {
            format = 'MMM D'; // e.g. Jan 4
        }
        return Math.abs(diff) > 7 ? created.format(format) : created.fromNow();
    };

	utilsService.isLoggedIn = function() {
		return $auth.isAuthenticated();
	};

    utilsService.logout = function () {
        $auth.logout();
        $state.go('root.login');
    };

    return utilsService;
});