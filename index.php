<?php include 'html/header.php';?>


  <div class="content-wrapper" ng-controller="mainCtrl">
    <div class="container text-center">
      <div ng-class="class" ng-include="'templates/mainInfo.html'"></div>
      <div ng-class="class" ng-include="'templates/matchs.html'"></div>
      <div ng-class="class" ng-include="'templates/statistics.html'"></div>
    </div>
  </div>

<?php include 'html/footer.php';?>
