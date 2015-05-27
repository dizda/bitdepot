'use strict';

angular.module('app').controller('AddressCtrl', ['$scope', '$location', '$modal', 'Address', function($scope, $location, $modal, Address) {

    $scope.filters = {
        show: 'all'
    };

    $scope.url = 'api/addresses.json';

}]);
