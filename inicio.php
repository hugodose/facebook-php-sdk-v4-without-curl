<?php
$helper = new FacebookRedirectLoginHelper('your redirect URL here');
$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to redirect to Facebook for authentication
?>
