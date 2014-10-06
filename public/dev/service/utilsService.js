angular.module('jrbank').factory('utilsService',function() {

	var utilsService = {};

	utilsService.relDate = function(theDate) {
		var created = moment(theDate + ' +0000');
		var diff = created.diff(moment(), 'days');
		return Math.abs(diff) > 7 ? created.format('L') : created.fromNow();
	};

	return utilsService;
});