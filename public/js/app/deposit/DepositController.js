'use strict';

app.controller('DepositCtrl', ['$scope', '$location', '$modal', 'Deposit', function($scope, $location, $modal, Deposit) {

    $scope.url = 'api/deposits.json';

    $scope.openModalAddDeposit = function() {

        $modal({
            title:    'New deposit',
            template: 'js/app/deposit/modal_add.html',
            animation:'am-fade-and-scale',
            placement:'center',
            show:     true,
            scope:    $scope
        });

    };

    $scope.$on('deposit:create', function(e, deposit) {
        $scope.deposits.unshift(deposit);
    });

}]);
