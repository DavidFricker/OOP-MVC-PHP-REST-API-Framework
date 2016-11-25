<?php
// this class is a simpe wrapper around PHP's json functions
// it aims to make error detection and parseing simpler
class Json
{
	public static function decode($string, $assoc = false)
	{
		// suppress errors, becuase they will be delt with on the next line
		$decode_result = @json_decode($string, $assoc);

		if($decode_result === null && json_last_error() !== JSON_ERROR_NONE)
		{
		    return false;
		}

		return $decode_result;
	}

	public static function encode($object)
	{
		return json_encode($object, $assoc);
	}

	private static function legacy_get_error_message()
	{
		switch(json_last_error())
		{
		    case JSON_ERROR_NONE:
		        $message = 'No errors';
		    	break;
		    case JSON_ERROR_DEPTH:
		        $message = 'Maximum stack depth exceeded';
		    	break;
		    case JSON_ERROR_STATE_MISMATCH:
		        $message = 'Underflow or the modes mismatch';
		    	break;
		    case JSON_ERROR_CTRL_CHAR:
		        $message = 'Unexpected control character found';
		    	break;
		    case JSON_ERROR_SYNTAX:
		        $message = 'Syntax error, malformed JSON';
		    	break;
		    case JSON_ERROR_UTF8:
		        $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
		    	break;
		    default:
		        $message = 'Unknown error';
		    	break;
		}

		return $message;
	}

	public static function get_error_message()
	{
		if(!function_exists('json_last_error_msg'))
		{
			return slef::legacy_get_error_message();
		}

		return json_last_error_msg();
	}
}