<?php
class databasefunctions{
	public $host="localhost";
	public $user="root";
	public $pass="";
	public $db="robotsite_db";
	public $mysqli;
	public function pull_latest_post(){
		$this->mysqli= new mysqli($this->host,$this->user,$this->pass,$this->db);
		$sql_latest= mysqli_query($this->mysqli,"SELECT * FROM post") or die("faild to retreve latest post!" . mysqli_error($this->mysqli));
		while($result_latest=mysqli_fetch_array($sql_latest)){
			echo $result_latest['post_title'];
			echo "<br>";
			echo $result_latest['post_text'];
			echo "<br>";
			echo $result_latest['post_date'];
			echo "<br>";
			echo $result_latest['post_img'];
			
			}//end of loop for pull_latest_post
		
		}//end of pull_latest_post
	
	}//end of class
?>