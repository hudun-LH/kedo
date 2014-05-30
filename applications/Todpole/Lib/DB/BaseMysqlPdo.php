<?php 
require_once __DIR__.'/MysqlInterface.php';

class Dadi_MysqlPDO implements Dadi_Mysql_Interface {
	
	protected static $_dbh = null;
	protected static $_instance = null;
	protected static $_sth = null;
	protected static $_sqlNum = 0;
	
	private function __construct($config = null){
		if(null === self::$_dbh){
			$config = self::getConfig();
			$dsn = 'mysql:dbname='.$config['database'].';host='.$config['host'];
			$user = $config['username'];
			$password = $config['password'];
			$pconnect = $config['pconnect'] ? null : array(PDO::ATTR_PERSISTENT=>true);
			try {
			    self::$_dbh = new PDO($dsn, $user, $password ,$pconnect);
			    self::$_dbh->exec('SET CHARACTER SET utf8');
			} catch (PDOException $e) {
			    throw $e;
			}
		}
	}
	   
	/**
     * Singleton pattern instantiation
     *
     * @param String $configSection
     * @return BaseApp_Loader
     */
    public static function getInstance($config = null) {
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
     function query ($sql) {
     	self::$_sqlNum++;
    	$sth = self::$_dbh->prepare($sql);
    	self::$_sth = $sth;
    	if (self::$_dbh->errorCode() != '00000') {
			$error = self::$_dbh->errorInfo();
			throw new \Exception('<br>MYSQL(PDO) PREPARE ERROR:' . $error[2]);
		}
		$sth->execute();
		if ($sth->errorCode() != '00000') {
			$error = $sth->errorInfo();
			throw new \Exception($sql . '<br>MYSQL(PDO) ERROR:' . $error[2]);
		}
		return $sth;
    }
    

	 /**
  * 根据条件删除数据
  * 
  * @param string $queryFragment 没有经过任何处理的sql语句
  * @return int 依据操作 select delete update insert而定 
  */
  function protypequery($sql){
	return self::$this->query($sql);
	//return self::$_mysqli->affected_rows;
  }


    /**
     * 返回 ResultSet 中第一行第一列的数据
     *
     * @param mixed $sql
     * @param array $bind
     * @return mixed
     */
    function fetchOne ($sql) {
    	$sth = $this->query($sql);
		return $sth->fetchColumn();
    }
    
    /**
     * 返回所有的 SQL 结果集
     *
     * @param mixed $sql
     * @return array
     */
 	function fetchAssoc ($sql) {
    	$sth = $this->query($sql);
		$return = $sth->fetchAll(PDO::FETCH_ASSOC);
		$data = array();
		if($return){
			foreach ($return as $row){
				$tmp = array_values(array_slice($row, 0, 1));
				$data[$tmp[0]] = $row;
			}
		}
		return $data ? $data : array();
    }

     /**
     * 返回所有的 SQL 结果集
     *
     * @param mixed $sql
     * @return array
     */
 	function fetchAll ($sql) {
    	$sth = $this->query($sql);
		$return = $sth->fetchAll(PDO::FETCH_ASSOC);
		$data = array();
		if($return){
			foreach ($return as $row){
				$tmp = array_values(array_slice($row, 0, 1));
				$data[] = $row;
			}
		}
		return $data ? $data : array();
    }
    /**
     * 返回 SQL 结果集中的第一行数据
     *
     * @param mixed $sql
     * @return mixed
     */
    function fetchRow ($sql) {
    	$sth = $this->query($sql);
    	$return = $sth->fetch(PDO::FETCH_ASSOC);
		return $return ? $return : array();
    }
    
    /**
     * 返回 ResultSet 中的第一列数据
     *
     * @param mixed $sql
     * @return array
     */
    function fetchCol ($sql) {
    	$sth = $this->query($sql);
		$cols = array();
		while ($col = $sth->fetchColumn()){
			$cols[] = $col;
		}
		return $cols ? $cols : array();
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
	        $sql = 'INSERT INTO '
	            . $table
	            . ' (' . implode(', ', $cols) . ') '
	            . 'VALUES (' . implode(', ', $vals) . ')';
	        $this->query($sql);
	        return self::$_dbh->lastInsertId();
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
	    return self::$_dbh->lastInsertId();
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
   			$set[] = "`".$key."`" . ' = ' . $this->quoteIdentifier($val);
   		$sql = 'UPDATE '
            . $table
            . ' SET ' . implode(', ', $set)
            . ' ' .$queryFragment;
        $sth = $this->query($sql);
		return $sth->rowCount();
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
   		$sql = 'DELETE FROM ' . $table . ' ' . $queryFragment;
   		$sth = $this->query($sql);
		return $sth->rowCount();
   	}
   	
   	/**
      * 返回上次sql影响记录数或返回结果集行数
      * 
      * @return int
      */
   static function rowCount () {
   		return self::$_sth->rowCount();
   }
   	
   
   
   /**
      * 返回最后插入的id(全局)
      * 
      * @return int
      */
   static function lastInsertId () {
   		return self::$_dbh->lastInsertId();
   }
   
   
   	/**
      * 获得数据库信息
      * 
      * @return array
      */
   static function getDbInfo () {
   		$array = array(	'EXTENTION'=>'MYSQL(PDO)',
   						'LAST-SQL'=> self::$_sth->queryString,
   						'LAST-INSERT-ID'=> self::$_dbh->lastInsertId(),
   						'AFFECTED-ROWS'=> self::$_sth->rowCount()
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
