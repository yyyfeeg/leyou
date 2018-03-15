<?php
#==========================================================
# 	FileName: mysql.class.php
# 		Desc: 数据库类文件(包括一些常用数据库操作函数)
# 	  Author: 
# 	   Email: 
# 		Date: 
# LastChange: 
#===========================================================

class Mysql {

	var $conn = false; //数据库连接
	var $result = ''; //结果集

	/**
	 * 构造函数(初始化以下变量及数据库连接)
	 * @param string $MYSQL_HOST_DB [数据库地址]
	 * @param string $MYSQL_USER_DB [用户名]
	 * @param string $MYSQL_PASS_DB [密码]
	 * @param string $MYSQL_DB_DB   [数据库名称]
	 */
	function __construct($MYSQL_HOST_DB='',$MYSQL_USER_DB='',$MYSQL_PASS_DB='',$MYSQL_DB_DB='')
	{
		global $MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS,$MYSQL_DB;
		if(empty($MYSQL_HOST_DB) || empty($MYSQL_USER_DB)){
			$MYSQL_HOST_DB = $MYSQL_HOST;
			$MYSQL_USER_DB = $MYSQL_USER;
			$MYSQL_PASS_DB = $MYSQL_PASS;
			$MYSQL_DB_DB   = $MYSQL_DB;
		}
		$this->DB($MYSQL_HOST_DB,$MYSQL_USER_DB,$MYSQL_PASS_DB,$MYSQL_DB_DB);
	}

	/**
	 * 数据库连接函数
	 * @param string $MYSQL_HOST [数据库地址]
	 * @param string $MYSQL_USER [用户名]
	 * @param string $MYSQL_PASS [密码]
	 * @param string $MYSQL_DB   [数据库名称]
	 */
	function DB($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS,$MYSQL_DB)
	{	
		$this->conn = mysql_connect($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS) or die('Could not connect to database');
		if($this->conn){
			mysql_select_db($MYSQL_DB,$this->conn) or die('Could not select database');
		}
		mysql_query("set names 'utf8'",$this->conn);
		return $this->conn;
	}


	/**
	 * 执行一条mySQL语句
	 * @param  string  $sql  [查询字符串语句(不应以分号结束)]
	 * @param  boolean $tran [是否回滚]
	 * @return [type]        [返回一个结果集或true/false]
	 */
	function Query($sql,$tran = false)
	{
		$this->sql = $sql;
		if($tran){
			$this->result = mysql_query($this->sql,$this->conn) or $this->RollBack();
			return $this->result;
		}else{
			$this->result = mysql_query($this->sql,$this->conn);
			return $this->result;
		}
	}

	/**
	 * 回滚函数
	 */
	function RollBack()
	{
		$this->Query("ROLLBACK;");
		die("MySQL ROLLBACK;");
	}

	/**
	 * 取得结果集中行的数目
	 * @param  [type] $result [发送一条sql返回的结果集]
	 * @return [type]         [返回结果集中行的数目]
	 */
	function NumRows($result)
	{
		$this->result = $result;
		return @mysql_num_rows($this->result);
	}

	/**
	 * 取结果集中一行数据
	 * @param  [type] $result [发送一条sql返回的结果集]
	 * @return [type]         [返回一个关联数组]
	 */
	function getOne($result)
	{
		$row = $this->FetchArray($result);
		return $row;
	}

	/**
	 * 取结果集所有数据
	 * @param  [type] $result [发送一条sql返回的结果集]
	 * @return [type]         [返回一个二维数组]
	 */
	function getAll($result)
	{
		$tmp_arr = array();
		while ($row = $this->FetchArray($result)) {
			$tmp_arr[] = $row;
		}
		return $tmp_arr;
	}

	/**
	 * 处理sql返回的结果集
	 * @param [type] $result [返回一个关联数组]
	 */
	function FetchArray($result)
	{
		$this->result = $result;
		return @mysql_fetch_array($this->result,MYSQL_ASSOC);
	}

	/**
	 * 取得上一步 INSERT 操作产生的 ID
	 */
	function InsertID()
	{
		return @mysql_insert_id($this->conn);
	}

	/**
	 * 取得前一次 MySQL 操作所影响的记录行数
	 */
	function AffectedRows()
	{
		return @mysql_affected_rows($this->conn);
	}

	/**
	 * 关闭数据库连接
	 */
	function Close()
	{
		if($this->conn){
			@mysql_close($this->conn);
		}
	}
}
?>