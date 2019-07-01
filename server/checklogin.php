<?php
	//set date zone for India.
	date_default_timezone_set('Asia/Kolkata');
	$timestamp = time();
	
	//last 15 mins data only
	$before15 = $timestamp - 900;
	
	//Verify
	$verify = $conn->prepare("SELECT username, unixtime FROM `logincontrol` WHERE  username = ? AND unixtime  >= ? ORDER BY unixtime DESC  ");
	$verify->bind_param("si", $username, $before15 );
	$verify->execute();

	//get result of query
	$countresul = $verify->get_result();

	$c=1;
	while($loginc = $countresul->fetch_assoc() )
	{
		$unixtime = $loginc['unixtime']; 
		$c++;
	}
	$loginattempts = $c;

	//15 mins 10 attempts
	$bad_login_limit = 10;
	$lockout_time = 900; 

	if( ($loginattempts > $bad_login_limit) && (time() - $unixtime < $lockout_time) ) 
	{
		//get current url
		$url=$_SERVER['REQUEST_URI'];
		header("Refresh: 0; URL=$url");
		die();
		
	}

	//get ip address
	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	$ipaddr = get_client_ip();
	//Insert Data
	$insertcheck = $conn->prepare( "INSERT INTO `logincontrol`(`unixtime`,`username`,`ip`) VALUES (?,?,?)");
	$insertcheck->bind_param("iss", $timestamp, $username, $ipaddr);
	$insertcheck->execute();

	
?>