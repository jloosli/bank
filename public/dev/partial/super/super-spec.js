describe('SuperCtrl', function () {

    beforeEach(module('jrbank'));

    var scope, ctrl;

    beforeEach(inject(function ($rootScope, $controller) {
        scope = $rootScope.$new();
        ctrl = $controller('SuperCtrl', {$scope: scope});
    }));

    it('should ...', inject(function () {

        expect(1).toEqual(1);

    }));

});
