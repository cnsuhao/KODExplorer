var Main = {};
Main.Config = {
	TreeId:"folderList",        // 目录树对象
	AnimateTime:200,			// 动画时间设定
	treeAjaxURL:"?explorer/folderListEditor",
	filetype : {
		'music'	: ['mp3','wma','wav','mid','acc'],
		'movie'	: ['avi','flv','f4v','wmv','3gp','rmvb','mp4'],
		'image'	: ['jpg','jpeg','png','bmp','gif','ico'],
		'code'	: ['html','htm','js','css','less','scss','sass','py','php','rb','erl','lua','pl',
				   'c','cpp','m','h','java','jsp','cs','asp','sql','as','go','lsp',
				   'yml','json','tpl','xml',
				   'cmd','reg','bat','vbs','sh'],
		'text'	: ['txt','ini','inf','conf','oexe','md','htaccess','csv','log'],
		'bindary':['zip','rar','exe','dll','dat','chm','pdf','doc','docx',
					'xls','xlsx','ppt','pptx','class','psd','ttf','class'],
	}
};
Main.Global = {
	frameLeftWidth:200,			// 左边树目录宽度
	treeSpaceWide:15,			// 树目录层级相差宽度
	isIE:!-[1,],				// 是否ie
};

Main.Player = {
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

				art.dialog.through({
					title:data.title,
					width:data.width,							
					height:data.height,
					content:htmlContent,
					padding:0,
					fixed:true
				});
			}
		});		
	},
	// 文件数组创建播放器列表
	_makeList:function(fileList){
		var play_url,i,xml='';
		for (i = fileList.length - 1; i >= 0; i--) {
			play_url=web_path+encodeURIComponent(fileList[i]);
			if(Main.Global.isIE){
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
	_insert:function(fileList){
		var new_list = Main.Player._makeList(fileList);
		var cmpo = CMP.get("cmp_media");//检测播放器是否存在,存在则加入列表
		if (cmpo) {
			//cmpo.config('play_mode', 'normal');//写入配置,播放模式改为自动跳到next
			cmpo.list_xml(new_list,true);			
			if (fileList.length==1) {//若只有一首则加入到最后时，播放最后一首
				cmpo.sendEvent('view_play',cmpo.list().length);
			}							
		}
	},
	play:function(fileList,type){		
		var cmpo = CMP.get("cmp_media");
		if (!cmpo) {
			Main.Player._create(type);
			setTimeout(function(){
				Main.Player._insert(fileList);
			},500);
		}else{
			Main.Player._insert(fileList);
		}		
	}
};


//__________________________________________________________________________________//
Main.UI = (function() {
	var _bindFrameSizeEvent= function(){
		var isDraging 		= false;
		var mouseFirst		= 0;
		var leftwidthFirst 	= 0;

		var $left = $('.frame-left');
		var $drag = $('.frame-resize');
		var $right= $('.frame-right');

		$drag.unbind('mousedown').live('mousedown',function(e){
			if (e.which != 1) return true;
			__dragStart(e);
            //事件 在 window之外操作，继续保持。
			if(this.setCapture) this.setCapture();
			$(document).mousemove(function(e) {__dragMove(e);});
			$(document).one('mouseup',function(e) {				
				__dragEnd(e);
				if(this.releaseCapture) {this.releaseCapture();}
				stopPP(e);return false;
			});
		});
		var __dragStart = function(e){
			isDraging = true;
			mouseFirst = e.pageX;
			leftwidthFirst = $('.frame-left').width();
			$drag.addClass('active');
			$('.resizeMask').css('display','block');
		};
		var __dragMove = function(e){
			if (!isDraging) return true;

			var mouseOffset = e.pageX - mouseFirst;
			var offset = leftwidthFirst+mouseOffset;
			if (offset < 50) offset = 50;
			if (offset > $(document).width()-200) offset = $(document).width()-200;

			$left.css('width',offset);
			$drag.css('left',offset-5);
			$right.css('left',offset+1);
		};
		var __dragEnd = function(e){
			if (!isDraging) return false;
			isDraging = false;
			$drag.removeClass('active');
			Main.Global.frameLeftWidth = $('.frame-left').width();
			$('.resizeMask').css('display','none');
		};
	};
	return{	
		init:function(){
			_bindFrameSizeEvent();
			Main.UI.tree.init();
			$("html").unbind('click').live('click',function (e) {
				Main.RightMenu.hidden();
				if (Main.Global.isIE && Main.Global.isDragSelect) return;
			});
		},
		// setting 对话框
		setting:function(setting){
			if (setting == undefined) setting = '';				
			var url='?setting/setting='+setting;
			$.dialog.open(url,{
				id:'setting_mode',
				fixed:true,
				title:'系统设置',
				width:910,
				height:580
			});
		},

		// tips 
		tips:{
			loading:function(msg){
				$('.messageBox').stop(true,true);//停止正在运行的动画，从新开始动画
				if (msg == undefined) msg = '操作中...';
				$('.messageBox .content').html(msg+"&nbsp;&nbsp;  <img src='./static/images/loading.gif'/>");
				$('.messageBox')
				.css({'display':'block','left':-$('.messageBox').width()})
				.animate({opacity:0.6,left:0},300);
			},
			close:function(msg,time){
				var timeout = 0;		
				if (msg != undefined){
					timeout = 1000;
					$('.messageBox .content').html(msg);
				}
				if (time != undefined) timeout=time;
				$('.messageBox').delay(timeout).animate({opacity:0,left:'-=50'},500,0,function(){
					$(this).css('display','none');
				});	
			},
			tips:function(msg,icon){
				$('.messageBox').stop(true,true);//停止正在运行的动画，从新开始动画
				$('.messageBox .content').html(msg);
				$('.messageBox')
				.css({'display':'block','left':-$('.messageBox').width()})
				.animate({opacity:0.6,left:0},300,0)
				.delay(1000)
				.animate({opacity:0,left:'-=50'},500,0,function(){
					$(this).css('display','none');
				});
			}
		},
		// 目录树操作
		tree:{
			setting:{
				async: {
					enable: true,
					url:Main.Config.treeAjaxURL,
					autoParam:["name=name","father","this_path"],
				},
				edit: {
					enable: true,
					showRemoveBtn: false,
					showRenameBtn: false,
					drag:{
						isCopy:true,
						isMove:true,
						prev:false,
						inner:true,
						next:false
					}
				},
				view: {
					showLine: false,
					selectedMulti: false,
					dblClickExpand: false,
					dblClickExpand: function(treeId, treeNode) {
						return treeNode.level > 0;
					},// 双击 展开&折叠
					addDiyDom: function(treeId, treeNode) {
						var spaceWidth = Main.Global.treeSpaceWide;
						var switchObj = $("#" + treeNode.tId + "_switch"),
						icoObj = $("#" + treeNode.tId + "_ico");
						switchObj.remove();
						icoObj.before(switchObj);

						if (treeNode.level >= 1) {
							var spaceStr = "<span class='space' style='display: inline-block;width:" + (spaceWidth * treeNode.level)+ "px'></span>";
							switchObj.before(spaceStr);
						}
					}
				},
				callback: {//事件处理回调函数
					onClick:function(event, treeId,treeNode){
						
						if (treeNode.iconSkin =='doc') {
							var filePath = treeNode.father+treeNode.name;
							Main.Editor.open(filePath);
						}
					},
					//右键click
					onRightClick:function(event, treeId,treeNode){
						var zTree = $.fn.zTree.getZTreeObj("folderList");
						zTree.selectNode(treeNode);
						var treeNode = zTree.getSelectedNodes()[0];
						if (!treeNode.father && !treeNode.this_path) return;
						Main.RightMenu.show('.menuTree',event.clientX, event.clientY);						
					},
					onRename:function(event, treeId,treeNode){
						var zTree = $.fn.zTree.getZTreeObj("folderList");
						if (treeNode.father == undefined
							&& treeNode.this_path == undefined
							&& treeNode.children == undefined
						){//新建目录
							var path,parent = treeNode.getParentNode(),
								newNode = {name:"新建文件夹",pId:treeNode.id};
							if (!parent.father && !parent.this_path) return;							
							if (parent.this_path) path=parent.this_path;
							if (parent.father) path=parent.father+parent.name+'/';

							if (parent.children.length >0) {
								for (var i = parent.children.length - 2; i >= 0; i--) {
									if(treeNode.name == parent.children[i].name){
										Main.UI.tips.tips('名称重复!');
										zTree.removeNode(treeNode);
										return;
									}
								};
							}
							Main.PathOperate.newFolderTree(path,treeNode.name,treeNode);
						}else if (treeNode.father != undefined){
							Main.PathOperate.pathRnameTree(treeNode.father,treeNode.beforeName,treeNode.name,treeNode);
						}
					},
					beforeDrag: function(treeId, treeNodes){
						for (var i=0,l=treeNodes.length; i<l; i++) {
							if (treeNodes[i].drag === false) return false;
						}
						return true;
					},
					beforeDrop: function(treeId, treeNodes, targetNode, moveType){
						return targetNode ? targetNode.drop !== false : true;
					},
					onDrop:function(event, treeId, treeNodes, targetNode, moveType){
    					Main.UI.tree.refresh(targetNode);
					}
				}
			},
			init:function(){
				$.ajax({
					url:'?explorer/folderListEditor&root=1&this_path='+rootPath,
					success:function(data){
						var tree_json = eval('('+data+')');
						$.fn.zTree.init($("#folderList"), Main.UI.tree.setting,tree_json);
					}
				});
				$('.ztree .switch').unbind('mouseenter').live('mouseenter',function(){
					$(this).addClass('switch_hover');
				}).unbind('mouseleave').live('mouseleave',function(){
					$(this).removeClass('switch_hover');
				});
			},

			// 刷新结点
			refresh:function(treeNode){
				var zTree = $.fn.zTree.getZTreeObj("folderList");
				if (treeNode == undefined) treeNode=zTree.getSelectedNodes()[0];
				zTree.reAsyncChildNodes(treeNode, "refresh");
			},
			
			//右键操作
			add:function(){				
				var zTree = $.fn.zTree.getZTreeObj("folderList"),
					treeNode = zTree.getSelectedNodes()[0],
					newNode = {name:"新建文件夹",pId:treeNode.id};

				if (treeNode) treeNode = zTree.addNodes(treeNode,newNode);
				if (treeNode) zTree.editName(treeNode[0]);
			},
			edit:function(){
				var zTree = $.fn.zTree.getZTreeObj("folderList"),
					treeNode = zTree.getSelectedNodes()[0];
				if(!treeNode) return;
				zTree.editName(treeNode);
				treeNode.beforeName = treeNode.name;
			},
			copy:function(){
				var zTree = $.fn.zTree.getZTreeObj("folderList"),path,
					nodes = zTree.getSelectedNodes()[0];

				if (!nodes.father && !nodes.this_path) return;
				if (nodes.father) path = nodes.father+urlEncode(nodes.name);
				if (nodes.this_path) path = nodes.this_path;
				Main.PathOperate.pathCopyTree(path);
			},
			cute:function(){
				var zTree = $.fn.zTree.getZTreeObj("folderList"),path,
					nodes = zTree.getSelectedNodes()[0];

				if (!nodes.father && !nodes.this_path) return;
				if (nodes.father) path = nodes.father+urlEncode(nodes.name);
				if (nodes.this_path) path = nodes.this_path;
				Main.PathOperate.pathCuteTree(path);
			},
			past:function(){
				var zTree = $.fn.zTree.getZTreeObj("folderList"),path,
					nodes = zTree.getSelectedNodes()[0];

				if (!nodes.father && !nodes.this_path) return;
				if (nodes.father) path = nodes.father+urlEncode(nodes.name);
				if (nodes.this_path) path = nodes.this_path;
				Main.PathOperate.pathPastTree(path);
			},			
			delete:function(){
				var zTree = $.fn.zTree.getZTreeObj("folderList"),path,
					nodes = zTree.getSelectedNodes(),
					treeNode = nodes[0];
				if(!treeNode) return;
				if(!treeNode.father) {
					$.dialog({fixed: true,resize: false,icon:'warning',drag: true,title:'提示',content: '系统文件夹，不能删除',ok:true});
				}else{
					var path = treeNode.father + urlEncode(treeNode.name);
					Main.PathOperate.pathDeleteTree(path,treeNode,treeNode.name);
				}
			},
			info:function(){
				var zTree = $.fn.zTree.getZTreeObj("folderList"),path,
					nodes = zTree.getSelectedNodes()[0];

				if (!nodes.father && !nodes.this_path) return;
				if (nodes.father) path = nodes.father+urlEncode(nodes.name);
				if (nodes.this_path) path = nodes.this_path;
				Main.PathOperate.pathInfoTree(path);
			}
		}
	}
})();


Main.PathOperate = (function() {
	var path_not_allow	= ['/','\\',':','*','?','"','<','>','|'];//win文件名命不允许的字符
	//检测文件名是否合法，根据操作系统，规则不一样
	//win 不允许  / \ : * ? " < > |，lin* 不允许 ‘、’
	var _pathAllow = function(path){
		if (_strHasChar(path,path_not_allow)){
			$.dialog({
				title:false,
				time:1,
				icon:'warning',
				content:'命名不允许出现:<br/>/ \ : * ? " < > |'
			});
			return false;
		}
		else {
			return true;
		}
	};
	//字符串中检验是否出现某些字符，check=['-','=']
	var _strHasChar = function(str,check){
		var len=check.length;
		var reg="";
		for (var i=0; i<len; i++){
			if(str.indexOf(check[i])>0){
				return true;
			}
		}
		return false;
	};
	// 树目录新建文件夹
	var _newFolderTree = function(path,filename,treeNode){
		if (!_pathAllow(filename)) return;
		path=path+urlEncode(filename);
		$.ajax({
			url: '?explorer/mkdir&path='+path,
			success: function(data) {
		  		Main.UI.tips.tips(data);
		  		Main.UI.tree.refresh(treeNode.getParentNode());
			}
		});
	};
	// 树目录重命名文件夹
	var _pathRnameTree = function(path,from,to,treeNode){
		if (from == to) return;
		if (!_pathAllow(to)) return;
		rname_from = path+urlEncode(from);
		rname_to   = path+urlEncode(to);
		$.ajax({
			type: "POST", 
			url: '?explorer/pathRname',
			data: 'path='+rname_from+'&rname_to='+rname_to,
			beforeSend:function(){
				Main.UI.tips.loading();
			},
			success: function(data) {
				Main.UI.tips.close(data);
				Main.UI.tree.refresh(treeNode.getParentNode());
			}
		});
	};
	// 树目录删除文件夹
	var _pathDeleteTree = function(path,treeNode,filename){
		var msg = '确认要删除 "'+filename+'"文件夹吗?';
		var delete_list = _getSelectJsonTree('delete_list',path);
		$.dialog({
			fixed: true,//不跟随页面滚动
			resize: false,//调整大小
			icon:'question',
			drag: true,//拖曳
			title:'删除文件夹',
			content: msg,
			ok:function() {
				isDeleteDialog=0;
				$.ajax({
					url: '?explorer/deletePath',
					type:'POST',
					data:delete_list,
					beforeSend: function(){
						Main.UI.tips.loading('删除中...');
					},
					success: function(data) {
						Main.UI.tips.close(data);
						Main.UI.tree.refresh(treeNode.getParentNode());
					}
				});
			}
		});
	};
	//包装数据请求
	var _getSelectJsonTree = function(param_name,path){
		var select_list = param_name+'=[{"type":"folder","file":"'+path+'"}]';
		return select_list;
	};
	//树目录复制
	var _pathCopyTree = function(path){
		$.ajax({
			url:'?explorer/pathCopy',
			type:'POST',
			data:_getSelectJsonTree('copy_list',path),
			success:function(data){
				Main.Global.canPast = true;
				Main.UI.tips.tips(data);
			}
		});
	};
	//树目录剪切
	var _pathCuteTree = function(path){
		$.ajax({
			url:'?explorer/pathCute',
			type:'POST',
			data:_getSelectJsonTree('cute_list',path),
			success:function(data){
				Main.UI.tips.tips(data);
			}
		});
	};
	// 树目录粘贴
	var _pathPastTree = function(path){
		var url='?explorer/pathPast&path='+path;
		$.ajax({
			url:url,
			dataType:'json',
			beforeSend: function(){
				Main.UI.tips.loading("粘贴操作中...");
			},
			success:function(jsonback){
				Main.UI.tips.close(jsonback['msg']);
			}
		});
	};
	// 树目录文件夹属性
	var _pathInfoTree = function(path){
		$.ajax({
			url:'?explorer/pathInfo&type=folder&path='+urlEncode(path),
			beforeSend: function(){
				Main.UI.tips.loading('获取中!  ');
			},
			success:function(data){
				Main.UI.tips.close('获取成功！');
		  		$.dialog({
		  			padding:5,
		  			fixed: true,//不跟随页面滚动
		  			resize:false,
				    drag: true,//拖曳
		  			title:'文件夹属性',
				    content:data				   
				});
			}
		});
	};
	return{
		// 树目录新建文件夹
		newFolderTree:_newFolderTree,
		pathRnameTree:_pathRnameTree,
		pathDeleteTree:_pathDeleteTree,
		pathCopyTree:_pathCopyTree,
		pathCuteTree:_pathCuteTree,
		pathPastTree:_pathPastTree,
		pathInfoTree:_pathInfoTree,
	}
})();

//__________________________________________________________________________________//

Main.PathOpen = (function() {
	var selectObj 	= '';
	var selectNum 	= 0;
	var ie 			= !-[1,];
	var _hasSelect = function(){
		selectNum = Main.Global.fileListSelectNum;
		selectObj = Main.Global.fileListSelect
		if(selectNum == 1){
			return true;
		}
		return false;
	};

	//双击或者选中后enter 打开 执行事件
	var _open = function(hotKey){
		if(!_hasSelect()) return;
		thistype     = thistype.toLowerCase();
		var thisfile = Main.SetSelect.getObjName(selectObj);
		if (inArray(Main.Config.filetype['bindary'],thistype)) {//二进制文件，则下载
			_download();
			return;
		}
		if (inArray(Main.Config.filetype['image'],thistype)) {
			if (hotKey != 13) return; //双击操作打开
			if (list_type=='icon') {
				$(selectObj).find('.ico').dblclick();
			}else{
				$(selectObj).dblclick();
			}
			return;
		}
		if (inArray(Main.Config.filetype['code'],thistype) 
			|| inArray(Main.Config.filetype['text'],thistype) ) {
			_openText();//代码文件，编辑
			return;
		}
		if (inArray(Main.Config.filetype['music'],thistype) 
			|| inArray(Main.Config.filetype['movie'],thistype) ) {
			Main.PathOperate.media.insert(thistype);
			return;
		}
		var url="?explorer/fileOpen&type="+thistype+"&path="+web_path+urlEncode(thisfile);
		$.ajax({
			url:url,
			dataType:'json',
			success:function(data){
				//$.dialog({  //跨窗口调用
				art.dialog.through({
					title:data.title,
					width:data.width,
					fixed:true,
					height:data.height,
					content:data.content,
					padding:data.padding
				});	
			}
		});
	};
	var _download = function(){
		if(!_hasSelect()) return;
		var filename=this_path+urlEncode(Main.SetSelect.getObjName(selectObj));
		var url='?explorer/fileDownload&path='+filename;
		Main.UI.tips.tips("即将开始下载");
		art.dialog.open(url,{title:false,time:0.1,width:0,height:0,});	
	};
	//新的页面作为地址打开。鼠标右键，IE下打开
	var _openIE = function(){
		if(!_hasSelect()) return;
		var url=web_host+urlDecode(web_path)+encodeURIComponent(Main.SetSelect.getObjName(selectObj));
		if (Main.SetSelect.getObjType(selectObj) == "folder") {
			url += '/' ;
		}
		//art.dialog.open(url,{title:'浏览器',width:700,height:500});
		window.open(url);
	};

	var _openWindow = function(url,title) {
		var id_num=Math.floor(Math.random()*1000);
		var id='openWindow'+id_num;
		art.dialog.open(url,{id:id,title:title,width:'70%',height:'65%'});
	};

	//用text编辑
	var _openText = function(){
		if(!_hasSelect()) return;
		var ext=Main.SetSelect.getObjType(selectObj);
		var file=Main.SetSelect.getObjName(selectObj);
		var url='?editor/edit&type='	+ext+'&filename='+this_path+urlEncode(file);
		var title=file+'   编辑';
		
		var list=['jpg','gif','bmp','png','swf',"mp3",'wma','wmv','flv'];
		if(inArray(list,ext)) {
			Main.UI.tips.tips('不是文本文件!');
			return ;
		}

		var size=0;//文件大小检测
		if (json_data['filelist']){
			for (var i=0; i<json_data['filelist'].length; i++){
				if (json_data['filelist'][i]['name']==Main.SetSelect.getObjName(selectObj)){
					size=json_data['filelist'][i]['size'];
				}
			}
		}
		if (size>1000000){//大概超过1M的文件，进行打开提示
			$.dialog({
				title:'警告!',
				resize:false,
				icon: 'question',
				content:'文件大小超过1M,是否继续打开？',
				okVal:'继续打开',
				cancel:'取消',
				ok:function(){
					openWindow(url,title);
				},
				cancle:function(){
					return false;
				}
			});
		}
		else {
			_openWindow(url,title);		
		}
	};

	return{
		//如果为图片的话，双击打开被colorbox绑定，
		//为避免pathOpen中绑定无限循环,拆分成两个函数。其他方式则单独处理
		open:_open,
		openText:_openText,
		openIE:_openIE,
		download:_download,
	}
})();


//点击右键，获取元素menu的值，对应为右键菜单div的id值。实现通用。
//流程：给需要右键菜单的元素，加上menu属性，并赋值，把值作为右键菜单div的id值

Main.RightMenu = (function(){
	var selectTreeSelector = ".menuTree";
	var _init = function(){
		$('.context-menu-list').unbind("click").live("click",function(e){
			stopPP(e);
			return false;//屏蔽html点击隐藏
		});
		$(Main.Config.BodyContent).contextmenu(function(e){
			Main.RightMenu.hidden();
			return true;
		});//屏蔽工具栏右键
		_bindTree();
	}
	var _bindTree = function(){
		$.contextMenu({
			zIndex:9999,
			selector: selectTreeSelector, 
			callback: function(key, options) {_menuTree(key);},
			items: {
				"refresh":{name:"刷新数据(E)",icon:"refresh",accesskey: "e"},
				"new":{name:"新建文件夹(N)",icon:"folder-close-alt",accesskey: "n"},	
				"rename":{name:"重命名(R)",icon:"pencil",accesskey: "r"},
				"sep1":"--------",
				"copy":{name:"复制(C)",icon:"copy",accesskey: "c"},
				"cute":{name:"剪切(T)",icon:"cut",accesskey: "t"},
				//"past":{name:"粘贴(P)",icon:"past",accesskey: "p"},
				"sep2":"--------",
				"delete":{name:"删除(D)",icon:"trash",accesskey: "d"},
				"info":{name:"属性(I)",icon:"info",accesskey: "i"}
			}
		});
	}
	var _menuTree = function(action) {//右键操作
		switch(action){
			case 'refresh':Main.UI.tree.refresh();break;			
			case 'new':Main.UI.tree.add();break;
			case 'copy':Main.UI.tree.copy();break;
			case 'cute':Main.UI.tree.cute();break;
			//case 'past':Main.UI.tree.past();break;
			case 'rename':Main.UI.tree.edit();break;
			case 'delete':Main.UI.tree.delete();break;
			case 'info':Main.UI.tree.info();break;
			default:break;
		}
	};

	return{
		init:_init,
		show:function(select,left,top){
			Main.RightMenu.hidden();
			$(select).contextMenu({x:left, y:top});
		},
		isDisplay:function(){//检测是否有右键菜单
			var display = false;
			$('.context-menu-list').each(function(){
				if($(this).css("display") !="none"){
					display = true;
				}
			});
			return display;
		},
		hidden:function(){
			$visibleMenu = $('.context-menu-list').filter(':visible');
 			$visibleMenu.trigger('contextmenu:hide');
		}
	}
})();

Main.Editor = (function(){
	_getFileType = function(path){
		var str = path.split(".");
		return str[str.length - 1];
	};
	_open = function(path){
		var ext = _getFileType(path);
		if (inArray(Main.Config.filetype['code'],ext) 
			|| inArray(Main.Config.filetype['text'],ext) ) {
			FrameCall.doFunction('editor','Main.Editor.add','"'+path+'"');
			return;
		}else{
			$.dialog({
				id:'unkonwFile',
				fixed: true,
				resize: false,
				icon:'warning',
				time:1,
				title:false,
				content: '不是文本文件！'
			});
		}
	};
	return{
		open:_open
	}
})();

$(document).ready(function() {
	Main.UI.init();
	Main.RightMenu.init();
});
