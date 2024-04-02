<?php

declare(strict_types=1);
/**
 * End User License Agreement ("EULA") *
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

use Spatie\Url\Url;

class HTTPRequest extends Url
{
   private static $_isInit = false;
   const filters = array(
      'Accept',
      'Accept-Language',
      'Accept-Encoding',
      'Sec-Fetch-Dest',
      'Sec-Fetch-Mode',
      'Sec-Fetch-User'
   );
   protected static function init()
   {
      if (!self::$_isInit) {
         //do init
      }
   }
   public function __construct()
   {
      parent::__construct();
      $toUrl = Url::fromString($_SERVER['REQUEST_URI']);
      $this->scheme = $toUrl->getScheme();
      $this->host = $toUrl->getHost();
      $this->port = $toUrl->getPort();
      $this->user = $toUrl->user;
      $this->password = $toUrl->password;
      $this->path = $toUrl->getPath();
      $this->query = $toUrl->getQuery();
      $this->fragment = $toUrl->getFragment();
   }

   public static function getHttpMethod(): string
   {
      self::init();
      return $_SERVER['REQUEST_METHOD'] ?? "GET";
   }
   public function get($name)
   {
      $headers = getallheaders();
      $contentType = $headers["Content-Type"] ?? $headers["content-type"] ?? "text/html";
      $contentType = strtolower($contentType);
      if (($pos = strpos($contentType, ';')) != false) {
         $contentType = substr($contentType, 0, $pos);
      }
      switch ($contentType) {
         case 'application/json':
            return $this->_getJSONrequest($name);
            break;
         case 'text/html':
         case 'application/x-www-form-urlencoded':
            return $_REQUEST[$name] ?? null;
            break;
         default:
            throw new InvalidDataReceivedException();
            /** FIXME: here below do something */
            // Unsupported content type
            // http_response_code(415); // Unsupported Media Type
            // echo "Unsupported content type: $contentType";
            // No POST data received
            //http_response_code(400); // Bad Request
            //echo "No POST data received";
      }
   }

   private function _getJSONrequest($name)
   {
      $json_data = file_get_contents('php://input');

      // Check if data was received
      if ($json_data !== false) {
         $data = json_decode($json_data, true);
         // Check if JSON decoding was successful
         if ($data !== null) {
            // Process the JSON data
            return $data[$name]??null;
         } 
      }
      return null;
   }
   private function _headers()
   {
      $headers = getallheaders();
      $new = array();
      foreach ($headers as $name => $value) {
         foreach (self::filters as $filter) {
            if (strcasecmp($filter,  $name) == 0) {
               $new = array_merge($new, array($name => $value));
               break;
            }
         }
      }
      return $new;
   }
   public function __get($name)
   {
      $name = '_' . $name;
      if ($name == '_headers') {
         return $this->_headers();
      }
      $ref = new ReflectionObject($this);
      $v = null;
      try {
         $prop = $ref->getProperty($name);
         $v = $prop->getValue($this);
         if ($v instanceof StdClass) {
            return new Environment($v);
         }
         return $v;
      } catch (Exception $e) {
         return null;
      }
   }
}
