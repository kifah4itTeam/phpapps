<?
if (!defined('GOOD')) die();
function getParent($id){
	$psql = "SELECT p_id from menus WHERE menu_id= $id";
	$presult = mysql_query($psql);
	$prows = mysql_num_rows($presult);
	while ($prows > 0){
		$pid= MYSQL_RESULT($presult,$i,"p_id");
		return $pid;
	}
	return 0;
}
function hasSub($id){
	$psql = "SELECT count(*) c from menus WHERE p_id= $id";
	$presult = mysql_query($psql);
	$prows = mysql_num_rows($presult);
	while ($prows > 0){
		$c= MYSQL_RESULT($presult,$i,"c");
		return $c;
	}
	return 0;
}
function getName($id){
	$psql = "SELECT item_label from menus WHERE menu_id= $id";
	$presult = mysql_query($psql);
	$prows = mysql_num_rows($presult);
	while ($prows > 0){
		$pid= MYSQL_RESULT($presult,$i,"item_label");
		return $pid;
	}
	return 0;
}
function getMenuId($link,$pageLang='ar'){
	$psql = "SELECT menu_id from menus WHERE item_link like '%$link%' and lang='$pageLang'";
	
	$presult = mysql_query($psql);
	$prows = mysql_num_rows($presult);
	while ($prows > 0){
		$pid= MYSQL_RESULT($presult,$i,"menu_id");
		return $pid;
	}
	$link  = $_SERVER['REQUEST_URI'];//////////
	
	//By Ahmad Hajjar : I modified this statement to NOT retrieve menus with NULL or EMPTY links.
	$psql = "SELECT menu_id from menus WHERE '$link' like concat('%',item_link ,'%') and lang='$pageLang' and (item_link is not null) and item_link <> '' ";
	$presult = mysql_query($psql);
	$prows = mysql_num_rows($presult);
	while ($prows > 0){
		$pid= MYSQL_RESULT($presult,$i,"menu_id");
		return $pid;
	}
	
	return 0;
}
function getLink($id){
	$psql = "SELECT item_link from menus WHERE menu_id= $id";
	$presult = mysql_query($psql);
	$prows = mysql_num_rows($presult);
	while ($prows > 0){
		$pid= MYSQL_RESULT($presult,$i,"item_link");
		if( $pid == '#')	return '';
		return $pid;
	}
	return 0;
}
function getMenuPath($cid=0){
	$mid = addslashes($_REQUEST['mid']);
	if($cid==0)	$cid = addslashes($_REQUEST['cid']);
	if(!$mid)	$mid = getTopParent($pageLang, $cid);
    if($cid){
		$curr = $cid;
		$link = getLink($curr);
		if($link)
			$path = "<a href='"._PREF."forms/".getLink($curr)."'>".getName($curr)."</a> ".$path;	
		else
			$path = "<a href='#'>".getName($curr)."</a> ".$path;	
		$curr = getParent($curr);
		while( getParent($curr) != 0){
			$link = getLink($curr);
			if($link)
				$path = "<a href='"._PREF."forms/".getLink($curr)."'>".getName($curr)."</a> ".$path;	
			else
				$path = "<a href='#'>".getName($curr)."</a> ".$path;	
			$curr = getParent($curr);
		}
    }
	$path = "<a href='"._PREF."'>".Homepage."</a> ".$path;
	return $path;
}

function getTitle($thisfile,$pageLang){
switch( $thisfile ){
	case "viewSPage":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT * FROM misc_pages where id=$id and lang='$pageLang'" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "title");
		break;
	case "viewAllNews":
		if($_REQUEST['cat_id']==2)	$title = Circulars;
		else	$title = News;
		break;
	case "viewAllEvents":
		$title = Events;
		break;		
	case "viewPage":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT * FROM pages where id=$id and lang='$pageLang'" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "title");
		break;
	case "contactUs":
		$title = Contact_Us;
		break;
	case "viewEvents":	 
		//$id = addslashes($_REQUEST['id']);
		//$query = "SELECT title_$pageLang FROM event where id=$id" ;
		//$result = mysql_query($query);
		//if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "title_$pageLang");
		break;
	case "viewNews":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT subject FROM news where id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "subject");
		break;
	case "viewForm":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT form_name FROM query_forms where form_id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "form_name");
		break;
	case "viewMembers":	 
		$id = addslashes($_REQUEST['member_id']);
		$query = "SELECT full_name_$pageLang name FROM members where member_id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewCompany":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT name_$pageLang name FROM company WHERE id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewActivities":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT title name FROM activities where id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewCommittees":
		$com_id = addslashes($_REQUEST['com_id']);
		$query = "SELECT com_name_$pageLang name FROM committees where com_id=$com_id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewArticles":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT title name FROM articles where id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewAllLinks":	 
		$id = addslashes($_REQUEST['category']);
		$query = "SELECT name_$pageLang name FROM links_cat where cat_id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewAssociations":	 
		$id = addslashes($_REQUEST['ass_id']);
		$query = "SELECT ass_name_$pageLang name FROM associations where ass_id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
	case "viewAssociations":	 
		$id = addslashes($_REQUEST['ass_id']);
		$query = "SELECT ass_name_$pageLang name FROM associations where ass_id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 )  $title = MYSQL_RESULT($result, 0, "name");
		break;
		
	case "enterNewComplaints":	 
		$title = Complaints;
		break;
	case "viewAllNew_laws":	 
		$title = New_laws;
		break;
	case "viewAllActivities":	 
		$title = Activities;
		break;
	case "viewAllArticles":	 
		$title = Articles;
		break;
	case "viewAllAssociations":	 
		$title = Associations;
		break;
	case "sendToFriend":	 
		$title = SendToFriend;
		break;
	case "viewAllSessions":	 
		$title = Sessions;
		break;
	case "viewSessions":	 
		$id = addslashes($_REQUEST['id']);
		$query = "SELECT session_name name FROM sessions where id=$id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 ){
			$title = MYSQL_RESULT($result, 0, "name");
			if($_REQUEST['pr']) $title .= "-".Session_program;
			else  $title .= "-".Session_report;
		}
		break;
	case "viewVotings":	 
		$title = Votings;
		break;
	case "viewAllLibrary":	 
		$title = Library;
		break;
	case "subscribe":	 
		$title = Subscribe_Mailing_list;
		break;
	case "sitemap":	 
		$title = sitemap;
		break;
		
		
	case "viewAllCommittees":
		$com_type = addslashes($_REQUEST['com_type']);
		if($com_type=="perment")	$title = perment;
		else	$title = "";
		break;
	case "viewProducts":
		$pro_id=$_REQUEST['pro_id'];
		$query = "SELECT pro_name_$pageLang FROM products where pro_id=$pro_id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 ){
			$title = MYSQL_RESULT($result, 0, "pro_name_$pageLang");
		}
		else 
		{
			$title="";
		}
		break;
	case "viewAllProducts":
		$cat_id=$_REQUEST['cat'];
		if($cat_id=="")
		{
			$title="All Categories";
			break;
		}
		$query = "SELECT cat_name_$pageLang FROM products_categories where cat_id=$cat_id" ;
		$result = mysql_query($query);
		if (mysql_num_rows($result) >0 ){
			$title = MYSQL_RESULT($result, 0, "cat_name_$pageLang");
		}
		else 
		{
			$title="";
		}
		break;
	default: $title = "";
   }
   return stripslashes($title);
}
function getSPath($thisfile,$pageLang){
	switch( $thisfile ){
		case "viewNews":	 
			$id=$_REQUEST['id'];
			$query = "SELECT * FROM `news` where id=$id" ;
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$mid=getMenuId("news/viewAllNews.php",$pageLang);
				$path=getMenuPath($mid);
				//$path.="<a href='"._PREF."forms/news/viewNews.php?id=$id'> ".getTitle("viewNews", $pageLang)." </a>";
			}
			else 
			{
				$title="";
			}
			break;
		case "viewEvents":	 
			$id=$_REQUEST['id'];
			$query = "SELECT * FROM `events` where id=$id" ;
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$mid=getMenuId("events/viewAllEvents.php",$pageLang);
				$path=getMenuPath($mid);
				//$path.="<a href='"._PREF."forms/news/viewNews.php?id=$id'> ".getTitle("viewNews", $pageLang)." </a>";
			}
			else 
			{
				$title="";
			}
			break;
		case "viewTeam_work":
			$id=$_REQUEST['id'];
			$query = "SELECT * FROM `team_work` where id=$id" ;
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$mid=getMenuId("team_work/viewAllTeam_work.php",$pageLang);
				$path=getMenuPath($mid);
			}
			else 
			{
				$title="";
			}
			break;
		case "viewJobs":
		case "jobsApply":
			$id=($_REQUEST['jobId']=="")?$_REQUEST['id']:$_REQUEST['jobId'];
			$query = "SELECT * FROM `jobs` where id=$id" ;
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$mid=getMenuId("jobs/viewAllJobs.php",$pageLang);
				$path=getMenuPath($mid);
				//$path.="<a href='"._PREF."forms/news/viewNews.php?id=$id'> ".getTitle("viewNews", $pageLang)." </a>";
			}
			else 
			{
				$title="";
			}
			break;
		case "viewModuleData":
			$id=$_REQUEST['module_id'];
			$query = "SELECT * FROM `modules` where `module_id`=$id" ;
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$mid=getMenuId("cmodules/viewAllModuleData.php?module_id=$id",$pageLang);
				$path=getMenuPath($mid);
			}
			else 
			{
				$title="";
			}
			break;
		case "viewGallery":	 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='"._PREF."all/viewAllGalleries.php'>".Photos."</a>  ";
			break;
		case "viewMembers":	 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='"._PREF."forms/members/viewAllMembers.php'>".Members."</a>  ";
			break;
		case "viewCompany":	 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='"._PREF."forms/company/viewAllCompany.php?c=1'>".Directory."</a>  ";
			break;
		case "viewAllLinks":	 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='#'>".Links."</a> ";
			break;
		case "viewAssociations": 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='"._PREF."forms/associations/viewAllAssociations.php'>".Associations."</a>  ";
			break;
		case "viewAllCommittees": 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='#'>".Committees."</a> ";
			break;
		case "viewCommittees": 
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='"._PREF."forms/committees/viewAllCommittees.php?com_type=perment'>".Committees."</a> ";
			break;
		case "viewArticles":
			$path = "<a href='"._PREF."'>".Homepage."</a>  <a href='"._PREF."forms/articles/viewAllArticles.php'>".Articles."</a> ";
			break;
		case "viewProducts":
			$pro_id=$_REQUEST['pro_id'];
			$query = "SELECT * FROM products where pro_id=$pro_id" ;
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$cat_id= MYSQL_RESULT($result, 0, "cat_id");
				//$mid=getMenuId("products/viewAllProducts.php?cat=$cat_id",$pageLang);
				//$path=getMenuPath($mid);
				$path="<a href='"._PREF."'>".Homepage."</a> ";
				$path.="<a href='"._PREF."forms/products/viewAllProducts.php?cat=$cat_id'> ".
				lookupField("products_categories", "cat_id", "cat_name_$pageLang", $cat_id)." </a>";
				$path.="<a href='"._PREF."forms/products/viewProducts.php?pro_id=$pro_id'> ".getTitle("viewProducts", $pageLang)." </a>";
			}
			else 
			{
				$path="";
			}
			break;
		case "viewAllProducts":
			$cat_id=$_REQUEST['cat'];
			$query = "SELECT * FROM products_categories where cat_id='$cat_id'";
			$result = mysql_query($query);
			$path="";
			if (mysql_num_rows($result) >0 ){
				$cat_name= MYSQL_RESULT($result, 0, "cat_name_$pageLang");
				$path="<a href='"._PREF."'>".Homepage."</a> ";
				$path.="<a href='"._PREF."forms/products/viewAllProducts.php?cat=$cat_id'> ".$cat_name." </a>";
			}
			else 
			{
				$path="";
			}
			break;
		default: $path = "<a href='"._PREF."'>".Homepage."</a> ";
	   }
	return $path;
}
function getPath($pageLang){
		$file  = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);
		$thisfile = substr($file ,0,strpos($file ,'.php'));		
		$mid = addslashes($_REQUEST['mid']);
		if($mid){
			$title = "";
			$path = getMenuPath();
		}
		else if($find_id = getMenuId(getFileURI(),$pageLang)){
			$title = "";
			$path = getMenuPath($find_id);
		}
		else{
//			$title = "<a href='#'>".getTitle($thisfile,$pageLang)."</a>";
			$title = "&nbsp;&nbsp;".getTitle($thisfile,$pageLang);
			$path = getSPath($thisfile,$pageLang);
	}
	if(($thisfile!="viewProducts")&&($thisfile!="viewNews"))
	{
		$path.=$title;
	}
	if($thisfile=="viewAllProducts")
	{
		$path.="&nbsp;&nbsp;".getTitle($thisfile, $pageLang);
	}
	return $path;
}
function addParToLink($link, $par){
	 if( $link == '#')	return '';
	 if(strpos($link, "?")===false) return $link."?".$par;
	 else return $link."&".$par;
}

function getTopParent($pageLang, $menu_id){
	$curr = $menu_id;
	while($curr!=0){
		$menu = MYSQL_QUERY("SELECT p_id FROM menus WHERE (lang = '$pageLang') AND menu_id=$curr");
		//echo ("SELECT p_id FROM menus WHERE (lang = '$pageLang') AND menu_id=$curr");
		if(MYSQL_NUMROWS($menu)==0) return 0;
		$p_id= MYSQL_RESULT($menu,0,"p_id");
		if($p_id == 0)	return $curr;
		else	$curr=$p_id;
	}
	return 0;
}


?>