<?php
declare(strict_types=1);
/**
 * Router for vefifcation for Phone number.
 *
 * This End User License Agreement ("EULA") governs the use of the property software ("Software") provided by [Software Owner], * hereinafter referred to as the "Owner."
 * 1. The Owner hereby grants you a non-exclusive, non-transferable license to use the Software in accordance with the terms and conditions set forth in this EULA.
 * 2. You may not copy, modify, distribute, sell, sublicense, or reverse engineer the Software. You may not use the Software for any illegal or unauthorized purpose.
 * 3. The Software is the property of the Owner and is protected by copyright laws and international treaty provisions. You acknowledge that you have no ownership rights to the Software.
 * The Software is provided "as is," without any warranties or guarantees of any kind. The Owner disclaims all warranties, express or implied, including but not limited to the implied warranties of merchantability and fitness for a particular purpose.
 * 5. In no event shall the Owner be liable for any damages, including but not limited to direct, indirect, incidental, special, or consequential damages arising out of the use or inability to use the Software, even if the Owner has been advised of the possibility of such damages.
 * 6. You agree to indemnify and hold harmless the Owner from any claims, damages, liabilities, costs, or expenses arising out of your use of the Software in violation of this EULA.
 * This EULA shall be governed by and construed in accordance with the laws of [Jurisdiction]. Any disputes arising under or in connection with this EULA shall be subject to the exclusive jurisdiction of the courts of [Jurisdiction].
 * 8. This EULA constitutes the entire agreement between you and the Owner regarding the use of the Software and supersedes all prior or contemporaneous agreements and understandings, whether oral or written.
 * 9. If any provision of this EULA is found to be invalid or unenforceable, the remaining provisions shall remain in full force and effect.
 * 10. By installing, copying, or otherwise using the Software, you acknowledge that you have read, understood, and agreed to be bound by the terms and conditions of this EULA.
 *
 * Written By Akanksha Adhikary(LADYHART)
 *
 * @contact: ladyhart@protonmail.com
 * @date: March 28, 2024
 * @copyright     Copyright (c) Akanksha Adhikary (https://ladyhart.org)
 * @link          https://ladyhart.in/
 * @since         1.1.6
 * @license       https://ladyhart.org/licenses/own GPL3 License
 *
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 */
 define('APP_ROOT', function_exists('realpath')? dirname(realpath(basename(__FILE__))):dirname(__FILE__) );
 if(!function_exists('path_join')){
   function path_join( string $base, $paths ): array {
     if ( is_string($paths) ) {
       $paths = array($paths);
	}
	return array_map( fn($p)=> $base . DIRECTORY_SEPARATOR . $p, $paths);
  }
 }
 require(path_join(APP_ROOT, 'vendor/autoload.php')[0]);
 require(path_join(APP_ROOT, 'vendor/_autoload.php')[0]);
 require(path_join(APP_ROOT, 'vendor/config/environ.php')[0]);
 require('vendor/config.php');
//  // ini_set(display)
//  // Display errors
//  ini_set('display_errors', 1);
//  ini_set('display_startup_errors', 1);
//  // Memory limit
//  ini_set('memory_limit', '256M');
//  // Timezone
//  ini_set('date.timezone', 'Your/Timezone')
//  // Maximum execution time
//  ini_set('max_execution_time', 60);
//  // Maximum file upload size
//  ini_set('upload_max_filesize', '32M');
//  // Maximum post size
//  ini_set('post_max_size', '32M');
//  // Session timeout
//  ini_set('session.gc_maxlifetime', 3600);
//
//  ini_set('error_log', '/path/to/error.log');
//
//  // Set error reporting level
//  error_reporting(E_ALL);
//  // Other configuration settings...
//
  define('ENV_', defined('PHP_ENVIRON')? PHP_ENVIRON:$_ENV);
  @define('DEBUG', false);

  /* namespace ladyhart.php; */
  use PharIo\Version\Version;
 use PharIo\Version\VersionConstraintParser;
 use \infobip\OneApiConfigurator;
 use OTPHP\TOTP as TimeBaseSecurePassword;
 use ParagonIE\ConstantTime\Base32;
 use \infobip\models\SMSRequest;

 if (!class_exists('_7')) {
   class _7 {
       public function __call($method, $args) {
          return NULL;
      }
    }
 }

  $checker = new VersionConstraintParser();
  //Config::add();
  $caret_constraint = $checker->parse( '^7.0' );
  $caret_constraint->complies( new Version( '7.0.17' ) );
  $caret_constraint->complies( new Version( '7.1.0' ) );
  $caret_constraint->complies( new Version( '6.4.34' ) );
  $checker->_version = ($checker->version=new Version(PHP_VERSION)) ?? $caret_constraint;
  $tilde_constraint = $checker->parse( '~1.1.0' );
  $tilde_constraint->complies( new Version( '1.1.4' ) );
  $tilde_constraint->complies( new Version( '1.2.0' ) );

  /* fix here: hide this*/
  //$checker->implies = array(ENV_vREQUIREDV, ENV_vREQUIREdv);
  $checker->vREQUIREDV = ENV_vREQUIREDV;
  $checker->vREQUIREdv = ENV_vREQUIREdv;
  /// fix end


  if((str_replace('.'.$checker->_version->getPatch()->getValue(), NULL,$checker->_version->getOriginalString()) <=> substr( rtrim($checker->vREQUIREDV,"\x25"), 0,-1-strpos(strrev(rtrim($checker->_version->getOriginalString(),"\x25")), '.') )) > ($checker->_version->getPatch()->getValue() <=> ltrim(preg_split(ENV_PHP_VERSION, $checker->vREQUIREdv, -1, PREG_SPLIT_NO_EMPTY)[0], '.')) && (str_replace('.'.$checker->_version->getPatch()->getValue(), NULL,$checker->_version->getOriginalString()) <=> substr( rtrim($checker->vREQUIREdv,"\x25"), 0,-1-strpos(strrev(rtrim($checker->_version->getOriginalString(),"\x25")), '.') )) >= ($checker->_version->getPatch()->getValue() <=> ltrim(preg_split(ENV_PHP_VERSION, $checker->vREQUIREdv, -1, PREG_SPLIT_NO_EMPTY)[0], '.')) ){
    // fix here
    //PHP_CONFIG::loadConfig()
  }

  if( preg_last_error() ){
    // fix here
    //PHP_CONFIG::logError()
    // throw Exception()
  }
//
//   // set all error handler here
//  //namespace
//  ///
  // fix here
  //
  require('lib/dispatcher.php');
  require('lib/httpresponse.php');
  require('lib/httprequest.php');
  require('lib/formmodel.php');
  require('lib/exception.php');
  require('lib/mysqlconnect.php');
  //
  $tokenizer = new TheSeer\Tokenizer\Tokenizer();
  //$tokens = $tokenizer->parse(file_get_contents(__DIR__ . '/src/XMLSerializer.php'));
  $serializer = new TheSeer\Tokenizer\XMLSerializer();
  //$xml = $serializer->toXML($tokens);

 $app = new Dispatcher(new HTTPRequest(), new HTTPResponse());
 $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/veryfynumberNow', 'verifyNumber');
    // {id} must be a number (\d+)
    //$r->addRoute('GET', '/veryfynumberNow', 'get_user_handler');
    // The /{title} suffix is optional
    //$r->addRoute('GET', '/veryfynumberNow', 'get_article_handler');
 });
 //$app->loadConfig();
 $routeInfo = $dispatcher->dispatch($app->httpMethod, $app->getPath());
 $otp = TimeBaseSecurePassword::create( Base32::encodeUpper(SECRET_KEY),   // Let the secret be defined by the class
    300,     // The period (5 min)
    'sha1', // The digest algorithm
    6       // The output will generate 8 digits
 );
 $app->timePeriond = time();
 $openAPI = new OneApiConfigurator();
 $openAPI->api_key = SMS_AUTH_KEY;
 $smsapi = new SMSRequest();
 $smsapi->senderAddress = 'fast2sms';
 $smsapi->address = 'smsapi:fast2sms.com:RESTAPI';
 $smsapi->message = $otp->at($app->timePeriond);
 $smsapi->senser_id = 'FSTSMS';
 $smsapi->flash = SEND_FLASH_SMS;
 $smsapi->serviceVerify = $otp;
 switch ($routeInfo[0]) {
     case FastRoute\Dispatcher::NOT_FOUND:
          throw new ServiceNotFoundException();
          break;
     case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
          $allowedMethods = $routeInfo[1];
          throw MethodNotAllowedException();
          break;
     case FastRoute\Dispatcher::FOUND:
          $app->event('begin', new WeakMap( array('smsapi'=>$smsapi, 'openAPI'=>$openAPI) )/*->getIterator()*/)->dispatch(FormModel::class, $dispatcher)->event('verifyNumber')->end();
 }
 $app->cache('flush');
