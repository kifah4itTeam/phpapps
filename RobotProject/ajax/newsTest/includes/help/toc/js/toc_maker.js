/************************************************************************************************************
(C) www.dhtmlgoodies.com, November 2005

This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

************************************************************************************************************/	




var activeTocLink = false;
var activePage = false;
var content_container;

var numberingLetters = 'ABCDEFGHIJKLMNOPQRSTUVXYZ';

// Convert to Roman Numerals
// copyright 25th July 2005, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown
function roman(n,s) {
	var r = '';
	var d;
	var rn = new Array('IIII','V','XXXX','L','CCCC','D','MMMM');
	for (var i=0; i< rn.length; i++) {var x = rn[i].length+1;var d = n%x; r= rn[i].substr(0,d)+r;n = (n-d)/x;}
	if (s) {r=r.replace(/DCCCC/g,'CM');r=r.replace(/CCCC/g,'CD');r=r.replace(/LXXXX/g,'XC');r=r.replace(/XXXX/g,'XL');r=r.replace(/VIIII/g,'IX');r=r.replace(/IIII/g,'IV');}
	return r;
}

function getTopPos(inputObj)
{		
	var returnValue = inputObj.offsetTop;
	while((inputObj = inputObj.offsetParent) != null){
		returnValue += inputObj.offsetTop;
	}
	return returnValue;
}


function linkSwitchPage()
{
	var pageObj = this.parentNode.parentNode;	// Reference to page div
	pageObj.style.display='none'
	var numericId = pageObj.id.replace(/[^0-9]/g,'')/1;
	if(this.innerHTML.indexOf(previous_txt)>=0)numericId-=1; else numericId+=1;
	
	document.getElementById('content_page' + numericId).style.display='block';
	activePage=document.getElementById('content_page' + numericId);
	window.scrollTop = 0;
	
	if(activeTocLink)activeTocLink.style.cssText='';
	firstEl = activePage.getElementsByTagName('H1')[0];
	if(firstEl){
		activeTocLink = document.getElementById('toc_link' + firstEl.id.replace(/[^0-9]/g,''));
		activeTocLink.style.cssText = cssActiveLink;
		if(tocStyle==2){
			showSubMenu(false,activeTocLink.parentNode);
		}
	}
		


}

function navigateToElement(e,inputObj)
{
	if(!inputObj)inputObj = this;
	var currentId = inputObj.id.replace(/[^0-9]/g,'');
	var elementToShow = document.getElementById('content_element' + currentId);
	
	if(activeTocLink)activeTocLink.style.cssText = '';
	inputObj.style.cssText = cssActiveLink;
	activeTocLink = inputObj;
	
	
	var parentEl = elementToShow.parentNode;
	while(parentEl && parentEl.className!='content_page'){
		parentEl = parentEl.parentNode;
	}
	if(activePage && parentEl!=activePage)activePage.style.display='none';
	
	parentEl.style.display='block';	
	activePage=parentEl;
	
	var topPos = getTopPos(elementToShow);

	window.scrollTo(0,Math.max(0,topPos - elementToShow.offsetHeight));

	if(tocStyle==1)return false;

}

function initTocMaker()
{
	var toc_pane = document.getElementById('toc_pane');
	var toc_pane_inner = document.getElementById('toc_pane_inner');
	content_container = document.getElementById('content_container');
		
	var pageObjects = new Array();
	var divs = content_container.getElementsByTagName('DIV');
	for(var no=0;no<divs.length;no++){
		if(divs[no].className=='content_page'){
			pageObjects[pageObjects.length] = divs[no];
		}	
	}
	
	var topUl = document.createElement('UL');
	toc_pane_inner.appendChild(topUl);
	
	
	var parentObjects = new Array();
	var reg = new RegExp('h[1-' + treeDepth + ']','gi');
	var elemCounter = 1;
	var partCounter = new Array();
	for(var no=0;no<pageObjects.length;no++){
		if(no==0)activePage = pageObjects[no];
		if(no>0)pageObjects[no].style.display='none';
		var elem = pageObjects[no].getElementsByTagName('H1')[0];
		
		pageObjects[no].id = 'content_page' + no;
	
		
		while(elem){
			if(elem.tagName && elem.tagName.match(reg)){
				var tmpDepth = elem.tagName.replace(/[^0-9]/g,'');
				if(!partCounter[tmpDepth])partCounter[tmpDepth] = 0;
				
				
				partCounter[tmpDepth]++;
				
				if(insertNumbers){
					for(var num = (tmpDepth/1+1);num<=treeDepth;num++)partCounter[num] = 0;
					var tmpNum = '';
					for(var num=1;num<=tmpDepth/1;num++){
						
						switch(insertNumbers_as[num-1]){
							case 4:
								tmpNum =  tmpNum + roman(partCounter[num],1) + '.';	
								break;
							case 1:
								tmpNum =  tmpNum + partCounter[num] + '.';	
								break;
							case 2:
								tmpNum =  tmpNum + numberingLetters.substr([partCounter[num]-1],1) + '.';	
								break;	
							case 3:
								tmpNum =  tmpNum + numberingLetters.substr([partCounter[num]-1],1).toLowerCase() + '.';	
								break;						
						}
					}
					elem.innerHTML = tmpNum + ' ' + elem.innerHTML;
				}
				var li = document.createElement('LI');
				var aTag = document.createElement('A');
				aTag.innerHTML = elem.innerHTML;
				elem.id = 'content_element' + elemCounter;
				aTag.id = 'toc_link' + elemCounter;
				aTag.href = '#';
				if(tocStyle == 2 && window.initSlideDownMenu)aTag.href='javascript:navigateToElement(false,document.getElementById("toc_link' + elemCounter +'"));';
				aTag.onclick = navigateToElement;
				li.appendChild(aTag);
				if(!activeTocLink){
					activeTocLink = aTag;
					activeTocLink.style.cssText = cssActiveLink;
				}
				
				if(tmpDepth>1){
					if(!parentObjects[tmpDepth-1]){
						parentObjects[tmpDepth-1] = document.createElement('UL');
						lastElem.appendChild(parentObjects[tmpDepth-1]);					
					}
					parentObjects[tmpDepth-1].appendChild(li);
						
				}else{
					topUl.appendChild(li);	
						
				}	

				
				var anchor = document.createElement('A');	
				anchor.name = 'content_anchor' + elemCounter;
				
				if(elem.nextSibling){
					pageObjects[no].insertBefore(anchor,elem.nextSibling);
				}else{
					pageObjects[no].appendChild(anchor);
				}
								
				
				
				parentObjects[tmpDepth] = false;	
				lastElem = li;				
				elemCounter++;	
				
				
			}
			elem = elem.nextSibling;
		}
		
		if((no>0 || no<(pageObjects.length+1)) && includeNextPreviousLink){
			var linkDiv = document.createElement('DIV');
			linkDiv.className='pageLinks';
			pageObjects[no].appendChild(linkDiv);	
			if(no>0){
				var previousLink = document.createElement('A');
				previousLink.innerHTML = previous_txt;	
				previousLink.style.cssText = 'float:left';
				linkDiv.appendChild(previousLink);
				previousLink.href='#';
				previousLink.onclick = linkSwitchPage;
					
			}	
			
			if(no<pageObjects.length-1){
				var nextLink = document.createElement('A');
				nextLink.innerHTML = next_txt;	
				nextLink.style.cssText = 'float:right';
				linkDiv.appendChild(nextLink);
				nextLink.href='#';
				nextLink.onclick = linkSwitchPage;	
			}
			
			
		}
						
	}

	if(tocStyle == 1 && window.initTree){
		topUl.id = 'dhtmlgoodies_tree';
		initTree();
		if(treeExpandAll)expandAll();
	}
	if(tocStyle == 2 && window.initSlideDownMenu){
		toc_pane_inner.id = 'dhtmlgoodies_slidedown_menu';
		initSlideDownMenu();
	}
}