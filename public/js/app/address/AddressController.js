'use strict';

app.controller('AddressCtrl', ['$scope', '$location', '$modal', 'Address', function($scope, $location, $modal, Address) {

    $scope.filters = {
        show: 'all'
    };
    $scope.addresses = Address.query();

    /**
     * Filtering when changing filters
     */
    $scope.$watch('filters.show', function() {
        $scope.addresses = Address.query($scope.filters);
    });

}]);
