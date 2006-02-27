<?php

	

  class database {

	var $db_user= DATABASE_USER;
	var $db_pass= DATABASE_PASS;
	var $db_host= DATABASE_HOST;
	var $db_name= DATABASE_NAME;

	function database()
	{
		$this->connect();
	}
	
	/**
	* Connect to the database using the parameters given
	*/
	function connect()
	{
		$this->db=mysql_connect($this->db_host,$this->db_user,$this->db_pass);
		mysql_select_db($this->db_name,$this->db);
		return $this->db;
	}

	/// use this for update, delete
	function execute($sql)
	{
		$query = $this->do_query($sql);
		$this->insert_id = @mysql_insert_id();
		$this->rows = @mysql_affected_rows();
		return $this->rows;
	}

	// use this for select
	// rows is the number of rows selected
	// return an array of objects
	function fetch($sql)
	{
		$query = $this->do_query($sql);
		$this->rows = mysql_num_rows($query);
//		echo "rows = ".$this->rows;
		while($row=@mysql_fetch_object($query))
		{
			$returned[] = $row;
		}
		return $returned;
	}

	function fetch_scalar($sql)
	{
		$query = $this->do_query($sql);
		$row = @mysql_fetch_row($query);
		return $row[0];
	}


	function fetch_object($sql)
	{
		$query = $this->do_query($sql);
		return @mysql_fetch_object($query);
	}


	// private, this do the real query
	function do_query($sql)
	{
		$query = mysql_query($sql) or die("error: ".mysql_error());
		return $query;
	}

	function count_rows($sql)
	{
		$query = $this->do_query($sql);
		$this->rows = mysql_num_rows($query);
		return $this->rows;
	}
}

?>
