<?php 
class debug extends Controller{
	public $path_app;
	public $path_to;
    function __construct() {
		$this->path_app	= BASIC_PATH;
		$this->path_to	= dirname(BASIC_PATH).'/src';
        parent::__construct();
    }
    /**
     * 首页
     */
    public function index() {
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		debug_out(HOST,WEB_ROOT,BASIC_PATH,APPHOST,$config,$_COOKIE,$_SESSION,$_SERVER);
    }

	function less($basic){
		load_class('lessc.inc');
		$path		= $basic.'static/style/skin/';
		$app_theme	= array('default','metro','simple');
		$app_less	= array(
			'app_code_edit',
			'app_desktop',
			'app_editor',
			'app_explorer',
			'app_setting'
		);
		$num = count($app_theme)*count($app_less);$i=1;
		foreach($app_theme  as $theme){
			foreach($app_less as $app){
				$less		= new lessc();
				$path_in	= $path.$theme.'/'.$app.'.less';
				$path_out	= $path.$theme.'/'.$app.'.css';
				try {
					$cache	= $less->cachedCompile($path_in);
					$out	= str_replace(array("{\n",";\n",",\n",'  '),
										  array('{',';',',',' '),$cache["compiled"]);
					file_put_contents($path_out,$out);
					echo $path_out,'	...成功!('.$i++.'/'.$num.')','<br/>';
				}catch (exception $e) {
					echo "<p style='color:#f66'>fatal error: " . $e->getMessage(),'</p>';
				}
				flush();
			}
		}
	}

	function export(){	
		header("Content-type: text/html; charset=utf-8");
		ob_end_clean();			//在循环输出前，要关闭输出缓冲区   
		echo str_pad('',1024);  //浏览器在接受输出一定长度内容之前不会显示缓冲输出
		echo '<h1>开始导出！</h1><hr/><h3>开始复制文件</h3><hr/>';flush();
		$this->_fileInit();
		echo '复制成功！<br/><h3>开始编译less</h3><hr/>';flush();
		$this->less($this->path_to.'/');
		echo '编译成功！<br/><h3>删除开发相关文件</h3><hr/>';flush();
		$this->_remove();
		echo '删除成功！<br/><h3>开始替换模板种less相关内容</h3><hr/>';flush();
		$this->_fileReplace();
		echo '替换成功！<br/><h3>初始化配置文件</h3><hr/>';flush();
		$this->_initConfig();
		echo '更新成功！<br/><h1>导出处理完成！^_^</h1>';flush();
	}
	
	//----------------------------	
	function _fileInit(){		
		mk_dir($this->path_to);
		echo '<br/>新建文件夹成功，开始复制文件';flush();
		copy_dir($this->path_app, $this->path_to);
		echo '<br/>复制文件成功，开始清除调试相关信息<hr/>';flush();
		echo $this->path_app,'<br/>',$this->path_to;
	}
	// 删除
	function _remove(){		
		$file_list = array(
			$this->path_to.'/controller/debug.class.php',
			$this->path_to.'/lib/class/lessc.inc.class.php',
			$this->path_to.'/static/style/base.less',
			$this->path_to.'/static/js/less-1.4.2.min.js'
		);
		$path_list = array(
			$this->path_to.'/data/log',
			$this->path_to.'/data/thumb'
		);
		foreach($file_list as $val){
			del_file($val);
		}
		echo '<br/>1.文件删除完成：';flush();
		foreach($path_list as $val){
			del_dir($val);
		}
		echo '<br/>2.文件夹删除完成：';flush();
		$path		= $this->path_to.'/static/style/skin/';
		$app_theme	= array('default','metro','simple');
		$app_less	= array(
			'app_code_edit','app_desktop','app_editor','app_explorer','app_setting',
			'button','config','desktop','dialog','editor','explorer','filelist','right_menu','tree'
		);
		foreach($app_theme  as $theme){
			foreach($app_less as $app){
				$temp	= $path.$theme.'/'.$app.'.less';
				del_file($temp);
			}
		}
		echo '<br/>3.less文件删除完成<hr/>';flush();
	}

	// 删除less相关信息
	function _fileReplace(){
		$file_list = array(
			$this->path_to.'/template/desktop/index.php',
			$this->path_to.'/template/editor/edit.php',
			$this->path_to.'/template/editor/editor.php',
			$this->path_to.'/template/explorer/index.php',
			$this->path_to.'/template/setting/index.php'
		);
		foreach($file_list as $val){
			$content = file_get_contents($val);
			$content = str_replace("<?php if(STATIC_LESS == 'css'){ ?>",'',$content);
			$content = preg_replace('/<\?php }else{\/\/less_compare_online \?>.*<\?php } \?>/isU','',$content);
			file_put_contents($val,$content);
			echo '<br/>处理template文件：'.$val,'成功';flush();
		}
	}
	// 修改用户信息demo/demo  
	function _initConfig(){
		$config_file = $this->path_to.'/config/user/setting.php';
		$config['user_name']="demo";
		$config['user_password']= md5('demo');
		$config['list']="icon";
		$config['list_sort_field']="name";
		$config['list_sort_order']="up";
		$config['theme']="metro";
		$config['codetheme']="github";
		$config['wall']="1";
		$config['musictheme']="kuwo";
		$config['movietheme']="webplayer";
		foreach($config as $key=>$val){
			update_config($config_file,"config['".$key."']",$val);
		}
	}
}