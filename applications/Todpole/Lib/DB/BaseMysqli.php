<?php 
require_once __DIR__.'/MysqlInterface.php';
 
class Dadi_Mysqli implements Dadi_Mysql_Interface {
	
	protected $_keys; 
	protected $_stmt;
	protected static $_mysqli = null;
	protected static $_instance = null;
	protected static $_sql = null;
	protected static $_sqlNum = 0;

	private function __construct(){
		if(null === self::$_mysqli){
			$config = self::getConfig();
		    self::$_mysqli = new mysqli($config['host'],$config['username'],$config['password'],$config['database']);
		    if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}
		    self::$_mysqli->set_charset('utf8');
		}
	}
	
	/**
     * Singleton pattern instantiation
     *
     * @param String $configSection
     * @return BaseApp_Loader
     */
    public static function getInstance($config = null)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($config);
        }
		
        return self::$_instance;
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
     * query 方法
     *
     * @param mixed $sql
     * @return mixed
     */
     function query ($sql ,$bind = array()) {
     	self::$_sqlNum++;
     	self::$_sql = $sql;
     	$this->_stmt = self::$_mysqli->prepare($sql);
     	if(false === $this->_stmt || self::$_mysqli->errno){
     		echo $sql;
     		var_export('<br>Mysqli prepare error : ' . self::$_mysqli->error);
     		exit;
     	}
     	if($bind){
     		$refs = array();
	        foreach ($bind as $k => &$v) {
	            $refs[$k] = &$v;
	        }
	        array_unshift($bind, str_repeat('s', count($bind)));
            call_user_func_array(
                array($this->_stmt, 'bind_param'),
                $bind
            );
     	}
     	$bState = $this->_stmt->execute();
     	if(false === $bState){
     		echo $sql;
     		var_export('<br>Mysqli statement execute error : ' . $this->_stmt->error);
     		exit;
     	}
     	$meta = $this->_stmt->result_metadata();
     	if($this->_stmt->errno){
     		echo '<br>Mysqli statement metadata error : ' . $this->_stmt->error;
     		exit;
     	}
     	if(false !== $meta){
     		$this->_keys = array();
            foreach ($meta->fetch_fields() as $col) {
                $this->_keys[] = $col->name;
            }
     	}
    	return $this->_stmt;
    }
    
     /**
     * 返回 ResultSet 中的一条数据
     *
     * @param mixed $sql
     * @param array $bind
     * @return mixed
     */
    function fetch () {
    	$values = array_fill(0, count($this->_keys), null);
    	$refs = array();
        foreach ($values as $i => &$f) {
            $refs[$i] = &$f;
        }
        $this->_stmt->store_result();
        @call_user_func_array(
                array($this->_stmt, 'bind_result'),
                $values
            );
        $aResult = $this->_stmt->fetch();
		return $aResult ? $values : $aResult;
    }
    
    /**
     * 返回 ResultSet 中第一行第一列的数据
     *
     * @param mixed $sql
     * @param array $bind
     * @return mixed
     */
    function fetchOne ($sql) {
    	$this->_stmt = $this->query($sql);
        $values = $this->fetch();
		return $values[0];
    }
    
    /**
     * 返回与ID关联的 SQL 结果集
     *
     * @param mixed $sql
     * @return array
     */
    function fetchAssoc ($sql) {
    	$this->query($sql);
    	$return = array();

    	while ($row = $this->fetch()){
    		$tmp = array_values(array_slice($row, 0, 1));
    		$data = array_combine($this->_keys, $row);
	    	$return[$tmp[0]] = $data;
    	}
	    return $return ? $return : array();
    }


    /**
     * 返回所有的 SQL 结果集
     *
     * @param mixed $sql
     * @return array
     */
    function fetchAll ($sql) {
    	$this->query($sql);
    	$return = array();

    	while ($row = $this->fetch()){
    		$data = array_combine($this->_keys, $row);
	    	$return[] = $data;
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
    	$this->query($sql);
    	$row = $this->fetch();
    	return $row ? array_combine($this->_keys, $row) : array();
    }
    
    /**
     * 返回 ResultSet 中的第一列数据
     *
     * @param mixed $sql
     * @return array
     */
    function fetchCol ($sql) {
    	$this->query($sql);
    	$return = array();
    	while ($row = $this->fetch())
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
	            $vals[] = '?';
	        }
	        $sql = "INSERT INTO "
	            . $table
	            . ' (' . implode(', ', $cols) . ') '
	            . 'VALUES (' . implode(', ', $vals) . ')';
	        $this->query($sql ,array_values($bind));
	        return self::$_mysqli->insert_id;
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
	    return self::$_mysqli->insert_id;
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
   		foreach ($bind as $key => $val){
   			$val = '?';
   			$set[] = "`".$key."`" . " = " . $val;
   		}
   		$sql = "UPDATE "
            . $table
            . ' SET ' . implode(', ', $set)
            . ' ' . $queryFragment;
		$this->query($sql ,array_values($bind));
		return self::$_mysqli->affected_rows;
   }
   
     /**
      * 给字符串加引号
      * 
      * @param string $value
      * @return string
      */
   function quoteIdentifier ($value){
		$q = '"';
		return ($q . str_replace("$q", "$q$q", $value) . $q);
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
		self::$_mysqli->query($sql);
		return  self::$_mysqli->affected_rows;
  }
 /**
  * 根据条件删除数据
  * 
  * @param string $queryFragment 没有经过任何处理的sql语句
  * @return int 依据操作 select delete update insert而定 
  */
  function protypequery($sql){
	self::$_mysqli->query($sql);
	return self::$_mysqli->affected_rows;
  }
  
     /**
      * 返回上次sql影响记录数或返回结果集行数
      * 
      * @return int
      */
   static function rowCount () {
   		return self::$_mysqli->affected_rows;
   }
  
   
   /**
      * 返回最后插入的id(全局)
      * 
      * @return int
      */
   static function lastInsertId () {
   		return self::$_mysqli->insert_id;
   }
   
   
     	/**
      * 获得数据库信息
      * 
      * @return mixed
      */
   static function getDbInfo () {
   		$array = array(	'EXTENTION'=>'MYSQLI',
   						'LAST-SQL'=> self::$_sql,
   						'LAST-INSERT-ID'=> self::$_mysqli->insert_id,
   						'AFFECTED-ROWS'=> self::$_mysqli->affected_rows
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