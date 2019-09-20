<?
#############################
### Auto create Wordpress ###
#############################

// Create by Hiram
// Date : 2019-08-30
// Last Update : 2019-09-02
// Version : 2.1.2
// PHP Version : 5.6 or newer

//aaa
#############################

$dbname="wordpress";
$dbuser="user";
$dbpass="password";
$dbhost="localhost";
$dbprefix="wp_";

$url="http://example.com";
$title="Blog Title";
$admin_user="adminuser";
$admin_password="password";
$admin_email="email@domain.com";

// example : $wp_version="--version=4.9.4";
$wp_version="";

// PHP command
$php_command[]="php";
$php_command[]="php70";
$php_command[]="php71";
$php_command[]="php56";
$php_command[]="php72";
$php_command[]="php73";
$php_command[]="php74";

#############################

    # Check PHP Version
	
	foreach ( $php_command as $key => $value ) {
	
	$php_cmd = $value ;
	
	$php_ver = shell_exec($php_cmd.' -r "echo PHP_VERSION;" | cut -d . -f 1,2');
	$php_ver = floatval($php_ver);
	
	if ( $php_ver >= 5.6 ) {
		break;
	}
	
	} //foreach

    # Check datebase connection
	$link = mysqli_connect($dbhost, $dbuser, $dbpass);
	
	if (!$link) {
		echo "Connot connect the datebase , please check the config." . "<br>";
		die('Not connected : ' . mysql_error());
	}
		
   # Download wp-cli
   $wp_cli = 'wp-cli.phar';
	
   do {
		if (file_exists($wp_cli)) {
			break;
		}
		$output = shell_exec('curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar');
	} while(true);
	

	# Download Wordpress files
	$wp_configfile = 'wp-config-sample.php';
	
	do {
		if (file_exists($wp_configfile)) {
			break;
		}
		$output = shell_exec($php_cmd.' wp-cli.phar core download '.$wp_version);
	} while(true);
	
	# Create config file 
	$wp_config = 'wp-config.php';
	
	do {
		if (file_exists($wp_config)) {
			break;
		}
		$output = shell_exec($php_cmd.' wp-cli.phar core config --dbname='.$dbname.' --dbuser='.$dbuser.' --dbpass='.$dbpass.' --dbhost='.$dbhost.' --dbprefix='.$dbprefix);
	} while(true);
	
	
	
	# Install Wordpress
	
	$check_db = shell_exec($php_cmd.' wp-cli.phar db query "SHOW TABLES"');
	
	if (is_null($check_db)) {
		$output = shell_exec($php_cmd.' wp-cli.phar core install --url="'.$url.'" --title="'.$title.'" --admin_user="'.$admin_user.'" --admin_password="'.$admin_password.'" --admin_email="'.$admin_email.'"');
	}
	
	
	# Remove installation files
	$output = shell_exec('rm -rf wp-cli.phar wp_install.php');

	# Redirect
	header('Location: /wp-admin/');
?>