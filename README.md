Facebook SDK for PHP (falls back to use Stream if PHP Curl module not enabled)
================================================================================
This is a fork of the official Facebook SDK for PHP, version 4. The official SDK
uses Curl to make all the API calls, making it unusable in environments that doesn't
have the PHP Curl module enabled (example: Google App Engine)

This fork checks if PHP Curl module is enabled. If not, it falls back to using
Stream (via file_get_contents()). If Curl is detected, it uses Curl just like
the official SDK.



Facebook SDK for PHP (Official Documentation)
=============================================

This repository contains the open source PHP SDK that allows you to access Facebook
Platform from your PHP app.


Usage
-----

This version of the Facebook SDK for PHP requires PHP 5.4 or greater.

Minimal example:

```php
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication('YOUR_APP_ID','YOUR_APP_SECRET');

// Use one of the helper classes to get a FacebookSession object.
//   FacebookRedirectLoginHelper
//   FacebookCanvasLoginHelper
//   FacebookJavaScriptLoginHelper
// or create a FacebookSession with a valid access token:
$session = new FacebookSession('access-token-here');

// Get the GraphUser object for the current user:

try {
  $me = (new FacebookRequest(
    $session, 'GET', '/me'
  ))->execute()->getGraphObject(GraphUser::className());
  echo $me->getName();
} catch (FacebookRequestException $e) {
  // The Graph API returned an error
} catch (\Exception $e) {
  // Some other error occurred
}

```

Complete documentation, installation instructions, and examples are available at:
[https://developers.facebook.com/docs/php](https://developers.facebook.com/docs/php)


Tests
-----

1) [Composer](https://getcomposer.org/) is a prerequisite for running the tests.

Install composer globally, then run `composer install` to install required files.

2) Create a test app on [Facebook Developers](https://developers.facebook.com), then
create `tests/FacebookTestCredentials.php` from `tests/FacebookTestCredentials.php.dist`
and edit it to add your credentials.

3) The tests can be executed by running this command from the tests directory:

```bash
../vendor/bin/phpunit --stderr .
```


Contributing
------------

For us to accept contributions you will have to first have signed the
[Contributor License Agreement](https://developers.facebook.com/opensource/cla).

When committing, keep all lines to less than 80 characters, and try to
follow the existing style.

Before creating a pull request, squash your commits into a single commit.

Add the comments where needed, and provide ample explanation in the
commit message.
