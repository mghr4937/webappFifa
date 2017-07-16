app.controller('tournamentCtrl', ['$scope', '$http', function($scope, $http, uibDateParser) {
$scope.btnAdd = "Add";
$scope.tour = {};
$scope.tournaments = [];
$scope.tour.usersSel = [];
$scope.users = users;
$scope.tour.matches = [];

$scope.getAll = function(){
     $http.get("php/dbActions/tournaments/get_tournaments.php").then(function successCallback(response){
        $scope.tournaments = response.records;
        //$scope.tournamentsDisplayed = [].concat($scope.tournaments);
    }, function errorCallback(response) {


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
    $scope.tour = {};
    if (form) {
        form.$setPristine();
        form.$setUntouched();
    }
}

// create new user
$scope.createTournament = function(){
// fields in key-value pairs
console.log($scope.tour);
$http.post('php/dbActions/tournaments/insert_tournaments.php', {
        'name' : $scope.tour.name.toUpperCase(),
        'monthyear': $scope.tour.monthyear.toUpperCase(),
        'place': $scope.tour.place.toUpperCase(),
        'users': $scope.tour.usersSel
    }
).then(function successCallback(response) {
      $scope.tour = {};
      $scope.setMsg(response.data);
    },
      function errorCallback(response) {
        $scope.setMsg(response.data);
    });
}

$scope.addTournament = function() {
    var esta = false;
    // for (var i = 0; i < $scope.tournaments.length; i++) {
    //     if ($scope.tournaments[i].id == $scope.tour.id) {
    //         esta = true;
    //     }
    // }
    if (!esta) {
        $scope.createTournament();
    } else {
        //$scope.updateUser();
    }
    $scope.reset();
    $scope.getAll();
    //$scope.usersDisplayed = [].concat($scope.users);
}

}]);
