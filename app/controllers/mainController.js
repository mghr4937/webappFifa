app.controller('mainCtrl', ['$scope', '$http', '$uibModal', function($scope, $http, $uibModal) {
  $scope.tournaments = [];
  $scope.matchs = [];
  $scope.selTour = {};
  $scope.table = [];

  $scope.getAllTournaments = function(){
       $http.get("php/dbActions/tournaments/get_tournaments.php").success(function successCallback(response){
          $scope.tournaments = response.records;


          //$scope.tournamentsDisplayed = [].concat($scope.tournaments);
      }, function errorCallback(response) {
      });
  }

  $scope.getAllTournaments();

  $scope.getAllMatchs = function(id){
    $scope.table = [];
    $http.post("php/dbActions/tournaments/get_matchs.php", {'id': id }
     ).success(function successCallback(response){
          $scope.matchs = response.records;
          getUsers($scope.matchs);
          $scope.generatePosTable();
      }, function errorCallback(response) {
        console.log(response);
      });
  }

  $scope.$watch('matchs', function() {

  });

  $scope.editMatch = function (match) {
       $scope.msg = "";
       var modalInstance = $uibModal.open({
         animation: $scope.animationsEnabled,
         templateUrl: 'editMatchModal.html',
         controller: 'EditModalCtrl',
         size: '',
         resolve: {
           match: function () {
             return match;
           }
         }
       }).closed.then(function(){
         
       });
   }


 $scope.generatePosTable = function (){
        for (var i = 0; i < $scope.table.length; i++) {
          var user = $scope.table[i].user;
          $scope.table[i].pj = 0;
          $scope.table[i].pg = 0;
          $scope.table[i].pe = 0;
          $scope.table[i].pp = 0;
          $scope.table[i].gf = 0;
          $scope.table[i].gc = 0;
          $scope.table[i].dif = 0;
          $scope.table[i].pts = 0;
          for (var x = 0; x < $scope.matchs.length; x++) {
            if( $scope.matchs[x].gol_a != ''){
              if($scope.matchs[x].user_a === user){
                  $scope.table[i].pj++;
                  $scope.table[i].gf += parseInt($scope.matchs[x].gol_a);
                  $scope.table[i].gc += parseInt($scope.matchs[x].gol_b);
                  if($scope.matchs[x].gol_a > $scope.matchs[x].gol_b){
                      $scope.table[i].pg++;
                  }else if ($scope.matchs[x].gol_a < $scope.matchs[x].gol_b) {
                      $scope.table[i].pp++;
                  }else{
                    $scope.table[i].pe++;
                  }
              }
              if($scope.matchs[x].user_b === user){
                  $scope.table[i].pj++;
                  $scope.table[i].gf += parseInt($scope.matchs[x].gol_b);
                  $scope.table[i].gc += parseInt($scope.matchs[x].gol_a);
                  if($scope.matchs[x].gol_a < $scope.matchs[x].gol_b){
                      $scope.table[i].pg++;
                  }else if ($scope.matchs[x].gol_a > $scope.matchs[x].gol_b) {
                      $scope.table[i].pp++;
                  }else{
                    $scope.table[i].pe++;
                  }
              }
              $scope.table[i].pts = $scope.table[i].pg *3 + $scope.table[i].pe;
              $scope.table[i].dif = $scope.table[i].gf - $scope.table[i].gc;
            }
          }
        }
        //console.log($scope.table);
   }

   function getUsers(matchs){
     var usersName = [];
     for (var i = 0; i < matchs.length; i++) {
       if(usersName.indexOf(matchs[i].user_a) < 0){
         usersName.push(matchs[i].user_a);
       }
       if(usersName.indexOf(matchs[i].user_b) < 0){
         usersName.push(matchs[i].user_b);
       }
     }
     for (var i = 0; i < usersName.length; i++) {
       var user = {'user':usersName[i]};
       $scope.table.push(user);
     }
   }


}]);

app.controller('EditModalCtrl', function ($scope, $uibModalInstance, match, $http) {

	$scope.match = angular.copy(match);

	$scope.clearMsg = function (){
		$scope.msg = "";
		$scope.msgDiv = "";
	}

	$scope.setMsg = function (msg){
	  $scope.msg = "** " + msg + " **";
		$scope.msgDiv = "msg-div";
	}

	  $scope.ok = function () {
		  if(angular.isDefined($scope.match.gol_a)){
				if(angular.isDefined($scope.match.gol_b)){
					$http.post("php/dbActions/tournaments/update_match.php", $scope.match).
					then(function successCallback(response){
						match.gol_a = $scope.match.gol_a;
					  match.gol_b = $scope.match.gol_b;
					},
					function errorCallback(response){
						$scope.match = angular.copy(match);
						$scope.setMsg ("UPDATE MATCH ERROR");
					});
			    $uibModalInstance.close();
				}else{
					$scope.setMsg("ERROR, GOL REQUIRED");
				}
			}else{
				$scope.setMsg("ERROR, GOL REQUIRED");
			}
	  };

	  $scope.cancel = function () {
		$scope.match = angular.copy(match);
	    $uibModalInstance.dismiss('cancel');
	  };
});
