<?php 
class debug extends Controller{
    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index() {
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		debug_out(HOST,WEB_ROOT,BASIC_PATH,APPHOST,$config,$_COOKIE,$_SESSION,$_SERVER);
    }
}
