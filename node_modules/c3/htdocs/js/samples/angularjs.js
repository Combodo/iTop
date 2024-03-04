var myApp = angular.module('myApp', [])
        .controller('myCtrl', function ($scope) {

            $scope.text = 'world';
            $scope.loading = true;

            var chart = c3.generate({
                data: {
                    columns: [
                        ['data1', 30, 200, 100, 400, 150, 250],
                        ['data2', 50, 20, 10, 40, 15, 25]
                    ]
                }
            });

            setTimeout(function () {
                $scope.loading = false;
            }, 1000);
        });
