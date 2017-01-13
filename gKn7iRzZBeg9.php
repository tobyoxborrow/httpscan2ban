<?php
/*
 *  Visiting this php file will get a user banned.
 *
 *  When a request comes to the web server, it will be checked against the
 *  mod_rewrite rules in the .htaccess file for common vulnerability scans. If
 *  there is a match then the request is redirected to the php file. The php
 *  file logs some details to a separate log file - including the source ip
 *  address. fail2ban jail rules will check the log file and adds an all ports
 *  iptables drop rule, banning them.
 */

$ip = $_SERVER['REMOTE_ADDR'];

// Ignore some ip addresses to avoid accidental banning of known trusted sites
//if (strpos($ip, '192.0.2', 0) === 0) { echo '.'; exit; }

$request = $_SERVER["REQUEST_URI"];

// fail2ban only cares about the IP, but we also store the date/time and URI
// for us humans to view later if necessary
$log = date('M d H:i:s') . "\tIP=" . $ip . "\tURI=$request\n";

// This should be outside of the webroot and writable by the web user
$fh = fopen('/var/www/httpscan2ban/httpscan2ban.log', 'a');
fwrite($fh, $log);
fclose($fh);

// Send some output back
// This is just to mess with them, so they get a positive HTTP 200 response
// that may screw up their scan.
// Send back a 404 if you want a more stealthy operation
echo '.';
