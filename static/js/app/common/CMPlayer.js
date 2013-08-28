var CMPlayer = {
	// 创建播放器；动态获取皮肤以及对应大小尺寸
	_create:function(type){
		if(type == undefined) type = 'mp3';
		$.ajax({
			url:"?explorer/playerSkin&type="+type,
			dataType:'json',
			success:function(data){
				var htmlContent = 			
					'<object type="application/x-shockwave-flash" id="cmp_media" data="./static/js/cmp4/cmp.swf" width="100%" height="100%">'
					+	'<param name="movie" value="./static/js/cmp4/cmp.swf" />'
					+	'<param name="allowfullscreen" value="true" />'
					+	'<param name="allowscriptaccess" value="always" />'
					+	'<param name="flashvars" value="context_menu=2&auto_play=1&play_mode=1&skin=skins/'+data.skin+'.zip" />'
					+	'<param name="wmode" value="transparent" />'
					+'</object>';

				if (window.top.CMP){
					art.dialog.through({
						id:'music_player',
						title:data.title,
						width:data.width,							
						height:data.height,
						content:htmlContent,
						resize:true,
						padding:0,
						fixed:true
					});
				}else{
					$.dialog({
						id:'music_player',
						title:data.title,
						width:data.width,							
						height:data.height,
						content:htmlContent,
						resize:true,
						padding:0,
						fixed:true
					});
				}
			}
		});
	},
	// 文件数组创建播放器列表
	_makeList:function(fileList){
		var play_url,i,xml='';
		for (i = fileList.length - 1; i >= 0; i--) {
			play_url=web_path+encodeURIComponent(fileList[i]);
			if(!-[1,]){//ie
				play_url=web_path+encodeURI(fileList[i]);
			}
			play_url=play_url.replace(/%3A/g,':');
			play_url=play_url.replace(/%2F/g,'/');
			play_url=play_url.replace(/\+/g,' ');
			play_url=web_host+play_url;
			xml +='<list><m type="" src="'+play_url+'" label="'+fileList[i]+'"/></list>';
		};
		return xml;
	},
	//获取播放器
	_get:function(){
		if (window.top.CMP) {
			return window.top.CMP.get("cmp_media");
		}else{
			return CMP.get("cmp_media");
		}		
	},
	_insert:function(fileList){
		var new_list = CMPlayer._makeList(fileList);
		var cmpo = CMPlayer._get();
		if (cmpo) {
			cmpo.config('play_mode', 'normal');//写入配置,播放模式改为自动跳到next
			cmpo.list_xml(new_list,true);			
			if (fileList.length==1) {//若只有一首则加入到最后时，播放最后一首
				cmpo.sendEvent('view_play',cmpo.list().length);
			}else{
				cmpo.sendEvent('view_play');
			}							
		}
	},
	play:function(fileList,type){
		var cmpo = CMPlayer._get();
		if (!cmpo) {
			CMPlayer._create(type);
			setTimeout(function(){
				CMPlayer._insert(fileList);
			},1000);
		}else{
			CMPlayer._insert(fileList);
			$('.music_player').css('visibility','visible');
		}		
	}
};
