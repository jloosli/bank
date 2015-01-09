angular.module('jrbank').directive('focusSelect', function($parse) {
	return {
		restrict: 'A',
        scope: {

        },
		link: function(scope, element, attrs, fn) {
            element.on('click', function() {
                if(!attrs.focusSelect || attrs.focusSelect===this.value) {
                    this.select();
                }
            })

		}
	};
});