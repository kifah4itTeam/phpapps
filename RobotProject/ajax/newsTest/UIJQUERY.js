function correctMessageWithoutBack(msg){
/*	$("#dialog").dialog("destroy");*/
	$.unblockUI();
	$.blockUI({
		message: '<h5><img src="includes/css/images/icons/_1_.png">'+msg+'</h5>', 
		css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'border-radius': '10px',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		},
		onUnblock: function(){
			//alert("asdasdasd"); 
			//$("#openDialog").dialog("close"); 
		},
		timeout: 2000
	});
}// JavaScript Document

function PopupCenter(url, title, w, h) {  
    // Fixes dual-screen position                         Most browsers      Firefox  
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  
              
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  
              
    var left = ((width / 2) - (w / 2)) + dualScreenLeft;  
    var top = ((height / 2) - (h / 2)) + dualScreenTop;  
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);  
  
    // Puts focus on the newWindow  
    if (window.focus) {  
        newWindow.focus();  
    }  
}  
function open_window(url, width, height) {
	var my_window;

	my_window = window.open(url, "Title", "scrollbars=0, width="+width+", height="+height+", left=300, top=300");
	my_window.focus();
}