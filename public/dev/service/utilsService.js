angular.module('jrbank').factory('utilsService', function ($auth, $state) {

    var utilsService = {};

    utilsService.relDate = function (theDate) {
        // Get to true ISO format. See https://github.com/moment/moment/issues/1407
        var created = moment(theDate.replace(' ', 'T') + '+0000');
        var diff = created.diff(moment(), 'days');
        return Math.abs(diff) > 7 ? created.format('L') : created.fromNow();
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