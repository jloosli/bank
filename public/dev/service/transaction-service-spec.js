describe('transactionService', function() {

  beforeEach(module('jrbank'));

  it('should get a page full of transactions', inject(function($httpBackend, API_URL, transactionService) {

      var apiCall = {
          transactions: [
              {id: 2345},
              {id: 23423},
              {id: 234234}
          ]

      };
      $httpBackend.expectGET(API_URL + 'banks/1/users/1/transactions?from_id=0&page_size=100').respond(200, JSON.stringify(apiCall));
      var transactions = transactionService.resource(1,1).get();
      transactions.$promise.then(function(transactionResults) {
          expect(transactionResults.transactions).toEqual(apiCall.transactions);
      });
      $httpBackend.flush();

  }));

});