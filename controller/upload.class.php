<?php 
class upload extends Controller{
    /**
     * 构造函数
     */
    function __construct()    {
        parent::__construct();
    }

    /**
     * 多文件上传框
     */
    public function index() {
		$save_path=urldecode($this->in['save_path']);
		$save_path = base64_encode($save_path);
		$save_path = str_replace(array('+'),array("@@@"),$save_path);
		//flash请求会处理+符号，提前进行转义

		$this->assign("save_path",$save_path);
		$this->tpl = TEMPLATE . 'common/upload/';
		$this->display("index.php");
    }

    /**
     * html5拖拽上传
     */
	function html5Upload(){
        if ($this->in['save_path']=='') {
            echo json_encode(array('success'=>'0','info'=>'上传路径错误！'));
        }
        else{
            $save_path=iconv(
                $this->config['app_charset'],
                $this->config['system_charset'],
                urldecode($this->in['save_path'])
            );
            upload('xfile',$save_path);
        }
    }  

    /**
     * flash 多文件上传
     */
    public function swfUpload() {		
		$save_path = str_replace(array("@@@"),array('+'),$this->in['save_path']);
		$save_path = base64_decode($save_path);//此处中文路径为utf8编码
		//解决session 通信问题。
		$session_id=$this->in['session_id'];
		if(isset($session_id) !='' ) {
			session_id($session_id);
		}else {
			die('not has session ');
		}
		$save_path=iconv(
			$this->config['app_charset'],
			$this->config['system_charset'],
			$save_path
		);
        upload('Filedata',$save_path);
	}
}
