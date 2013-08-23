/*
* iframe之间函数调用
*
* main frame中每个frame需要name和id，以便兼容多浏览器
* 如果需要提供给其他frame调用，则需要在body中加入
* <input id="FrameCall" type='hidden' action='' value='' onclick='FrameCall.api()'/>
* 调用例子：Frame.doFunction('main','goUrl','"'+url+'"');该frame调用id为main的兄弟frame的goUrl方法，参数为后面的
* 参数为字符串时需要加引号，否则传过去会被理解成一个未定义变量
*/
var FrameCall = (function(){
	var idName 		= "FrameCall";
	var idNameAll	= "#"+idName;
	var ie = !-[1,];//是否ie
	return{
		apiOpen:function(){
			var html = '<input id="FrameCall" type="hidden" action="1" value="1" onclick="FrameCall.api()" />';
			$(html).prependTo('body');
		},
		//其他窗口调用该窗口函数，调用另一个frame的方法
		api:function(){
			var action = $(idNameAll).attr('action');
			var value=$(idNameAll).attr('value');
			var fun=action+'('+value+');';//拼装执行语句，字符串转换到代码
			eval(fun);
		},

		//该窗口调用父窗口的子窗口api,调用iframe框架的js函数.封装控制器。
		doFunction:function(iframe,action,value){
			var obj = window.parent.frames[iframe].document;
			obj=obj.getElementById(idName);		
			$(obj).attr("action",action);
			$(obj).attr("value",value);
			obj.click();
		},

		//该窗口调用父窗口的子窗口api,调用iframe框架的js函数.封装控制器。
		doTopFunction:function(iframe,action,value){
			var obj = window.top.frames[iframe].document;
			obj=obj.getElementById(idName);		
			$(obj).attr("action",action);
			$(obj).attr("value",value);
			obj.click();
		},

		//该窗口调用父窗口的api
		fatherFunction:function(action,value){
			if (ie){//获取兄弟frame的dom树
				var obj=window.parent.document;//IE
			}else{
				var obj=window.parent.document;//chrome safari firefox...
			}
			obj=obj.getElementById(idName);	
			$(obj).attr("action",action);
			$(obj).attr("value",value);
			obj.click();	
		},
		//___自定义通用方法，可在页面定义更多提供给接口使用的api。
		goUrl:function(url){
			window.location.href=url;
		},
		goRefresh:function(){
			window.location.reload(); 
		}
	}
})();

$(document).ready(function() {
	FrameCall.apiOpen();
});
