<?php
require_once('../simplesamlphp/lib/_autoload.php');
$_simplesamlphp_auth = new SimpleSAML_Auth_Simple('profilephoto-prod');
$_simplesamlphp_auth->requireAuth();
$_simplesamlphp_auth_attributes = $_simplesamlphp_auth->getAttributes();

/*
if ($_simplesamlphp_auth->isAuthenticated()) {
  print 'Logged-IN';
} else {
  print 'Logged-OUT';
}
*/

//$config = SimpleSAML_Configuration::getInstance();
//print '<pre>'; print_r($config); print '</pre>';
//$configStoreType = $config->getValue('store.type');
?>