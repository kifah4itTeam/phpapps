<?php
include("connectdb.php");
$sqlqu = "SELECT post_title,post_text,post_date,post_img,Category_id FROM post ORDER BY post_date";
$data = mysqli_query($connection,$sqlqu);
$id2="SELECT Category_Id,Category_name FROM category";
$data = mysqli_query($connection,$sqlqu);
$row;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>posts</title>
<script src="bootstrap-3.3.5-dist/js/jquery.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap-rtl.min.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<style>
.post_title{
	text-align:center;
	font-size:72px;
	color:#999;
	background-image:url(Images/robots.png);
	}
	.post_cat{
		text-align:right;
		}
		.post_img{
			margin-right:50%;
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
								 <form class="form" role="form" method="post" action="login.php" accept-charset="UTF-8" id="login-nav">
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
<h1 class="post_title"><?php echo $row['post_title'] ?></h1>
</div>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
<h4><label  class="post_cat">الصنف:</label><?php echo $row['Category_id'] ?></h4>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="post_img"><img src="<?php echo($row['post_img'])?>" width="700px" height="500px" class="img-responsive"></div>
</div>
</div>
<div class="row">
<div>
<p>تاريخ:<?php echo $row['post_date'] ?></p>
</div>
<div class="row">
<div class="col-md-12">
<p class="post_parghraph"><?php echo($row['post_text'])?></p>
</div>
<?php }} ?>
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
</body>
</html>