<?php

class ReceiptModel extends AbstractModel 
{
	public function generate_receipt_key()
	{
		$this->db->run('SELECT * FROM parent WHERE email = :email', array(':email' => $email));
		if($this->db->row_count() > 0)
		{
			$this->set_last_error_message('Email already in assocaited to another account');
			return false;
		}
	}

	public function generate_receipt_key_bulk()
	{

	}
}