<?php 

class Utils
{
	public static function generate_token($Length)
	{
		$CharPool = '0123456789';
		$CharPool .= 'abcdefghijklmnopqrstuvwxyz';
		$CharPool .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$RandomNumber = function($Minimum, $Maximum) 
		{			
			# Find the range of the maximum and minimum allowed output
			$Range = $Maximum - $Minimum;

			# If the range is less than 0 forget the rest and return the minimum allowed number as  a 'random' bit
			if($Range < 0) 
			{
				return $Minimum; 
			}

			# Calculate the logarithm for $Range variable
			$Logarithm = (int) log($Range, 2)+1;

			$ByteLength = (int) ($Logarithm-1/8)+1;

			$BitF = (int) (1 << $Logarithm)-1; 

			do 
			{	
				# Get some random binary bytes
				$RndBinBytes = openssl_random_pseudo_bytes($ByteLength);

				# Converts the binary to hexadecimal
				$HexBytes = bin2hex($RndBinBytes);
				
				# Convert the hexadecimal bytes to decimal
				$Random = hexdec($HexBytes);

				# Use the AND operator to discard the unneeded bits
				$Random = $Random & $BitF; 
			} 
			while($Random >= $Range);
			
			# Return the random number found by the sub function to the main function
			return $Minimum + $Random;
		};

		# Initialise the RandChars variable
		$RandChars = '';

		$LengthOfPool = strlen($CharPool);

		for ($Counter = 0; $Counter < $Length; $Counter +=1) 
		{
			$RandNum = $RandomNumber(0, $LengthOfPool);

			# Pick from the pool of chars
			$RandChar = $CharPool[$RandNum];

			# Append the random char to the token to be returned at the end
			$RandChars .= $RandChar;
		}
		return $RandChars;
	}


	// allows quick change of db stanard input date format incase DB engine changes
	public static function database_date($unix_timestamp = NULL)
	{
		if(is_null($unix_timestamp))
		{
			$unix_timestamp = time();
		}

		return date('Y-m-d H:i:s', $unix_timestamp);
	}

	/**
	* Redirects the user to a new page, with optional delay timer.
	*
	* @param string $URL - the URL the user should be redirected to
	* @param int $Delay - the number of seconds to delay the redirect 
	*
	* @return Void
	*/

	public static function redirect($URL, $Delay=0) 
	{
		if(headers_sent())
		{
    			if($Delay == 0)
			{
				die(print("<script type=\"text/javascript\">window.location.href='{$URL}';</script>"));
			}else{
				die(print("<script type=\"text/javascript\">setTimeout( \"window.location.href = '{$URL}'\", {$Delay}*1000);</script>"));
			}
		}else{
    			if($Delay == 0)
			{
				die(header('Location: '.$URL));
			}else{
				die(header('Refresh: '.(int)$Delay.'; url='.$URL));
			}
    		}
	}

	/**
	* Get the remote address of a user
	*
	* @param NULL
	*
	* @return string - the remote address of the user (IP)
	*/

	public static function get_ip()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	/*
	 * days_in_month($month, $year)
	 * Returns the number of days in a given month and year, taking into account leap years.
	 *
	 * $month: numeric month (integers 1-12)
	 * $year: numeric year (any integer)
	 *
	 * Prec: $month is an integer between 1 and 12, inclusive, and $year is an integer.
	 * Post: none
	 */
	// corrected by ben at sparkyb dot net
	public static function days_in_month($month, $year)
	{
		// calculate number of days in a month
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}

	public static function round_up_to_any($n,$x=1)
	{
		return (ceil($n)%$x === 0) ? ceil($n) : round(($n+$x/2)/$x)*$x;
	} 

	public static function is_url($URL)
	{
		return filter_var($URL, FILTER_VALIDATE_URL);
	}

	public static function is_email($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function is_int($Int)
	{
		return filter_var($Int, FILTER_VALIDATE_INT);
	}

	public static function xss_secure($Input)
	{
		return htmlentities($Input, 'utf-8');
	}

	public static function range_check($Int, $Min, $Max)
	{
		return filter_var(
		    $Int, 
		    FILTER_VALIDATE_INT, 
		    array(
		        'options' => array(
		            'min_range' => $Min, 
		            'max_range' => $Max
		        )
		    )
		);
	}

	public static function is_date($DateString, $DateFormat = 'Y-m-d')
	{
		$date = DateTime::createFromFormat($DateFormat, $DateString);
		$date_errors = DateTime::getLastErrors();
		if($date_errors['warning_count'] + $date_errors['error_count'] > 0)
		{
		    return FALSE;
		}

		return TRUE;
	}
}