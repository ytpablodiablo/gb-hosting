<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

     <?php

     if(isset($_SESSION['success'])){
   $ok = $_SESSION['success'];
   echo "<div class='success'>$ok</div>";
   unset($_SESSION['success']);
     } else {}
     if(isset($_SESSION['error'])){
     $greske = $_SESSION['error'];
   echo "
          <div class='error'>$greske</div>";
   unset($_SESSION['error']);
     } else {}

     ?>


<script type="text/javascript">
  
setTimeout(function(){
  $(".success").fadeOut('slow', function(){
    $(".success").hide();
  });
},5000);

setTimeout(function(){
  $(".error").fadeOut('slow', function(){
    $(".error").hide();
  });
},5000);


</script>