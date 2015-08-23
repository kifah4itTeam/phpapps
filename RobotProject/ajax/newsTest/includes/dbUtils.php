<? 
include("dbUtils_sec.php");
$menuGroups=array('Dashboard');
function imploded_fields($table,$field,$condition=Null){
	if($condition){
		$condition = " WHERE $condition";
	}
	$sql = "SELECT * FROM $table  $condition";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows>0){
		$i=0;
		$fileds_arr = array();
		while ($i<$numberOfRows){
			$field_val = MYSQL_RESULT($result,$i,$field);
			array_push($fileds_arr,$field_val);
			$i++;
		}
	}
	if($fileds_arr){
		return implode(',',$fileds_arr);
	}else{
		return 0;
	}
}

function getMainPhoto($gallery,$width,$height,$photos_path,$thumb_prefex,$default_photo,$hasDefault){
	$main_photo_id=0;
	if($gallery){
		$main_photo_id = lookupField('galleries_galleries','id','main_photo',$gallery);
		if($main_photo_id){
			$main_photo = basename(lookupField('galleries_photos','id','photo',$main_photo_id));
			$thumb = basename(resizeToFile($photos_path.'/'.$main_photo, $width , $height , $photos_path."/cash/$thumb_prefex".$main_photo));	
		}
		
	}
	if($main_photo_id!=0){				
		if(file_exists("$photos_path/cash/$thumb")){
			$src = "$photos_path/cash/$thumb";
		}else{	
			$src = "$photos_path/$thumb";		
		}
	}else{
		if($hasDefault){
			$src=$default_photo;
		}else{
			return false;
		}
	}
	return $src;
}


function returnImplode_ids($table,$condition,$imploded_field){
	$condition = str_replace('where','',$condition);
	$condition = str_replace('WHERE','',$condition);
	$sql = "SELECT * FROM  $table  where $condition";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	
	$i=0;
	$fields = '';
	while ($i<$numberOfRows){
		$field = MYSQL_RESULT($result,$i,$imploded_field);
		if($fields==''){
			$fields = $field;
		}else{
			$fields .= ','.$field;
		}
		$i++;
	}
	return $fields;
}







function getMagOrder($mm){
	$sql = "SELECT MAX(ord) AS max FROM magz_pages where magz_id='$mm'";
	$result = mysql_query($sql);
	if ($result) return $max = MYSQL_RESULT($result,0,'max')+1;
	else return 1;
}

/**
 * Exports table specific data to excel file according to some condition   
 * 
 * @param string $table table from which the data will be exported
 * @param string $fields (Optional) A comma separated list of fields to export from the specified table
 * @param string $condition (Optional) Free condition according to which data will be retrieved
 * @param string $orderBy (Optional) A comma separated list of fields to order exported data according to it
 * @param string $outputFilename (Optional) The name of the excel file to be exported 
 */
function exportDataToExcel($table,$fields="*",$condition="",$orderBy="",$outputFilename="default.xls"){
	$sql="SELECT $fields FROM `$table` ";
	$sql.=($condition!="")?" WHERE ".$condition:"";
	$sql.=($orderBy!="")?" ORDER BY ".$orderBy:"";
	if(exportSqlToExcel($sql,$outputFilename)==false){
		return false;
	}
}
/**
 * Exports the result of the specified SQL query
 * 
 * @param string $sql the SQL statement according to which the data will be exported
 * @param string $outputFilename (Optional) The name of the excel file to be exported 
 */
function exportSqlToExcel($sql,$outputFilename="default.xls"){
	$result=mysql_query($sql);
	$numRows=mysql_num_rows($result);
	$excelArray=array();
	if($numRows>0){
		$i = 0;
		$headerRow=array();
		while ($i < mysql_num_fields($result)) {
			$meta = mysql_fetch_field($result, $i);
			$headerRow[]=$meta->name;
			$i++;
		}
		$excelArray[]=$headerRow;
		$i=0;
		while($i<$numRows){
			$arr=array();
			$arr=mysql_fetch_array($result,MYSQL_NUM);
			$excelArray[]=$arr;
			$i++;
		}
		exportArrayToExcel($excelArray,$outputFilename);
	}
	else 
	{
		return false;
	}
}
/**
 * Exports the passed array to an excel file
 * 
 * @param Array $arr a 2-D array to export to excel
 * @param string $outputFilename (Optional) The name of the excel file to be exported
 */
function exportArrayToExcel($arr,$outputFilename="default.xls"){
	if(countDimensions($arr)<2)
	{
		return false;
	}
	$rows=count($arr);
	$excelFileContents="";
	for($i=0;$i<$rows;$i++)
	{
		$cols=count($arr[$i]);
		for($j=0;$j<$cols;$j++)
		{
			$excelFileContents.=$arr[$i][$j]." \t ";
		}
		$excelFileContents.="\n";
	}
	// Send Header
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-type: application/ms-excel');
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header('Content-Disposition: attachment; filename='.$outputFilename);
	header("Content-Transfer-Encoding: binary ");
	echo $excelFileContents;
}
/**
 * Counts number of dimensions in an array
 * 
 * @param Array $array the array to count its number of dimensions
 */
function countDimensions($array){
	if (is_array(reset($array))){
		$return = countDimensions(reset($array)) + 1;
	}
	else
	{
		$return = 1;
	}
	return $return;
}

/**
 * Get successors of a category
 * 
 * @param Number $cat_id The category to get its successors
 * @param Boolean (Optional) $withMe Determine wither to append $cat_id to the result or not. Default is true
 * @return string Coma separated list of category id's
 */
function getCatSuccessor($cat_id,$withMe=true){
	$Successors=($withMe)?" '".$cat_id."' ":" ";
	if(isLeaf($cat_id))
	{
		return $Successors;
	}
	$sql="SELECT `cat_id` FROM `products_categories` WHERE `parent_cat`=$cat_id";
	$result=mysql_query($sql);
	$numRows=mysql_num_rows($result);
	$i=0;
	while($i<$numRows){
		$son=mysql_result($result,$i,"cat_id");
		$Successors.=", ".getCatSuccessor($son)."";
		$i++;
	}
	return $Successors;
}
/**
 * Check if a category is leaf or not
 * 
 * @param Number $cat_id the category to check if it is a leaf
 */
function isLeaf($cat_id){
	$sql="SELECT * FROM `products_categories` WHERE `parent_cat`='$cat_id';";
	$result=mysql_query($sql);
	$numRows=mysql_num_rows($result);
	if($numRows>0)
	{
		return false;
	}
	else 
	{
		return true;
	}
}
/**
 * Gets the parent of the given category
 * 
 * @param Number $cat_id the category to get its parent
 */
function getCatParent($cat_id){
	return lookupField("products_categories", "cat_id", "parent_cat", $cat_id);
}

//Get current page URL 
function getPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

//To get the URL of the current page without language parameter :
function getFileURIWithoutLang()
{
	//Form the URL
	$currentPageURL = getPageURL();
	//echo $currentPageURL."<br/>";
	//remove language parameters (for users pages)
	$currentPageURL = filterURL($currentPageURL, "pageLang");
	//echo $currentPageURL."<br/>";
	//remove language parameters (for admins pages)
	$currentPageURL = filterURL($currentPageURL, "lang");
	//echo $currentPageURL."<br/>";
	$currentPageURL = trim($currentPageURL,"&");
	//echo $currentPageURL."<br/>";
	//If last char is not ? return $currentPageURL
	//else : If the URI has ? then append & to it 
	//		 else append ?
	if($currentPageURL[strlen($currentPageURL)-1]!='?')
	{
		if(strstr($currentPageURL, '?'))
		{
			$currentPageURL .= "&";			
		}
		else
		{
			$currentPageURL .= "?";
		}
	}
	return $currentPageURL;
}

function limit($text, $limit) {
    if(strlen($text)>$limit){
        $to = strpos($text, " ",$limit);
        if( $to !== false)
            $text = substr( $text , 0 , $to);
    }
    return $text;
}

function ShowGallery_pretty_photo($gid,$show_icon=Null){
	global $HP;
	if($HP==1){$Pref="";}else{$Pref="../";}
	$res="";
	$sql2 = "SELECT * FROM galleries_galleries  where  id=$gid ";
	$result2 = MYSQL_QUERY($sql2);
	$numberOfRows2 = MYSQL_NUMROWS($result2);
	if($numberOfRows2>0){

		$photos=MYSQL_RESULT($result2,0,"photos");
		$main_photo=MYSQL_RESULT($result2,0,"main_photo");
		 $sqlG = "SELECT * FROM galleries_photos  where  id in ($photos) ";
		$resultG = MYSQL_QUERY($sqlG);
		$numberOfRowsG = MYSQL_NUMROWS($resultG);
		if($numberOfRowsG>0){
			$res.="<div class='slideshow-content'>
				<ul style ='list-style:none; display:inline;' id='gallery' class='gallery clearfix cer'>";
			$pxx=1;
			$g=0;
			while($numberOfRowsG>$g){
				$PHid=MYSQL_RESULT($resultG,$g,"id");
				$Photo=MYSQL_RESULT($resultG,$g,"photo");
				$photo2=_PREF."forms/uploads/cash/gal1_".$Photo;
				/*CropSquare($Pref."uploads/".$Photo,100,$Pref."uploads/cash/gal1_".$Photo);*/
				if($show_icon == true){
					if($PHid == $main_photo){
						/*$res.='<li style ="display:inline;"><a  href="'._PREF.'forms/uploads/'.$Photo.'" id="thumb_'.$PHid.'" rel=prettyPhoto[gallery1]" >';
						$res.='<img src="'._PREF.'forms/uploads/'.$Photo.'" border="0" class="thumb">&nbsp;See full size</a></li>';*/
						
						$res.='<li style ="display:inline;"><a class="startGal" href="'._PREF.'forms/uploads/'.$Photo.'" id="thumb_'.$PHid.'" rel=prettyPhoto[gallery1]" >';
						$res.='<img src="'._PREF.'theme/images/see_all.png" border="0" class="thumb">&nbsp;See full size</a></li>'.PHP_EOL;
					}else{
						$res.='<li style ="display:none;"><a  href="'._PREF.'forms/uploads/'.$Photo.'" id="thumb_'.$PHid.'" rel=prettyPhoto[gallery1]" >';
						$res.='<img src="'.$photo2.'" border="0" class="thumb"></a></li>';
					}
					
				}else{
					$res.='<li style ="display:inline;"><a  href="'._PREF.'forms/uploads/'.$Photo.'" id="thumb_'.$PHid.'" rel=prettyPhoto[gallery1]" >';
					$res.='<img src="'.$photo2.'" border="0" class="thumb"></a></li>';
				}

				$g++;
			}
			$res.="</ul>
				</div>";
		}
	}

	return $res."&nbsp;";
}


// Get select all and disselect all checkboxes 
function getJsCheck(){
	return "
		<script>
		$(document).ready(function(){
			$('.selectAll').click(function(){
				$('.check input').each(function(){
					$(this).attr('checked','checked');
				});
				
			});
			$('.disselectAll').click(function(){
				$('.check input').each(function(){
					$(this).attr('checked','');
				});
				
			});
		});	
		</script>
		<div style='cursor:pointer; float:right;'><span class='disselectAll'>".DeselectAll."</span> &nbsp; | &nbsp;	<span class='selectAll'>".SelectAll."</span>  </div>
	";
}
// Get the filename with parameters
function getFileURI(){
   $file  = substr(strrchr($_SERVER['REQUEST_URI'],"/"),1);
   return $file;
}

function getPrevFileURI(){
   $file  = substr(strrchr($_SERVER['HTTP_REFERER'],"/"),1);
   return $file;
}


// we didn't use this method alone but we  call it in getNewId method
//  
function getColumnMax($table, $column) {
	$sql_max = "SELECT MAX($column) AS 'max' FROM $table";
	$result_max =mysql_query($sql_max);
	checkError($result_max, $sql_max);
	$rows_max =mysql_num_rows($result_max);
	if ($rows_max == null || $rows_max = 0) {
		return 0;
	} else {
		return mysql_result($result_max,0,"max");
	}
}
// this method will get the maximum number of this &coloumn  from this $table
// getNewId("agencies","id");
 
function getNewId($table, $coloumn){
	$id=0;	
	$res=getColumnMax($table, $coloumn);
	if (($res>0)||($res==0)){
		$id=$res+1;	  
	}
	 return  $id;
}
//----------------------------
//We use this method to get the order for the last item inserted
function getOrder($table , $field = null , $cond = null ){
	if($cond) $cond = "WHERE ".$cond;
	if(!$field) $field='item_order';
	$sql = "SELECT MAX($field) AS max FROM $table $cond";
	$result = mysql_query($sql);
	if ($result) return $max = MYSQL_RESULT($result,0,'max')+1;
	else return 1;
}

//---------------------------
//this method  returns the first $n words from this string $str
// NOTICE:  YO NEED INSTALL  4.4.0
function strToWords($str){
  $word="";
  $r=0;
  $index=0;
  $res_array;
 while( $r < strlen($str))
 {
  $chr=substr($str,$r,1);
  if ($chr != " ")  $word=$word.$chr;
  elseif ($chr == " ") {
     $res_array[$index]=$word;
	 $index=$index+1;
	 $word="";
	 } 
  $r=$r+1;
 }
 $res_array[$index]=$word;
return $res_array;
}
function getNwords($str,$n){
  $a = strToWords($str);
  $result="";
  $i=0;
  $wordsCount=$a.length;
  if ($n < $wordsCount) return $str;
  else{
  while($i<$n){
     $result=$result." ".$a[$i];
     $i++;
   }
   return $result;
   }
}

/**
 * 
 * Get count of rows in the selected table
 * @param String $table the table of which the count of rows is required
 * @param String $condition free condition to get count on specific rows on the selected table
 */
function getObjectsCount($table,$condition="1>0")
{
	$sql="SELECT COUNT(*) AS co FROM `$table` WHERE $condition";
	$resultCount = MYSQL_QUERY($sql);
	$rowsCount = MYSQL_NUM_ROWS($resultCount);
	if ($rowsCount == null || $rowsCount = 0) {
		return null;
	} else {
		$count= MYSQL_RESULT($resultCount,0,"co");
		return trim($count);
	}
}
/**
 * 
 * Get maximum value of selected field in selected table
 * @param String $table the table of which the count of rows is required
 * @param Strin $field the field to get its maximum value
 * @param String $condition free condition to get maximum of selected field on specific rows of the selected table
 */
function getObjectsMax($table,$field,$condition="1>0")
{
	$sql="SELECT MAX($field) AS max FROM `$table` WHERE $condition";
	$resultMax = MYSQL_QUERY($sql);
	$rowsMax = MYSQL_NUM_ROWS($resultMax);
	if ($rowsMax == null || $rowsMax = 0) {
		return null;
	} else {
		$Max= MYSQL_RESULT($resultMax,0,"max");
		return trim($Max);
	}
}

///------------------------------------------------
function lookupField($table, $id_field, $lookup_field, $id_value) {
	$sql_lookup = "SELECT `$lookup_field` from `$table` where `$id_field` = '$id_value'";
	$result_lookup = MYSQL_QUERY($sql_lookup);
	checkError($result_lookup, $sql_lookup);
	$rows_lookup = MYSQL_NUM_ROWS($result_lookup);
	if ($rows_lookup == null || $rows_lookup = 0) {
		return 0;
	} else {
		$filds = explode(",",$lookup_field);
		$ind = 0; $value="";
		while($ind < count($filds)){
			$value.= stripslashes(MYSQL_RESULT($result_lookup,0,$filds[$ind])." ");
			$ind++;
		}
		return trim($value);
	}
}

function lookupFieldFiltered($table, $id_field, $lookup_field, $id_value, $condition, $no_idValue) {
	if($no_idValue==true){
		$sql_lookup = "SELECT $lookup_field from $table where $condition";
	}else{
		$sql_lookup = "SELECT $lookup_field from $table where ($id_field = $id_value) and $condition";
	}
	$result_lookup = MYSQL_QUERY($sql_lookup);
	checkError($result_lookup, $sql_lookup);
	$rows_lookup = MYSQL_NUM_ROWS($result_lookup);
	if ($rows_lookup == null || $rows_lookup = 0) {
		return 0;
	} else {
		return MYSQL_RESULT($result_lookup, $lookup_field);
	}
}
//----------------------------------------------------------------------
function createCheckbox($table, $id_field, $value_field , $ids_values , $field_name, $condition){
	$sql_check = "SELECT * from $table ";
	if ($condition != null && $condition != "") $sql_check .= " WHERE $condition ";
	$sql_check .= " ORDER BY $id_field";
	$result_check = MYSQL_QUERY($sql_check);
	$rows_check = MYSQL_NUM_ROWS($result_check);
	
	$selected_ids = explode(',',$ids_values);
	if($rows_check){
		$i = 0;		
		while ($i<$rows_check) {
			if($i%3==0 ){echo "<br/>";}
			$id = MYSQL_RESULT($result_check, $i, $id_field);
			$field = MYSQL_RESULT($result_check, $i, $value_field);
			$checkboxs .= "&nbsp; $field <input name='".$field_name."[]' type='checkbox' value='$id' "; 
			if(in_array($id,$selected_ids)){
				$checkboxs .= " checked='checked' />";
			}else{
				$checkboxs .= "/>";
			}
			$i++;
		}
	}
	return $checkboxs;
}
//----------------------------------------------------------------------
function createComboBoxFiltered($table, $id_field, $value_field, $id_value, $field_name, $condition, $required="") {
	$result = "<select name=\"$field_name\" id=\"$field_name\" class=\"$required\">\n";
	if($required!='required'){
		$result .= "<option></option>\n";
	}
	$sql_combo = "SELECT $id_field , $value_field from $table ";
	if ($condition != null && $condition != "") $sql_combo .= " WHERE $condition ";
	$sql_combo .= " ORDER BY $value_field";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, $id_field);
		$filds = explode(",",$value_field);
		$ind = 0; $value="";
		while($ind < count($filds)){
			$value.= MYSQL_RESULT($result_combo,$i, $filds[$ind])." ";
			$ind++;
		}
		$result .= "<option ";
		if ($id_value != null && $id_value != "" && $id_value == $id) 
			$result .= "selected ";
		$result .= "value=\"$id\">".stripslashes($value)."</option>\n";
		$i++;
	}
	$result .= "</select>\n";
	return $result;
}
//----------------------------------------------------------------------
function createComboBoxFilteredFromArray($array,$field_name) {
	$result = "<select name=\"$field_name\" id=\"$field_name\" >\n";
	for($i=0;$i<count($array);$i++){
		$result .= '<option ';
		if ($i == $_REQUEST[$field_name])$result .= ' selected ';
		if($i==0)$i='';
		$result .= 'value="'.$i.'">'.$array[$i].'</option>';

	}
	$result .= "</select>\n";
	return $result;
}
function createComboBox($table, $id_field, $value_field, $id_value, $field_name , $required="", $actions="", $cond="") {
	
	$result = "<select name=\"$field_name\" id=\"$field_name\" class=\"$required\" $actions>\n";
	if($required==''){$result .= "<option></option>\n";}
	$sql_combo = "SELECT $id_field , $value_field from $table $cond ORDER BY $value_field";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, $id_field);
		$filds =explode(",",$value_field);
		$ind = 0; $value="";
		while($ind < count($filds)){
			$value.= MYSQL_RESULT($result_combo,$i, $filds[$ind])." ";
			$ind++;
		}
		$result .= "<option ";
		if ($id_value != null && $id_value != "" && $id_value == $id) 
			$result .= "selected ";
		$result .= "value=\"$id\">".stripslashes($value)."</option>\n";
		$i++;
	}
	$result .= "</select>\n";
	return $result;
}


function createComboBoxNew($table, $id_field, $value_field, $id_value, $field_name, $textField, $condition) {
	$result = "<select name=\"$field_name\" onChange=\"if(this.options[this.selectedIndex].value=='other') $textField.style.display='block'; else  $textField.style.display='none'; \" >\n";
	if ($id_value == null || $id_value == "") 
		$result .= "<option></option>\n";
	$sql_combo = "SELECT $id_field , $value_field from $table ";
	if ($condition != null && $condition != "") $sql_combo .= " WHERE $condition GROUP BY $value_field";
	$sql_combo .= " ORDER BY $value_field";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, $id_field);
		$filds = explode(",",$value_field);
		$ind = 0; $value="";
		while($ind < count($filds)){
			$value.= MYSQL_RESULT($result_combo,$i, $filds[$ind])." ";
			$ind++;
		}
		$result .= "<option ";
		if ($id_value != null && $id_value != "" && $id_value == $id) 
			$result .= "selected ";
		$result .= "value=\"$id\">".stripslashes($value)."</option>\n";
		$i++;
	}
	$result.= "<option value=\"other\">Other --</option>";
	$result.= "</select>\n";
	return $result;
}

function createComboBoxValue($table, $id_field, $value_field, $id_value, $field_name) {
	$result = "<select name=\"$field_name\" >\n";
	if ($id_value == null || $id_value == "") 
		$result .= "<option></option>\n";
	$sql_combo = "SELECT $id_field , $value_field from $table ORDER BY $value_field";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, $id_field);
		$value = MYSQL_RESULT($result_combo,$i,$value_field);
		$result .= "<option ";
		if ($id_value != null && $id_value != "" && $id_value == $id) 
			$result .= "selected ";
		$result .= "value=\"$value\">".stripslashes($value)."</option>\n";
		$i++;
	}
	$result .= "</select>\n";
	return $result;
}

function createComboBoxScript($table, $id_field, $value_field, $id_value, $field_name,$condition, $required="",$scriptFunc,$first_text=null) {
	$result = "<select name=\"$field_name\" class=\"$required\" onChange=\"$scriptFunc\">\n";
	//if ($id_value == null || $id_value == "") 
		if($first_text){
			$result .= "<option value='nothing'>$first_text</option>\n";
		}else{
			$result .= "<option value='nothing'>-------</option>\n";
		}
		
	$sql_combo = "SELECT $id_field , $value_field from $table";
	if ($condition != null && $condition != "") $sql_combo .= " WHERE $condition ";
	$sql_combo .= " ORDER BY $value_field";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, $id_field);
		$value = MYSQL_RESULT($result_combo,$i,$value_field);
		$result .= "<option ";
		if ($id_value != null && $id_value != "" && $id_value == $id) 
			$result .= "selected ";
		$result .= "value=\"$id\">".stripslashes($value)."</option>\n";
		$i++;
	}
	$result .= "</select>\n";
	return $result;
}

function createComboUlBoxScript($table, $id_field, $value_field, $id_value, $field_name,$condition, $html_id="",$ajax_loader,$load_pros=null) {

	
	$result ='<div class="dropdown"  onclick="hide_list(); show_dropdown('.$html_id.')" >';
	if($html_id==1){
		$result .='<input class="dd_text dd_text'.$html_id.'"  type="text" value="'.all_categories.'"    readonly=""  />';
	}
	if($html_id==2){
		$result .='<input class="dd_text dd_text'.$html_id.'"  type="text" value="'.all_brands.'"    readonly=""  />';
	}
				
	if($id_value){
		$result .= '<input class="dd_val '.$field_name.' field'.$html_id.'" name="'.$field_name.'" type="hidden" value="'.$id_value.'"    readonly=""  />';
	}else{
		$result .= '<input class="dd_val '.$field_name.' field'.$html_id.'" name="'.$field_name.'" type="hidden" value="nothing"    readonly=""  />';
	}
	
	
	$result .= '<div class="dd_list dd_list'.$html_id.'" onmousemove="show_dropdown('.$html_id.')" onmouseout="hide_list()" >
					<div class="ul">';
	
	if ($id_value == null || $id_value == ""){
		if($html_id==1){
			$result .="<div class='li'><a onclick='setText(\"$html_id\",\"".all_categories."\",\"nothing\");'>".all_categories."</a></div>";
		}
		if($html_id==2){
			$result .="<div class='li'><a onclick='setText(\"$html_id\",\"".all_brands."\",\"nothing\");'>".all_brands."</a></div>";
		}
	}
		
	$sql_combo = "SELECT $id_field , $value_field from $table";
	if ($condition != null && $condition != "") $sql_combo .= " WHERE $condition ";
	$sql_combo .= " ORDER BY $value_field";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, $id_field);
		$value = MYSQL_RESULT($result_combo,$i,$value_field);
		
		//if ($id_value != null && $id_value != "" && $id_value == $id) 
			//$selected_value = $value;
			
		if($ajax_loader){
			$result .="<div class='li'><a onclick='loadModels(\"$id\",\"\",true); setText(\"$html_id\",\"$value\",\"$id\");";
			if($load_pros){$result.=" loadPros(); ";}
			$result.="'>$value</a></div>";
		}else{
			$result .="<div class='li'><a onclick='";
			$result .=" setText(\"$html_id\",\"$value\",\"$id\");";
			if($load_pros){$result.=" loadPros(); ";}
			$result.="'>$value</a></div>";
		}
		
		$i++;
	}
	//$result .= "</select>\n";
	$result .='</div></div></div>';
	return $result;
}
//-------------------------------------------------------------------------------------------------------
//Gallery Section

function adminGetGPhoto($gid,$w=100,$h=100, $al=""){
	global $HP;
	if($HP==1){$Pref="forms/";}else{$Pref="../";}
	$res="&nbsp;";
	if($gid!=0){
		$sql2 = "SELECT * FROM galleries_galleries g , galleries_photos p where g.id =$gid and g.main_photo=p.id ";
		$result2 = MYSQL_QUERY($sql2);
		$numberOfRows2 = MYSQL_NUMROWS($result2);
		if($numberOfRows2>0){
			$photo=MYSQL_RESULT($result2,0,"p.photo");
			$g_name=MYSQL_RESULT($result2,0,"g.gallery_name");
			if(file_exists($Pref.'uploads/'.$photo)&& $photo!='' ){
				$photo2=Crop($Pref."uploads/".$photo,$w,$h,$Pref."uploads/cash/c".$w."_".$photo);
				$res='<img src="'.$photo2.'" align="'.$al.'" border="0">';
			}
		}
	}
	return $res;
}

function Crop($img, $w,$h, $newfilename){
	if(file_exists($newfilename)) return $newfilename;
	checkDir($newfilename);	
	$strY=0;
	$strX=0;
	//echo $w."-".$h;
	/*if($w==$h){
		CropSquare ($img, $w, $newfilename);
	}
	else*/
	{
	
	//Check if GD extension is loaded
	 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
		 trigger_error("GD is not loaded", E_USER_WARNING);
		 return false;
	 }
	 //Get Image size info
	 $imgInfo = getimagesize($img);
	 $nHeight = $imgInfo[1];
	 $nWidth = $imgInfo[0];
	 switch ($imgInfo[2]) {
		 case 1: $im = imagecreatefromgif($img); break;
		 case 2: $im = imagecreatefromjpeg($img);  break;
		 case 3: $im = imagecreatefrompng($img); break;
		 default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
	 }
	 //If image dimension is smaller, do not resize
	 
	 if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
		 $nHeight = $imgInfo[1];
		 $nWidth = $imgInfo[0];
	 	 return $img;
	 }else{
		//yeah, resize it, but keep it proportional
		if($nWidth/$w<$nHeight/$h){
			$ww=$w;
			$hh=($nHeight*$w)/$nWidth;
			$strY=($hh-$h)/2;
			$side2=($nWidth*$h)/$w;
			$side=$nWidth;
		}else{
			$hh=$h;
			$ww=($nWidth*$h)/$nHeight;
			$strX=($ww-$w)/2;
			$side=($nHeight*$w)/$h;
			$side2=$nHeight;
		}
	 }
	 $newImg = imagecreatetruecolor($w, $h);
	 /* Check if this image is PNG or GIF, then set if Transparent*/  
	 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		 imagealphablending($newImg, false);
		 imagesavealpha($newImg,true);
		 $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		 imagefilledrectangle($newImg, 0, 0, $w, $h, $transparent);
	 }
	 imagecopyresampled($newImg, $im, 0, 0, $strX, $strY, $w, $h,$side,$side2);
	 //Generate the file, and rename it to $newfilename
	 switch ($imgInfo[2]) {
		 case 1: imagegif($newImg,$newfilename); break;
		 case 2: imagejpeg($newImg,$newfilename,100);  break;
		 case 3: imagepng($newImg,$newfilename); break;
		 default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
	 }
	 return $newfilename;
	}
}
function checkDir($file){
	$file;
	$d=explode('/',$file);
	$dir=$d[(count($d)-2)];
	$d1=explode($dir,$file);
	$F_dir=$d1[0].$dir;
	if(!file_exists($F_dir)){
		mkdir($F_dir."/", 0777);
	}
}
function galleryComboBox($id_value, $field_name , $required="",$src="") {
	$result = "<select name=\"$field_name\" id=\"$field_name\" class=\"$required\" onChange=\"if(this.options[this.selectedIndex].value=='new') winopen('../includes/gallery/enterGallery.php?src=$src',850,480) \">\n";
	$result .= "<option></option>\n";
	$sql_combo = "SELECT id , gallery_name_en from galleries_galleries ORDER BY gallery_name_en";
	$result_combo = MYSQL_QUERY($sql_combo);
	checkError($result_combo, $sql_combo);
	$rows_combo = MYSQL_NUM_ROWS($result_combo);
	$i = 0;
	while ($i<$rows_combo) {
		$id = MYSQL_RESULT($result_combo, $i, 'id');
		$filds = explode(",",$value_field);
		$ind = 0; $value="";
		while($ind < count($filds)){
			$value.= MYSQL_RESULT($result_combo,$i, 'gallery_name_en')." ";
			$ind++;
		}
		$result .= "<option ";
		if ($id_value != null && $id_value != "" && $id_value == $id) 
			$result .= "selected ";
		$result .= "value=\"$id\">".stripslashes($value)."</option>\n";
		$i++;
	}
	$result .="<option id=\"newGal\" value=\"new\">--New Galley--</option>\n";
	$result .= "</select>\n";
	return $result;
}

function lookupGallery($id_value) {
	$sql_lookup = "SELECT thumb from galleries_galleries g, galleries_photos p where g.id = '$id_value' AND p.id = g.main_photo";
	$result_lookup = MYSQL_QUERY($sql_lookup);
	checkError($result_lookup, $sql_lookup);
	$rows_lookup = MYSQL_NUM_ROWS($result_lookup);
	if ($rows_lookup == null || $rows_lookup == 0) {
		return 0;
	} else {
		$value.= MYSQL_RESULT($result_lookup,0,'thumb')." ";
		return "<img src=\""._PREF."uploads/$value\" border=\"0\" align=\"top\" /> ";
	}
}
function lookupGalleryUI($id_value, $uploadPath="../uploads/") {
	$sql_lookup = "SELECT photo from galleries_galleries g, galleries_photos p where g.id = $id_value AND p.id = g.main_photo";
	$result_lookup = MYSQL_QUERY($sql_lookup);
	checkError($result_lookup, $sql_lookup);
	$rows_lookup = MYSQL_NUM_ROWS($result_lookup);
	if ($rows_lookup == null || $rows_lookup == 0) {
		return 0;
	} else {
		$value = MYSQL_RESULT($result_lookup,0,'photo');
		//$newfilename = $uploadPath."cash/TEMP_$value";
		//$url = resizeToFile($uploadPath."$value", 150, 135, $newfilename);
		$url=$uploadPath.$value;
		if($url)
			return "<img src=\"$url\" width='600px' border=\"0\" align=\"top\"/> ";
		else
			return "<img src=\""._PREF."uploads/$value\" border=\"0\" align=\"top\" width=\"200\" height=\"135\"/> ";
	}
}
//-------------------------------------------------------------------------------------------------------

function checkError($result, $sql) {
	if (!$result) {
		echo "<hr>\n" . $sql ."<br>\n"; // todo: this line should removed before final delivery.
		echo mysql_error() ."<hr>\n";
	}
}

// this method will make an array from $table 
// this array index is $coloumn1 and this array values are $coloumn2
function  getColoumnAsArray($table,$coloumn1,$coloumn2){
if($coloumn2){
$sql="SELECT $coloumn1,$coloumn2 FROM $table";
$result = MYSQL_QUERY($sql);
$rows_result= MYSQL_NUM_ROWS($result);
checkError($result, $sql);
$arra=Array();
$i=0;
while ($i<$rows_result) {
         $j=MYSQL_RESULT($result,$i,$coloumn2);
        $arra[$j]=MYSQL_RESULT($result,$i,$coloumn1);
		$i++;
	}
	}
else	
{
$sql="SELECT $coloumn1 FROM $table";
$result = MYSQL_QUERY($sql);
$rows_result= MYSQL_NUM_ROWS($result);
checkError($result, $sql);
$arra= Array();
$i=0;
while ($i<$rows_result) {
        
        $arra[$i]=MYSQL_RESULT($result,$i,$coloumn1);
		$i++;
	}
	}
	
	return $arra;
	
}

//genrate random string
function randomStringUtil($length=5){
  $type='num';
  $randstr='';
  srand((double)microtime()*1000000);

  $chars = array ( '1','2','3','4','5','6','7','8','9','0',
  'Q','W','E','R','T','Y','U','I','O','P','L','K','J','H','G','F','D','S','A','Z','X','C','V','B','N','M',
  'q','w','e','r','t','y','u','i','o','p','l','k','j','h','g','f','d','s','a','z','x','c','v','b','n','m'
  );
  if ($type == "alpha") {
    array_push ( $chars, '1' );
  }

  for ($rand = 0; $rand < $length; $rand++)
  {
    $random = rand(0, count($chars) -1);
    $randstr .= $chars[$random];
  }
  return $randstr;
}

//upload file
function handleupload($fieldName, $folder) {
	$availableEx = array("jpg","gif","png","jpeg","pdf","doc","docx","xls","xlsx");
	$realName = str_replace(" ","_",$_FILES[$fieldName]['name']);
	$fileNameParts = explode(".", "$realName");
	$fileExtension = end($fileNameParts); // part behind last dot
	$ext = $fileExtension."";
	if(in_array($ext,$availableEx)){
		while(file_exists($folder."/".$realName)){
			$realName=$fileNameParts[0]."1.".$ext;
		}
		$uploadfile = $folder."/" . $realName;
		if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
			return $realName;
		}
	}
	return NULL;
}

function uploadImg($fieldName, $folder=''){
	$availableEx = array("jpg","gif","png","jpeg");
	if($folder=='')$folder='../../uploads';
	$file=$_FILES[$fieldName]['name'];
	if($file!=''){		
		$fileNameParts = explode(".",$file);
		$ext =strtolower(end($fileNameParts));
		if(in_array($ext,$availableEx)){
			$realName =randomStringUtil(15).'.'.$ext;
			while(file_exists($folder."/".$realName)){
				$realName =randomStringUtil(15).'.'.$ext;
			}
			$uploadfile=$folder."/".$realName;
			if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
				return $realName;		
			}
		}else{
			return 'x';	
		}
	}

}

function uploadimageToGal($fieldName, $gal_name,$src) {
	$folder="../../uploads";
	$realName = str_replace(" ","_",$_FILES[$fieldName]['name']);
  	while(file_exists($folder."/".$realName)){
		$fileNameParts = explode(".", "$realName");
		$fileExtension = end($fileNameParts); 
		$ext = $fileExtension."";
		$realName=$fileNameParts[0]."1.".$ext;
	}
	$small =  "mcith/mcith_".randomStringUtil(20).'.'.$fileExtension;
	$uploadfile = $folder."/".$realName;
	if(move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)){
		$newPhotoId=getNewId("galleries_photos", "id");
		$sql="INSERT INTO `galleries_photos`(`id`,`photo`,`description`,`thumb`)
		VALUES('$newPhotoId','$realName', '', '$small');";
		if(mysql_query($sql)){
			$newGalId=getNewId("galleries_galleries", "id");
			$sql="INSERT INTO `galleries_galleries` 
			(`id`,`gallery_name_en`,`gallery_name_ar`,`description_en`,`main_photo`,`photos`,`src` )VALUES
			('$newGalId','$gal_name','','', '$newPhotoId' , '$newPhotoId' ,'$src');";
			if(mysql_query($sql)){
				return $newGalId;
			}else{
				return 'x';
			}
		}else {
			return 'x';
		}
	}
}
function uploadimageToGallery($fieldName, $folder , $width, $height) {
	$realName = str_replace(" ","_",$_FILES[$fieldName]['name']);
  	while(file_exists($folder."/".$realName)){
		$fileNameParts = explode(".", "$realName");
		$fileExtension = end($fileNameParts); // part behind last dot
		$ext = $fileExtension."";
		$realName=$fileNameParts[0]."1.".$ext;
	}
	if($width && $height){
		$uploadfile = $folder."/cash/" . $realName;
		if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
			$small=resizeToFile ($folder."/cash/".$realName, $width, $height, $folder."/".$realName);		
		}
	}else{
		$uploadfile = $folder."/" . $realName;
		if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile))
		{
			$small=resizeToFile ($folder."/cash/".$realName, $width, $height, $folder."/".$realName);
		}
	}
	$newPhotoId=getNewId("galleries_photos", "id");
	$sql="INSERT INTO `galleries_photos` ( `id` , `photo` , `description` , `thumb` )
						VALUES ('$newPhotoId' , '$realName', '', '$small');";
	if(mysql_query($sql))
	{
		$newGalId=getNewId("galleries_galleries", "id");
		$sql="INSERT INTO `galleries_galleries` ( `id` , `gallery_name` , `description` , `main_photo` , `photos` )
						VALUES ('$newGalId' , '', NULL , '$newPhotoId' , '$newPhotoId');";
		if(mysql_query($sql))
		{
			return $newGalId;
		}
		else
		{
			return false;
		}
	}
	else 
	{
		return false;
	}
}

//upload image
function uploadimage($fieldName, $folder , $width, $height) {
	$realName = str_replace(" ","_",$_FILES[$fieldName]['name']);
  	while(file_exists($folder."/".$realName)){
		$fileNameParts = explode(".", "$realName");
		$fileExtension = end($fileNameParts); // part behind last dot
		$ext = $fileExtension."";
		$realName=$fileNameParts[0]."1.".$ext;
	}
	if($width && $height){
		$uploadfile = $folder."/cash/" . $realName;
		if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
			resizeToFile ($folder."/cash/".$realName, $width, $height, $folder."/".$realName);
			unlink($folder."/cash/".$realName);
			return $realName;		
		}
	}else{
		$uploadfile = $folder."/" . $realName;
		if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile))
			return $realName;
	} 
	return NULL;
}

//resise image
function resizeToFile ($img, $w, $h, $newfilename){
	 if(file_exists($newfilename)) return $newfilename;
	//Check if GD extension is loaded
	 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
		 trigger_error("GD is not loaded", E_USER_WARNING);
		 return false;
	 }
	 //Get Image size info
	 $imgInfo = getimagesize($img);
	 switch ($imgInfo[2]) {
		 case 1: $im = imagecreatefromgif($img); break;
		 case 2: $im = imagecreatefromjpeg($img);  break;
		 case 3: $im = imagecreatefrompng($img); break;
		 default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
	 }
	 //If image dimension is smaller, do not resize
	 if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
		 $nHeight = $imgInfo[1];
		 $nWidth = $imgInfo[0];
	 	 return $img;
	 }else{
		 	 
		//yeah, resize it, but keep it proportional
		$rate = (($w/$imgInfo[0]) < ($h/$imgInfo[1])) ? ($w/$imgInfo[0]) : ($h/$imgInfo[1]);
		$nWidth  = $imgInfo[0] * $rate;
		$nHeight = $imgInfo[1] * $rate;
	 }
	 $nWidth = round($nWidth);
	 $nHeight = round($nHeight);
	 
	 $newImg = imagecreatetruecolor($nWidth, $nHeight);
	 
	 /* Check if this image is PNG or GIF, then set if Transparent*/  
	 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		 imagealphablending($newImg, false);
		 imagesavealpha($newImg,true);
		 $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		 imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	 }
	 imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
	 
	 //Generate the file, and rename it to $newfilename
	 switch ($imgInfo[2]) {
		 case 1: imagegif($newImg,$newfilename); break;
		 case 2: imagejpeg($newImg,$newfilename,100);  break;
		 case 3: imagepng($newImg,$newfilename); break;
		 default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
	 }
	   
	 return $newfilename;
}

//Show banner
function showBanner($loc ,$w ,$h ){
	$sql = "SELECT upload,link FROM banners_banners WHERE (location IN ($loc)) AND (start_date<='CURDATE()') AND (end_date<='CURDATE()') ORDER BY RAND() LIMIT 1";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows>0) {
	$i=0;
		$upload= basename(MYSQL_RESULT($result,$i,"upload"));
		$link= MYSQL_RESULT($result,$i,"link");
		$fileNameParts = explode(".", "$upload");
		$fileExtension = end($fileNameParts); // part behind last dot
		$ext = $fileExtension."";
		if ($ext == "swf"|| $ext == "SWF" ){
		echo'<embed src="'._PREF.'uploads/'.$upload.'" width="'.$w.'" height="'.$h.'" quality="high"  allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />';
		}else{
		if($link) echo'<a href="'.$link.'" target="_blank"><img src="'._PREF.'uploads/'.$upload.'" width="'.$w.'" height="'.$h.'" border="0"></a>';
		else echo'<img src="'._PREF.'uploads/'.$upload.'" width="'.$w.'" height="'.$h.'" border="0">';
		}//end else
	}//end nuber of rows
}
function getBannarName($loc){
	global $BannerLocation;
	for($b=0;$b <count($BannerLocation);$b=$b+2){
		if($BannerLocation[$b]==$loc){
			echo $BannerLocation[$b+1];
		}
	}
}
function bannerLocComboBox($loc){
	global $BannerLocation;
	$ret='<select name="location">';
	for($b=0;$b <count($BannerLocation);$b=$b+2){
		$ret.='<option value="'.$BannerLocation[$b].'"';
		if($BannerLocation[$b]==$loc){
			$ret.= ' selected ';
		}
		$ret.='>'.$BannerLocation[$b+1].'</option>';
	}
	$ret.='</select>';
	echo $ret;
}


function getEval($time,$requirment,$quality,$attitude){
	return ((50*$requirment) + (40*$time) + (5*$quality) + (5*$attitude))/100;
}
function sendMail($from,$to,$subject,$body){ 
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "To:".$to."\r\n";
	$headers .= "From: ".$from." \r\n";

	if(@mail($to, $subject, $body, $headers)){
		return 1;
	}else{ 
		return 0;
	}
}
function sendMessage($from,$to,$subject,$body,$type,$task_id){
	$title = addslashes($subject);
	$description = addslashes($body);
	$sql = "INSERT INTO msgs (`title`,`description`,`create_date`,`from`,`to`,`type`,`task_id`) VALUES 
							 ('$title','$description','".date('Y-m-d h:i:s')."','$from','$to','$type','$task_id')";
	@mysql_query($sql);
	
	
	/*Send Message As Email*/
	$sendto = "info@voilaapps.com";
	$res = mysql_query("SELECT email FROM employees WHERE id = '$to'");
	if(mysql_num_rows($res))
		$sendto.= ",".MYSQL_RESULT($res,0,"email");

	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=windows-1256\r\n";

	/* additional headers */
	$headers .= "To:".$sendto."\r\n";
	$headers .= "From: $email \r\n";
	
	$bodys = explode("<br>",$body);
	$body = $bodys[0]."<br> Please login to the task manager to check it";
	
	mail($sendto,$subject,$body,$headers);
		
}

// Get the filename with parameters
function getFileName(){
   $file  = substr(strrchr($_SERVER['REQUEST_URI'],"/"),1);
   return $file;
}

function getScriptName(){
   $file  = substr(strrchr($_SERVER['SCRIPT_NAME'],"/"),1);
   return $file;
}

function getFileName_no_uri(){
   $file  = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);
   return $file;
}

function getWidgets(){
	$res = array();
	$currFile = getFileName();
	$whereStm = " WHERE ('$currFile' LIKE CONCAT('%',filename) OR '$currFile' LIKE CONCAT('%',filename,'?%') OR '$currFile' LIKE CONCAT('%',filename,'&%') )And (filename<>'') ";
	$sql ="SELECT * FROM wid_curr_widgets $whereStm ";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	
	if($numberOfRows==0){
		if($currFile=='index.php' OR $currFile==''){
			$def_type = 1;
		}else{
			$def_type = 2;
		}
		$whereStm = " WHERE (type='$def_type')";
		
		$ids = returnImplode_ids('wid_curr_widgets',"  $whereStm  ",'id');
	}
	
	if ($numberOfRows>0) {
		
		$ids = returnImplode_ids('wid_curr_widgets',"  $whereStm  ",'id');
	}
	return $ids;
}


function print_internal_Link($link){
	echo $link;
	$url = "";
	if(strpos($link,"page.php?"))
		//27-12-2011 Ahmad mahmoud
		$url = str_replace("view/page/page.php?","pages/editPages.php?",$link);
	if($url)
		echo "&nbsp;&nbsp;<a href=\"../".$url."\" target=\"_blank\">["."Edit Content"."]</a>";
}

function print_GridAdminHead($title, $module, $del, $add, $ref, $help=1,$order=0,$seo=0,$viewAll=0,$lang=''){
	global $activeModule;
	$out .= "<div class=\"head\">";
	$out .= '<div class="heasdTitle">&raquo; '.$activeModule['GN'].' &raquo; <span>'.$activeModule['MN'].$title.'</span></div>';
	if($del)
		$out .= "<a href=\"javascript:actionf('Delete')\" class=\"delete\">
		<img src=\""._PREFICO."Delete.png\" border='0' alt=\""._Delete."\" title=\""._Delete."\"/></a>";
	
	if($add)
		$out .= "<img src=\""._PREFICO."new.png\" border=\"0\" hspace=\"5\" onClick=\"actionf('Enter')\" style=\"cursor:pointer\"  
		alt=\""._add."\" title=\""._add."\"/>";
	
	if($ref)
		$out .= "<img src=\""._PREFICO."refresh.png\" onClick=\"actionf('Refresh')\" onMouseOver=\"over(this)\" style=\"cursor:pointer\" alt=\""._refresh."\" title=\""._refresh."\"/>"; 
	
	if($order){
		$out .= "<img src=\""._PREFICO."Drag.png\" alt=\"".DragToReorder."\" title=\"".DragToReorder."\"/>"; 
	}
	if($seo){
		$out .= '<a href="../common/seo.php?lang='.$lang.'&filename='.$seo.'" class="dialog-form" title="'._SEO.'"><img src="'._PREFICO.'SEO.png" alt="'._SEO.'" title="'._SEO.'" border=0></a>';
	}
	if($viewAll){
		$out .= '<a href="'.$viewAll.'" target="_blank"><img src="'._PREFICO.'View.png" alt="'._View.'" title="'._View.'" border=0></a>';
	}
	$out .= getHelp_brief();
	$out .= "</div>";
	echo $out;
}
function print_ListAdminHead($title, $module, $addUrl=0, $del=0 ,$help=1,$order=0,$seo=0,$viewAll=0,$lang='',$archive=0,$restore=0){
	global $activeModule;
	$out .= "<div class=\"head\">";
	$out .= '<div class="heasdTitle">&raquo; '.$activeModule['GN'].' &raquo; <span>'.$activeModule['MN'].$title.'</span></div>';
	if($del)
		$out .= "<a href=\"javascript:actionf('Delete')\" class=\"delete\"><img src=\""._PREFICO."Delete.png\" alt=\""._Delete."\"  title=\""._Delete."\" border='0'/></a>";
	if($archive)
		$out .= "<a href=\"javascript:actionf('Archive')\" class=\"delete\"><img src=\""._PREFICO."archive.png\" alt=\""._archive."\"  title=\""._archive."\" border='0'/></a>";
	if($restore)
		$out .= "<a href=\"javascript:actionf('Restore')\" class=\"delete\"><img src=\""._PREFICO."restore.png\" alt=\""._restore."\"  title=\""._restore."\" border='0'/></a>";
	
	if($addUrl)
		$out .= "<a href='".$addUrl."'><img src='"._PREFICO."new.png' border='0' align='left' hspace='5' alt=\""._add."\"  title=\""._add."\" /></a>";

	if($order){
		$out .= "<img src=\""._PREFICO."Drag.png\" alt=\"".DragToReorder."\" title=\"".DragToReorder."\"/>"; 
	}	
	if($seo){
		$out .= '<a href="../common/seo.php?lang='.$lang.'&filename='.$seo.'" class="dialog-form" title="'._SEO.'"><img src="'._PREFICO.'SEO.png" alt="'._SEO.'" title="'._SEO.'" border=0></a>';
	}
	if($viewAll){
		$out .= '<a href="'.$viewAll.'" target="_blank"><img src="'._PREFICO.'View.png" alt="'._View.'" title="'._View.'" border=0></a>';
	}
	$out .= getHelp_brief();
	$out .="</div>";
	echo $out;
}
function addToCombobox($grp_title, $pk, $title_col, $table, $cond,$url_template, $id_value ){
	$result .= "<optgroup label=\"".$grp_title."\">\n";
	if($cond)	$cond = "WHERE $cond";
	$sql_p = "SELECT $pk , $title_col from $table $cond  ORDER BY $title_col ";
	$result_p = mysql_query($sql_p);
	$rows_p = mysql_num_rows($result_p);
	$i = 0;
	while ($i<$rows_p){
		$id= MYSQL_RESULT($result_p,$i,"$pk");
		$title= mysql_result($result_p,$i,"$title_col");
		$result .= "<option ";
		$url = str_replace("#ID#",$id,$url_template);
		if ($id_value != null && $id_value != "" && $id_value == $url ) $result .= "selected ";
		$result .= "value=\"$url\">$title</option>\n";
		$i++;
	}
	$result .= "</optgroup>\n";
	return $result;
}
function getLinks($id_value , $lang, $name="item_link", $class="", $script="", $linkPref=null){
$result = "<select name=\"$name\" id=\"$name\" class=\"$class\" $script>\n";
//if ($id_value == null || $id_value == "") $result .= "<option></option>\n";
$result .= "<option value='#'>--------</option>\n";
$result .= "<option value='index.php'>Home</option>\n";
if($lang) $langStmt = "lang='$lang'";
// Pages
$result .= addToCombobox("Text Pages", "id", "title", "pages_pages", $langStmt,$linkPref."view/pages/viewPages.php?id=#ID#", $id_value);

// Forms
$result .= addToCombobox("Forms", "id", "form_name", "qforms_qforms", $langStmt,$linkPref."view/qforms/viewQforms.php?id=#ID#", $id_value);

// Gallery
$result .= addToCombobox("Galleries", "id", "gallery_name_en", "galleries_galleries", "isGallery=1",$linkPref."view/galleries/viewGalleries.php?id=#ID#", $id_value);


// Modules
$result .='<optgroup label="Modules">';
$res=mysql_query("select * from menu_modules where active=1 ");
while($row=mysql_fetch_array($res)){
	$sel='';
	 if($id_value==$row['page'])$sel=" selected ";
	$result .='<option value="'.$row['page'].'" '.$sel.' >'.$row['name'].'</option>';
}

$result .= "</select>\n";
$result .= "</optgroup>\n";

return $result;
}


function menuHasChildren($mid)
{
	$sql="SELECT menu_id FROM menus WHERE p_id='$mid'";
	$res=mysql_query($sql);
	return mysql_numrows($res)>0;
}

//***********************************************************//
/* getGPhoto Function 
1- $gid = gallary id
2- $w = Photo Width
3- $h = Photo Hight
4- $bigSize= (if=1 onclick show photo in oregnal size);
5- $id = to make deffernt bettwn photos put any  unic value here 
6- $noPhoto= URL for Defult photo 
7- $homePage = if funanction load from hom page enter "1"
8- $resizeText = any text to add to resize name
*/
function getGPhoto($gid,$w,$h,$bigSize,$id,$noPhoto,$homePage,$resizeText){
	global $HP;
	if($HP==1){$Pref="";}else{$Pref="../";}
	$pxx=0;
	$res="";
	if($gid===0){
		$pxx=1;
	}else{
		$ph==0;
		$sql2 = "SELECT * FROM galleries_galleries g , galleries_photos p where g.id =$gid and g.main_photo=p.id ";
		$result2 = MYSQL_QUERY($sql2);
		$numberOfRows2 = MYSQL_NUMROWS($result2);
		if($numberOfRows2<=0){
			$pxx=1;
		}else{
			
			$photo=MYSQL_RESULT($result2,0,"p.photo");
			if($bigSize==1){
			$res.='<a  href="'._PREF.'uploads/'.$photo.'" id="thumb_'.$id.'" onClick="return hs.expand(this, {slideshowGroup: '.$id.'})" >';
			}
			$photo2 = resizeToFile($Pref."uploads/".$photo,$w,$h,$Pref."uploads/cash/".$resizeText."_".$photo);
			$res.='<img src="'.$photo2.'" border="0" >';
			if($bigSize==1){$res.='</a>';}
		}
	}
	if($pxx==1){
		$res="&nbsp;";
		if($noPhoto){
			$res='<img src="'.$noPhoto.'" >';
		}
		//$res='<img src="'.resizeToFile ("../images/noEvent.jpg",$width,$hight,"../images/".$id."noEvent.jpg").'" width="'.$width.'" height="'.$hight.'">';
	}
	return $res;
}

//***********************************************************//
//ShowGallery2($gal_id,640,400,90,90,"g1")
/* ShowGallery2
1- $gid = gallary id
2-$pw = Gallery width
3-$ph = Gallery hight
4-$fw = gallary width
5-$fh = gallary hight
6-$Resiz_name = any text to add to resize name
CSS file: includes/gallery/jquery.galleryview/style.css
*/
function ShowGallery2($gid,$pw,$ph,$fw,$fh,$Resiz_name){
	global $HP;
	if($HP==1){$Pref="";}else{$Pref="../";}
	$res="";
	$d="";
	$l="";
	$sql2 = "SELECT * FROM galleries_galleries  where  id=$gid ";
	$result2 = @MYSQL_QUERY($sql2);
	$numberOfRows2 =@ MYSQL_NUMROWS($result2);
	if($numberOfRows2>0){
		$photos=@MYSQL_RESULT($result2,0,"photos");
		$sqlG = "SELECT * FROM galleries_photos  where  id in ($photos) ";
		$resultG = @MYSQL_QUERY($sqlG);
		$numberOfRowsG = @MYSQL_NUMROWS($resultG);
		if($numberOfRowsG>0){
			$pxx=1;
			$g=0;
			$res.='<script type="text/javascript" src="'._PREF.'includes/gallery/jquery.galleryview/galleryview.js"></script>
<script type="text/javascript" src="'._PREF.'includes/gallery/jquery.galleryview/easing.js"></script>
<script type="text/javascript" src="'._PREF.'includes/gallery/jquery.galleryview/timers.js"></script>';
			$res.="<script>\$(document).ready(function(){\$('#photos').galleryView({
				panel_width:".$pw.",panel_height:".$ph.",frame_width:".$fw.",frame_height:".$fh."});});</script>";
			$res.='<table align="center"><tr><td><DIV id=photos class=galleryview align="center">';
			while($numberOfRowsG>$g){
				$PHid=@MYSQL_RESULT($resultG,$g,"id");
				$description=@MYSQL_RESULT($resultG,$g,"description");
				$Photo=@MYSQL_RESULT($resultG,$g,"photo");
				$photo2=_PREF."uploads/cash/galary1".$Resiz_name."_".$Photo;
				$photo3=_PREF."uploads/cash/galary2".$Resiz_name."_".$Photo;
				//if(file_exists(_PREF."uploads/".$Photo)){
					Crop($Pref."uploads/".$Photo,$fw,$fh,$Pref."uploads/cash/galary1".$Resiz_name."_".$Photo);
					Crop($Pref."uploads/".$Photo,$pw,$ph,$Pref."uploads/cash/galary2".$Resiz_name."_".$Photo);
					$d.='<DIV class=panel><IMG src="'.$photo3.'"><DIV class=panel-overlay>'.$description.'</DIV></DIV>';
					$l.='<li><img src="'.$photo2.'" border="0" width="'.($fw-2).'" height="'.($fh-2).'" alt="'.$description.'"  title="'.$description.'"/></li>';
				//}
				$g++;
			}
			$res.=$d;
			$res.='<ul class="filmstrip">'.$l.'</ul>
			';
			$res.='</DIV></td></tr></table>';
		}
	}
	return $res."&nbsp;";
}


function ShowGallery3($gid){
	global $HP;
	//if($HP==1){$Pref="";}else{$Pref="../../";}
	$Pref="../../";
	$res="";
	$sql2 = "SELECT * FROM galleries_galleries  where  id=$gid ";
	$result2 = MYSQL_QUERY($sql2);
	$numberOfRows2 = MYSQL_NUMROWS($result2);
	if($numberOfRows2>0){
		$photos=MYSQL_RESULT($result2,0,"photos");
		 $sqlG = "SELECT * FROM galleries_photos  where  id in ($photos) ";
		$resultG = MYSQL_QUERY($sqlG);
		$numberOfRowsG = MYSQL_NUMROWS($resultG);
		if($numberOfRowsG>0){
			$pxx=1;
			$g=0;
			while($numberOfRowsG>$g){
				$PHid=MYSQL_RESULT($resultG,$g,"id");
				$Photo=MYSQL_RESULT($resultG,$g,"photo");
				$photo2=_PREF."uploads/cash/gal3_".$Photo;
				CropSquare($Pref."uploads/".$Photo,100,$Pref."uploads/cash/gal3_".$Photo);
				$res.='<a  class="thumb zoombox zgallery1" href="'._PREF.'uploads/'.$Photo.'" id="thumb_'.$PHid.'" rel="" >';
				$res.='<img src="'.$photo2.'" border="0" class=""></a>';
				$g++;
			}
			
		}
	}

	return $res."&nbsp;";
}



/**************************************************************************/
// Crop Photo  to be Square
function CropSquare ($img, $s, $newfilename){
	if(file_exists($newfilename)) return $newfilename;
	checkDir($newfilename);	
	$strY=0;
	$strX=0;
	//Check if GD extension is loaded
	 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
		 trigger_error("GD is not loaded", E_USER_WARNING);
		 return false;
	 }
	 //Get Image size info
	 $imgInfo = getimagesize($img);
	 $nHeight = $imgInfo[1];
	 $nWidth = $imgInfo[0];
	 switch ($imgInfo[2]) {
		 case 1: $im = imagecreatefromgif($img); break;
		 case 2: $im = imagecreatefromjpeg($img);  break;
		 case 3: $im = imagecreatefrompng($img); break;
		 default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
	 }
	 //If image dimension is smaller, do not resize
	 if ($imgInfo[0] <= $s && $imgInfo[1] <= $s) {
		 $nHeight = $imgInfo[1];
		 $nWidth = $imgInfo[0];
	 	 return $img;
	 }else{
		//yeah, resize it, but keep it proportional
		if($nHeight>$nWidth){
			$ww=$s;
			$hh=($nHeight*$s)/$nWidth;
			$strY=($nHeight-$nWidth)/2;
			$side=$imgInfo[0];
		}else{
			$hh=$s;
			$ww=($nWidth*$s)/$nHeight;
			$strX=($nWidth-$nHeight)/2;
			$side=$imgInfo[1];
			
		}
	 }
	 $newImg = imagecreatetruecolor($s, $s);
	 /* Check if this image is PNG or GIF, then set if Transparent*/  
	 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		 imagealphablending($newImg, false);
		 imagesavealpha($newImg,true);
		 $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		 imagefilledrectangle($newImg, 0, 0, $s, $s, $transparent);
	 }
	 imagecopyresampled($newImg, $im, 0, 0, $strX, $strY, $s, $s, $side, $side);
	 //Generate the file, and rename it to $newfilename
	 switch ($imgInfo[2]) {
		 case 1: imagegif($newImg,$newfilename); break;
		 case 2: imagejpeg($newImg,$newfilename,100);  break;
		 case 3: imagepng($newImg,$newfilename); break;
		 default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
	 }

	   
	 return $newfilename;
}
/**
 * Create paging plugin for a page
 * @param int $tp Number of records in this page 
 * @param int $pn Current page nummber
 * @param int $LPP Records per page
 * @param string $link (Optional) Additional parameters to be passed to next page 
*/
function createPagination($tp,$pn,$LPP,$link=""){
	

	$PHP_SELF = getPageURL();
	//Remove tp,pn and llp parameters from the URL to avoid repeating parameters
	$pattern = '/tp=(\d+)&/i';
	$PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
	$pattern = '/pn=(\d+)&/i';
	$PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
	$pattern = '/llp=(\d+)&/i';
	$PHP_SELF = preg_replace($pattern, "", $PHP_SELF);

	//Trim & if found to avoid repeating 
	$PHP_SELF = trim($PHP_SELF,"&");
	//If last char is not ? return $PHP_SELF
	//else : If the URI has ? then append & to it 
	//		 else append ?
	if($PHP_SELF[strlen($PHP_SELF)-1]!='?')
	{
		if(strstr($PHP_SELF, '?'))
		{
			$PHP_SELF .= "&";			
		}
		else
		{
			$PHP_SELF .= "?";
		}
	}

	
	$res = "<div align=\"right\" class=\"pagination\">";
	$pages = ceil($tp / $LPP);
	if(!$pn) $pn = 0;
	
	$start = max(0,($pn-2));
	$end = min($pages-1,($start+5));
	$start = max(0,($end-5));
	
	if($pn>0){	
	$res .= "<a class='page' href='{$PHP_SELF}llp=$LPP&tp=$tp&pn=".max(0,($pn-1))."&$link'>&laquo;</a>&nbsp;";
	for($i=$start;$i<$end;$i++)
		if($i == $pn) $res .= '[<font color="#EE4717" size="3">'.($i+1).'</font>] ';
		else $res .= "<a class='page' href='{$PHP_SELF}llp=$LPP&tp=$tp&pn=".($i)."&$link'>".($i+1)."</a> ";
	if($pn+1 < $pages) $res .= "&nbsp;<a class='page' href='{$PHP_SELF}llp=$LPP&tp=$tp&pn=".min(($pn+1),$pages-1)."&$link'>&raquo;</a>";
	$res .= " &nbsp;|&nbsp;";
	}
	
	$res .= "Go To:&nbsp; <select onchange=\"location='{$PHP_SELF}llp=$LPP&tp=$tp&pn='+(options[selectedIndex].value)+'&$link'\" name='select_pn' id='select_pn' >";
	for($i=0;$i<$pages;$i++){
		$sel = "";
		if($i == $pn)	$sel = "selected";
		$res .= "<option $sel value='$i'>".($i+1)."</option>";
	}
	$res .= "</select>";
	$res .= " &nbsp;|&nbsp;";
	if( $tp > 10 )
		$res .= " <a href='{$PHP_SELF}tp=$tp&llp=10&pn=".floor($pn*$LPP/10)."&$link'>".(($LPP==10)?"<u>10</u>":"10")."</a>";
	if( $tp > 20 )
		$res .= " <a href='{$PHP_SELF}tp=$tp&llp=20&pn=".floor($pn*$LPP/20)."&$link'>".(($LPP==20)?"<u>20</u>":"20")."</a>";
	if( $tp > 50 )
		$res .= " <a href='{$PHP_SELF}tp=$tp&llp=50&pn=".floor($pn*$LPP/50)."&$link'>".(($LPP==50)?"<u>50</u>":"50")."</a>";
	if( $tp > 100 )
		$res .= " <a href='{$PHP_SELF}tp=$tp&llp=100&pn=".floor($pn*$LPP/100)."&$link'>".(($LPP==100)?"<u>100</u>":"100")."</a>";
	
	$res .= " <a href='{$PHP_SELF}tp=$tp&llp=".($tp+1)."&pn=".floor($pn*$LPP/($tp+1))."&$link'>".(($LPP==$tp+1)?"<u>ALL</u>":"ALL")."</a>";
	
	$res .= " from $i</div>";
	return $res;
}



function createPagination_ajax($tp,$pn,$LPP,$link=""){
	

	$PHP_SELF = getPageURL();
	//Remove tp,pn and llp parameters from the URL to avoid repeating parameters
	$pattern = '/tp=(\d+)&/i';
	$PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
	$pattern = '/pn=(\d+)&/i';
	$PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
	$pattern = '/llp=(\d+)&/i';
	$PHP_SELF = preg_replace($pattern, "", $PHP_SELF);

	//Trim & if found to avoid repeating 
	$PHP_SELF = trim($PHP_SELF,"&");
	//If last char is not ? return $PHP_SELF
	//else : If the URI has ? then append & to it 
	//		 else append ?
	if($PHP_SELF[strlen($PHP_SELF)-1]!='?')
	{
		if(strstr($PHP_SELF, '?'))
		{
			$PHP_SELF .= "&";			
		}
		else
		{
			$PHP_SELF .= "?";
		}
	}

	
	$res = "<div align=\"center\" class=\"pagination\">";
	$pages = ceil($tp / $LPP);
	if(!$pn) $pn = 0;
	
	$start = max(0,($pn-2));
	$end = min($pages-1,($start+5));
	$start = max(0,min($start,($end-5)));
	//if($pn>0)	{$res .= "<a class='page_num' id='llp=$LPP&tp=$tp&pn=".max(($pn-1),0)."&$link'><img class='left_arrow' src='"._PREF."wedgits/order_products/left_page.jpg'/></a>&nbsp;";}else{$res .= "<img class='left_arrow' src='"._PREF."wedgits/order_products/left_page.jpg'/>&nbsp;";}
	if($pn>0)	{$res .= "<a class='page_num' id='llp=$LPP&tp=$tp&pn=".max(($pn-1),0)."&$link'> &laquo; </a>&nbsp;";}else{$res .= "<font color='#e7dbba'> &laquo; </font> &nbsp;";}
	for($i=$start;$i<=$end;$i++)
		if($i == $pn) $res .= '<font class="curr_num" style="">'.($i+1).'</font> ';
		else $res .= "<a class='page_num num' id='llp=$LPP&tp=$tp&pn=".($i)."&$link'>".($i+1)."</a> ";
	if($pn+1 < $pages){ 
		//$res .= "&nbsp;<a class='page_num' id='llp=$LPP&tp=$tp&pn=".min(($pn+1),$pages-1)."&$link'><img class='right_arrow' src='"._PREF."wedgits/order_products/right_page.jpg'/></a>";
		$res .= "&nbsp;<a class='page_num' id='llp=$LPP&tp=$tp&pn=".min(($pn+1),$pages-1)."&$link'> &raquo; </a>";
	}else{
		//$res .= "<img class='right_arrow' src='"._PREF."wedgits/order_products/right_page.jpg'/>";
		$res .= " <font color='#e7dbba'> &raquo; </font>";
	}

	return $res;
}

function count_items($table, $cond=""){
	if($cond)$cond = "WHERE ".$cond;
	
	$sql="SELECT count(*) co FROM $table $cond";
	$res=mysql_query($sql);
	$co= mysql_result($res,0,"co");	
	return $co;
}

function getUrlParameters(){
	$params = array();
	$i = 0;
	if(!empty($_POST)){
        foreach($_POST as $x => $y){
			$params[$i][0] = ($x);
			$params[$i][1] = $_POST[$x];
			$i++;
        }
    }
	if(!empty($_GET)){
        foreach($_GET as $x => $y){
			$params[$i][0] = ($x);
			$params[$i][1] = $_GET[$x];
			$i++;
        }
    }
	return $params;
}


/**
 * Create Filtering form and return SQL condition
 * @param filters: (array) filtering input names starting with (# <=> equal , % <=> like)
 * @param fields: (array) db fields 
 * @param op: AND/OR
 * @param with_hidden: 1/0 show url variables in SQL condition.
 * @return SQL condition
 */
function createFilter($filters ,$fields , $op="AND", $extention = "", $with_hidden=1,$black_list = null){
	//create filter fields:
	$params = getUrlParameters();
	if(is_array($black_list)){
		array_push($black_list,'action');
		array_push($black_list,'id');
	}else{
		$black_list = array("action","id");
	}
	
	foreach( $params as $param ){
		$name = $param[0];
		$value =  $param[1];
		if($name=="pn")	$value=0;
		if($name=="")	continue;
		if(! in_array($name,$filters) && ! in_array($name,$black_list)){
			 $hidden_input .= "<input type='hidden' id='$name' name='$name' value='$value'/>";
		}
	}
	$result="<form method='get' action='".$_SERVER['SCRIPT_NAME']."'><u>".filteringBy.":</u>&nbsp;";
	$i = 0;
	foreach( $filters as $filter ){
		$filter_name = ucfirst(str_replace("_"," ",$filter)).":";
		$filter_type = "type='text'";
		if($fields[$i][0] == '^')	continue;
		$result .= "$filter_name&nbsp;<input $filter_type id='$filter' name='$filter' value='".$_REQUEST[$filter]."'/>&nbsp;&nbsp;";
		$i ++;
	}
	$result .= $extention;
	if($with_hidden)
		$result .= $hidden_input;
	$result .= "<input type='submit' value='"._go."' style='width:35px' class='go' /></form>";
	echo "<div class='filtering'>".$result."</div>";
	
	//create filter query stmt:
	$num = count($fields);
	$cond = "";
	for($i=0; $i<$num; $i++){
		$value = $_REQUEST[$filters[$i]];
		if($value=="")	continue;
		
		$fieldGrp = trim($fields[$i]);
		$fieldArr  = explode('|', $fieldGrp);
		$cond .= "(";
		foreach( $fieldArr as $field ){
			if(strlen($field)<=1)	continue;
			$compare_type = $field[0];
			$field = substr ($field,1);
			switch($compare_type){
				case "#": case "^":
					$cond .= $field."='".$value."' ";
					break;
				case "%":
					$cond .= $field." LIKE '%".$value."%'";
					break;
			}
			$cond .= " OR ";
		}
		$cond=trim(trim($cond),"OR");
		$cond .= ") $op ";
	}
	$cond=trim(trim($cond),$op);
	return $cond;
}

function orderingUrlSuffix(){
	$PHP_SELF = getPageURL();
	$PHP_SELF = trim($PHP_SELF,"&");
	
	if($PHP_SELF[strlen($PHP_SELF)-1]!='?')
		$PHP_SELF .= (strstr($PHP_SELF, '?'))?"&":"?";

	$pattern = '/so=(\w+)&/i';
	$orderingUrlSuffix = preg_replace($pattern, "", $PHP_SELF);
	$pattern = '/sb=(\w+)&/i';
	$orderingUrlSuffix = preg_replace($pattern, "", $orderingUrlSuffix);
	return $orderingUrlSuffix;
}

function filterURL($url, $filter){
	$pattern = '/'.$filter.'=(\w+)&/i';
	$url = preg_replace($pattern, "", $url);
	$pattern = '/'.$filter.'=(\w+)/i';
	$url = preg_replace($pattern, "", $url);
	return $url;
}

function deleteRecord($table, $cond, $conf_msg){
	$sql = "DELETE FROM $table WHERE $cond";
	$result = MYSQL_QUERY($sql);
	if($result && $conf_msg)
		echo '<script>correctMessage("'.Record_Update.'")</script>';
	return $result;
}


function getCurrentFormName(){
   $file  = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);
   $thisfile = substr($file ,0,strpos($file ,'.php'));
   return $thisfile;
}

function getPrevFormName(){
   $file  = substr(strrchr($_SERVER['HTTP_REFERER'],"/"),1);
   $thisfile = substr($file ,0,strpos($file ,'.php'));
   return $thisfile;
}

///////////////////////////////////
/*function getCurrentFormName(){
   $file  = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);
   $thisfile = substr($file ,0,strpos($file ,'.php'));
   return $thisfile;
}*/

function get_help_topics(){
	$sql = "select * from help_tips_groups";
	$result = mysql_query($sql);
	$numrows = mysql_numrows($result);
	if($numrows){
		$i=0;
		while($i<$numrows){
			$group_id = mysql_result($result,$i,'id');
			$group_label = mysql_result($result,$i,'group_title');
			
			$html_result="<div style='cursor:pointer' class='group_label' id='group_$group_id'><img class='status_img' style='width:5px; height:5px; margin-bottom:2px;' src='"._PREF."images/icons/start.gif'/>$group_label</div>";
			$sql2 = "select * from help_tips where group_id = '$group_id'";
			$result2 = mysql_query($sql2);
			$numrows2 = mysql_numrows($result2);
			if($numrows2){
				$html_result.="<div class='group group_$group_id' id='closed'>";
				$ii = 0;
				while($ii<$numrows2){
					$tip_id = mysql_result($result2,$ii,'id');
					$title = mysql_result($result2,$ii,'title_en');
					$html_result.="<div><a href='viewHelp.php?id=$tip_id'>$title</a></div>";
					$ii++;
				}
				
				$html_result.="</div>";
			}
			$html_result.="</div>";
			$i++;
		}
		
	}
	return $html_result;
}

function getHelp_brief(){
	$thisfile = getCurrentFormName();
	$sql = "select m.id,h.title_en,h.brief_en from login_modules m,help_help h 
	where m.file='$thisfile' and m.m_id!=0 and m.id=h.file_id ";
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if($numrows){
		$tip_id = mysql_result($result,0,'m.id');
		$title = mysql_result($result,0,'h.title_en');
		$brief = mysql_result($result,0,'h.brief_en');
		$help_content = "<div class='help_content'><div class='title'>$title</div><div class='brief'>$brief<a class='details_link' target='_blank' href='../help/help.php?tip_id=$tip_id'>Details...</a></div><br/></div>";
		$html_result = '<div id="help">
		<img src="'._PREFICO.'Help.png" title="'.$help_content.'" style="cursor:pointer" 
		 alt="'.Help.'" title="'.Help.'"/>
	</div>';
	}
	return $html_result;
}

function print_AdminRecordHead($field, $head, $sb, $so, $static_order=0){
	if($static_order)	return $head;
	$out .= '<a href="'.orderingUrlSuffix().'so='.$so.'&sb='.$field.'&"><b>';
	if($sb==$field)
		$out .= "<u>$head</u>&nbsp;<img src='"._PREFICO.$so.".gif' alt='D' border=0>";
	else
		$out .= $head;
	$out .= '</b></a>';
	return $out;
}

function print_seo_icon($filename,$lang=''){
	if($lang){$lang_param = "lang=".$lang."&";}
	return '<a href="../common/seo.php?'.$lang_param.'filename='.$filename.'" class="dialog-form" title="'._SEO.'"><img src="'._PREFICO.'SEO.png" alt="'._SEO.'" title="'._SEO.'" border=0></a>';
}
function print_widget_icon($filename){
	return '<a href="#" onClick="winopen(\'../widgets/listWidgets.php?filename='.$filename.'\',600,700)" ><img src="'._PREFICO.'widgets.png" alt="'.Widgets.'" title="'.Widgets.'" border="0"></a>';
}
function print_gallery_icon($gallery){
	return '<a href="javascript:winopen(\'../includes/gallery/editGallery.php?gid='.$gallery.'\',850,480)"><img src="'._PREFICO.'gallery.png" alt="'._editGal.'" title="'._editGal.'" border=0></a>';
}
function print_edit_icon($url){
	return '<a href="'.$url.'"><img src="'._PREFICO.'Edit.png" alt="'._Edit.'" title="'._Edit.'"  border=0></a>';
}
function print_view_icon($url){
	return '<a href="'.$url.'" target="_blank"><img src="'._PREFICO.'View.png" alt="'._View.'" title="'._View.'" border=0></a>';
}
function print_delete_icon($url=""){
	if($url != "")
		return '<a href="'.$url.'" class="delete"><img src="'._PREFICO.'Delete.png" alt="'._Delete.'"  title="'._Delete.'" border=0></a>';
	else
		return '<img src="'._PREFICO.'Delete_unactive.png" alt="'._Delete.'" title="'._Delete.'" border=0>';
}
function print_save_icon($action){
	return '<img src="'._PREFICO.'save.png" onClick="actionf(\''.$action.'\')" onMouseOver="over(this)" alt="Save" title="Save"  />';
}
function print_subItems_icon($url){
	return '<a href="'.$url.'"><img src="'._PREFICO.'menu.png" alt="'._ico_subMenu.'"  title="'._ico_subMenu.'" border=0></a>&nbsp';
}
function print_delete_ckbox($value=""){
	if($value!="")
		return '<span class="check"><input name="rid[]" type="checkbox" value="'.$value.'" /></span>';
	else
		return '<input  type="checkbox" DISABLED/>';
}
function print_fildes_icon($form_id){
	return '<a href="listForm_fields.php?form_id='.$form_id.'" class="icon"><img src="'._PREFICO.'fields.png" alt="'.Form_Fields.'" title="'.Form_Fields.'" border=0></a>';
}
function print_exp_excel_icon($form_id){
	return '<a target="_blank" href="generate_file.php?id='.$form_id.'" class="icon"><img src="'._PREFICO.'export_excel.png" title="'.downEXCEL.'" alt="'.downEXCEL.'" border=0></a>';
}

function create_lang_switcher($lang){
	if(count_items("languages")>1)
	echo "<div class='lang_switcher' >".switch_Lang." ".createComboBoxScript("languages", "lang", "lang_name", $lang, "lang","","","window.location='?lang='+this.value")."</div>";
}

function createLangCombo($items,$values,$name,$class,$selected_item){
	$items = trim($items);
	$itemsArr  = explode('|', $items);
	$valuesArr  = explode('|', $values);
	$i=0;
	echo "<select name='".$name."' class='".$class."'>";	
	foreach( $itemsArr as $item ){
		if($valuesArr[$i] == $selected_item){
			echo "<option selected value='".$valuesArr[$i]."'>$item</option>";
		}else{
			echo "<option value='".$valuesArr[$i]."'>$item</option>";
		}		
		$i++;		
	}
	echo "</select>";
}

//////////////////////////////Ordering function//////////////////////////////////

function getNewItemOrder($table, $ordering_col, $unique_cond){
	if($unique_cond)	$unique_cond = "WHERE ".$unique_cond;
	$sql = "SELECT MAX($ordering_col) AS max FROM $table $unique_cond ";
	$result = mysql_query($sql);
	if ($result) return $max = MYSQL_RESULT($result,0,'max')+1;
	else return 1;
}

function changeOrder($table, $pk, $ordering_col, $new_ord ){
	$action = $_REQUEST['action'];
	if($action!='up' && $action!='down') return;
	$id = $_REQUEST['id'];
	$sid = addslashes($_REQUEST['sid']);
	$o = addslashes($_REQUEST['o']);
	$so = addslashes($_REQUEST['so']);
	if($id !="" && $sid !="" && $o !="" && $so !="" ){
		if($so==$o){$so=$new_ord;$o=$new_ord+1;}
		$res = mysql_query("UPDATE $table SET $ordering_col='$so' WHERE $pk='$id'");
		$res1 = mysql_query("UPDATE $table SET $ordering_col='$o'  WHERE $pk='$sid'");
		if($res && $res1)
		echo '<script>correctMessageWithoutBack("'.Record_Update.'")</script>';
	}
}

function showUpDownIcons($result, $i, $pk, $ordering_col, $url_prefix){
	$menu_id= MYSQL_RESULT($result,$i,$pk);
	$ord= MYSQL_RESULT($result,$i,$ordering_col);
	$numberOfRows = MYSQL_NUMROWS($result);
	echo '<TD align="center" WIDTH="35">';
	if($i!='0'){
		$So= MYSQL_RESULT($result,$i-1,$ordering_col);
		$Sid= MYSQL_RESULT($result,$i-1,$pk);
		echo '<a href="'.$_SERVER['PHP_SELF'].'?action=up&id='.$menu_id.'&sid='.$Sid.'&o='.$ord.'&so='.$So.'&'.$url_prefix.'">';
		echo '<img src="'._PREFICO.'up.png" alt="Up" border=0>';
		echo '</a>';
	}else echo "&nbsp";
	echo '</TD>';
	echo '<TD align="center" WIDTH="34">';
	if($i+1!=$numberOfRows){
		$So= MYSQL_RESULT($result,$i+1,$ordering_col);
		$Sid= MYSQL_RESULT($result,$i+1,$pk);
		echo '<a href="'.$_SERVER['PHP_SELF'].'?action=down&id='.$menu_id.'&sid='.$Sid.'&o='.$ord.'&so='.$So.'&'.$url_prefix.'">';
		echo '<img src="'._PREFICO.'down.png" alt="Down" border=0>';
		echo '</a>';
	}else echo "&nbsp";
	echo '</TD>';
}

function myFormate($number){
	return number_format(round($number,2), 2, '.', ',');
}
function getLangsAsArray(){
	$langs=array();
	$res=mysql_query("select `lang` from languages ");
	while($row=mysql_fetch_array($res)){
		$langs[]=$row['lang'];
	}
	
	return $langs;
	
}
function makeOrderingList($sql,$id,$table,$filed){
	$res="<script>
	var order_table=\"".$table."\";
	var order_filed =\"".$filed."\";
	var order_id =\"".$id."\";
	ordIds=new Array();";
	$i=1;
	while($row=mysql_fetch_array($sql)){
		$res.="ordIds[".$i."]=".$row[$id].";";
		$i++;
	}
	$res.='
	$(document).ready(function(){
		$("#list tbody tr").hover(function(){
			$(this).css( "cursor", "move" );
		});
		$("#list tbody").sortable({
			connectWith: "tr",
			cursor: "move",
			forcePlaceholderSize: true,
			opacity: 0.4,
			stop: function(event, ui){
				var orderChanges="";
				var sortorder="";
				var itemorder=0;
				$(".sortable tr").each(function(){
					var columnId=$(this).attr("id");
					itemorder++;
					if(columnId!=ordIds[itemorder]){
						orderChanges+=columnId+","+ordIds[itemorder]+"|";
					}
					ordIds[itemorder]=columnId;
				});
				//alert(orderChanges);
				if(orderChanges!=""){
					$("tr").css("cursor","wait");
					$.post("../includes/order.php", {ot:order_table,of:order_filed,oi:order_id, ids:orderChanges} ,function(data){
						$("tr").css("cursor","default");
						$("#info").html(data);
					});
				}
			}
		})
	})';
	$res.="</script>";
	return $res;
}
function getBannerTitle($l){
	global $locations;
	for($i=0;$i<count($locations);$i++){
		if($l==$locations[$i][0]){
			return $locations[$i][1];
		}
	}	
}

function getFileEx($file){
	$ex = explode(".",$file);
	return strtolower(end($ex)); 	
}
function ViewMainPhoto($g_id,$w=100,$h=100){
	$file='';
	$folder='uploads/';
	$reziseFolder="uploads/mcith/mcith_";
	$images_array = array("jpg","gif","png","jpeg");
	$nophoto='<div class=" listViewFileDiv noPhoto" style="width:'.$w.'px; height:'.$h.'px">&nbsp;</div>';
	$sql="select p.photo, p.folder from galleries_galleries g , galleries_photos p where g.main_photo=p.id and g.id='$g_id'";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	if($rows>0){
		$file=mysql_result($res,0,'p.photo');
		$subfolder=mysql_result($res,0,'p.folder');
		$file_ex=getFileEx($file);
		if(file_exists("../../".$folder.$subfolder.$file)){
			if($file==""){
				return $nophoto;
			}else{
				if(in_array($file_ex,$images_array)){
					$newImage=resizeToFile('../../'.$folder.$subfolder.$file,100,100,'../../'.$reziseFolder.$file);
					if($newImage!=''){
						return '
						<div class="listViewFileDiv" style="width:'.$w.'px; height:'.$h.'px;background-image:url('.$newImage.')" 
						onclick="window.open(\'../../'.$folder.$file.'\',\'\',\'width=800,height=500\')" ></div>';
					}else{
						return $nophoto;
					}
				}else{
					if(file_exists('../../images/filesTypes/'.$file_ex.'.png')){
						return '<a href="../../'.$folder.$file.'" target="_blank"><img src="../../images/filesTypes/'.$file_ex.'.png" width="100" style="margin:5px" border="0"></a>';
					}else{
						return '<img src="../../images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
					}
					
				}
			}
		}else{
			return '<img src="../../images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
		}
	}else{return $nophoto;}
}
function ViewAdminListFile($file,$reziseFolder='uploads/mcith/mcith_',$folder='uploads/',$w=100,$h=100){
	$images_array = array("jpg","gif","png","jpeg");
	$file_ex=getFileEx($file);
	$nophoto='<div class=" listViewFileDiv noPhoto" style="width:'.$w.'px; height:'.$h.'px">&nbsp;</div>';
	if(file_exists("../../".$folder.$file)){
		if($file==""){
			return $nophoto;
		}else{
			if(in_array($file_ex,$images_array)){
				$newImage=resizeToFile('../../'.$folder.$file,100,100,'../../'.$reziseFolder.basename($file));
				if($newImage!=''){
					return '
					<div class="listViewFileDiv" style="width:'.$w.'px; height:'.$h.'px;background-image:url('.$newImage.')" 
					onclick="window.open(\'../../'.$folder.$file.'\',\'\',\'width=800,height=500\')" ></div>';
				}else{
					return $nophoto;
				}
			}else{
				if(file_exists('../includes/css/images/filesTypes/'.$file_ex.'.png')){
					return '<a href="../../'.$folder.$file.'" target="_blank"><img src="../includes/css/images/filesTypes/'.$file_ex.'.png" width="100" style="margin:5px" border="0"></a>';
				}else{
					return '<img src="../includes/css/images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
				}
				
			}
		}
	}else{
		return '<img src="../includes/css/images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
	}
}
function checkGallogin(){
	if(isset($_SESSION["enterCMS"]) && $_SESSION["enterCMS"]=='go'){return true;}else{return false;}
}
function convTime($v){
	$str="H ";
	$v=intval($v);
	//-------Hours--------------
	if($v>60*60){
		$str.=intval($v/(60*60)).":";
		$xhour=intval($v%(60*60));
	}else{
		$xhour=$v;
		$str.="0:";
	}
	//-------Mins--------------
	if($xhour>60){
		$str.=intval($xhour/(60)).":";
		$xmin=intval($xhour%(60));
	}else{
		$str.="0:";
		$xmin=$xhour;
	}
	//-------Sec--------------
		$str.=$xmin;

	return $str;

}
function getSortCuts(){
	global $allow_modules;
	$allow=explode(',',$allow_modules);
	$user=$_SESSION['USER_ID'];
	$str='';
	$sql="select* from shortcuts s ,login_modules m , login_modules g 
	where 
	s.user_id='$user' and 
	s.module=m.id and 
	g.id=m.g_id 
	order by g.ord ASC , m.ord ASC
	";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	if($rows>0){
		$str.='<div class="blocktitle">Shortcuts</div>';
		$i=0;
		while($i<$rows){
			$id=mysql_result($res,$i,'m.id');
			$title=mysql_result($res,$i,'m.name');
			$group=mysql_result($res,$i,'g.name');
			$photo=mysql_result($res,$i,'g.photo');
			$folder=mysql_result($res,$i,'m.folder');
			$out_link=mysql_result($res,$i,'m.out_link');
			$file=mysql_result($res,$i,'m.file');
			$link='../'.$folder.'/'.$file.'.php';
			$bg='';
			if($photo)$bg=' style="background:url('._PREFICO.$photo.'.png) no-repeat center 5px" ';
			
			$target='';
			if($out_link)$target=' target="_blank" ';
			
			if(in_array($id,$allow) || $user<2 ){
				$str.='
				<a  href="'.$link.'" class="sr_cut_link" '.$target.'>
				<div '.$bg.' class="shortc" title="'.$group.' : '.$title.'" >
					<div><span>'.$group.'</span><br>'.$title.'</div>
				</div>
				</a>';
			}
			
			$i++;			
		}
	}
	return $str;
}
//*******************************Top News*************************************************/
$permissins=array('','add','edit','del','pub','com');
function get_per($user,$cat){
	$per=array(0,0,0,0,0,0);
	$sql="select * from tnews_permissions where user='$user' and cat='$cat' limit 1";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	if($rows>0){
		$per[1]=mysql_result($res,0,'add');
		$per[2]=mysql_result($res,0,'edit');
		$per[3]=mysql_result($res,0,'del');
		$per[4]=mysql_result($res,0,'pub');
		$per[5]=mysql_result($res,0,'com');
	}
	return $per;
}
function checkNewsUser(){
	$u=filter($_POST["username"]);
	$p=filter($_POST["password"]);
	$sql="select * from tnews_users where user='$u' and pass='$p' and active=1 limit 1";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	if($rows>0){
		$user_id=mysql_result($res,0,'user_id');
		$name=mysql_result($res,0,'full_name');
		$_SESSION["enterCMS"]="topNews";
		$_SESSION["news_user_id"]=$user_id;
		$_SESSION["news_user_name"]=$name;
		$_SESSION["vAdmin"]="0";
		return 1;
	}	
}
function check_news_permissins($user,$p){
	if($_SESSION["enterCMS"]=="go")return 1;
	global $permissins;
	$per=$permissins[$p];
	$sql="select count(*) c from tnews_permissions where `user`='$user' and `$per`=1 ";
	$res=mysql_query($sql);
	return mysql_result($res,0,'c');

}
function get_news_cats($user,$p){
	global $permissins;
	$arr=array();
	$per=$permissins[$p];
	$sql="select cat from tnews_permissions where `user`='$user' and `$per`=1 ";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	while($row=mysql_fetch_array($res)){
		array_push($arr,$row['cat']);
	}
	return $arr;
}
function convToTimeStamp($t){
	$t=substr($t,8,2).'-'.substr($t,5,2).'-'.substr($t,0,4);
	$timestamp = strtotime(date($t)); 
	//echo "<br>";
	//echo $timestamp = date('U');
	return $timestamp;
}

function logNews($user,$opr,$news_id,$comment=0){
	$date=date('U');
	$sql="INSERT INTO tnews_log (`date`,`user`,`opr`,`news`,`comment`)values('$date','$user','$opr','$news_id','$comment')";
	$res=mysql_query($sql);
}
function make_input_date($d){
	$str='';
	$str.= date('Y-m-d',$d);
	return $str; 
}
function make_database_date($t){
	$str='';
	$t=substr($t,5,2).'/'.substr($t,8,2).'/'.substr($t,0,4);
	return $t; 
}
function make_date($d){
	$str='';
	$str.= date('Y-m-d A g:i:s ',$d);
	$from=dateToTimeS(date('U')-$d);
	$str.="(".$from.")";
	return $str; 
}
function dateToTimeS($d){
	$c=0;
	$str='';
	$tt=$d;
	if($tt>60*60*24*365){
		$str.= "Year aGo";		
	}else{
		if($tt>60*60*24){
			$str= intval($tt/60/60/24)." Days";
			$tt2=$tt-(intval($tt/60/60/24)*(60*60*24));
			$c++;
		}else{
			$tt2=$tt;
		}
		if($tt2>60*60){
			if($c<2){
				if($c>0){$str.=' - ';}
				$str.= intval($tt2/60/60)." Hours";
				$tt3= $tt2-(intval($tt2/60/60)*(60*60));
				$c++;
			}
		}else{
			$tt3=$tt2;
		}
		if($tt3>60){
			if($c<2){
				if($c>0){$str.=' - ';}
				$str.= intval($tt3/60)." Minute";
				$tt4= $tt3-(intval($tt3/60)*(60));
				$c++;
			}
		}else{
			$tt4=$tt3;
		}
		if($tt4>0){
			if($c<2){
				if($c>0){$str.=' - ';}
				$str.=intval($tt4)." Second";
			}
		}
	}
	
	return $str;
}
function getPubPer($user,$news,$per){
	$sql="select count(*) c from tnews_tnews n ,tnews_permissions p where 
	n.id='$news' and
	n.category=p.cat and 
	p.user='$user' and 
	p.$per=1
	";
	$res=mysql_query($sql);
	return mysql_result($res,0,'c');	
}
function get_comment_cat($com_id){
	$sql="select n.category from tnews_comments c , tnews_tnews n where c.id='$com_id' and n.id=c.news_id limit  1";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	if($rows>0){ return mysql_result($res,0,'n.category');}
}
function fileUploader($filedName='photos',$photos){
	$vphotos='';
	if($photos){
		$phs=explode(',',$photos);
		for($i=0;$i<count($phs);$i++){
			$round=rand(111,9999);
			$folder=getPhotoFolder($phs[$i]);
			$Bphoto='../../uploads/'.$folder.'/'.$phs[$i];
			$p1=explode('.',$phs[$i]);
			$rsphoto='../../uploads/temp/'.$p1[0].'_s.'.$p1[1];
			$sphoto=resizeToFile ($Bphoto, 80, 80,$rsphoto);
			$vphotos.='
			<div id="id_'.$round.'" style="background-image:url('.$sphoto.')" class="upImage" ><div class="close_butt" onclick="del_photo(\''.$round.'\',\''.$phs[$i].'\')"></div></div>';
		}	
	}
	$str='
	<input name="'.$filedName.'" id="photosArray" style="width:640px;" value="'.$photos.'" type="hidden" />
	<DIV style="width:700px; clear:both" id="vPhotos">&nbsp;'.$vphotos.'</DIV>
	<div id="upload_div" ></div>
	<script type="text/javascript" src="../includes/upload_photo/script/uploadPhoto.js" ></script>
	<script>$(document).ready(function(){uploadImage();})</script>';
 return $str;
}
function getPhotosFromTemp($p,$id=0){
	$out='';
	$f=0;
	$photos=explode(',',$_POST[$p]);
	//delete files---------------
	if($id){
		$lastPhotos=lookupField('tnews_tnews','id','photos',$id);
		$lastPhotosArray=explode(',',$lastPhotos);
		for($i=0;$i<count($lastPhotosArray);$i++){
			if(!in_array($lastPhotosArray[$i],$photos)){
				$folder=getPhotoFolder($lastPhotosArray[$i]);
				$files='../../uploads/'.$folder.'/'.$lastPhotosArray[$i];
				@unlink($files);
			}
		}
	}
	//-------------------

	for($i=0;$i<count($photos);$i++){
		$folder=getPhotoFolder($photos[$i]);
		$tempFile='../../uploads/temp/'.$photos[$i];
		$path='../../uploads/'.$folder.'/';
		if(!file_exists($path)){
			mkdir($path, 0777);
		}
		$newFile=$path.$photos[$i];
		if(!file_exists($newFile)){
			@copy($tempFile,$newFile);
		}
		if(file_exists($newFile)){
			if($f!=0){
				$out.=',';
			}
			$f=1;
			$out.=$photos[$i];
		}
	}
	return $out;
}
function getPhotoFolder($photo){
	$date=substr($photo,0,10);
	$forder=date('ym',$date);
	return $forder;
}
function ViewPhotos($photos,$n='',$w=100,$h=100){
	$file='';
	$allPhotos='';
	$path='../../uploads/';
	$reziseFolder="uploads/cash/thumb_";
	$nophoto='<div class=" listViewFileDiv noPhoto" style="width:'.$w.'px; height:'.$h.'px">&nbsp;</div>';
	if($photos){
		$phs=explode(',',$photos);
		$all=count($phs);
		if($n=='' || $n>count($phs))$n=count($phs);
		for($i=0;$i<$n;$i++){
			$photo=$path.getPhotoFolder($phs[$i]).'/'.$phs[$i];
			$ohito_ex=getFileEx($phs[$i]);	
			if(file_exists($photo)){
				$thamp=resizeToFile($photo,$w,$h,'../../'.$reziseFolder.$phs[$i]);
				if($thamp!=''){
					if($n==1){$allPhotos.= '<div class="totalPhotos">Photos: <b>'. $all.'</b></div>';}
					$allPhotos.= '
					<div class="listViewFileDiv" style="float:left;width:'.$w.'px; height:'.$h.'px;background-image:url('.$thamp.')" 
					onclick="window.open(\''.$photo.'\',\'\',\'width=800,height=500\')" ></div>';
					
				}else{
					$allPhotos.= $nophoto;
				}
				
			}else{
				return '<img src="../includes/css/images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
			}	
		}
	}else{return $nophoto;}
	return $allPhotos;
}
///////////////////////////////////////////////////////////////////////////
function setSEO($lang,$file,$title,$des,$keywords=''){
	$des=strip_tags($des);
	$title=strip_tags($title);
	$sql ="SELECT * FROM seo WHERE filename = '$file' ";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows>0) {
		$SQL = "UPDATE seo SET title_".$lang." = '$title' , keywords_".$lang." = '$keywords' , description_".$lang." = '$des' WHERE filename = '$file' ";
		$result = mysql_query($SQL);
	}else{
		$SQL = "INSERT INTO seo (filename , title_".$lang." , keywords_".$lang." , description_".$lang." ) VALUES 
		('$file' , '$title' , '$keywords' , '$des')";
		$result = mysql_query($SQL);	
	}
}
function deleteTempFiles($m=10){//$m=minutes
	$tempFolder='../../uploads/temp/';
	if(file_exists($tempFolder)){
		$date=date('U')-($m*60);
		if ($Folder = opendir($tempFolder)){
			while (false !== ($file = readdir($Folder))){
				if ($file != "." && $file != ".."){
					$filedate=$date-intval(substr($file,0,10));					
					if($filedate>0){
						@unlink($tempFolder.$file);
					}
				}			
			}
			closedir($Folder);
		}
	}
	
}
function moveToArchive($ids){
	$sql="select * from tnews_tnews where id in ($ids)";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	$arc_date=date('U');
	if($rows>0){
		$i=0;
		while($i<$rows){
			$colms='';
			$values='';
			$c=array('id','subject','brief','details','start_date','end_date','photos','special','lang','user_editor','create_date','user_publisher','publish_date','status','category','views');
			for($a=0;$a<count($c);$a++){
				if($a!=0){$colms.=',';$values.=',';}
				$colms.="`".$c[$a]."`";
				$values.="'".mysql_result($res,$i,$c[$a])."'";
			}
			$colms.=" ,`archive_date`";
			$values.=" ,'".$arc_date."'";
			
			$sql2="INSERT INTO tnews_archive ($colms)values($values)";
			$res2=mysql_query($sql2);
			$id=mysql_result($res,$i,$c[0]);
			if($res2){
				$s="delete from tnews_tnews where id='$id' ";
				mysql_query($s);
			}
			$i++;
		}
	}
}
function backFromArchive($ids){
	$sql="select * from tnews_archive where id in ($ids)";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	$arc_date=date('U');
	if($rows>0){
		$i=0;
		while($i<$rows){
			$colms='';
			$values='';
			$c=array('id','subject','brief','details','start_date','end_date','photos','special','lang','user_editor','create_date','user_publisher','publish_date','status','category','views');
			for($a=0;$a<count($c);$a++){
				if($a!=0){$colms.=',';$values.=',';}
				$colms.="`".$c[$a]."`";
				$values.="'".mysql_result($res,$i,$c[$a])."'";
			}			
			$sql2="INSERT INTO tnews_tnews ($colms)values($values)";
			$res2=mysql_query($sql2);
			$id=mysql_result($res,$i,$c[0]);
			if($res2){
				$s="delete from tnews_archive where id='$id' ";
				mysql_query($s);
			}
			$i++;
		}
	}
}
function createDateFilter(){
	$str='';
	$str.='From : <input name="date_s" class="dateF datefilter" value="'.$_REQUEST['date_s'].'">';
	$str.='To : <input name="date_e" class="dateF datefilter" value="'.$_REQUEST['date_e'].'">';
	return $str;
}
function reciveDatePars($colume,$and='',$dateType='n'){//s=timestamp or n=normal date<br />
	
	if( $_REQUEST['date_s']!='' ||  $_REQUEST['date_e']!='')$Q=' '.$and.' ';
	$f=0;
	//start date
	if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){
		if($dateType=='s'){$ds=convToTimeStamp($_REQUEST['date_s']);}
		if($dateType=='n'){$ds=$_REQUEST['date_s'].' 00:00:00';}
		$Q.=" ".$colume." > '".$ds."' ";
		$f=1;
	}
	
	//end date 
	if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){
		if($dateType=='s'){ $de=convToTimeStamp($_REQUEST['date_e'])+86400;}
		if($dateType=='n'){ $de=$_REQUEST['date_e'].' 23:59:59';}
		if($f==1)$Q.=' and ';
		$Q.=" ".$colume." < '".$de."' ";
	}
	return $Q;
}
//********************Users*******************************
function getActModule(){
	$arr=array('G'=>0,'M'=>0,'GN'=>'','MN'=>'');
	$page=getPageName();
	$sql="select t1.g_id,t1.m_id ,t2.name , t3.name from login_modules t1 , login_modules t2 ,login_modules t3 where 
		t1.file='$page' and 
		t1.active=1 and 
		t1.m_id!=0 and 
		t1.m_id=t2.id and 
		t2.g_id=t3.id		
		limit 1";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	if($rows>0){
		$arr['G']=mysql_result($res,0,'t1.g_id');
		$arr['M']=mysql_result($res,0,'t1.m_id');
		$arr['MN']=mysql_result($res,0,'t2.name');
		$arr['GN']=mysql_result($res,0,'t3.name');
	}
	return $arr;
}
function getPageName($page=''){
	if($page!=''){
		$url=$page;
	}else{
		$url=$_SERVER['REQUEST_URI'];
	}
	$p=explode('/',$url);
	$p2=explode('.',end($p));	
	return $p2[0];
}
function getAllowGroup($user_id){
	$ids='';
	$sql="select m.g_id groups from 
	login_users u , 
	login_groups g ,
	login_groups_permissions p , 
	login_modules m
	where 
	u.user_id='$user_id' and
	u.grp_id=g.grp_id and 
	p.group=g.grp_id and 
	p.module=m.id group by m.g_id";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	$i=0;
	if($rows>0){
		while($i<$rows){
			$id=mysql_result($res,$i,'groups');
			if($i!=0)$ids.=',';
			$ids.=$id;
			$i++;
		}
	}	
	return $ids;
}
function getAllowModules($user_id,$groups){
	$ids='';
	$sql="select m.id modules from 
	login_users u , 
	login_groups g ,
	login_groups_permissions p , 
	login_modules m
	where 
	u.user_id='$user_id' and
	u.grp_id=g.grp_id and 
	p.group=g.grp_id and 
	p.module=m.id and 
	m.g_id in ($groups) 
	
	";
	$res=mysql_query($sql);
	$rows=mysql_num_rows($res);
	$i=0;
	if($rows>0){
		while($i<$rows){
			$id=mysql_result($res,$i,'modules');
			if($i!=0)$ids.=',';
			$ids.=$id;
			$i++;
		}
	}	
	return $ids;
}
/**********************************************************/
function chechUserPermissions($page){
	$page=getPageName($page);
	$user_id=$_SESSION['USER_ID'];
	if($_SESSION["SAdmin"]==1){
		return 1;
	}else if($user_id==1){
		$sql="select count(*)c from 
		login_modules g , 
		login_modules m ,
		login_modules f 
		where 
		f.file='$page' and 
		f.m_id=m.id and 
		f.g_id=g.id and 		
		m.active=1 and
		g.active=1 ";		
		$res=mysql_query($sql);
		return mysql_result($res,0,'c');
		
	}else{	
		$sql="select count(*)c from 
		login_modules g , 
		login_modules m ,
		login_modules f ,
		login_users u,
		login_groups grp,
		login_groups_permissions p 
		where 
		f.file='$page' and 
		f.m_id=m.id and 
		f.g_id=g.id and 		
		m.active=1 and
		g.active=1 and
		
		u.user_id=$user_id and 
		u.active=1 and
		u.grp_id=grp.grp_id and 
		grp.grp_id=p.group and 
		p.module=m.id";
		$res=mysql_query($sql);
		return mysql_result($res,0,'c');
	}
}
function chechDomain(){
	$domins=array('google.com','bing.com','voila5','voila6');
	if($_SERVER['HTTP_HOST']!=_SITE && !in_array($_SERVER['HTTP_HOST'],$domins)){
		echo '<script>parent.document.location="http://'._SITE._PREF.'modules/"</script>';
		return 0;
	}else{
		return 1;
	}
}
?>