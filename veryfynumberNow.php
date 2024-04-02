<?php

declare(strict_types=1);
/**
 * FIXME: License declaration here
 */
/*#<!-- LICENSE.txt -->#*/

/**
 * FIXME: Write code all ini_set and error_reporting
 */

define('HTTP_ROOT', function_exists('realpath') ? dirname(realpath(basename(__FILE__))) : dirname(__FILE__));
define('APP_ROOT', HTTP_ROOT. DIRECTORY_SEPARATOR);
if (!function_exists('path_join')) {
  function path_join(string $base, $paths): array
  {
    if (is_string($paths)) {
      $paths = array($paths);
    }
    return array_map(fn ($p) => $base . DIRECTORY_SEPARATOR . $p, $paths);
  }
}
require(path_join(APP_ROOT, 'vendor/autoload.php')[0]);
/**
 * FIXME: remove _autoload.php file. and add this to autoload.php
 */
require(path_join(APP_ROOT, 'vendor/_autoload.php')[0]);
require(path_join(APP_ROOT, 'vendor/config/environ.php')[0]);
require('vendor/config.php');
/**
 * FIXME: remove below line from prod
 **/
require('_require.php');

define('ENV_', defined('PHP_ENVIRON') ? PHP_ENVIRON : $_ENV);

use PharIo\Version\Version;
use PharIo\Version\VersionConstraintParser;
use \infobip\OneApiConfigurator;
use OTPHP\TOTP as TimeBaseSecurePassword;
use ParagonIE\ConstantTime\Base32;
use \infobip\models\SMSRequest;

if (!class_exists('_7')) {
  class _7
  {
    public function __call($method, $args)
    {
      return NULL;
    }
  }
}

$checker = new VersionConstraintParser();
$caret_constraint = $checker->parse('^7.0');
$caret_constraint->complies(new Version('7.0.17'));
$caret_constraint->complies(new Version('7.1.0'));
$caret_constraint->complies(new Version('6.4.34'));

/**
 * FIXME: hide nad integrate it within mother file.
 */
$checker->_version = ($checker->version = new Version(PHP_VERSION)) ?? $caret_constraint;

$tilde_constraint = $checker->parse('~1.1.0');
$tilde_constraint->complies(new Version('1.1.4'));
$tilde_constraint->complies(new Version('1.2.0'));

/**
 * FIXME: hide below 2 lines.
 */
$checker->vREQUIREDV = ENV_vREQUIREDV;
$checker->vREQUIREdv = ENV_vREQUIREdv;

/**
 * FIXME: too big line and empty 'if' logic
 **/
if ((str_replace('.' . $checker->_version->getPatch()->getValue(), NULL, $checker->_version->getOriginalString()) <=> substr(rtrim($checker->vREQUIREDV, "\x25"), 0, -1 - strpos(strrev(rtrim($checker->_version->getOriginalString(), "\x25")), '.'))) > ($checker->_version->getPatch()->getValue() <=> ltrim(preg_split(ENV_PHP_VERSION, $checker->vREQUIREdv, -1, PREG_SPLIT_NO_EMPTY)[0], '.')) && (str_replace('.' . $checker->_version->getPatch()->getValue(), NULL, $checker->_version->getOriginalString()) <=> substr(rtrim($checker->vREQUIREdv, "\x25"), 0, -1 - strpos(strrev(rtrim($checker->_version->getOriginalString(), "\x25")), '.'))) >= ($checker->_version->getPatch()->getValue() <=> ltrim(preg_split(ENV_PHP_VERSION, $checker->vREQUIREdv, -1, PREG_SPLIT_NO_EMPTY)[0], '.'))) {
  // fix here
  //PHP_CONFIG::loadConfig()
}

if (preg_last_error()) {
  /**
   * FIXME: Don't know what to do
   **/
  //PHP_CONFIG::logError()
  // throw Exception()
}
/**
 * FIXME: Don't know what to do
 **/
$tokenizer = new TheSeer\Tokenizer\Tokenizer();
//$tokens = $tokenizer->parse(file_get_contents(__DIR__ . '/src/XMLSerializer.php'));
$serializer = new TheSeer\Tokenizer\XMLSerializer();
//$xml = $serializer->toXML($tokens);

$app = new Dispatcher(new HTTPRequest(), new HTTPResponse());
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
  $r->addRoute('POST', '/veryfynumberNow', 'verifyNumber');
  $r->addRoute('POST', '/chkmobilenumberNow', 'checkNumber');
  $r->addRoute('POST', '/registerNow', 'registerUser');
  $r->addRoute('POST', '/signup', 'signUp');
});

/**
 * TODO: work from here
 */
$routeInfo = $dispatcher->dispatch(Dispatcher::$httpMethod, $app->getPath());
$otp = TimeBaseSecurePassword::create(
  Base32::encodeUpper(SECRET_KEY),   // Let the secret be defined by the class
  SYS_TIMESTAMP,     // The period (5 min)
  'sha1', // The digest algorithm
  6       // The output will generate 8 digits
);
$openAPI = new OneApiConfigurator();
$openAPI->api_key = SMS_AUTH_KEY;
$smsapi = new SMSRequest();
$smsapi->senderAddress = 'fast2sms';
$smsapi->address = 'smsapi:fast2sms.com:RESTAPI';
$smsapi->message = $otp->at($app->timePeriod);
$smsapi->senser_id = 'FSTSMS';
$smsapi->flash = SMS_SEND_FLASH;
$smsapi->serviceVerify = $otp;
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:
    throw new ServiceNotFoundException();
    break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
    $allowedMethods = $routeInfo[1];
    throw new MethodNotAllowedException();
    break;
  case FastRoute\Dispatcher::FOUND:
    $app->event('begin', new WeakMap(array('smsapi' => $smsapi, 'openAPI' => $openAPI))/*->getIterator()*/)->dispatch(FormModel::class, $dispatcher)->event('verifyNumber')->end();
}
$app->cache('flush');
