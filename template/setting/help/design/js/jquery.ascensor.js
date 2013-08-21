//**********************************************************************************************
//
//		ASCENSOR 1.0
//		CREDIT: LEO GALLEY
//		KIRKAS.CH
//
//			INDEX
// 				1. CREATION OF SEVERAL VARIABLES AND ADJUSTMENT OF LARGE CONTAINER
// 				2. DEFINITION AND PLACEMENT OF CHILDREN OF THE CONTAINER, + SCROLL OF MANAGEMENT
// 				3. POSITIONING OF CONTENTS (UPSTAIRS)
// 				4. CREATING LINKS
// 				5. CREATING NAVIGATION
//				6. CREATING PREV/NEXT BUTTON
// 				7. RESIZE MANAGEMENT
// 				8. KEY MANAGEMENT
// 				9. FRIENDLY CODE MODE MANAGEMENT
// 				10. TARGET MANAGEMENT
//				
//**********************************************************************************************

(function($) {
	$.fn.ascensor = function(params){
	var params = $.extend({
		AscensorName:'maison',
		
		WindowsFocus:true,
		WindowsOn:0,
		NavigationDirection:'xy',
		Direction: 'y',
		
		Navig:true,
		Link:true,
		PrevNext:true,
		KeyArrow:false,
		keySwitch:false,
		CSSstyles:true,
		ReturnURL:true,
		ReturnCode:false,
		
		ChocolateAscensor:false,
		AscensorMap: '4|3',
		ContentCoord: '1|1 & 2|2 & 1|2 & 3|4 & 1|3 & 3|1 & 2|3 & 2|1'
	},params);	




//**********************************************************************************************
//
//	  1. CREATION OF SEVERAL VARIABLES AND ADJUSTMENT OF LARGE CONTAINER
//	  
//**********************************************************************************************


	var trueURL=params.WindowsOn;
	
	var type = params.AscensorName.length 
	var url = location.href;
	var value = url.substring(url.lastIndexOf('/') + 1);
	var valueLenght = value.length;
	var OriginLength = params.AscensorName.length;
	var windowsNumber = value.split(params.AscensorName);
	var WindowsFinal = parseInt(windowsNumber[1]);
	
	if(value!=0 && valueLenght==OriginLength+1){
		params.WindowsOn = WindowsFinal;
	}
	
	var PageNumber=0;
	var windowsHeight = $(window).height();
	var windowsWidth = $(window).width();
	
	var node=$(this);
	
	var resultatPage='<p>every page has the class ".'+params.AscensorName+'"';
	var resultatLink='';
	var resultatNav='<p>every navigation button has the class ".'+params.AscensorName+'NavigationButton"</br></br>';
	
	
	var MapName=params.AscensorMap;
	var MapeCoupe = MapName.split('|');
	
	var MapWidth = parseInt(MapeCoupe[0]);
	var MapHeight = parseInt(MapeCoupe[1]);
		

	$(node).css('width',MapWidth*windowsWidth);
	$(node).css('height',MapHeight*windowsHeight);



//**********************************************************************************************
//
//	  2. DEFINITION AND PLACEMENT OF CHILDREN OF THE CONTAINER, + SCROLL OF MANAGEMENT
//	  
//**********************************************************************************************

	$(node).children('div').each(function(index) {
		PageNumber+=1;
	
		$(this).addClass(params.AscensorName);
		$(this).attr('id', params.AscensorName+index);
		$(this).height(windowsHeight);
		$(this).width(windowsWidth);
		
		resultatPage+='<p>the id of the content '+index+' is "#'+params.AscensorName+index+'" </p>';
        	
		if($(this).children().height()>windowsHeight){
			$(this).css({'overflow-y':'scroll'});
		}else{
			$(this).css({'overflow-y':'hidden'});
		}
		
		if($(this).children().width()>windowsWidth){
			$(this).css({'overflow-x':'scroll'});
		}else{
			$(this).css({'overflow-x':'hidden'});
		}
	});



//**********************************************************************************************
//
//	  3. POSITIONING OF CONTENTS (UPSTAIRS)
//	  
//**********************************************************************************************   

	if(params.Direction=='x'&&params.ChocolateAscensor==false){
		$(node).children('div').each(function(index) {
			var PageAscensor = $(this).attr('id');
			var PageCoupe = PageAscensor.split(params.AscensorName);
			var PageFinal = parseInt(PageCoupe[1]);
			$(this).css('position','absolute');
			$(this).css('left',windowsWidth*PageFinal);
		});
	}else if(params.Direction=='y'&&params.ChocolateAscensor==false){
		$(node).children('div').each(function(index) {
			var PageAscensor = $(this).attr('id');
			var PageCoupe = PageAscensor.split(params.AscensorName);
			var PageFinal = parseInt(PageCoupe[1]);
			$(this).css('position','absolute');
			$(this).css('top',windowsHeight*PageFinal);
		});
	}else if(params.Direction=='y'&&params.ChocolateAscensor==true||params.Direction=='x'&&params.ChocolateAscensor==true){
		$(node).children('div').each(function(index) {
			var CoordName = params.ContentCoord;
			var CoordCoupe = CoordName.split(' & ');
			var CoordoneUne = CoordCoupe[index];
			var Coord = CoordoneUne.split('|');
			var CoordX = parseInt(Coord[1])-1;
			var CoordY = parseInt(Coord[0])-1;
	       	$(this).css('margin-top', function(index) {return (CoordY*windowsHeight);});
			$(this).css('margin-left', function(index) {return (CoordX*windowsWidth);});	
			$(this).css('position','absolute');
		});
	}



//**********************************************************************************************
//
//	  4. CREATING LINKS
//	  
//**********************************************************************************************

	if(params.Link==true){
		$(node).children('div').each(function(index) {
			var Link = index+1;	
			$(this).append('<a href="#'+params.AscensorName+Link+'" rel="ConcentLink" class="'+params.AscensorName+'Link'+index+'">liens'+index+'</a>');
			

			resultatLink+='&lt;a href="#'+params.AscensorName+Link+'" rel="ConcentLink" class="'+params.AscensorName+'Link'+index+'"&gt;liens'+index+'&lt;/a&gt;</br>';
		})
	}
	
	
	
	
	
	$('a').click(function(){
		if($(this).attr('rel')=='ConcentLink'){
			jQuery('html,body').queue([]).stop();
			var IdName=$(this).attr('class');
			var NameCoupe = IdName.split(params.AscensorName+'Link');
			var NumberFinal = parseInt(NameCoupe[1])+ 1;

				
			if(NumberFinal>PageNumber-1){
				NumberFinal=0;
			}
			params.WindowsOn = NumberFinal;
			
			$.scrollTo(($('.'+params.AscensorName+':eq('+NumberFinal+')')), 1000, {axis: params.NavigationDirection,onAfter:function(){
				if(params.ReturnURL==true){

					if(trueURL==NumberFinal){
						window.location.href='#/'+params.AscensorName+'0';
					}else{
						window.location.href='#/'+params.AscensorName+NumberFinal;
					}
				}
			}});
			
			if(params.CSSstyles==true){
				$('.'+params.AscensorName+'NavigationButton').css('background','#999');
				$('.'+params.AscensorName+'NavigationButton:eq('+params.WindowsOn+')').css('background','#444');
			}
			
			
			
			return false;
		}
		
	});
	
	
	
	
	
	
	
	








//**********************************************************************************************
//
// 		5. CREATING NAVIGATION
//	  
//**********************************************************************************************

	if(params.Navig==true){
		$(node).append('<dl id="'+params.AscensorName+'Navigation"></dl>');
		
		resultatNav+=('&lt;dl id="'+params.AscensorName+'Navigation"&gt;</br>');
		
		$(node).children('div').each(function(index) {
			$('#'+params.AscensorName+'Navigation').append('<dt class="'+params.AscensorName+'NavigationButton" ><a href="#" title="Link for Content"  id="'+params.AscensorName+'NavigationButton'+index+'"></a></dt>').find('dt:eq('+params.WindowsOn+')').css('background','#444');
			resultatNav+='&lt;dt class="'+params.AscensorName+'NavigationButton" &gt;&lt;a href="#" title="Link for Content"  id="'+params.AscensorName+'NavigationButton'+index+'"&gt;&lt;/a&gt;&lt;/dt&gt;</br>';
		})
		
		resultatNav+=('&lt;/dl&gt;');
	}
	
	if(params.CSSstyles==true){
		
			
		$('#'+params.AscensorName+'Navigation dt').css({'z-index': '20000','position': 'fixed','width':'40px','height':'25px','background':'#999'});
		$('#'+params.AscensorName+'Navigation dt:eq('+params.WindowsOn+')').css({'background': '#444'});
		
		if(params.ChocolateAscensor==true){
	
			$('#'+params.AscensorName+'Navigation').find('dt').each(function(index) {
				var CoordNameNav = params.ContentCoord;
				var CoordCoupeNav = CoordNameNav.split(' & ');
				var CoordoneUneNav = CoordCoupeNav[index];
				var CoordNav = CoordoneUneNav.split('|');
				var CoordNavX = parseInt(CoordNav[1])-1;
				var CoordNavY = parseInt(CoordNav[0])-1;
				
				
				var positionY= MapHeight*60;
				
				var positionX= MapWidth*65;
				
				
				$(this).css('bottom', function() {return (-1*CoordNavY*50+positionY); });
				$(this).css('right', function() {return (-1*CoordNavX*70+positionX);});		

				
			});
		}else{
		$('#'+params.AscensorName+'Navigation').find('dt').each(function(index) {
			$(this).css('bottom', function() {return (index*30); });		
			
		});
		
		}
		
		
		
		
			
	}
	
	

	

	

	
	$('.'+params.AscensorName+'NavigationButton').click(function(){
	

		jQuery('html,body').queue([]).stop(); 
		
		var EtageAscensor = $(this).find('a').attr('id');
		var EtageCoupe = EtageAscensor.split(''+params.AscensorName+'NavigationButton');
		var AscensorFinal = parseInt(EtageCoupe[1]);
    	params.WindowsOn = AscensorFinal;
    	
    	$.scrollTo(($('.'+params.AscensorName+':eq('+AscensorFinal+')')), 1000, {axis: params.NavigationDirection,onAfter:function(){
    		if(params.ReturnURL==true){
    			if(trueURL==AscensorFinal){
    				window.location.href='#/'+params.AscensorName+'0';
    			}else{
    				window.location.href='#/'+params.AscensorName+AscensorFinal;
    			}
    		
    			
    		}
    	}});
    	
    	if(params.CSSstyles==true){
			$('.'+params.AscensorName+'NavigationButton').css('background','#999');
			$(this).css('background','#444');
		}
    });



//**********************************************************************************************
//
//	  6.CREATING PREV/NEXT BUTTON
//	  
//**********************************************************************************************

	if(params.PrevNext==true){
		$(node).append('<a id="'+params.AscensorName+'Prev"  href="?">上一页</a><a id="'+params.AscensorName+'Next" href="?">下一页</a>');
	}
	    		
	    		
	    $('#'+params.AscensorName+'Prev').click(function(){
	    jQuery('html,body').queue([]).stop();
			var windowsON=params.WindowsOn;
			var PrevWindows=windowsON-1;


			if(PrevWindows<0){
				PrevWindows=PageNumber-1;
			}
	
			$.scrollTo(($('#'+params.AscensorName+PrevWindows)),1000, {axis:params.NavigationDirection,onAfter:function(){
				if(params.ReturnURL==true){
				
				if(trueURL==PrevWindows){
					window.location.href='#/'+params.AscensorName+'0';
				}else{
					window.location.href='#'+params.AscensorName+PrevWindows;
				}
				
				
				}
			}});
			
			
		params.WindowsOn=PrevWindows;
				
		if(params.CSSstyles==true){
					$('.'+params.AscensorName+'NavigationButton').css('background','#999');
					$('.'+params.AscensorName+'NavigationButton:eq('+params.WindowsOn+')').css('background','#444');
		}
				
				return false;
	    });
	    
	    if(params.CSSstyles==true){
	    	$('#'+params.AscensorName+'Prev').css({'position':'fixed', 'z-index':'20000', 'top':'20px', 'left':'30px', 'background':'#ccc','color':'333', 'padding':'10px'});
	    }
	    		
	   
		
		if(params.CSSstyles==true){
			 $('#'+params.AscensorName+'Next').css({'position':'fixed', 'z-index':'20000', 'top':'20px', 'left':'90px', 'background':'#ccc','color':'333', 'padding':'10px'});
		}
	
	$('#'+params.AscensorName+'Next').click(function(){
	
		jQuery('html,body').queue([]).stop();
			var windowsON=params.WindowsOn;
			var NextWindows=windowsON+1;
			if(NextWindows>PageNumber-1){
				NextWindows=0;
			}
				
			$.scrollTo(($('#'+params.AscensorName+NextWindows)),1000, {axis:params.NavigationDirection,onAfter:function(){
				if(params.ReturnURL==true){
				
					if(trueURL==NextWindows){
						window.location.href='#/'+params.AscensorName+'0';
					}else{
						window.location.href='#'+params.AscensorName+NextWindows;
					}
				
				}
			}});		
			
			params.WindowsOn=NextWindows;
			
			if(params.CSSstyles==true){
				$('.'+params.AscensorName+'NavigationButton').css('background','#999');
				$('.'+params.AscensorName+'NavigationButton:eq('+params.WindowsOn+')').css('background','#444');
			}
	
			return false;
		})



//**********************************************************************************************
//
// 		7. RESIZE MANAGEMENT
//	  
//**********************************************************************************************  

	$(window).resize(function() {
		windowsHeight = $(window).height();
		windowsWidth = $(window).width();
		
		if(params.Direction=='x'&&params.ChocolateAscensor==false){
			$(node).children('div').each(function(index) {
				var PageAscensor = $(this).attr('id');
				var PageCoupe = PageAscensor.split(params.AscensorName);
				var PageFinal = parseInt(PageCoupe[1]);
				$(this).css('left',windowsWidth*PageFinal);
			});
		}else if(params.Direction=='y'&&params.ChocolateAscensor==false){
			$(node).children('div').each(function(index) {
				var PageAscensor = $(this).attr('id');
				var PageCoupe = PageAscensor.split(params.AscensorName);
				var PageFinal = parseInt(PageCoupe[1]);
				$(this).css('top',windowsHeight*PageFinal);
			});
		}else if(params.Direction=='y'&&params.ChocolateAscensor==true||params.Direction=='x'&&params.ChocolateAscensor==true){
			$(node).children('div').each(function(index) {		
				var CoordName = params.ContentCoord;
				var CoordCoupe = CoordName.split(' & ');
				var CoordoneUne = CoordCoupe[index];
				var Coord = CoordoneUne.split('|');
				var CoordX = parseInt(Coord[1])-1;
				var CoordY = parseInt(Coord[0])-1;
				$(this).css('margin-top', function(index) {return (CoordY*windowsHeight); });		
				$(this).css('margin-left', function(index) {return (CoordX*windowsWidth);  });	
			});
		}
		
		
		$(node).children('div').each(function(index) {
			if($(this).children().height()>windowsHeight){
				$(this).css({'overflow-y':'scroll'});
			}else{
				$(this).css({'overflow-y':'hidden'});
			}
			
			if($(this).children().width()>windowsWidth){
				$(this).css({'overflow-x':'scroll'});
			}else{
				$(this).css({'overflow-x':'hidden'});
			}
		});
		
		
		$('.'+params.AscensorName).height(windowsHeight);
		$('.'+params.AscensorName).width(windowsWidth);
		
		$.scrollTo(($('#'+params.AscensorName+params.WindowsOn)),0, {axis:params.NavigationDirection});    
	});



//**********************************************************************************************
//
// 		8. KEY MANAGEMENT
//	  
//**********************************************************************************************   

	var temps=1000;

	if(params.KeyArrow==false || params.keySwitch==false){      
		function checkKey(e){
			switch (e.keyCode) {
            	case 40:
                	return false;
                case 38:
                	return false;
                case 37:
                	return false;
                case 39:
                	return false;
			}      
		}
        if ($.browser.mozilla) {
        	$(document).keypress (checkKey);
        }else{
        	$(document).keydown (checkKey);
        }
	}else if(params.KeyArrow==true && params.keySwitch==true){
		function checkKey(e){
			switch (e.keyCode) {
	    		case 40:
	    	    	ArrowFunction(0, -1);
	    	    	return false;
	    	    	break;
	    	    	
	    	    case 38:
	   	     		ArrowFunction(0, +1);
	   	     		return false;
	   	     		break;
	   		    case 37:
	   	     		ArrowFunction(1, +1);
	   	     		return false;
	   	     		break;
	        	case 39:
	        		ArrowFunction(1, -1);
	        		return false;
	      			break;
			}      
		}
	
		if ($.browser.mozilla) {
			$(document).keypress (checkKey);
		}else{
			$(document).keydown (checkKey);
		}
	
	};
	

		
	function ArrowFunction (coordPart, action){ 
			
		jQuery('html,body').queue([]).stop();
		
		temps-=50;
		
		var ThisContainer = params.WindowsOn;
		var PrevContainer = params.WindowsOn-1;
		var NextContainer = params.WindowsOn+1;
		
		if (PrevContainer<0){
			PrevContainer=PageNumber-1;
		}
		
		if(NextContainer>PageNumber-1){
			NextContainer=0;
		}
		
		var CoordName = params.ContentCoord;
		var CoordCoupe = CoordName.split(' & ');
		
		var CoordoneThis = CoordCoupe[ThisContainer];
		var CoordonePrev = CoordCoupe[PrevContainer];
		var CoordoneNext = CoordCoupe[NextContainer];
		
		var CoordThis = CoordoneThis.split('|');
		var CoordThisX = parseInt(CoordThis[coordPart]);
		
		var CoordPrev = CoordonePrev.split('|');
		var CoordPrevX = parseInt(CoordPrev[coordPart]);
		
		var CoordNext = CoordoneNext.split('|');
		var CoordNextX = parseInt(CoordNext[coordPart]);
		
		if(CoordThisX==CoordNextX+action){
			params.WindowsOn=NextContainer;
			$.scrollTo(($('#'+params.AscensorName+NextContainer)),temps, {axis:params.NavigationDirection,onAfter:function(){
				temps=1000;
				if(params.ReturnURL==true){
				
					if(trueURL==NextContainer){
						window.location.href='#/'+params.AscensorName+'0';
					}else{
						window.location.href='#/'+params.AscensorName+NextContainer;
					}
				
				}
			}});	
			
			if(params.CSSstyles==true){
				$('.'+params.AscensorName+'NavigationButton').css('background','#999');
				$('.'+params.AscensorName+'NavigationButton:eq('+params.WindowsOn+')').css('background','#444');
			}	
		}else if(CoordThisX==CoordPrevX+action){
			params.WindowsOn=PrevContainer;
			$.scrollTo(($('#'+params.AscensorName+PrevContainer)),1000, {axis:params.NavigationDirection,onAfter:function(){
				if(params.ReturnURL==true){
				
					if(trueURL==NextContainer){
						window.location.href='#/'+params.AscensorName+'0';
					}else{
						window.location.href='#/'+params.AscensorName+PrevContainer;
					}
				
				}
			}});	
			
			if(params.CSSstyles==true){
				$('.'+params.AscensorName+'NavigationButton').css('background','#999');
				$('.'+params.AscensorName+'NavigationButton:eq('+params.WindowsOn+')').css('background','#444');
			}	
		}else{
			$.scrollTo(($('#'+params.AscensorName+params.WindowsOn)),1000, {axis:params.NavigationDirection});
		}
		
	}
	
	
	
	


//**********************************************************************************************
//
// 		9. FRIENDLY CODE MODE MANAGEMENT
//	  
//**********************************************************************************************   
	
	if(params.ReturnCode==true){
		$('body').append('<div><div id="result"><h1>Code Page</h1><p id="pageCode">'+resultatPage+'</p><br/><h1>Code Link</h1><p id="LinkCode">'+resultatLink+'</p></br><h1>Code Navigation</h1><p id="NavCode" ><p>'+resultatNav+'<p id="close">close this windows</p></div></div>');
   		
		$('#result').css({'position':'fixed','top':'20%','left':'30%','background':'#fff','padding':'20px','font-size':'12px'});
		$('#close').css({'padding-left':'600px','color':'#f00','margin-top':'20px'});
		$('#result h1').css({'font-size':'18px'});
		$('#result p').css({'margin-left':'30px'});
   		
		$('#close').click(function(){
			$('#result').hide();
		});
	}



//**********************************************************************************************
//
// 	10. TARGET MANAGEMENT
//	  
//**********************************************************************************************   
    
    if(params.WindowsFocus==true){
       	$.scrollTo(($('#'+params.AscensorName+params.WindowsOn)),0, {axis:params.NavigationDirection});
	}
	
	
	
	
	
         
};
})(jQuery);
