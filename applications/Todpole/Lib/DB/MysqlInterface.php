<?php 
interface Dadi_Mysql_Interface {
	 /**
     * query 方法
     *
     * @param mixed $sql
     * @return mixed
     */
     public function query ($sql);
    
    /**
     * 返回 ResultSet 中第一行第一列的数据
     *
     * @param mixed $sql
     * @param array $bind
     * @return mixed
     */
    public function fetchOne ($sql);
    
    /**
     * 返回所有的 SQL 结果集
     *
     * @param mixed $sql
     * @return array
     */
    public function fetchAll ($sql);
    
    /**
     * 返回 SQL 结果集中的第一行数据
     *
     * @param mixed $sql
     * @return mixed
     */
    public function fetchRow ($sql);
    
    /**
     * 返回 ResultSet 中的第一列数据
     *
     * @param mixed $sql
     * @return array
     */
    public function fetchCol ($sql);
    
	/**
     * 向数据表中插入一行数据,并返回插入的id值
     * $multi = true 向表内插入多行数据，并返回最后插入的id值
     *
     * @param mixed $table
     * @param array $bind
     * @param  bool $multi
     * @return int
     */
   public function insert($table ,array $bind ,$multi = false);
   
   /**
     * 更新数据表中符合 $where 条件的记录，并返回影响记录数
     *
     * @param mixed $table
     * @param array $bind
     * @param string $where
     * @return int
     */
   public function update($table, array $bind, $queryFragment = '');
   
        /**
      * 根据条件删除数据
      * 
      * @param string $table
      * @param string $queryFragment
      * @return string
      */
   	public function delete ($table ,$queryFragment);
   
     /**
      * 给字符串加引号
      * 
      * @param string $value
      * @return string
      */
   public function quoteIdentifier ($value);
 	
}
?>
