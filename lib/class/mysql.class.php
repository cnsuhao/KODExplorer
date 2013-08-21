<?php
/*
 * MYSQL数据库连接类
*/
class mysql {

	public static $conn;	//MYSQL连接标识符
	public $rows;			//查询结果条数
	public $field_num;		//查询结果字段数目
	
	//分页方式查询配置
	public $each_num  		= 20;	//每页显示条数
	public $total_mum 		= 0;	//总条数
	public $total_page		= 0;	//总分页数
	public $current_page	= -1;	//当前页,未被初始化标记
	public $sub_page		= 10;	//分页页码数目
	
	
	public function __construct($db = array()) {
		$this->connect($db);
		return $this->conn;
	}

	/**
     * 初始化连接数据库,设置字符编码
	 * 
     * @param array   $rsql
     * @return null
     */
	public function connect($db = array()) {
		$this->conn = mysql_connect($db['db_host'].':'.$db['db_port'],$db['db_user'],$db['db_password'])
		or die($this->error(__LINE__,"mysql connect error!"));
		mysql_select_db($db['db_name'], $this->conn)
		or die($this->error(__LINE__,"mysql select db error!"));
		mysql_query("set names {$db['db_charset']}");
	}	
	
	/**
     * 执行SQL,所有sql执行中转中心，集中sql处理，tabl表前缀处理
	 * sql支持换行处理，转义处理。
     * @param string   $rsql
     * @return resource
     */
	public function execute($sql) {
		if (!$this->_is_sql_allow($sql)){
			die($this->error(__LINE__,$sql ."\n<br/>------mysql_query sql not allowed!---------"));
		}
		$sql = str_replace(array("\\n","\\r"),array(' ',' '),$sql);
		$sql = stripslashes($sql);
		$result = mysql_query($sql, $this->conn)
			or die($this->error(__LINE__,$sql ."\n<br/><b>"
			.mysql_error()."</b>\n<br/>------mysql_query error!---------"));
		
		//Log::write(date().'【'$sql.'】\n', 'db', 'debug');
		return $result;
	}
	/**
	 * 检测sql 语句
	 * @param string   $sql  sql语句
	 * @return bool
	 */
	public function _is_sql_allow($sql) {
		if (preg_match ("/.*(union|sleep|benchmark|load_file|outfile).*/isU",$sql,$out)) {
			return false;
		}else{
			return true;
		}		
    }
	
	/**
     * 执行SQL,直接返回数据，通常用于一条数据的查询，count等等。
	 * 
     * @param string   $rsql
     * @return array
     */
	public function get_one($sql) {
		$result = $this -> execute($sql);
		$out	= array();
		while ($row = mysql_fetch_array($result)) {
			$out = $row;
		}
		mysql_free_result($result);
		return $out[0];
	}

	/**
     * 执行SQL,并返回数组,获取一行
	 * 
     * @param string   $rsql
     * @return array
     */
	public function get_row($sql) {
		$result = $this -> execute($sql);		
		$getout = $this->_fetch_all($result);
		return $getout[0];
	}

	/**
     * 执行SQL,并返回数组
	 * 
     * @param string   $rsql
     * @return array
     */
	public function get_all($sql) {
		$result = $this -> execute($sql);		
		$this->rows = mysql_num_rows($result);
		$this->field_num = mysql_num_fields($result); 
		return $this->_fetch_all($result);
	}

	/**
     * 从结果集中取出所有记录做为二维数组$arr
	 * 
     * @param resource   $result
     * @return array
     */
	public function _fetch_all($result) {
		$arr = array();
		while ($row = mysql_fetch_array($result)) {
			$i = 0;
			$roe_this = array();
			foreach($row as $key=>$val){
				$i++;
				if ($i % 2 == 0){
					$roe_this[$key] = $val;	
				}				
			}
			$arr[] = $roe_this;
		}
		mysql_free_result($result);
		return $arr;
	}
	
	 /**
     * 查看一个字段的值是否存在
     *
     * @param string   $s_table
     * @param string   $s_field
     * @param string   $s_value
     * @return boolean
     */
    public function is_exist($table, $field, $value)
    {
    	$sql = "select exists(select {$field} from {$table} where {$field} = {$value}) as is_exist";
        if($this->get_one($sql) == 1){
            return true;
        }else{
            return false;
        }
    }

	/**
	 * 更新数据，返回前一次MySQL操作所影响的记录行数
	 * 
	 * @param string   $sql
	 * @return int
	 */
	public function update($sql){
		$result = $this -> execute($sql);
		return mysql_affected_rows($this->conn);
	}

	/**
	 * 插入数据到数据库，返回最后INSERT操作产生的自增字段ID
	 * 
	 * @param string   $sql
	 * @return int
	 */
	public function insert($sql){
		$sql = mysql_real_escape_string($sql);;
		$result = $this -> execute($sql);
		return mysql_insert_id($this->conn);
	}
	
	//--------------------------------------------------------
	/**
	 * 设置分页参数,
	 * 
	 * @param int	each_num 每页显示条数	
	 * @param int	sub_page 显示页码数
	 */
	public function set_page_config($each_num=20,$sub_page=10){
		$this->each_num = $each_num;
		$this->sub_page	= $sub_page;
	}
	/**
	 * 分页方式查询。分页页码的显示，需要先调用getPageList；
	 * @param string	$sql	查询的sql语句 ，select from之间的会被替换成count(1) 作为查询记录数。
	 * 							sql语句可以任意方式，可以order by等等。之后采用limit方式进行指定页数据获取。
	 * @param int		$page	第N页
	 * @return array
	 */
	public function get_page_list($sql,$page = 1){
		$this -> _get_page_info($sql,$page);
		$from	= ($this->current_page -1) * $this->each_num;	//条数起始标记
		$count	= $this->each_num;			//该页记录上限截止
		$sql	= 'select * from ('.$sql.")as result limit {$from},{$count}";//优化后的sql语句

		$result = $this->get_all($sql);
		return $result;
	}
	
	/**
	 * 获取分页数据，
	 * @param string $sql 查询的sql语句 ，select from之间的会被替换成count(1) 作为查询记录数。
	 
	 如果top查询，知道条数的情况下，则直接传入参数
	 (因为已经使用了count(*) as count 最后group by **　order by count 就会歧义。)

	 * @param int		$page	第N页
	 * @return null
	 */		
	public function _get_page_info($sql,$page){
		//$sql = preg_replace('/(Select)\s+.*\s+(from.*)/iU', '\1 count(1) \2', $sql,1);
		$sql = 'select count(1) from ('.$sql.') as count_table';
		$this->total_mum	= $this->get_one($sql);
		$this->total_page	= ceil($this->total_mum / $this->each_num);
		
		//页数修正
		$page = intval($page);
		if($page <= 1){
			$this->current_page = 1;
		}elseif($page > $this->total_page){
			$this->current_page = $this->total_page;
		}else{
			$this->current_page = $page;
		}
	}	
	
	/**
	 * 分页页码的显示，需要先调用getPageList；
	 * @param string	$url	url，用于返回
	 * @param int		$page	第N页
	 * @return array
	 */	
	public function get_page_info(){
		if($this->current_page == -1) return false;	

		$left	= $right = 0;
		$form	= $to = 0;
		if ($this->sub_page % 2 ==1){
			$left = $right = intval($this->sub_page / 2);
		}else{
			$left = intval($this->sub_page / 2);
			$right = $left - 1;
		}
		
		//总页数小于显示页数
		if ($this->total_page <= $this->sub_page){
			$from	= 1;
			$to		= $this->total_page;
		}else{
			if ( $this->current_page-$left<=1){	
				$from	= 1;
				$to		= $this->sub_page;
			}elseif ($this->current_page+$right >= $this->total_page){
				$from	= $this->total_page - $this->sub_page +1;
				$to		= $this->total_page;
			}else{
				$from	= $this->current_page - $left;
				$to		= $this->current_page + $right;			
			}
		}
		$list_arr = array();
		for($i=$from; $i<=$to; $i++){
			$list_arr[] = $i;
		}
		$page_info = array(
			'total_num'		=> $this->total_mum,
			'total_page'	=> $this->total_page,
			'current_page'	=> $this->current_page,
			'pre_page'		=> ($this->current_page - 1) < 1 ? 1:($this->current_page - 1),
			'next_page'		=> ($this->current_page + 1) > $this->total_page? $this->total_page:($this->current_page+1),			
			'list'			=> $list_arr
		);	
		return $page_info;
	}
	/**
	 * 关闭链接
	 * @return string 
	 */
	public function close() {
		if($this->conn){
			mysql_close($this->conn);
		}		
	}

	/*
	 * 提示错误
	 * 说明:对给出的错误提示内容给予ECHO
	 */
	public function error($line,$err_msg = "") {
		Log::write(__CLASS__.'->'.__FUNCTION__.'[L'.$line."] |".$err_msg, 'db', 'error');
		if ($err_msg == "") {
			echo "Errno:" . mysql_errno . "</br>";
			echo "Error:" . mysql_error . "</br>";
		} else {
			echo $err_msg;
		}
	}

	public function __destruct() {
		$this->close();
	}
}
?>
