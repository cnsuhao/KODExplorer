<?php 
class node extends Controller{
    /**
     * 构造函数
     */
    function __construct()    {
        parent::__construct();
        $this->tpl = TEMPLATE.'node/';
    }

    function index(){
        if($this->in['path']!=''){
            $dir = $_GET['path'];
        }else if($_SESSION['this_path']!=''){
            $dir = $_SESSION['this_path'];
        }else{
            $dir = HOME;//首次进入系统,不带参数
        }
        $this->assign('dir',$dir);
        $this->display('index.php');
    }

    function pathInfo(){
        $path=urldecode($this->in['path']);
        $type=$this->in['type'];
        $path_this_name=get_path_this($path);
        $path_father_name=get_path_father($path);
        if ($type=="folder"){
            $path_info = path_info($path,"Y年m月d日 H:i:s");
            include($this->tpl.'fileinfo/pathinfo.php');
        }elseif($type=="file"){
            $file_info = file_info($path,"Y年m月d日 H:i:s");
            include($this->tpl.'fileinfo/fileinfo.php');
        }
    }
    function pathInfoMuti(){
        $info_list = json_decode($this->in['info_list'],true);
        foreach ($info_list as $val) {          
            $val['file'] = urldecode($info_list[$i]['file']);
        }
        $pathinfo = path_info_muti($info_list);
        include($this->tpl.'fileinfo/pathinfo_muti.php');
    }      
    function pathRname(){
        $app_path=iconv(
            $this->config['app_charset'],
            $this->config['system_charset'],
            urldecode($this->in['path'])
        );
        if (!is_writable($app_path)) {
            echo "没有权限";return;
        }
        $rname_to=iconv(
            $this->config['app_charset'],
            $this->config['system_charset'],
            urldecode($this->in['rname_to'])
        );
        if (file_exists($rname_to)) {
            echo "出错啦，该名称已存在！";return;
        }

        @rename($app_path,$rname_to);
        echo "重命名成功！";
    }
    function pathList(){
        $path=$this->in['path'];
        $folderlist=path_list(urldecode($path));
        echo json_encode($folderlist);
    }
    function folderListEditor(){
        if ($this->in['this_path'] !='') {
            $path=urldecode($this->in['this_path']).'/';
        }else if($this->in['father'] !=''){
            $path=urldecode($this->in['father'].$this->in['name']).'/';
        }
        $folderlist=tree_list($path,true);
        if ($folderlist == NULL) {
            echo "[]";return;
        }
        $listFolder=array();
        $listFile=array();      
        foreach($folderlist as $val){
            if ($val['fileType'] != '') {
                array_push($listFile,array(
                    'name'=>$val['name'],
                    'father'=>$path,
                    'iconSkin'=>$val['fileType'],
                    'isParent'=>false
                    )
                );                  
            }else{
                array_push($listFolder,array(
                    'name'=>$val['name'],
                    'father'=>$path,
                    'isParent'=>$val['hasChildren']
                    )
                );          
            }
        }
        
        function sort_by_key($a, $b){
            if ($a['name'] == $b['name']) return 0;
            return ($a['name'] > $b['name']) ? 1 : -1;
        }
        usort($listFolder, "sort_by_key");
        usort($listFile, "sort_by_key");
        $list = array_merge($listFolder,$listFile);
        echo json_encode($list);
    }
    function folderList(){        
        if ($this->in['father']=="" && $this->in['this_path']==''){
            $lib_array =  array(
                array('name'=>"桌面",'iconSkin'=>"doc",'this_path'=> USER.'desktop/','isParent'=>true,'drop'=>false,'drag'=>false),
                array('name'=>"我的文档",'iconSkin'=>"doc",'this_path'=> USER.'doc/','isParent'=>true,'drop'=>false,'drag'=>false),
                array('name'=>"我的照片",'iconSkin'=>"pic",'this_path'=> USER.'image/','isParent'=>true,'drop'=>false,'drag'=>false)
            );
            $tree_root = array(               array('name'=>"根目录",'iconSkin'=>"lib",'open'=>true,'children'=>$lib_array,'drop'=>false,'drag'=>false));
            echo json_encode($tree_root);
            return;
        }else{
            if ($this->in['this_path'] !='') {
                $path=urldecode($this->in['this_path']).'/';
            }else if($this->in['father'] !=''){
                $path=urldecode($this->in['father'].$this->in['name']).'/';
            }           
        }
        $folderlist=tree_list($path,false);
        if ($folderlist == NULL) {
            echo "[]";return;
        }
        $list=array();
        foreach($folderlist as $val){
            array_push($list,array(
                'name'=>$val['name'],
                'father'=>$path,
                'isParent'=>$val['hasChildren']
                )
            );
        }
        echo json_encode($list);
    }

    function deletePath(){
        $delete_list = json_decode($this->in['delete_list'],true);
        $success = 0;
        $error   = 0;
        foreach ($delete_list as $val) {
            $path_full = iconv(
                $this->config['app_charset'],
                $this->config['system_charset'],
                urldecode($val['file'])
            );
            if ($val['type'] == 'folder') {
                if(del_dir($path_full)) $success ++;
                else $error++;
            }else{
                if(del_file($path_full)) $success++;
                else $error++;
            }
        }
        if (count($delete_list) == 1) {
            if ($success == 1) echo "删除成功!";
            else echo "没有权限，删除失败!";
        }else{
            echo '删除操作完成，'.$success.'个成功，'.$error.'个失败';
        }       
    }
    function mkfile(){
        $newfile=iconv(
            $this->config['app_charset'],
            $this->config['system_charset'],
            urldecode($this->in['path'])
        );
        if(@touch($newfile)){
            echo "新建成功！";
        }else{
            echo "新建失败,请检查目录权限！";
        }
    }
    function mkdir(){
        $newfolder=iconv(
            $this->config['app_charset'],
            $this->config['system_charset'],
            urldecode($this->in['path'])
        );
        if(@mkdir($newfolder,0777)){
            echo "新建成功！";
        }else{
            echo "新建失败,请检查目录权限！";
        }
    }
    function pathCopy(){
        $copy_list = json_decode($this->in['copy_list'],true);
        $list_num = count($copy_list);
        for ($i=0; $i < $list_num; $i++) { 
            $copy_list[$i]['file'] =urldecode($copy_list[$i]['file']);
        }
        $_SESSION['path_copy']= json_encode($copy_list);            
        $_SESSION['path_copy_type']='copy';     
        echo "【复制】—— 覆盖剪贴板成功!";
    }
    function pathCute(){
        $cute_list = json_decode($this->in['cute_list'],true);
        $list_num = count($cute_list);
        for ($i=0; $i < $list_num; $i++) { 
            $cute_list[$i]['file'] = urldecode($cute_list[$i]['file']);
        }
        $_SESSION['path_copy']= json_encode($cute_list);            
        $_SESSION['path_copy_type']='cute';     
        echo "【剪切】—— 覆盖剪贴板成功!";
    }
    function pathCuteDrag(){
        $clipboard = json_decode($this->in['cute_list'],true);
        $path_past=iconv(
            $this->config['app_charset'],
            $this->config['system_charset'],
            urldecode($this->in['path'])
        );
        if (!is_writable($path_past)) {
            echo "没有权限";return;          
        }
        foreach ($clipboard as $val) {
            $path_copy = iconv($this->config['app_charset'],$this->config['system_charset'],$val['file']);
            $filename  = get_path_this($path_copy);
            if ($clipboard[$i]['type'] == 'folder') {                   
                @rename($path_copy,$path_past.$filename.'/');
                
            }else{
                @rename($path_copy,$path_past.$filename);
                
            }
        }
        echo '移动成功！';
    }      
    function pathHasCopy(){
        $clipboard = json_decode($_SESSION['path_copy'],true);
        if (count($clipboard) == 0){
            echo "0";
        }else{
            echo "1";
        }       
    }  
    function pathCopySee(){
        $clipboard = json_decode($_SESSION['path_copy'],true);
        if (count($clipboard) == 0){
            echo "空!";
        }else{          
            $msg='<div style="height:200px;overflow:auto;padding:10px;width:400px"><b>剪切板状态:'.($_SESSION['path_copy_type']=='cute'?'剪切':'复制').'</b><br/>';
            $len = 40;
            foreach ($clipboard as $val) {
                $path=(strlen($val['file'])<$len)?$val['file']:'...'.substr($val['file'],-$len);
                $msg.= '<br/>'.$val['type'].' :  '.$path;
            }
            echo $msg."</div>";
        }
    }
    function pathPast(){
        $clipboard = json_decode($_SESSION['path_copy'],true);
        $copy_type = $_SESSION['path_copy_type'];       
        $path_past=iconv(
            $this->config['app_charset'],
            $this->config['system_charset'],
            urldecode($this->in['path'])
        );
        if (!is_writable($path_past)) {
            $echojson['msg']="没有写权限!";
            echo json_encode($echojson);return;
        }

        $echojson=array('select'=>array(),'msg'=>'');
        $list_num = count($clipboard);
        if ($list_num == 0) {
            $echojson['msg']="剪贴板为空!";
            echo json_encode($echojson);
        }
        for ($i=0; $i < $list_num; $i++) {
            $path_copy = $clipboard[$i]['file'];            
            $path_copy = iconv($this->config['app_charset'],$this->config['system_charset'],$path_copy);
            $filename  = get_path_this($path_copy);
            $filename_out  = iconv($this->config['app_charset'],
                $this->config['system_charset'],$filename);

            if (!file_exists($path_copy) && !is_dir($path_copy)){
                $echojson['msg'] .=$path_copy."<li>{$filename_out}来源不存在!</li>";
                continue;
            }
            if ($clipboard[$i]['type'] == 'folder'){
                if ($path_copy == substr($path_past,0,strlen($path_copy))){
                    $echojson['msg'] .="<li>{$filename_out}目标文件夹是源文件夹的子文件夹!</li>";
                    continue;
                }
            }       
            if ($copy_type == 'copy') {
                if ($clipboard[$i]['type'] == 'folder') {
                    copy_dir($path_copy,$path_past.$filename);
                }else{
                    copy($path_copy,$path_past.$filename);
                }
                
            }else{
                if ($cute_list[$i]['type'] == 'folder') {
                    rename($path_copy,$path_past.$filename.'/');
                }else{
                    rename($path_copy,$path_past.$filename);
                }                
            }            
            $echojson['select'][] = array('type'=>$clipboard[$i]['type'],'name'=>$filename);
        }
        if ($copy_type == 'copy') {
            $echojson['has_clipboard'] = true;
            $echojson['msg']='<b>粘贴操作完成</b>'.$echojson['msg'];
        }else{
            $_SESSION['path_copy'] = json_encode(array());
            $_SESSION['path_copy_type'] = '';
            $echojson['has_clipboard'] = false;
            $echojson['msg']='<b>粘贴操作完成</b>(源文件被删除,剪贴板清空)'.$echojson['msg'];
        }
        echo json_encode($echojson);
    }
}
