<?php
include("connectdb.php");
$sqlqu = "SELECT workshop_id,workshop_name,workshop_desc,workshop_supervisor,workshop_date,workshop_end_date FROM workshop ORDER BY workshop_date";
$data = mysqli_query($connection,$sqlqu);
$row;
ob_start();
session_start();
if(isset($_SESSION['sessionname']) && isset($_SESSION['seesionpass']))
//header("location: cpanel.php");
exit();
?>
<!doctype html>
<html>
<head>
<!-- Start WOWSlider.com HEAD section -->
<link rel="stylesheet" type="text/css" href="engine1/style.css" />
<script type="text/javascript" src="engine1/jquery.js"></script>
<!-- End WOWSlider.com HEAD section -->
<meta charset="utf-8">
<title>Workshop</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<script src="bootstrap-3.3.5-dist/js/jquery.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap-rtl.min.css">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

</script>
<style>
.post-content {
    position:absolute;
    bottom:5px;
	margin:5px 100px 15px;
    }
.post_title{
	text-align:center;
	font-size:72px;
	color:#999;
	background-image:url(Images/workshop3.jpg);
	}
	.post-content1 {
   position: absolute;
    bottom:5px;
	margin:5px 140px 15px;
    }
	.post_parghraph{
	  font-style:oblique;
	   font-size:20px;
		color:#666;
				}	
.footer{
	background:#000;
	color:#eee;
	font-size:11px;
	}
	ul.unstyled{
		list-style:none;
		padding:0;
		}
		body{
    background:url();
    padding:0;
}

#login-dp{
    min-width: 250px;
    padding: 14px 14px 0;
    overflow:hidden;
    background-color:rgba(255,255,255,.8);
}
#login-dp .help-block{
    font-size:12px    
}
#login-dp .bottom{
    background-color:rgba(255,255,255,.8);
    border-top:1px solid #ddd;
    clear:both;
    padding:14px;
}

#login-dp .form-group {
    margin-bottom: 10px;
}
.btn-fb{
    color: #fff;
    background-color:#3b5998;
}


@media(max-width:768px){
    #login-dp{
        background-color: inherit;
        color: #fff;
    }
    #login-dp .bottom{
        background-color: inherit;
        border-top:0 none;
    }
}

</style>
</head>

<body>
<header>
<nav class="navbar navbar-default navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="robotics.php">الصفحة الرئيسية</a></li>
        <li><a href="posts.php">المقالات</a></li>
        <li><a href="workshop.php">الورشات</a></li>
        <li><a href="#">المشاريع</a></li></ul>

      <form class="navbar-form navbar-left" role="search">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right" style="alignment-adjust:central;">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
			<ul id="login-dp" class="dropdown-menu">
				<li>
					 <div class="row">
							<div class="col-md-12">
								Login via
								 <form class="form" role="form" method="post" action="ok.php" accept-charset="UTF-8" id="login-nav" data-toggle="validator">
										<div class="form-group">
											 <label class="sr-only" for="exampleInputEmail2">Email address</label>
											 <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" name="Email_address" required>
										</div>
										<div class="form-group">
											 <label class="sr-only" for="exampleInputPassword2">Password</label>
											 <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" name="Password" required>
                                             <div class="help-block text-right"><a href="">Forget the password ?</a></div>
										</div>
										<div class="form-group">
											 <button type="submit" class="btn btn-primary btn-block">Sign in</button>
										</div>
										<div class="checkbox">
											 <label>
											 <input type="checkbox"> keep me logged-in
											 </label>
										</div>
								 </form>
							</div>
							<div class="bottom text-center">
								New here ? <a href="#"><b>Join Us</b></a>
							</div>
					 </div>
				</li>
			</ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</header>
<div class="container">
<div class="row">
<?php if (mysqli_num_rows($data)){
while($row=mysqli_fetch_assoc($data)){?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<h1 class="post_title"><?php echo $row['workshop_name'] ?></h1>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<h4>تاريخ البدأ:<?php echo $row['workshop_date'] ?></h4>
<h4>تاريخ الانتهاء:<?php echo $row['workshop_end_date'] ?></h4>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<p class="post_parghraph"><?php echo $row['workshop_desc'] ?></p>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<h4>المشرف:<?php echo $row['workshop_supervisor'] ?></h4>
<?php }} ?>
</div>
</div>
    <div class="container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Sign In</div>
                        
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="loginform" class="form-horizontal" role="form" method="post" action="ok.php" data-toggle="validator">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="email" class="form-control" name="email1"  placeholder=" Enter Email">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="pass" placeholder="password">
                                    </div>
                            <div class="input-group">
                                      <div class="checkbox">
                                        <label>
                                          <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                                        </label>
                                      </div>
                                    </div>
                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->
                                    <div class="col-sm-12 controls">
                                     <button id="btn-signin" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp sign in</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 control">
                                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                            Don't have an account! 
                                        <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                            Sign Up Here
                                        </a>
                                        </div>
                                    </div>
                                </div>    
                            </form>     
                        </div>                     
                    </div>  
        </div>
        <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Sign Up</div>
                            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form" action="sign up.php" method="post" data-toggle="validator">
                                
                                <div id="signupalert" style="display:none" class="alert alert-danger">
                                    <p>Error:</p>
                                    <span></span>
                                </div>
                                <div class="form-group">
                             <label for="email" class="col-md-3 control-label">Email</label>
                                   <div class="col-md-9">
                                      <input type="email" class="form-control" name="email" placeholder="Email Address">
                                    </div> 
                                </div>
                                 
                                <div class="form-group">
                                    <label for="firstname" class="col-md-3 control-label">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="firstname" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="col-md-3 control-label">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="lastname" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
        <label class="col-xs-3 control-label">year</label>
        <div class="col-xs-5 selectContainer">
            <select class="form-control" name="year">
                <option value="">السنة</option>
                <option value="s">سنة اولى</option>
                <option value="m">سنة ثانية</option>
                <option value="l">سنة ثالثة</option>
                <option value="xl">سنة رابعة</option>
            </select>
        </div>
    </div>

                                     <div class="form-group">
                                    <label for="password" class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="passwd" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phonenum" class="col-md-3 control-label">phone</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone" placeholder="phone Number">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-offset-3 col-md-9">
                                        <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Sign Up</button>
                                        <span style="margin-left:8px;">or</span>  
                                    </div>
                                </div>
                            </form>
                         </div>
                    </div>
         </div>
    </div>
<footer>
<div class="container">
<div class="row footer">
<div class="col-sm-2">
<h6>copyrate &copy; Robot</h6>
</div>
<div class="col-sm-4">
<h6>about us</h6>
<p>Robotics club</p>
</div>
<div class="col-sm-2">
<h6>navigation</h6>
<ul class="unstyled">
<li><a href="robotics.php">Home</a></li>
<li><a href="#">services</a></li>
<li><a href="#">links</a></li>
<li><a href="#">contact</a></li>
</ul>
</div>
<div class="col-sm-2">
<h6>follow us</h6>
<ul class="unstyled">
<li><a href="#">Twitter</a></li>
<li><a href="#">Facebook</a></li>
<li><a href="#">Googleplus</a></li>
</ul>
</div>
</div>
</div>
</footer>
</div>
</div>
</div>
</div>
</body>
</html>
<?php
ob_end_flush();
?>