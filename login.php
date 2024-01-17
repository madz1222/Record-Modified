<!DOCTYPE html>
<html lang="en">
	
<?php 
session_start();
include('./db_connect.php');
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login | Record System</title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");
?>

<style>
.navbar{background-color:black;}
</style>	

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">  
    <div class="navbar-header">
    <a class="navbar-brand" href="login.php"><b style='color:#00ff00' >THE REGISTRAR :</b> <b style='color:gold'><b style="font-family:bradley hand itc;"> Records Management System</b></b></a>
    </div>
</nav>

</head>

<style>
	body{
		width: 100%;
	    height: calc(100%);
	    position: fixed;
	    top:0;
	    left: 0
		
	    /*background: #007bff;*/
	}
	main#main{
		background: linear-gradient(to bottom right, #006600 45%, #ffff00 100%);

		width:100%;
		height: calc(100%);
		display: flex;
	}

	.imgcontainer {
  text-align: center;
  margin: 20px 0 12px 0;
  position: relative;
	}

	img.avatar {
  width: 40%;
  border-radius: 50%;
	}

	.card{background-color:#F5F5F5;}
</style>

<body class="bg-dark">
  <main id="main" >
  	
  		<div class="align-self-center w-100 h-80">
  		<div id="login-center" class="bg-none row justify-content-center">
  			<div class="card col-md-4">
  				<div class="card-body">
  					<form id="login-form"  >
					  <h4 class="text-white text-center"><b style="color:#4a4a4a">LOGIN FORM</b></h4><br>
					  <center><img src="images/loginlogo.jpg" alt="Avatar" class="avatar"></center>

  						<div class="form-group">
  							<label for="email" class="control-label text-dark">Username</label>
  							<input type="text" id="email" name="email" class="form-control form-control-sm">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label text-dark">Password</label>
  							<input type="password" id="password" name="password" class="form-control form-control-sm">
  						</div>
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-success">Login</button></center>
  					</form>
  				</div>
  			</div>
  		</div>
  		</div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
	$('.number').on('input',function(){
        var val = $(this).val()
        val = val.replace(/[^0-9 \,]/, '');
        $(this).val(val)
    })
</script>	
</html>