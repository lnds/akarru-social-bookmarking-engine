<?php
require ('config.php');

class Database extends mysqli {

	private static $instance;

	public static function singleton()
	{
		if (!isset(self::$instance)) 
		{
           $c = __CLASS__;
           self::$instance = new $c;
		}
		return self::$instance;
	}

	var $user= DATABASE_USER;
	var $pass= DATABASE_PASS;
	var $host= DATABASE_HOST;
	var $name= DATABASE_NAME;

	private function __construct()
	{
        parent::__construct(
           $this->host,
           $this->user,
           $this->pass,
           $this->name);
		$this->query("SET CHARACTER SET utf8");
		$this->query("SET NAMES 'utf8'");
	}

	function __destruct()
	{
		$this->close();
	}

	public function sanitize($str)
	{
		$str = htmlentities($str, ENT_QUOTES);
		$str = strip_tags($str, '<a><b><em><br><p><h1><h2><h3><h4><ul><li>');
		return $this->real_escape_string($str);
	}

	public function fetch_object($sql)
	{
		$result = $this->query($sql, MYSQLI_USE_RESULT);
		$row = $result->fetch_object();
		$result->free();
		return $row;
	}

	public function fetch_scalar($sql)
	{
		$result = $this->query($sql, MYSQLI_USE_RESULT);
		$row = $result->fetch_row();
		$result->free();
		return $row[0];
	}

	public function execute($query)
	{
		return $this->query($query);
	}

	public function count($from, $where)
	{
		$sql = 'select count(*) '.$from.' '.$where;
		return $this->fetch_scalar($sql);
	}

	public function select($from, $where, $order='', $limit='')
	{
		return $this->select_fields('*', $from, $where, $order, $limit);
	}

	public function select_fields($fields, $from, $where, $order='', $limit='')
	{
		$sql = 'select '.$fields.' '.$from.' '.$where.' '.$order.' '.$limit;
		$result = $this->query($sql, MYSQLI_USE_RESULT);
		$data = array();
		if ($result) 
		{
			while ($row = $result->fetch_object())
			{
				$data[] = $row;
			}
			$result->free();
		}
		return $data;
	}

}


?>
