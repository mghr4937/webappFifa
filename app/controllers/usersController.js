app.controller('usersCtrl', ['$scope', '$http', 'ngNotify', function ($scope, $http, ngNotify, uibDateParser) {

    ngNotify.config({
        theme: 'pure',
        position: 'bottom',
        duration: 3000,
        type: 'info',
        sticky: false,
        button: true,
        html: false
    });

    $scope.user = {};
    $scope.users = {};
    $scope.btnAdd = "Add";

    // read users
    $scope.getAll = function () {
        $http.get("php/dbActions/users/get_users.php").then(function (response) {
            console.log(response.data.records);
            if (response.data.records != null) {
                $scope.users = response.data.records;
                //ngNotify.set('Datos de usuarios cargados correctamente', 'success');
            }
        }, function (error) {
            console.log(error);
            ngNotify.set('ERROR - Datos de usuarios no cargados', 'error');

        });
    }
    $scope.getAll();

    $scope.clearMsg = function () {
        $scope.msg = "";
        $scope.msgDiv = "";
    }

    $scope.setMsg = function setMsg(msg) {
        $scope.msg = "** " + msg + " **";
        $scope.msgDiv = "msg-div";
    }

    $scope.reset = function (form) {
        $scope.user = {};
        if (form) {
            form.$setPristine();
            form.$setUntouched();
        }
        for (var i = 0; i < $scope.users.length; i++) {
            $scope.users[i].isSelected = false;
        }
    }

    $scope.$watch('user', function () {
        if ($scope.user.id != null) {
            $scope.btnAdd = "Update";
        } else {
            $scope.btnAdd = "Add";
        }
    });

    $scope.deleteUser = function (row) {
        console.log(row)
        $http.put('php/dbActions/users/delete_user.php', {
            'id': row.id
        }).then(function (response) {
            $scope.user = {};
            ngNotify.set('Usuario eliminado', 'success');
            $scope.getAll();
        }, function (error) {
            //$scope.setMsg(response.data);
            ngNotify.set('ERROR - Usuario no eliminado', 'error');
        });

    }

    $scope.rowSelect = function (row) {
        if (row.id == $scope.user.id) {
            $scope.user = {};
        } else {
            $scope.user = angular.copy(row);
        }
    }

    // create new user
    $scope.createUser = function () {
        // fields in key-value pairs
        $http.post('php/dbActions/users/insert_user.php', {
            'name': $scope.user.name.toUpperCase(),
        }).then(function (response) {
                $scope.user = {};
                ngNotify.set('Usuario creado', 'success');
                $scope.getAll();
            },
            function (error) {
                ngNotify.set('ERROR', 'error');
            });
    }

    // create new user
    $scope.updateUser = function () {
        // fields in key-value pairs
        $http.put('php/dbActions/users/update_user.php', {
            'name': $scope.user.name.toUpperCase(),
            'id': $scope.user.id
        }).then(function successCallback(response) {
                $scope.user = {};
                $scope.setMsg(response.data);
            },
            function errorCallback(response) {
                $scope.setMsg(response.data);
            });

    }

    $scope.addUser = function () {
        var esta = false;
        for (var i = 0; i < $scope.users.length; i++) {
            if ($scope.users[i].id == $scope.user.id) {
                esta = true;
            }
        }
        if (!esta) {
            $scope.createUser();
        } else {
            $scope.updateUser();
        }
        $scope.reset();
        $scope.getAll();
        //$scope.usersDisplayed = [].concat($scope.users);
    }


}]);