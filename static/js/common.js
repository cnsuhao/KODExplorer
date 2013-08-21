
//是否在数组中。
var inArray = function(arr,value) {
    for (var i=0,l = arr.length ; i <l ; i++) {
        if (arr[i] === value) {
            return true;
        }
    }
    return false;
}
var stopPP = function(e){//防止事件冒泡
	e = e || window.event;
	if (e.stopPropagation) {
		e.stopPropagation();
	} else {
		e.cancelBubble = true;
	}
	if (e.preventDefault) {
		e.preventDefault();
	} else {
		e.returnValue = false;
	}
}

//URL 编码,utf-8实现，同return encodeURIComponent(string);
//查看文章：http://www.ruanyifeng.com/blog/2010/02/url_encoding.html
var  urlEncode = function(string) {
	if(string == undefined || string == '') return '';
	var str = string.replace(/\r\n/g,"\n");
	var utftext = "";  
	for (var n = 0; n < str.length; n++) {  
		var c = str.charCodeAt(n);  
		if (c < 128) {
			utftext += String.fromCharCode(c);
		}
		else if((c > 127) && (c < 2048)) {
			utftext += String.fromCharCode((c >> 6) | 192);
			utftext += String.fromCharCode((c & 63) | 128);
		}
		else {
			utftext += String.fromCharCode((c >> 12) | 224);
			utftext += String.fromCharCode(((c >> 6) & 63) | 128);
			utftext += String.fromCharCode((c & 63) | 128);
		}
	}	
	utftext=utftext.replace('+','%2B');
	//utftext=utftext.replace(' ','%20');
	return escape(utftext);
}
var urlDecode = function(string) {
	if(string == undefined || string == '') return '';
	var utftext=unescape(string);
	var string = "";
	var i = 0;
	var c = c1 = c2 = 0;  
	while ( i < utftext.length ) {  
		c = utftext.charCodeAt(i);  
		if (c < 128) {
			string += String.fromCharCode(c);
			i++;
		}
		else if((c > 191) && (c < 224)) {
			c2 = utftext.charCodeAt(i+1);
			string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
			i += 2;
		}
		else {
			c2 = utftext.charCodeAt(i+1);
			c3 = utftext.charCodeAt(i+2);
			string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
			i += 3;
		}  
	}
	string=string.replace('%2B','+');
	return string;
}