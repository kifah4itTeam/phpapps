<?php
include("connectdb.php");
$sqlqu = "SELECT post_id, post_title, post_img FROM Post ORDER BY post_date desc limit 1";
$data = mysqli_query($connection,$sqlqu);
$row;
?>
<!doctype html>
<html>
<head>
<script type="text/javascript" src="ajax.js"></script>
<title>robotic_club</title>
<!-- Start WOWSlider.com HEAD section -->
<link rel="stylesheet" type="text/css" href="engine1/style.css" />
<script type="text/javascript" src="engine1/jquery.js"></script>
<!-- End WOWSlider.com HEAD section -->
<meta charset="utf-8">
<script src="bootstrap-3.3.5-dist/js/jquery.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap-rtl.min.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<style>

.post-content {
    position:absolute;
    bottom:5px;
	margin:5px 100px 15px;
    }
.post-title{
	font-size:16px;
	font:list-menu;
	font-style:oblique;
	}
	.post-content1 {
   position: absolute;
    bottom:5px;
	margin:5px 140px 15px;
    }
	.thumbnail {
    position: relative;
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
<div class="row">
<div class="col-md-12">
<!-- Start WOWSlider.com BODY section -->
<div id="wowslider-container1">
<div class="ws_images"><ul>
		<li><img src="data1/images/besttopdesktophighqualitywallpapershdbackgroundimagepictures05.jpg" alt="Best-top-desktop-high-quality-wallpapers-hd-background-image-pictures-05" title="Best-top-desktop-high-quality-wallpapers-hd-background-image-pictures-05" id="wows1_0"/></li>
		<li><img src="data1/images/ev3rstorm_and_friends_2560x1600.jpg" alt="Ev3rstorm_and_friends_2560x1600" title="Ev3rstorm_and_friends_2560x1600" id="wows1_1"/></li>
		<li><img src="data1/images/forex_robots.jpg" alt="Forex_Robots" title="Forex_Robots" id="wows1_2"/></li>
		<li><a href="http://wowslider.com"><img src="data1/images/maxresdefault.jpg" alt="responsive slider" title="maxresdefault" id="wows1_3"/></a></li>
		<li><img src="data1/images/mip_robot3.jpg" alt="MiP_Robot-3" title="MiP_Robot-3" id="wows1_4"/></li>
	</ul></div>
	<div class="ws_bullets"><div>
		<a href="#" title="Best-top-desktop-high-quality-wallpapers-hd-background-image-pictures-05"><span><img src="data1/tooltips/besttopdesktophighqualitywallpapershdbackgroundimagepictures05.jpg" alt="Best-top-desktop-high-quality-wallpapers-hd-background-image-pictures-05"/>1</span></a>
		<a href="#" title="Ev3rstorm_and_friends_2560x1600"><span><img src="data1/tooltips/ev3rstorm_and_friends_2560x1600.jpg" alt="Ev3rstorm_and_friends_2560x1600"/>2</span></a>
		<a href="#" title="Forex_Robots"><span><img src="data1/tooltips/forex_robots.jpg" alt="Forex_Robots"/>3</span></a>
		<a href="#" title="maxresdefault"><span><img src="data1/tooltips/maxresdefault.jpg" alt="maxresdefault"/>4</span></a>
		<a href="#" title="MiP_Robot-3"><span><img src="data1/tooltips/mip_robot3.jpg" alt="MiP_Robot-3"/>5</span></a>
	</div></div><div class="ws_script" style="position:absolute;left:-99%"><a href="http://wowslider.net">jquery slideshow</a> by WOWSlider.com v8.0</div>
<div class="ws_shadow"></div>
</div>	
<script type="text/javascript" src="engine1/wowslider.js"></script>
<script type="text/javascript" src="engine1/script.js"></script>
<!-- End WOWSlider.com BODY section -->
</div>
</div>
<div class="row">
<?php if (mysqli_num_rows($data)){
while($row=mysqli_fetch_assoc($data)){?>
<div class="col-xs-6 col-sm-6 col-md-3">
<div class="thumbnail">
<a href="<?php echo("article.php?id=".$row['post_id'])?>"><img src="<?php echo($row['post_img']) ?>" width="300" height="300"  alt=""/></a>
 <div class="caption">
<p class="post-title"><?php echo $row['post_title'] ?></p>
<?php }} ?>
</div>
</div>
</div>
<div class="col-xs-6 col-sm-6 col-md-3">
   <div class="thumbnail">
<a href="#"><img src="Images/Robot.png" width="300" height="300"  alt=""/></a>
 <div class="caption">
<p class="post-title">this is paragraph</p>
</div>
</div>
</div>
<div class="clearfix hidden-md hidden-lg"></div>
<div class="col-xs-6 col-sm-6 col-md-3">
   <div class="thumbnail">
<a href="#"><img src="Images/Robot.png" width="440" height="315"  alt=""/></a>
 <div class="caption">
<p class="post-title">this is paragraph</p>
</div>
</div>
</div>
<div class="col-xs-6 col-sm-6 col-md-3">
   <div class="thumbnail">
<a href="#"><img src="Images/Robot.png" width="440" height="315"  alt=""/></a>
 <div class="caption">
<p class="post-title">this is paragraph</p>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-xs-6 col-sm-6 col-md-4">
<div class="thumbnail">
<a href="#"><img src="Images/Robot.png" width="440" height="315"  alt=""/></a>
 <div class="caption">
<p class="post-title">this is paragraph</p>
</div>
</div>
</div>
<div class="col-xs-6 col-sm-6 col-md-4">
<div class="thumbnail">
<a href="#"><img src="Images/Robot.png" width="440" height="315"  alt=""/></a>
 <div class="caption">
<p class="post-title">this is paragraph</p>
</div>
</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-4">
<div class="thumbnail">
<a href="#"><img src="Images/Robot.png" width="440" height="315"  alt=""/></a>
 <div class="caption">
<p class="post-title">this is paragraph</p>
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
<li><a href="#">Home</a></li>
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
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
