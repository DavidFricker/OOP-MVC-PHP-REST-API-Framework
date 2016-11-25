<?php
// wrapper class provided by http://www.imavex.com/php-pdo-wrapper-class/
class db extends PDO {

    public $error = '';
    public $querycount = 0;
    public $dbh;

    public function __construct($dsn, $user='', $passwd='') 
    {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//ERRMODE_WARNING, //
            PDO::ATTR_PERSISTENT => true
            );

        try 
        {
            parent::__construct($dsn, $user, $passwd, $options);
        }
        catch (PDOException $e) 
        {
            var_dump($e);
            $this->error = $e->getMessage();
        }
    }

    public function run($query, $bind=false, $handler=false) 
    {
        $this->querycount++;
        try 
        {
            if($bind !== false) 
            {
                $bind = (array) $bind;
                $this->dbh = $this->prepare(trim($query));
                $this->dbh->execute($bind);
            }else{
                $this->dbh = $this->query(trim($query)); // because query is 3x faster than prepare+execute
            }

            if(preg_match('/^(select|describe|pragma)/i', $query)) 
            {
                // if $query begins with select|describe|pragma, either return handler or fetch
                return ($handler) ? $this->dbh : $this->dbh->fetchAll();
            }else if (preg_match('/^(delete|insert|update)/i', $query)) 
            {
                // if $query begins with delete|insert|update, return count
                return $this->dbh->rowCount();
            }else{
                return true;
            }
        } 

        catch (PDOException $e) 
        {
            $this->error = $e->getMessage();
            return FALSE;
        }
    }

    public function get_last_insert_id()
    {
        if($a=$this->run('SELECT LAST_INSERT_ID() as ID'))
        {
            return $a[0]['ID'];
        }else{
            return FALSE;
        }
    }
    public function row_count()
    {
        return $this->dbh->rowCount();
    }

    private function prepBind($pairs, $glue) 
    {
        $parts = array();
        foreach ($pairs as $k=>$v) 
        { 
            $parts[] = "`$k` = ?"; 
        }
        return implode($glue, $parts);
    }

    public function update($table, $data, $where, $limit=false) 
    {
        if (is_array($data) && is_array($where)) 
        {
            $dataStr  = $this->prepBind( $data, ', ' );
            $whereStr = $this->prepBind( $where, ' AND ' );
            $bind = array_merge( array_values($data), array_values($where) );

            $sql = "UPDATE `$table` SET $dataStr WHERE $whereStr";
            if ($limit && is_int($limit)) 
            { 
                $sql .= ' LIMIT '. $limit; 
            }
            return $this->run($sql, $bind);
        }
        return false;
    }

    public function insert($table, $data) 
    {
        if (is_array($data)) 
        {
            $dataStr = $this->prepBind($data, ', ');
            $bind = array_values($data);

            $sql = "INSERT `$table` SET  $dataStr";
            return $this->run($sql, $bind);
        }
        return FALSE;
    }

    public function delete($table, $where, $limit=false) 
    {
        if (is_array($where)) 
        {
            $whereStr = $this->prepBind($where, ' AND ');
            $bind = array_values($where);

            $sql = "DELETE FROM `$table` WHERE $whereStr";
            if ($limit && is_int($limit)) 
            { 
                $sql .= ' LIMIT '. $limit; 
            }
            return $this->run($sql, $bind);
        }
        return false;
    }
}