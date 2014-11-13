'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', 'Withdraw', function($scope, $location, Withdraw) {

    $scope.withdraws = Withdraw.query();

}]);