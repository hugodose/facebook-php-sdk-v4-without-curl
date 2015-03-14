

<?php
session_start();

echo 'Hello! ';


//require_once( 'src/Facebook/FacebookSession.php' );
//require_once( 'src/Facebook/FacebookRedirectLoginHelper.php' );
//require_once( 'src/Facebook/FacebookRequest.php' );
//require_once( 'src/Facebook/FacebookResponse.php' );
//require_once( 'src/Facebook/FacebookSDKException.php' );
//require_once( 'src/Facebook/FacebookRequestException.php' );
//require_once( 'src/Facebook/FacebookAuthorizationException.php' );
//require_once( 'src/Facebook/GraphObject.php' );

require_once($_SERVER['DOCUMENT_ROOT'].'/sys/facebook/FacebookSession.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sys/facebook/FacebookRequest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sys/facebook/GraphObject.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sys/facebook/FacebookRequestException.php');

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;
    
//use src\Facebook\FacebookSession;
//use src\Facebook\FacebookRequest;
//use src\Facebook\GraphUser;
//use src\Facebook\FacebookRequestException;
//use src\Facebook\FacebookRedirectLoginHelper;

echo 'Hello!2 ';

echo 'Hello!3 ';

FacebookSession::setDefaultApplication('1560115454240194','e9b7a69cc961d012592996b2dd540e3a');
echo 'Hello!4 ';

$helper = new FacebookRedirectLoginHelper('http://globo.com');
$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to redirect to Facebook for authentication

echo 'Hello!5 ';


?>

<?php
/**
 * Copyright 2014 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
//namespace Facebook;

/**
 * Class FacebookSession
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookSession
{

  /**
   * @var string
   */
  private static $defaultAppId;

  /**
   * @var string
   */
  private static $defaultAppSecret;

  /**
   * @var string The token string for the session
   */
  private $token;

  /**
   * When creating a Session from an access_token, use:
   *   var $session = new FacebookSession($accessToken);
   * This will validate the token and provide a Session object ready for use.
   * It will throw a SessionException in case of error.
   *
   * @param string $accessToken
   */
  public function __construct($accessToken)
  {
    $this->token = $accessToken;
  }

  /**
   * Returns the access token
   *
   * @return string
   */
  public function getToken()
  {
    return $this->token;
  }

  /**
   * getSessionInfo - Makes a request to /debug_token with the appropriate
   *   arguments to get debug information about the sessions token.
   *
   * @param string|null $appId
   * @param string|null $appSecret
   *
   * @return GraphSessionInfo
   */
  public function getSessionInfo($appId = null, $appSecret = null)
  {
    $targetAppId = static::_getTargetAppId($appId);
    $targetAppSecret = static::_getTargetAppSecret($appSecret);
    return (new FacebookRequest(
      static::newAppSession($targetAppId, $targetAppSecret),
      'GET',
      '/debug_token',
      array(
        'input_token' => $this->getToken(),
      )
    ))->execute()->getGraphObject(GraphSessionInfo::className());
  }

  /**
   * getLongLivedSession - Returns a new Facebook session resulting from
   *   extending a short-lived access token.  If this session is not
   *   short-lived, returns $this.
   *
   * @param string|null $appId
   * @param string|null $appSecret
   *
   * @return FacebookSession
   */
  public function getLongLivedSession($appId = null, $appSecret = null)
  {
    $targetAppId = static::_getTargetAppId($appId);
    $targetAppSecret = static::_getTargetAppSecret($appSecret);
    $params = array(
      'client_id' => $targetAppId,
      'client_secret' => $targetAppSecret,
      'grant_type' => 'fb_exchange_token',
      'fb_exchange_token' => $this->getToken()
    );
    // The response for this endpoint is not JSON, so it must be handled
    //   differently, not as a GraphObject.
    $response = (new FacebookRequest(
      self::newAppSession($targetAppId, $targetAppSecret),
      'GET',
      '/oauth/access_token',
      $params
    ))->execute()->getResponse();
    if ($response) {
      return new FacebookSession($response['access_token']);
    } else {
      return $this;
    }
  }

  /**
   * getExchangeToken - Returns an exchange token string which can be sent
   *   back to clients and exchanged for a device-linked access token.
   *
   * @param string|null $appId
   * @param string|null $appSecret
   *
   * @return string
   */
  public function getExchangeToken($appId = null, $appSecret = null)
  {
    $targetAppId = static::_getTargetAppId($appId);
    $targetAppSecret = static::_getTargetAppSecret($appSecret);
    // Redirect URI is being removed as a requirement.  Passing an empty string.
    $params = array(
      'client_id' => $targetAppId,
      'access_token' => $this->getToken(),
      'client_secret' => $targetAppSecret,
      'redirect_uri' => ''
    );
    $response = (new FacebookRequest(
      self::newAppSession($targetAppId, $targetAppSecret),
      'GET',
      '/oauth/client_code',
      $params
    ))->execute()->getGraphObject();
    return $response->getProperty('code');
  }

  /**
   * validate - Ensures the current session is valid, throwing an exception if
   *   not.  Fetches token info from Facebook.
   *
   * @param string|null $appId Application ID to use
   * @param string|null $appSecret App secret value to use
   *
   * @return boolean
   */
  public function validate($appId = null, $appSecret = null)
  {
    $targetAppId = static::_getTargetAppId($appId);
    $targetAppSecret = static::_getTargetAppSecret($appSecret);
    $info = $this->getSessionInfo($targetAppId, $targetAppSecret);
    return self::validateSessionInfo($info, $targetAppId, $targetAppSecret);
  }

  /**
   * validateTokenInfo - Ensures the provided GraphSessionInfo object is valid,
   *   throwing an exception if not.  Ensures the appId matches,
   *   that the token is valid and has not expired.
   *
   * @param GraphSessionInfo $tokenInfo
   * @param string|null $appId Application ID to use
   *
   * @return boolean
   *
   * @throws FacebookSDKException
   */
  public static function validateSessionInfo(GraphSessionInfo $tokenInfo,
                                           $appId = null)
  {
    $targetAppId = static::_getTargetAppId($appId);
    if ($tokenInfo->getAppId() !== $targetAppId
      || !$tokenInfo->isValid() || $tokenInfo->getExpiresAt() === null
      || $tokenInfo->getExpiresAt()->getTimestamp() < time()) {
      throw new FacebookSDKException(
        'Session has expired, or is not valid for this app.', 601
      );
    }
    return true;
  }

  /**
   * newSessionFromSignedRequest - Returns a FacebookSession for a
   *   given signed request.
   *
   * @param string $signedRequest
   * @param string $state
   *
   * @return FacebookSession
   */
  public static function newSessionFromSignedRequest($signedRequest,
                                                     $state = null)
  {
    $parsedRequest = self::parseSignedRequest($signedRequest, $state);
    if (isset($parsedRequest['code'])
      && !isset($parsedRequest['oauth_token'])) {
      return self::newSessionAfterValidation($parsedRequest);
    }
    return new FacebookSession($parsedRequest['oauth_token']);
  }

  /**
   * newSessionAfterValidation - Returns a FacebookSession for a
   *   validated & parsed signed request.
   *
   * @param array $parsedSignedRequest
   *
   * @return FacebookSession
   *
   * @throws FacebookRequestException
   */
  private static function newSessionAfterValidation($parsedSignedRequest)
  {
    $params = array(
      'client_id' => self::$defaultAppId,
      'redirect_uri' => '',
      'client_secret' =>
        self::$defaultAppSecret,
      'code' => $parsedSignedRequest['code']
    );
    $response = (new FacebookRequest(
      self::newAppSession(
        self::$defaultAppId, self::$defaultAppSecret),
      'GET',
      '/oauth/access_token',
      $params
    ))->execute()->getResponse();
    if (isset($response['access_token'])) {
      return new FacebookSession($response['access_token']);
    }
    throw FacebookRequestException::create(
      json_encode($parsedSignedRequest),
      $parsedSignedRequest,
      401
    );
  }

  /**
   * Parses a signed request.
   *
   * @param string $signedRequest
   * @param string $state
   *
   * @return array
   *
   * @throws FacebookSDKException
   */
  private static function parseSignedRequest($signedRequest, $state)
  {
    if (strpos($signedRequest, '.') !== false) {
      list($encodedSig, $encodedData) = explode('.', $signedRequest, 2);
      $sig = self::_base64UrlDecode($encodedSig);
      $data = json_decode(self::_base64UrlDecode($encodedData), true);
      if (isset($data['algorithm']) && $data['algorithm'] === 'HMAC-SHA256') {
        $expectedSig = hash_hmac(
          'sha256', $encodedData, static::$defaultAppSecret, true
        );
        if (strlen($sig) !== strlen($expectedSig)) {
          throw new FacebookSDKException(
            'Invalid signature on signed request.', 602
          );
        }
        $validate = 0;
        for ($i = 0; $i < strlen($sig); $i++) {
          $validate |= ord($expectedSig[$i]) ^ ord($sig[$i]);
        }
        if ($validate !== 0) {
          throw new FacebookSDKException(
            'Invalid signature on signed request.', 602
          );
        }
        if (!isset($data['oauth_token']) && !isset($data['code'])) {
          throw new FacebookSDKException(
            'Invalid signed request, missing OAuth data.', 603
          );
        }
        if ($state && (!isset($data['state']) || $data['state'] != $state)) {
          throw new FacebookSDKException(
            'Signed request did not pass CSRF validation.', 604
          );
        }
        return $data;
      } else {
        throw new FacebookSDKException(
          'Invalid signed request, using wrong algorithm.', 605
        );
      }
    } else {
      throw new FacebookSDKException(
        'Malformed signed request.', 606
      );
    }
  }

  /**
   * newAppSession - Returns a FacebookSession configured with a token for the
   *   application which can be used for publishing and requesting app-level
   *   information.
   *
   * @param string|null $appId Application ID to use
   * @param string|null $appSecret App secret value to use
   *
   * @return FacebookSession
   */
  public static function newAppSession($appId = null, $appSecret = null)
  {
    $targetAppId = static::_getTargetAppId($appId);
    $targetAppSecret = static::_getTargetAppSecret($appSecret);
    return new FacebookSession(
      $targetAppId . '|' . $targetAppSecret
    );
  }

  /**
   * setDefaultApplication - Will set the static default appId and appSecret
   *   to be used for API requests.
   *
   * @param string $appId Application ID to use by default
   * @param string $appSecret App secret value to use by default
   */
  public static function setDefaultApplication($appId, $appSecret)
  {
    static::$defaultAppId = $appId;
    static::$defaultAppSecret = $appSecret;
  }

  /**
   * _getTargetAppId - Will return either the provided app Id or the default,
   *   throwing if neither are populated.
   *
   * @param string $appId
   *
   * @return string
   *
   * @throws FacebookSDKException
   */
  public static function _getTargetAppId($appId = null) {
    $target = ($appId ?: static::$defaultAppId);
    if (!$target) {
      throw new FacebookSDKException(
        'You must provide or set a default application id.', 700
      );
    }
    return $target;
  }

  /**
   * _getTargetAppSecret - Will return either the provided app secret or the
   *   default, throwing if neither are populated.
   *
   * @param string $appSecret
   *
   * @return string
   *
   * @throws FacebookSDKException
   */
  public static function _getTargetAppSecret($appSecret = null) {
    $target = ($appSecret ?: static::$defaultAppSecret);
    if (!$target) {
      throw new FacebookSDKException(
        'You must provide or set a default application secret.', 701
      );
    }
    return $target;
  }

  /**
   * Base64 decoding which replaces characters:
   *   + instead of -
   *   / instead of _
   * @link http://en.wikipedia.org/wiki/Base64#URL_applications
   *
   * @param string $input base64 url encoded input
   *
   * @return string The decoded string
   */
  public static function _base64UrlDecode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
  }

}

?>

<?php
/**
 * Copyright 2014 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
//namespace Facebook;

/**
 * Class FacebookRedirectLoginHelper
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookRedirectLoginHelper
{

  /**
   * @var string The application id
   */
  private $appId;

  /**
   * @var string The application secret
   */
  private $appSecret;

  /**
   * @var string The redirect URL for the application
   */
  private $redirectUrl;

  /**
   * @var string Prefix to use for session variables
   */
  private $sessionPrefix = 'FBRLH_';

  /**
   * @var string State token for CSRF validation
   */
  protected $state;

  /**
   * Constructs a RedirectLoginHelper for a given appId and redirectUrl.
   *
   * @param string $redirectUrl The URL Facebook should redirect users to
   *                            after login
   * @param string $appId The application id
   * @param string $appSecret The application secret
   */
  public function __construct($redirectUrl, $appId = null, $appSecret = null)
  {
    $this->appId = FacebookSession::_getTargetAppId($appId);
    $this->appSecret = FacebookSession::_getTargetAppSecret($appSecret);
    $this->redirectUrl = $redirectUrl;
  }

  /**
   * Stores CSRF state and returns a URL to which the user should be sent to
   *   in order to continue the login process with Facebook.  The
   *   provided redirectUrl should invoke the handleRedirect method.
   *
   * @param array $scope List of permissions to request during login
   * @param string $version Optional Graph API version if not default (v2.0)
   *
   * @return string
   */
  public function getLoginUrl($scope = array(), $version = null)
  {
    $version = ($version ?: FacebookRequest::GRAPH_API_VERSION);
    $this->state = md5(uniqid(mt_rand(), true));
    $this->storeState($this->state);
    $params = array(
      'client_id' => $this->appId,
      'redirect_uri' => $this->redirectUrl,
      'state' => $this->state,
      'sdk' => 'php-sdk-' . FacebookRequest::VERSION,
      'scope' => implode(',', $scope)
    );
    return 'https://www.facebook.com/' . $version . '/dialog/oauth?' .
      http_build_query($params);
  }

  /**
   * Returns the URL to send the user in order to log out of Facebook.
   *
   * @param FacebookSession $session The session that will be logged out
   * @param string $next The url Facebook should redirect the user to after
   *   a successful logout
   *
   * @return string
   */
  public function getLogoutUrl($session, $next)
  {
    $params = array(
      'next' => $next,
      'access_token' => $session->getToken()
    );
    return 'https://www.facebook.com/logout.php?' . http_build_query($params);
  }

  /**
   * Handles a response from Facebook, including a CSRF check, and returns a
   *   FacebookSession.
   *
   * @return FacebookSession|null
   */
  public function getSessionFromRedirect()
  {
    $this->loadState();
    if ($this->isValidRedirect()) {
      $params = array(
        'client_id' => FacebookSession::_getTargetAppId($this->appId),
        'redirect_uri' => $this->redirectUrl,
        'client_secret' =>
          FacebookSession::_getTargetAppSecret($this->appSecret),
        'code' => $this->getCode()
      );
      $response = (new FacebookRequest(
        FacebookSession::newAppSession($this->appId, $this->appSecret),
        'GET',
        '/oauth/access_token',
        $params
      ))->execute()->getResponse();
      if (isset($response['access_token'])) {
        return new FacebookSession($response['access_token']);
      }
    }
    return null;
  }

  /**
   * Check if a redirect has a valid state.
   *
   * @return bool
   */
  protected function isValidRedirect()
  {
    return $this->getCode() && isset($_GET['state'])
        && $_GET['state'] == $this->state;
  }

  /**
   * Return the code.
   *
   * @return string|null
   */
  protected function getCode()
  {
    return isset($_GET['code']) ? $_GET['code'] : null;
  }

  /**
   * Stores a state string in session storage for CSRF protection.
   * Developers should subclass and override this method if they want to store
   *   this state in a different location.
   *
   * @param string $state
   *
   * @throws FacebookSDKException
   */
  protected function storeState($state)
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      throw new FacebookSDKException(
        'Session not active, could not store state.', 720
      );
    }
    $_SESSION[$this->sessionPrefix . 'state'] = $state;
  }

  /**
   * Loads a state string from session storage for CSRF validation.  May return
   *   null if no object exists.  Developers should subclass and override this
   *   method if they want to load the state from a different location.
   *
   * @return string|null
   *
   * @throws FacebookSDKException
   */
  protected function loadState()
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      throw new FacebookSDKException(
        'Session not active, could not load state.', 721
      );
    }
    if (isset($_SESSION[$this->sessionPrefix . 'state'])) {
      $this->state = $_SESSION[$this->sessionPrefix . 'state'];
      return $this->state;
    }
    return null;
  }

}
?>
<?php
/**
 * Copyright 2014 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
//namespace Facebook;

/**
 * Class FacebookRequest
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookRequest
{

  /**
   * @const string Version number of the Facebook PHP SDK.
   */
  const VERSION = '4.0.4';

  /**
   * @const string Default Graph API version for requests
   */
  const GRAPH_API_VERSION = 'v2.0';

  /**
   * @const string Signed Request Algorithm
   */
  const SIGNED_REQUEST_ALGORITHM = 'HMAC-SHA256';

  /**
   * @const string Graph API URL
   */
  const BASE_GRAPH_URL = 'https://graph.facebook.com';

  /**
   * @const Curl Version which is unaffected by the proxy header length error.
   */
  const CURL_PROXY_QUIRK_VER = 0x071E00;

  /**
   * @const "Connection Established" header text
   */
  const CONNECTION_ESTABLISHED = "HTTP/1.0 200 Connection established\r\n\r\n";

  /**
   * @var FacebookSession The session used for this request
   */
  private $session;

  /**
   * @var string The HTTP method for the request
   */
  private $method;

  /**
   * @var string The path for the request
   */
  private $path;

  /**
   * @var array The parameters for the request
   */
  private $params;

  /**
   * @var string The Graph API version for the request
   */
  private $version;

  /**
   * @var string ETag sent with the request
   */
  private $etag;

  /**
   * getSession - Returns the associated FacebookSession.
   *
   * @return FacebookSession
   */
  public function getSession()
  {
    return $this->session;
  }

  /**
   * getPath - Returns the associated path.
   *
   * @return string
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * getParameters - Returns the associated parameters.
   *
   * @return array
   */
  public function getParameters()
  {
    return $this->params;
  }

  /**
   * getMethod - Returns the associated method.
   *
   * @return string
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * getETag - Returns the ETag sent with the request.
   *
   * @return string
   */
  public function getETag()
  {
    return $this->etag;
  }

  /**
   * FacebookRequest - Returns a new request using the given session.  optional
   *   parameters hash will be sent with the request.  This object is
   *   immutable.
   *
   * @param FacebookSession $session
   * @param string $method
   * @param string $path
   * @param array|null $parameters
   * @param string|null $version
   * @param string|null $etag
   */
  public function __construct(
    $session, $method, $path, $parameters = null, $version = null, $etag = null
  ) {
    $this->session = $session;
    $this->method = $method;
    $this->path = $path;
    if ($version) {
      $this->version = $version;
    } else {
      $this->version = static::GRAPH_API_VERSION;
    }
    $this->etag = $etag;

    $params = ($parameters ?: array());
    if ($session
      && !isset($params["access_token"])) {
      $params["access_token"] = $session->getToken();
    }
    $this->params = $params;
  }

  /**
   * Returns the base Graph URL.
   *
   * @return string
   */
  protected function getRequestURL() {
    return static::BASE_GRAPH_URL . '/' . $this->version . $this->path;
  }

  /**
   * execute - Makes the request to Facebook and returns the result.
   * If Curl is not available, falls back to use Stream
   *
   * @return FacebookResponse
   *
   * @throws FacebookRequestException
   */
  public function execute() {
    $commonOptions = array(
      'timeout' => 60,
      'useragent' => 'fb-php-' . self::VERSION,
      'cafile' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fb_ca_chain_bundle.crt',
    );

    if (function_exists('curl_init')) {
      list($httpStatus, $headers, $result) = $this->executeCurl($commonOptions);
    } else {
      list($httpStatus, $headers, $result) = $this->executeStream($commonOptions);
    }

    $etagHit = 304 == $httpStatus;
    $etagReceived = null;
    if (($etagPos = strpos($headers, 'ETag: ')) !== FALSE) {
      $etagPos += strlen('ETag: ');
      $etagReceived = substr($headers, $etagPos,
        strpos($headers, chr(10), $etagPos)-$etagPos-1);
    }

    $decodedResult = json_decode($result);
    if ($decodedResult === null) {
      $out = array();
      parse_str($result, $out);
      return new FacebookResponse($this, $out, $result, $etagHit, $etagReceived);
    }

    if (isset($decodedResult->error)) {
      throw FacebookRequestException::create(
        $result, $decodedResult->error, $httpStatus
      );
    }

    return new FacebookResponse($this, $decodedResult, $result, $etagHit, $etagReceived);

  }

  /**
   * Makes the request to Facebook using Curl
   *
   * @param array $commonOptions
   * @return array
   * @throws FacebookSDKException
   */
  private function executeCurl($commonOptions) {
    $url = $this->getRequestURL();
    $params = $this->getParameters();
    $curl = curl_init();
    $options = array(
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT        => $commonOptions['timeout'],
      CURLOPT_ENCODING       => '', // Support all available encodings.
      CURLOPT_USERAGENT      => $commonOptions['useragent'],
      CURLOPT_HEADER         => true // Enable header processing
    );
    if ($this->method === "GET") {
      $url = self::appendParamsToUrl($url, $params);
    } else {
      $options[CURLOPT_POSTFIELDS] = $params;
    }
    if ($this->method === 'DELETE' || $this->method === 'PUT') {
      $options[CURLOPT_CUSTOMREQUEST] = $this->method;
    }
    $options[CURLOPT_URL] = $url;

    // ETag
    if ($this->etag != null) {
      $options[CURLOPT_HTTPHEADER] = array('If-None-Match: '.$this->etag);
    }
    curl_setopt_array($curl, $options);

    $rawResult = curl_exec($curl);
    $error = curl_errno($curl);

    if ($error == 60 || $error == 77) {
      curl_setopt($curl, CURLOPT_CAINFO,
        dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fb_ca_chain_bundle.crt');
      $rawResult = curl_exec($curl);
      $error = curl_errno($curl);
    }

    // With dual stacked DNS responses, it's possible for a server to
    // have IPv6 enabled but not have IPv6 connectivity.  If this is
    // the case, curl will try IPv4 first and if that fails, then it will
    // fall back to IPv6 and the error EHOSTUNREACH is returned by the
    // operating system.
    if ($rawResult === false && empty($opts[CURLOPT_IPRESOLVE])) {
      $matches = array();
      $regex = '/Failed to connect to ([^:].*): Network is unreachable/';
      if (preg_match($regex, curl_error($curl), $matches)) {
        if (strlen(@inet_pton($matches[1])) === 16) {
          error_log(
            'Invalid IPv6 configuration on server, ' .
            'Please disable or get native IPv6 on your server.'
          );
          curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
          $rawResult = curl_exec($curl);
          $error = curl_errno($curl);
        }
      }
    }

    $errorMessage = curl_error($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    curl_close($curl);

    if ($rawResult === false) {
      throw new FacebookSDKException($errorMessage, $error);
    }

    // This corrects a Curl bug where header size does not account
    // for additional Proxy headers.
    if ( self::needsCurlProxyFix() ) {
      if ( stripos($rawResult, self::CONNECTION_ESTABLISHED) !== false ) {
        $headerSize += strlen(self::CONNECTION_ESTABLISHED);
      }
    }

    $headers = mb_substr($rawResult, 0, $headerSize);
    $result = mb_substr($rawResult, $headerSize);

    return array($httpStatus, $headers, $result);
  }

  /**
   * Makes the request to Facebook using Streams
   *
   * @param $commonOptions
   * @return array
   * @throws FacebookSDKException
   */
  private function executeStream($commonOptions) {
    $url = $this->getRequestURL();
    $params = $this->getParameters();

    $options = array(
      'http'=>array(
        'method'=> $this->method,
        'user-agent'=> $commonOptions['useragent'],
        'timeout'=> $commonOptions['timeout'],
        'ignore_errors' => true
      ),
      'ssl'=>array(
        'verify_peer'=> true,
        'cafile' => $commonOptions['cafile'],
      )
    );

    if ($this->method === "GET") {
      $url = self::appendParamsToUrl($url, $params);
    } else {
      $options['http']['content'] = http_build_query($params);
    }

    // ETag
    if ($this->etag != null) {
      $options['http']['header'] = 'If-None-Match: '.$this->etag;
    }

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === false || empty($http_response_header)) {
      throw new FacebookSDKException('STREAM_GOT_NOTHING', 52);
    }

    $httpHeader = explode(' ', $http_response_header[0], 3);
    $httpStatus = $httpHeader[1];
    $headers = implode("\r\n", $http_response_header);

    return array($httpStatus, $headers, $result);
  }

  /**
   * appendParamsToUrl - Gracefully appends params to the URL.
   *
   * @param string $url
   * @param array $params
   *
   * @return string
   */
  public static function appendParamsToUrl($url, $params = array())
  {
    if (!$params) {
      return $url;
    }

    if (strpos($url, '?') === false) {
      return $url . '?' . http_build_query($params);
    }

    list($path, $query_string) = explode('?', $url, 2);
    parse_str($query_string, $query_array);

    // Favor params from the original URL over $params
    $params = array_merge($params, $query_array);

    return $path . '?' . http_build_query($params);
  }

  /**
   * needsCurlProxyFix - Detect versions of Curl which report
   *   incorrect header lengths when using Proxies.
   *
   * @return boolean
   */
  private static function needsCurlProxyFix() {
    $ver = curl_version();
    $version = $ver['version_number'];

    return $version < self::CURL_PROXY_QUIRK_VER;
  }
}
?>


<?php
/**
 * Copyright 2014 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
//namespace Facebook;

/**
 * Class GraphObject
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphObject
{

  /**
   * @var array - Holds the raw associative data for this object
   */
  protected $backingData;

  /**
   * Creates a GraphObject using the data provided.
   *
   * @param array $raw
   */
  public function __construct($raw)
  {
    if ($raw instanceof \stdClass) {
      $raw = get_object_vars($raw);
    }
    $this->backingData = $raw;

    if (isset($this->backingData['data']) && count($this->backingData) === 1) {
      $this->backingData = $this->backingData['data'];
    }
  }

  /**
   * cast - Return a new instance of a FacebookGraphObject subclass for this
   *   objects underlying data.
   *
   * @param string $type The GraphObject subclass to cast to
   *
   * @return GraphObject
   *
   * @throws FacebookSDKException
   */
  public function cast($type)
  {
    if ($this instanceof $type) {
      return $this;
    }
    if (is_subclass_of($type, GraphObject::className())) {
      return new $type($this->backingData);
    } else {
      throw new FacebookSDKException(
        'Cannot cast to an object that is not a GraphObject subclass', 620
      );
    }
  }

  /**
   * asArray - Return a key-value associative array for the given graph object.
   *
   * @return array
   */
  public function asArray()
  {
    return $this->backingData;
  }

  /**
   * getProperty - Gets the value of the named property for this graph object,
   *   cast to the appropriate subclass type if provided.
   *
   * @param string $name The property to retrieve
   * @param string $type The subclass of GraphObject, optionally
   *
   * @return mixed
   */
  public function getProperty($name, $type = 'Facebook\GraphObject')
  {
    if (isset($this->backingData[$name])) {
      $value = $this->backingData[$name];
      if (is_scalar($value)) {
        return $value;
      } else {
        return (new GraphObject($value))->cast($type);
      }
    } else {
      return null;
    }
  }

  /**
   * getPropertyAsArray - Get the list value of a named property for this graph
   *   object, where each item has been cast to the appropriate subclass type
   *   if provided.
   *
   * Calling this for a property that is not an array, the behavior
   *   is undefined, so don’t do this.
   *
   * @param string $name The property to retrieve
   * @param string $type The subclass of GraphObject, optionally
   *
   * @return array
   */
  public function getPropertyAsArray($name, $type = 'Facebook\GraphObject')
  {
    $target = array();
    if (isset($this->backingData[$name]['data'])) {
      $target = $this->backingData[$name]['data'];
    } else if (isset($this->backingData[$name])
      && !is_scalar($this->backingData[$name])) {
      $target = $this->backingData[$name];
    }
    $out = array();
    foreach ($target as $key => $value) {
      if (is_scalar($value)) {
        $out[$key] = $value;
      } else {
        $out[$key] = (new GraphObject($value))->cast($type);
      }
    }
    return $out;
  }

  /**
   * getPropertyNames - Returns a list of all properties set on the object.
   *
   * @return array
   */
  public function getPropertyNames()
  {
    return array_keys($this->backingData);
  }

  /**
   * Returns the string class name of the GraphObject or subclass.
   *
   * @return string
   */
  public static function className()
  {
    return get_called_class();
  }

}
?>

<?php
/**
 * Copyright 2014 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
//namespace Facebook;

/**
 * Class FacebookRequestException
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookRequestException extends FacebookSDKException
{

  /**
   * @var int Status code for the response causing the exception
   */
  private $statusCode;

  /**
   * @var string Raw response
   */
  private $rawResponse;

  /**
   * @var array Decoded response
   */
  private $responseData;

  /**
   * Creates a FacebookRequestException.
   *
   * @param string $rawResponse The raw response from the Graph API
   * @param array $responseData The decoded response from the Graph API
   * @param int $statusCode
   */
  public function __construct($rawResponse, $responseData, $statusCode)
  {
    $this->rawResponse = $rawResponse;
    $this->statusCode = $statusCode;
    $this->responseData = self::convertToArray($responseData);
    parent::__construct(
      $this->get('message', 'Unknown Exception'), $this->get('code', -1), null
    );
  }

  /**
   * Process an error payload from the Graph API and return the appropriate
   *   exception subclass.
   *
   * @param string $raw the raw response from the Graph API
   * @param array $data the decoded response from the Graph API
   * @param int $statusCode the HTTP response code
   *
   * @return FacebookRequestException
   */
  public static function create($raw, $data, $statusCode)
  {
    $data = self::convertToArray($data);
    if (!isset($data['error']['code']) && isset($data['code'])) {
      $data = array('error' => $data);
    }
    $code = (isset($data['error']['code']) ? $data['error']['code'] : null);

    if (isset($data['error']['error_subcode'])) {
      switch ($data['error']['error_subcode']) {
        // Other authentication issues
        case 458:
        case 459:
        case 460:
        case 463:
        case 464:
        case 467:
          return new FacebookAuthorizationException($raw, $data, $statusCode);
          break;
      }
    }

    switch ($code) {
      // Login status or token expired, revoked, or invalid
      case 100:
      case 102:
      case 190:
        return new FacebookAuthorizationException($raw, $data, $statusCode);
        break;

      // Server issue, possible downtime
      case 1:
      case 2:
        return new FacebookServerException($raw, $data, $statusCode);
        break;

      // API Throttling
      case 4:
      case 17:
      case 341:
        return new FacebookThrottleException($raw, $data, $statusCode);
        break;

      // Duplicate Post
      case 506:
        return new FacebookClientException($raw, $data, $statusCode);
        break;
    }

    // Missing Permissions
    if ($code == 10 || ($code >= 200 && $code <= 299)) {
      return new FacebookPermissionException($raw, $data, $statusCode);
    }

    // OAuth authentication error
    if (isset($data['error']['type'])
      and $data['error']['type'] === 'OAuthException') {
      return new FacebookAuthorizationException($raw, $data, $statusCode);
    }

    // All others
    return new FacebookOtherException($raw, $data, $statusCode);
  }

  /**
   * Checks isset and returns that or a default value.
   *
   * @param string $key
   * @param mixed $default
   *
   * @return mixed
   */
  private function get($key, $default = null)
  {
    if (isset($this->responseData['error'][$key])) {
      return $this->responseData['error'][$key];
    }
    return $default;
  }

  /**
   * Returns the HTTP status code
   *
   * @return int
   */
  public function getHttpStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * Returns the sub-error code
   *
   * @return int
   */
  public function getSubErrorCode()
  {
    return $this->get('error_subcode', -1);
  }

  /**
   * Returns the error type
   *
   * @return string
   */
  public function getErrorType()
  {
    return $this->get('type', '');
  }

  /**
   * Returns the raw response used to create the exception.
   *
   * @return string
   */
  public function getRawResponse()
  {
    return $this->rawResponse;
  }

  /**
   * Returns the decoded response used to create the exception.
   *
   * @return array
   */
  public function getResponse()
  {
    return $this->responseData;
  }

  /**
   * Converts a stdClass object to an array
   *
   * @param mixed $object
   *
   * @return array
   */
  private static function convertToArray($object)
  {
    if ($object instanceof \stdClass) {
      return get_object_vars($object);
    }
    return $object;
  }

}
?>
