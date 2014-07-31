describe('messageService', function() {

  beforeEach(module('jrbank'));

  it('should have no messages to start', inject(function(messageService) {

	expect(messageService.messages()).toEqual([]);

  }));

});