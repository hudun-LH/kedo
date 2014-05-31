<?php 
require_once __DIR__.'/BaseMysql.php';
require_once __DIR__.'/BaseMysqli.php';
require_once __DIR__.'/BaseMysqlPdo.php';
require_once __DIR__.'/../../Config.php';

class Table{
	protected static $_tablePrefix = '';
    protected  $_table;//表名
    protected  $_primaryKey;//主键
    protected  $_columns = array();//表信息
    protected  $_fields = array();//字段
    protected  $_notNullFields = array();//必填字段
    public	   $lastInsertId = 0;//最后插入的id(实例)
    public	   $updateCount = 0;//最后更新影响的行数(实例)
    public 	   $errorFields = array();//错误字段
    protected  $_bindData = null;//插入的数据数组
    protected  static $_dbInstance = null;
	
    public function __construct($table ,$primaryKey = 'id'){
      	$this->_table = self::$_tablePrefix . $table;
      	$this->_primaryKey = $primaryKey;
      	$this->getDbInstance();
    }

	 /**
     * Singleton pattern instantiation
     *
     * @param String $configSection
     * @return BaseApp_Loader
     */
    public static function getDbInstance() {
        if (null === self::$_dbInstance) {
			$adapter = self::getAdapter();
			switch($adapter){
			case 'mysql' : 
				require_once ('BaseMysql.php');
				self::$_dbInstance = Dadi_Mysql::getInstance();
				break;
			case 'mysqli' : 
				require_once ('BaseMysqli.php');
				self::$_dbInstance = Dadi_Mysqli::getInstance();
				break;
			case 'mysqlpdo' : 
				require_once ('BaseMysqlPdo.php');
				self::$_dbInstance =  Dadi_MysqlPDO::getInstance();
				break;
			default : 
				require_once ('BaseMysqli.php');
				self::$_dbInstance = Dadi_Mysqli::getInstance();
			}
		}
        return self::$_dbInstance;
    }
    
    /**
     * 获得驱动
     * 
     * @param void
     * @return void
     */
    public static  function getAdapter(){
    	$config = new Config();
		return $config->db['adapter'];
    }

	public static function setTablePrefix($prefix){
		$this->_tablePrefix = $prefix;
	}
  
   
    /**
     * 执行sql语句
     * 
     * @param mixed $sql
     * @return void
     */
    public function query($sql){
    	return self::$_dbInstance->fetchAll($sql);
    }
    
    
	 /**
     * 通过主键获得关联数组
     *
     * 
     *   @example ：user->get(10);//或user->get('*',10);  等价于  fetchRow('select * from d_users where id = 10')****fetchRow
     *   @example ：user->get('user_nick_name',10);// 等价于  fetchOne('select user_nick_name from d_users where id = 10')****fetchOne
     *   @example ：user->get('id,uname,user_qq' ,10);// 等价于 fetchRow('select `id`,`uname`,`user_qq` from d_users where id =10')
     *	 @example ：user->get('id,uname,user_qq' ,array(10,11,23,25));
     *	 @example ：user->get(array('id','uname,user_qq') ,'10,11,23,25');
     *	 @example ：user->get('id,uname,user_qq' ,array(10,11,'23,25'));
     *   @example ：//等价于 fetchAll('select `id`,`uname`,`user_qq` from d_users where id in (10,11,23,25)')****fetchAll
     * 
     * 注意：
     *   返回数组有fetchAll，fetchCol，fetchRow，fetchOne等形式，自动检测;
     *   多 = array('XX') 或 array('XX','XX'..) 或'XX,XX,XX'
     *   1 = 'XX'
     *   get(多,多)------fetchAll
     *   get(多,1)-------fetchRow
     *   get(1,多)-------fetchCol
     *   get(1,1)--------fetchOne
     *   get(多)---------fetchAll
     *   get(1)----------fetchRow
     * @param mixed
     * @return mixed
     */
    public function get(){
    	$params = func_get_args();
    	$ids = array_pop($params);
    	if (!$ids) return array();
    	$cols = &$params[0];
    	$multiCols = is_array($cols) || !$cols || $cols == '*' || strpos($cols ,',');
    	$multiRows = is_array($ids) || strpos($ids ,',');
    	$sql = "SELECT ";
		$sql .= ($multiCols && !$cols) ? "*" : implode(',' ,(array)$cols);
    	$sql .= " FROM " . $this->_table . " WHERE " . $this->_primaryKey;
    	//$fetchModel = $multiRows ? (($multiCols) ? 'fetchAssoc' : 'fetchCol') : (($multiCols) ? 'fetchRow' : 'fetchOne');
		$fetchModel = $multiRows ? (($multiCols) ? 'fetchAssoc' : 'fetchCol') : (($multiCols) ? 'fetchRow' : 'fetchOne');
    	$sql .= " IN (" . implode(',' ,(array)$ids) . ") ";
    	return self::$_dbInstance->$fetchModel($sql);
    }
    
    
    /**
     * 通过条件fetchAll数据，提供分页排序功能
     * 
     * 	 
     *   @example ：user->find('id,user_qq,user_email','where user_nick_name like '%dadi%');
     *   @example ：usre->find(array('id','user_qq','user_email'))
     * 
     * @param mixed $where
     * @param mixed $order
     * @param mixed $by
     * @param mixed $start
     * @param mixed $limit
     * @param mixed $col
     * @return void
     */
    public function find ($cols = '*' ,$queryFragment = null ,$order = null ,$sort = 'DESC', $start = 0, $limit = 0) {
    	$multiCols = is_array($cols) || !$cols || $cols == '*' || strpos($cols ,',');
    	$multiRows = $limit == 1 ? false : true;
		$sql = "SELECT ";
		$sql .= empty($cols) ? '*' : implode(',' ,(array)$cols);
		$sql .= " FROM " . $this->_table . " ";
		if ($queryFragment) $sql .=  $queryFragment;
		if ($order) $sql .= " ORDER BY " . $order . " " . $sort;
		if ($limit) $sql .= " LIMIT " . $start . " , " . $limit;
		$fetchModel = $multiRows ? (($multiCols) ? 'fetchAssoc' : 'fetchCol') : (($multiCols) ? 'fetchRow' : 'fetchOne');
		return self::$_dbInstance->$fetchModel($sql);
   }


	/**
     * 插入一行数据并返回相应的id值
     * $multi = true时插入多行数据，并返回最后插入的id值
     * 返回0 参数$bind 为空数组
     * 返回false 说明$bind中没有包含所有必填字段，
     * 			或者$bind中必填字段中有空的值(包括空白字符串)。
     * 			这时$table->errorFields数组记载了这些有问题的字段
     * 
     * 
     *   @example ：$data = array('user_nick_name'=>'liang','user_qq'=>'123456789');
     *   @example ：$id = $user->insert($data);//$id值为本次插入的id值
     *   @example ：if(false === $id){//注意要用 ===
     *   @example ：		var_dump($user->errorFields);
     *   @example ：}
     *   @example ：
	 *   @example ：	打印出	array (
	 *   @example ：				1 => 'user_join_date',
	 *   @example ：				2 => 'user_password_md5',
	 *   @example ：				3 => 'user_email',
	 *   @example ：				4 => 'user_level',
	 *   @example ：			)

     * @param array $bind
     * @return int
     */
	public function insert(array $bind ,$multi = false){
		if(!$bind) return 0;
		if(false === $multi){
			$bind = $this->prepareDataForInsert($bind);
			if(false === $bind) return false;
			$this->_bindData = $bind;
		}
		$this->lastInsertId = self::$_dbInstance->insert($this->_table ,$bind ,$multi);
		return $this->lastInsertId;
	}
	
	
	/**
     * 更新数据并返回相应的更新的行数
     *  
     *  例如：
     *   $data = array('user_nick_name'=>'liang','user_qq'=>'123456789');
     *   $affected_rows = user->update($data ,'id>10');//$affected_rows值为本次更新数据行数
     *
     * @param array $bind
     * @return int
     */
	public function update(array $bind ,$queryFragment){
//		$this->_bindData = $bind;
		if(!$bind) return 0;
		$bind = $this->prepareDataForUpdate($bind);
		if(false === $bind) return false;
		$this->updateCount = self::$_dbInstance->update($this->_table ,$bind ,$queryFragment);
		return $this->updateCount;
	}
	
	/**
     * 根据主键删除数据，并返回删除的记录数。
     * $recycle = true 回收到对应recycle表中
     *
     * 例如：
     *   user->delete(10);
     *   user->delete('10,11,12');
     *   user->delete(array('10','11','12'));
     *   user->delete('10,11,12',true);
     * @param mixed $ids
     * @return mixed
     */
	public function delete($ids ,$recycle = false){
		if(!$ids) return 0;
		return self::$_dbInstance->delete ($this->_table ,'where '.$this->_primaryKey.' in ('.implode(',' ,(array)$ids ).')');
	}

	/**
	 * 不经过任何处理的sql语句
	 *
	 *@param stirng $query
	 *@return 依操作而定
	 */
	public function protypequery($query){
		return self::$_dbInstance->protypequery($query);
	}
	
	/**
     * 根据给定字段查找数据 -----废弃  X
     * 
     * 例如：
     *   user->select(10);//等价于user->get(10);
     *   user->select('pid','100');//等价于fetchAow(select * from d_users where pid = 100);
     *   user->select('id,user_nick_name,user_qq','pid','100,200');
     *   user->select(array('id','user_nick_name','user_qq'),'pid','100,200');
     *   user->select(array('id','user_nick_name,user_qq'),'pid',array('100','200'));
     * @param array $bind
     * @return mixed
     */
	public function select () {
		$params = func_get_args();
		$paramscount = count($params);
		if($paramscount < 2) return $this->get(implode(',' ,$params));
		$ids = array_pop($params);
		$find = array_pop($params);
		$cols = array_pop($params);
		$multiCols = is_array($cols) || !$cols || $cols == '*' || strpos($cols ,',');
		$sql = "SELECT " . (empty($cols) ? '*' : implode(',' ,(array)$cols));
		$sql .= " FROM " . $this->_table . " WHERE " . $find . " IN (" . implode(',' ,(array)$ids) . ")";
		return $multiCols ? self::$_dbInstance->fetchAll($sql) : self::$_dbInstance->fetchCol($sql);
	}
	
	
	/**
     * 判断是否是二维数组
     * @param array $array
     * @return bool
     */
	public static function isTwoDimensionalArray ($array) {
		 return !(count($array)==count($array, 1));
	}
	
	/**
     * 返回数据库信息
     * 包括上一次sql语句，上一次sql影响记录数，
     * 上一次sql最后插入的id(主键)值
     * 
     * @return 
     */
	public static function getInfo(){
		return self::$_dbInstance->getDbinfo();
	}
	
	/**
     * 返回数据库插入的或更新的数据数组
     * 
     * @return $array
     */
	public function getBindData(){
		return $this->_bindData;
	}
	
	/**
     * 返回数据库插入的或更新的数据数组
     * 
     * @return $array
     */
	public function getTableName(){
		return $this->_table;
	}
	
	  /**
      * 返回（该类）上次sql影响记录数或返回结果集行数 区别$this->updateCount
      * 
      * @return int
      */
   static function rowCount () {
   		return self::$_dbInstance->rowCount();
   }
   
   /**
      * 返回最后插入的id值(针对单个实例)
      * 
      * @return int
      */
   function getLastInsertId () {
   		return $this->lastInsertId;
   }
   
   /**
     * 返回表的字段信息
     * 
     * @return array
     */
	public function getColumns(){
		if(!$this->_columns) 
			$this->_columns = array_values(self::$_dbInstance->fetchAll('show columns from '.$this->_table));
		return $this->_columns;
	}
	
	/**
     * 返回表的字段
     * 
     * @return array
     */
	public function getFields(){
		if(!$this->_fields){
			$columns = $this->getColumns();
			foreach ($columns as $column)
				$this->_fields[] = $column['Field'];
		}
		return $this->_fields;
	}
	
	/**
     * 返回表的必填字段
     * 
     * @return array
     */
	public function getNotNullFields(){
		if(!$this->_notNullFields){
			$columns = $this->getColumns();
			foreach ($columns as $column){
				if( ($column['Null'] === 'NO' && empty($column['Default']) && $column['Default'] != '0') && $column['Field'] != $this->_primaryKey && $column['Key'] != 'PRI')
				$this->_notNullFields[] = $column['Field'];
			}
		}
		return $this->_notNullFields;
	}
	
	/**
     * 处理要插入的数据
     * 
     * @return mixed
     */
	public function prepareDataForInsert($data){
		$temp = array_intersect($this->getNotNullFields(),array_keys($data));
		if(count($temp) != count($this->_notNullFields)){//判断$data是否包含所有必填字段
			$this->errorFields = array_diff($this->_notNullFields,$temp);//找出$data中没有被包含的字段
			var_dump($this->errorFields);
			return false;
		}
		unset($temp);
		foreach ($this->_notNullFields as $field){
			if(trim($data[$field]) == null) $this->errorFields[] = $field;//找出$data中为空的必填字段
		}
												//屏蔽多余
		return $this->errorFields ?  false : array_intersect_key($data ,array_flip($this->getFields()));
	}
	
	/**
     * 处理要更新的数据
     * 
     * @return mixed
     */
	public function prepareDataForUpdate($data){
		$this->getNotNullFields();
		$temp = array_intersect_key($data ,array_flip($this->_notNullFields));
		foreach ($temp as $key=>$value){
			if(trim($value) == null) $this->errorFields[] = $key;//找出$data中为空的必填字段
		}								//屏蔽多余
		return $this->errorFields ?  false : array_intersect_key($data ,array_flip($this->getFields()));
	}
	
	/**
      * 获得sql执行次数
      * 
      * @return mixed
      */
 	public static function getSqlNum () {
 		if (null === self::$_dbInstance)
 			self::getDbInstance();
   		return self::$_dbInstance->getSqlNum();
   	}
}
?>
