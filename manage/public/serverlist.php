<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Viva Connect | Server Management</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
</head>

<body id="main_body">
<?php
require '../lib/manage_functions.php';
require '../lib/config.php';

## check if the user is authenticated and what is the username
session_start();

if(isset($_SESSION['username'])) {
	## We know who the user is
	$user = $_SESSION['username'];
	$id = session_id();
	if (!strcmp('admin',$user)) {
		header('Location: http://manage.email360api.com/' . $admin_page);
		exit;
	}
        if(!isset($_POST['s'])) {
                syslog(LOG_NOTICE, "manage: serverlist.php: client: {$_SERVER['REMOTE_ADDR']}, user: $user, sess_id: $id, servername: unset, redirect $splash_page");
                header('Location: http://manage.email360api.com/' . $splash_page);
                exit;
        }

        $servername = $_POST['s'];
        syslog(LOG_NOTICE, "manage: index.php: client: {$_SERVER['REMOTE_ADDR']}, user: $user, sess_id: $id, servername: $servername, loading serverlist");
} else {
        syslog(LOG_NOTICE, "manage: index.php: client: {$_SERVER['REMOTE_ADDR']}, user: undet, sess_id: unset, redirect $auth_page");
        header('Location: http://manage.email360api.com/' . $auth_page);
        exit;
}

$servername =  $_POST['s'];

?>


<div id="form_container">
<div style="position: relative; float: left; ">
<img id="top1" src="viva.png" alt="">
</div>

<div class="index_bottom">
<a class="indexa" href="index.php">Home</a>
<t class="index">&nbsp;&nbsp;|&nbsp;&nbsp;</t>
<a class="indexa" href="contact.php">Contact</a>
<t class="index">&nbsp;&nbsp;|&nbsp;&nbsp;</t>
<a class="indexa" href="logout.php">Logoff(<?php echo $user ?>)</a>
<t class="index">&nbsp;&nbsp;</t>
</div>

<img id="top" src="top.png" alt="">
<div class="form_description">
<h2>&nbsp;Viva Connect | Server Management</h2>
&nbsp;Control panel for servers and services
<div>
</div>
</div>


<?php

## get the list of servers now and generate the tables thus
syslog(LOG_NOTICE, "manage: index.php: client: {$_SERVER['REMOTE_ADDR']}, user: $user, sess_id: $id, load vm $servername");
echo "
<table id=\"ServerList\" cellspacing=\"0\" summary=\"Virtual containers hosted on $servername\">
</tr>
<caption>&nbsp;VM's on $servername</caption>
<tr>
<th scope=\"col\" abbr=\"Virtual Machine\">Servername</th>
<th scope=\"col\" abbr=\"Ip Addr\">Ip Addr</th>
<th scope=\"col\" abbr=\"Status\">Status</th>
</tr>";

$output = array();
exec("sudo -u sysad /opt/scripts/control-panel/get_vm_list.sh $servername", $output);
foreach($output as $a) {
	$b = preg_split('/ +/',$a);

	##check if we want to list this vm, in the first place
	echo "
	<tr>
	<td id=\"hoverthis\">
	<form id=\"serverpost\" action=\"action.php\" method=\"post\">
	<input type=\"hidden\" name=\"v\" value=\"$b[0]\">
	<input type=\"hidden\" name=\"i\" value=\"$b[1]\">
	<input type=\"hidden\" name=\"s\" value=\"$b[2]\">
	<input type=\"hidden\" name=\"c\" value=\"$b[3]\">
	<input type=\"hidden\" name=\"p\" value=\"$servername\">
	<button type=\"submit\">$b[0]</button>
	</form>
	</td>

	<td><t class=\"vmname\">$b[1]</t></td>
	<td><t class=\"vmname\">$b[2]</t></td>
	</tr>";
}

echo "</table>";
echo "<br>";
echo "<img id=\"bottom\" src=\"bottom.png\" />";
echo "<br>";
echo "<br>";
?>

<div id="footer">
Powered by <a href="http://madinix.com">Madinix</a>
</div>
</div>
<img id="bottom" src="bottom.png" alt="">
</body>
</html>

