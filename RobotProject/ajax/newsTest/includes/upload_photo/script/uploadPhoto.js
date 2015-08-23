
// JavaScript Document
function uploadImage(){
	$.post("../includes/upload_photo/index.php",{},function (data){
		$("#upload_div").html(data);
	})
	
}
function showphoto(photo){
	addToText(photo);
	var car_id=parseInt(Math.random()*100000);
	var imges=$("#vPhotos").html();
	var pn=photo.split('.');
	var pn_s=pn[0]+'_s.'+pn[1];
	$("#vPhotos").html(imges+'<div id="id_'+car_id+'" style="background-image:url(../../uploads/temp/'+pn_s+')" class="upImage" ><div class="close_butt" onclick="del_photo(\''+car_id+'\',\''+photo+'\')"></div></div>');
}
function addToText(photo){
	str=$('#photosArray').val();
	if(str!=''){
		str+=',';
	}
	str+=photo;
	$('#photosArray').val(str);
	
}
function del_photo(id,photo){
	$('#id_'+id).hide(1000);
	var str=$('#photosArray').val();
	var newVal='';
	var photos=str.split(',');
	var first=0;
	for(i=0;i<photos.length;i++){
		
		if(photos[i]!=photo){
			if(first!=0){
				newVal+=',';
			}
			first=1;
			newVal+=photos[i];
		}
	}
	$('#photosArray').val(newVal);
}