'use strict';

app.controller('DepositCtrl', ['$scope', '$location', '$modal', 'Deposit', function($scope, $location, $modal, Deposit) {

    $scope.deposits = Deposit.query();

}]);
