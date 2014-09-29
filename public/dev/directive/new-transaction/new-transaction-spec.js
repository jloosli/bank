describe('newTransaction', function() {

  beforeEach(module('jrbank'));

  var scope,compile;

  beforeEach(inject(function($rootScope,$compile) {
    scope = $rootScope.$new();
    compile = $compile;
  }));

  it('should add envelopes', function() {

      var envelopes = [
          {
              id: 1,
              user_id: '1',
              name: "Spending",
              percent: 30,
              default_spend: true
          },
          {
              id: 2,
              user_id: '1',
              name: "Tithing",
              percent: 10,
              default_spend: false
          },
          {
              id: 3,
              user_id: '1',
              name: "Savings",
              percent: 60,
              default_spend: false
          }
      ];
  });
});