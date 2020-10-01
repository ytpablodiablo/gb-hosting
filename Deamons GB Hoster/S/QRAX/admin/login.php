<?php
session_start();

include("config.php");
include("./template/header.php");

if(isset($_SESSION['a_id'])){
	header('location: index.php');
}

if(isset($_SESSION['login'])){
	if($_SESSION["login"] == 2){ 
		?>
		<div class="alert">
			<span class="closebtn">&times;</span>  
			<strong>Greska!</strong> Podaci za prijavljivanje nisu tacni!
		</div>
		<?php
		$_SESSION["login"]= "0";
	}
}
?>
<h2>Login</h2>

<form action="login_process.php">
  <div class="imgcontainer">
    <img src="template/images/img_avatar.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
        
    <button type="submit">Login</button>
  </div>
</form>
<?php
include("./template/footer.php");
?>