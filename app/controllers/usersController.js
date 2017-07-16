app.controller('usersCtrl', ['$scope', '$http', function($scope, $http, uibDateParser) {
    $scope.user = {};
    $scope.users = {};
    $scope.btnAdd = "Add";

    // read users
    $scope.getAll = function(){
        $http.get("php/dbActions/users/get_users.php").then(function(response){
            if(response.records[index] != null ){
                $scope.users = response.records;         
            }
        });
    }
    $scope.getAll();

    $scope.clearMsg = function() {
        $scope.msg = "";
        $scope.msgDiv = "";
    }

    $scope.setMsg = function setMsg(msg) {
        $scope.msg = "** " + msg + " **";
        $scope.msgDiv = "msg-div";
    }

    $scope.reset = function(form) {
        $scope.user = {};
        if (form) {
            form.$setPristine();
            form.$setUntouched();
        }
        for (var i = 0; i < $scope.users.length; i++) {
            $scope.users[i].isSelected = false;
        }
    }

    $scope.$watch('user', function() {
        if ($scope.user.id != null) {
            $scope.btnAdd = "Update";
        } else {
            $scope.btnAdd = "Add";
        }
    });

    $scope.deleteUser = function(row) {
      $http.post('php/dbActions/users/delete_user.php', {
              'id' : row.id
          }
      ).then(function successCallback(response) {
            $scope.user = {};
            $scope.setMsg(response.data);
  				},
  					function errorCallback(response) {
              $scope.setMsg(response.data);
  				});
          $scope.getAll();
    }

    $scope.rowSelect = function(row) {
        if (row.id == $scope.user.id) {
            $scope.user = {};
        } else {
            $scope.user = angular.copy(row);
        }
    }

    // create new user
$scope.createUser = function(){
    // fields in key-value pairs
    $http.post('php/dbActions/users/insert_user.php', {
            'name' : $scope.user.name.toUpperCase(),
        }
    ).then(function successCallback(response) {
          $scope.user = {};
          $scope.setMsg(response.data);
				},
					function errorCallback(response) {
            $scope.setMsg(response.data);
				});
}

// create new user
$scope.updateUser = function(){
// fields in key-value pairs
$http.post('php/dbActions/users/update_user.php', {
        'name' : $scope.user.name.toUpperCase(),
        'id': $scope.user.id
    }
).then(function successCallback(response) {
      $scope.user = {};
      $scope.setMsg(response.data);
    },
      function errorCallback(response) {
        $scope.setMsg(response.data);
    });

}

    $scope.addUser = function() {
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
