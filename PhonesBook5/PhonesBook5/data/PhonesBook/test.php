<?php
//include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/php/Adldap2/Adldap.php");
header('Content-type: text/html; charset=utf-8');



$config = array(
    'account_suffix' => "@murmansk.net",
    'domain_controllers' => ["10.51.119.10","10.51.115.10"],
    'base_dn' => 'dc=murmansk,dc=net',
    'admin_username' => 'ldap',
    'admin_password' => 'ldap',
);

//$ad = new Adldap($config);
//$ad = new Adldap();
// Create a new connection provider.
$provider = new \Adldap\Connections\Provider($config);
// Construct new Adldap instance.
$ad = new \Adldap\Adldap();
// Add the provider to Adldap.
$ad->addProvider('default', $provider);

// Try connecting to the provider.
try {
    // Connect using the providers name.
    $ad->connect('default');

    // Create a new search.
    $search = $provider->search();

    // Call query methods upon the search itself.
    $results = $search->where('...')->get();

    // Or create a new query object.
    $query = $search->newQuery();

    // Perform a query.
    $results = $search->where('...')->get();

    // Create a new LDAP object.
    $user =  $provider->make()->user();

    // Set a model's attribute.
    $user->cn = 'John Doe';

    // Persist the changes to your AD server.
    if ($user->save()) {
        // User was saved!
    }
} catch (\Adldap\Exceptions\Auth\BindException $e) {

    // There was an issue binding / connecting to the server.
die("Can't bind to LDAP server!");
}


/*
$a = 66048;// 512;
$b = 66050; //514;

$d1 = ($a |  2);
$e1 = ($a & ~2);
$d2 = ($b |  2);
$e2 = ($b & ~2);


$format = '(%1$2d = %1$04b) = (%2$2d = %2$04b)'
        . ' %3$s (%4$2d = %4$04b)' . "\n";

	echo "a=$a => d1=$d1   e1=$e1<br>b=$b => d2=$d2   e2=$e2<br>";	
*/
?>