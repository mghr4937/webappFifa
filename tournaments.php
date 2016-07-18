<?php include 'html/header.php';?>



  <div class="content-wrapper">
    <div class="container text-center">
      <div ng-class="class" ng-include="'templates/tournaments.html'"></div>
    </div>
  </div>

<script type="text/javascript">
 <?php
 include_once(dirname(__FILE__).'/php/config/database.php');
 include_once(dirname(__FILE__).'/php/objects/user.php');
 // instantiate database and product object
 $database = new Database();
 $db = $database->getConnection();
 // initialize object
 $user = new User($db);
 $stmt = $user->readAll();
 $num = $stmt->rowCount();
 // check if more than 0 record found
 if($num>0){
     $data="";
     $x=1;
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
         extract($row);
         $data .= '{';
             $data .= '"id":"'  . $id . '",';
             $data .= '"name":"' . ucfirst(strtolower($name)) . '"';
         $data .= '}';
         $data .= $x < $num ? ',' : ''; $x++;
       }
 }
?>
var users = <?php echo '[' . $data . ']';  ?>;

</script>

<?php include 'html/footer.php';?>
