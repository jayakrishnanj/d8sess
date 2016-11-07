<?php
/**
 * Created by PhpStorm.
 * User: jayakrishnan
 * Date: 11/7/16
 * Time: 12:23 PM
 */


// the server you wish to connect to - you can also use the server ip ex. 107.23.17.20
$ftp_server = "ps530449.dreamhostps.com";

$username  = 'tdftp1';
$password = 'vYJw8?Zn';


// check if a file exist
$path = "/d8files/"; //the path where the file is located

$file = "Amazon.pdf"; //the file you are looking for

// set up a connection to the server we chose or die and show an error
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
ftp_login($conn_id, $username , $password);


$ftpfile   = file_get_contents("ftp://".$username.":". $password ."@"
  .$ftp_server .$path.$file);

print '<pre>'.print_r($ftpfile, TRUE).'</pre>';


$check_file_exist = $path.$file; //combine string for easy use

$contents_on_server = ftp_nlist($conn_id, $path); //Returns an array of filenames from the specified directory on success or FALSE on error.

// Test if file is in the ftp_nlist array
if (in_array($check_file_exist, $contents_on_server))
{
  echo "<br>";
  echo "I found ".$check_file_exist." in directory : ".$path;
}
else
{
  echo "<br>";
  echo $check_file_exist." not found in directory : ".$path;
};

// output $contents_on_server, shows all the files it found, helps for debugging, you can use print_r() as well
var_dump($contents_on_server);

// remember to always close your ftp connection
ftp_close($conn_id);