<?

class Tag {
	var $data;

	public function __construct($data_id=0, $data_name='')
	{
		$data_id = intval($data_id);
		$db = Database::singleton();

		if ($data_id > 0) 
		{
			$this->data = $db->fetch_object('select * from tags where ID = '.$data_id);
		}
		else
		{
			$data_name = $db->sanitize($data_name);
			$this->data = $db->fetch_object("select * from tags where tag = '$data_name'");
		}
	}

	 /**
	 * 
	 * @return the value of the property for the category
	 */
	private function __get($name)
	{
		if (isset($this->data->$name)) {
			return $this->data->$name;
		}
		return '';
	}

	
}
?>
