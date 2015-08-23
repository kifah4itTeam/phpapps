var ajaxLoaderBasy=0;
var stopSelFolder=0;
mainPhoto=0;
var loading='<div class="loading">LOADING ...</div>';
function loader(s){
	if(s==1){
		ajaxLoaderBasy=1;
		$('#loading').show();
	}else{
		ajaxLoaderBasy=0;
		$('#loading').hide();
	}
}
function loadFolderList(path,actPath,f){
	if(ajaxLoaderBasy==0 && stopSelFolder==0){
		loader(1)
		if(f==1){
			$('#foldersList').html(loading);
		}
		$.post("loadFolders.php",{path:path,actPath:actPath},function(data){
			$('#foldersList').html(data);
			loader(0) 
			if(f!=2){
				loadImages();
			}
		});
	}
}
function del_photo(id,opr,t){
	if(ajaxLoaderBasy==0){
		loader(1)
		$("#upload_div").html(loading);
		if(opr=='info'){				
			$("#upload_div").show('fast');			
			$.post("del.php",{id:id,opr:opr,t:t},function(data){		
				$("#upload_div").html(data);
				loader(0);
				var correntPath=$('#correntPath').val();
			});
		}
		if(opr=='del'){					
			$.post("del.php",{id:id,opr:opr},function(data){
				$("#upload_div").hide('slow');		
				loader(0);
				loadImages(t);
			});
		}
	}
}
function edit_photo(id,des,thumb,t){	
	$("#upload_div").html('<div class="edit_photo"><div style="float:right; margin-right:50px;"><img src="../../../uploads/'+thumb+'"/></div>Edit Image<textarea id="photo_des">'+des+'</textarea><div style=" margin-top:10px;"><div class="back" onClick="$(\'#upload_div\').hide(\'fast\')" style="margin-right:5px;">Cancel</div><div class="next" onclick="save_photo_des('+id+','+t+')">Save</div></div></div>');	
	$("#upload_div").show('fast');			
}
function save_photo_des(id,t){
	if(ajaxLoaderBasy==0){
		loader(1)
		var des=$('#photo_des').val();
		$("#upload_div").html(loading);	
		$.post("editPhoto.php",{id:id,des:des},function(data){
			$("#upload_div").hide('slow');		
			loader(0);
			loadImages(t);
		});
	}	
}
function loadImages(t){
	if(t==2){
		if(ajaxLoaderBasy==0){
			loader(1)
			var folder=$('#correntPath').val();
			$('#gallery_admin2').html(loading);
			$.post("browse.php",function(data){
				$('#gallery_admin2').html(data); 
				loader(0)
			});
		}	
	}else{
		if(ajaxLoaderBasy==0){
			loader(1)
			var folder=$('#correntPath').val();
			$('#gallery_admin').html(loading);
			$.post("browse-library.php?folder="+folder,function(data){
				$('#gallery_admin').html(data); 
				var folder=$('#correntPath').val(); 
				$('#folder_path_view').html(makeRoot(folder))
				loader(0)
			});
		}
	}
}
function makeRoot(folder){
	var viewNewPath='root/';
	var len=rootUploadsFolder.length;
	viewNewPath+=folder.substring(len);	
	return viewNewPath;
}
function viewDes(id,s){
	if(s==1){
		$('#des'+id).show();
	}
	if(s==0){
		$('#des'+id).hide();
	}
}
function newFolder(){
	$('#new_folder').show('slow')
	document.getElementById('addFolder').focus();
}
function saveFolder(){
	if(ajaxLoaderBasy==0){
		loader(1)
		var newFolder=$('#addFolder').val();
		var correntPath=$('#correntPath').val();
		if(newFolder!=''){
			$.post("newFolder.php",{p:correntPath,n:newFolder},function(data){		
				$('#new_folder').hide('slow')
				$('#addFolder').val('');
				loader(0)
				loadFolderList(rootUploadsFolder,correntPath+newFolder+'/',1);
			});		
		}
	}
}
function uploadImage(){
	stopSelFolder=1;
	var correntPath=$('#correntPath').val();
	$("#upload_div").show('slow');
	$("#upload_div").load("index.php?gal=<?=$gid?>&folder="+correntPath);
}
function sync(){
	if(ajaxLoaderBasy==0){
		var correntPath=$('#correntPath').val();
		loader(1)
		$('#foldersList').html('');
		$('#gallery_admin').html(loading);
		$.post("sync.php",{folder:correntPath},function(data){		
			$('#gallery_admin').html(data);
			loader(0)
			loadFolderList(rootUploadsFolder,correntPath,2);
		});	
	}
}
function addToGallery(id,t){
	if(document.getElementById('ch_'+id).checked==true){
		opr='add';
	}else{
		opr='del';	
	}
	if(ajaxLoaderBasy==0){
		loader(1)
		$.post("photoToGallary.php",{id:id,opr:opr},function(data){
			//alert(data)
			loader(0)	
			if(document.getElementById('ch_'+id).checked==true){
				$('#tools_'+id).css('backgroundColor','#abe656');
				opr='add';
			}else{
				$('#tools_'+id).css('backgroundColor','#ccc');
				opr='del';	
			}
			if(t==2){
				loadImages(t)
			}				
		});	
	}else{
		if(document.getElementById('ch_'+id).checked==true){
			document.getElementById('ch_'+id).checked=false;
		}else{
			document.getElementById('ch_'+id).checked=true;	
		}
	}	
}
function setMainPhoto(id){
	if(mainPhoto!=id){
		if(ajaxLoaderBasy==0){
			loader(1);
			$.post("setMainPhoto.php",{id:id},function(data){		
				loader(0)
				if(document.getElementById('main_'+mainPhoto)){
					document.getElementById('main_'+mainPhoto).className='mainPhoto';
				}
				if(document.getElementById('main_'+id)){
					document.getElementById('main_'+id).className='mainPhotoAct';
				}
				mainPhoto=id
			});
	
		}
	}
} 
