<?php 
require_once __DIR__.'/MysqlInterface.php';


class Dadi_Mysql implements Dadi_Mysql_Interface {
	

	protected static $_mysql = null;
	protected static $_instance = null;
	protected static $_sql = null;
	protected static $_sqlNum = 0;
	
	private function __construct($config = null){
		if(null === self::$_mysql){
			$config = self::getConfig();
			if($config['pconnect']){
				mysql_pconnect($config['host'],$config['username'],$config['password']);
			}else{
				mysql_connect($config['host'],$config['username'],$config['password']);
			}
			if (mysql_errno()) {
			    printf("Connect failed: %s\n", mysql_error());
			    exit;
			}
			mysql_select_db($config['database']); 
			mysql_query("set names utf8");
		}
	}
    
	/**
     * 获得数据库配置信息
     *
     * @return array
     */
    public static function getConfig(){
		$config = new Config;
	    return $config->db;
	}
	
	
	/**
     * Singleton pattern instantiation
     *
     * @param String $configSection
     * @return BaseApp_Loader
     */
    public function getInstance($config = null) {
        if (null === self::$_instance) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }
	
	 /**
     * query 方法
     *
     * @param mixed $sql
     * @return mixed
     */
     function query ($sql) {
     	self::$_sqlNum++;
     	self::$_sql = $sql;
    	$resource = mysql_query($sql) or die($sql."<br>MYSQL ERROR: ".mysql_error());
    	return $resource;
    }
    
    /**
     * 返回 ResultSet 中第一行第一列的数据
     *
     * @param mixed $sql
     * @param array $bind
     * @return mixed
     */
    function fetchOne ($sql) {
    	return  ($row = mysql_fetch_row($this->query($sql)))?$row[0]:null;
    }
    
    /**
     * 返回所有的 SQL 结果集
     *
     * @param mixed $sql
     * @return array
     */
    function fetchAll ($sql) {
    	$result = $this->query($sql);
    	$return = array();
    	while ($row = mysql_fetch_assoc($result)){
    		$tmp = array_values(array_slice($row, 0, 1));
    		$return[$tmp[0]] = $row;
    	}
	    return $return ? $return : array();
    }
    
    /**
     * 返回 SQL 结果集中的第一行数据
     *
     * @param mixed $sql
     * @return mixed
     */
    function fetchRow ($sql) {
    	$row = mysql_fetch_assoc($this->query($sql));
    	return $row ? $row : array();
    }
    
    /**
     * 返回 ResultSet 中的第一列数据
     *
     * @param mixed $sql
     * @return array
     */
    function fetchCol ($sql) {
    	$result = $this->query($sql);
    	$return = array();
    	while ($row = mysql_fetch_row($result))
	    	$return[] = $row[0];
	    return $return ? $return : array();
    }
    
    
	/**
     * 向数据表中插入一行数据,并返回插入的id值
     * $multi = true 向表内插入多行数据，并返回最后插入的id值
     *
     * @param mixed $table
     * @param array $bind
     * @param  bool $multi
     * @return int
     */
   function insert($table ,array $bind ,$multi = false) {
		if(empty($bind)) return 0;
		if (!$multi) {
	        $cols = array();
	        $vals = array();
	        foreach ($bind as $col => $val) {
	            $cols[] = "`".$col."`";
	            $vals[] = $this->quoteIdentifier($val);
	        }
	        $sql = "INSERT INTO "
	            . $table
	            . ' (' . implode(', ', $cols) . ') '
	            . 'VALUES (' . implode(', ', $vals) . ')';
	        $this->query($sql);
	        return mysql_insert_id();
		}
		$bind = array_values($bind);
		$sql = 'INSERT INTO '
	            . $table
	            . ' (' . implode(',' ,array_keys($bind[0])) . ') '
	            . 'VALUES'; 
	    foreach ($bind as &$row) {
	    	foreach ($row as &$val){
	    		$val = $this->quoteIdentifier($val);
	    	}
	    	$row = ' ('. implode(',' ,array_values($row)) . ') ';
	    }
	    $sql .= implode(',' ,$bind);
	    $this->query($sql);
	    return mysql_insert_id();
   }
   
   /**
     * 更新数据表中符合 $where 条件的记录，并返回影响记录数
     *
     * @param mixed $table
     * @param array $bind
     * @param string $where
     * @return int
     */
   function update($table, array $bind, $queryFragment = ''){
   		$set = array();
   		foreach ($bind as $key => $val)
   			$set[] = "`".$key."`" . " = " . $this->quoteIdentifier($val);
   		$sql = "UPDATE "
            . $table
            . ' SET ' . implode(', ', $set)
            . ' ' . $queryFragment;
		$this->query($sql);
		return mysql_affected_rows();
   }
   
     /**
      * 给字符串加引号
      * 
      * @param string $value
      * @return string
      */
   function quoteIdentifier ($value){
   		return "'" . addslashes($value) . "'";
   }
 	
   /**
      * 根据条件删除数据
      * 
      * @param string $table
      * @param string $queryFragment
      * @return string
      */
   	function delete ($table ,$queryFragment) {
   		$sql = "DELETE FROM " . $table . " " . $queryFragment;
   		$this->query($sql);
   		return mysql_affected_rows();
   	}
   	
   	
  	/**
      * 返回上次sql影响记录数或返回结果集行数
      * 
      * @return int
      */
   static function rowCount () {
   		return mysql_affected_rows();
   }
   
   /**
      * 返回最后插入的id(全局)
      * 
      * @return int
      */
   static function lastInsertId () {
   		return mysql_insert_id();
   }
   	
   	   	/**
      * 获得数据库信息
      * 
      * @return mixed
      */
   static function getDbInfo () {
   		$array = array(	'EXTENTION'=>'MYSQL',
   						'LAST-SQL'=> self::$_sql,
   						'LAST-INSERT-ID'=> mysql_insert_id(),
   						'AFFECTED-ROWS'=> mysql_affected_rows()
   		);
   		return $array;
   	}
   	
	/**
      * 获得sql执行次数
      * 
      * @return mixed
      */
 	static function getSqlNum () {
   		return self::$_sqlNum;
   	}
}   
?>