app.controller('tournamentCtrl', ['$scope', '$http', 'ngNotify', function($scope, $http, ngNotify, uibDateParser) {

    ngNotify.config({
        theme: 'pastel',
        position: 'bottom',
        duration: 3000,
        type: 'info',
        sticky: false,
        button: true,
        html: false
    });

$scope.btnAdd = "Add";
$scope.tour = {};
$scope.tournaments = [];
$scope.tour.usersSel = [];
$scope.users = users;
$scope.tour.matches = [];


// read users
$scope.getAll = function () {
    $http.get("php/dbActions/tournaments/get_tournaments.php").then(function (response) {
        console.debug(response.data.records);
        if (response.data.records != null) {
            $scope.tournaments = response.data.records;
            //ngNotify.set('Datos de usuarios cargados correctamente', 'success');
        }
    }, function (error) {
        console.log(error);
        ngNotify.set('ERROR - Datos de torneos no cargados', 'error');

    });
}
$scope.getAll();

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
    }).then(function (response) {
      $scope.tour = {};
      ngNotify.set('Torneo creado', 'success');
      $scope.getAll();
    },
    function (error) {
        ngNotify.set('ERROR', 'error');
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
