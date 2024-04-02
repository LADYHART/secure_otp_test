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

use infobip\models\SMSRequest;

class FormModel
{
   private $_environ;
   private $_session;
   private $_request;

   /** TODO: load form structure */
   private $_form;

   public function __construct($dispatcher)
   {
      $this->_environ = $dispatcher->Environ;
      $this->_request = $dispatcher->Request;
      $this->_session = Dispatcher::getSession($dispatcher);
   }


   private function validate(): bool
   {

      // // write a generator here
      // foreach($input in $config->form){
      //     if( !preg_match() ){
      //         break;
      //     }
      // }
      return true;
   }


   private function _send_otp($smsapi, $mobile)
   {
      $otp = trim($smsapi->message);
      $GuesColourResponse = $this->_environ->guescolourresponse;
      try {
         $connection = new MySQLConnect();
         $d = $connection->table('tbl_user')->select()->where(['mobile' => $mobile]);
         if ($d) {
            $this->_session->set('signup_mobile', $mobile);
            $this->_session->set('signup_otp', $otp);
            $connection->table('opt_tbl')->insertOne(['otp' => $otp]);
            ///
            $this->_session->set('is_ready', true);
            ///
         } else {
            return $GuesColourResponse->Failed;
         }
      } catch (MySQLException $e) {
         throw new RunTimeException($e);
      }
      try {
         if ($this->_session->get('is_ready')) {
            // get data from above
            // $runner = new SimpleObjectAccessProtocol();
            // if($runner->isGood()){
            //    return $GuesColourResponse->WaitForVerify;
            // }
         }
         return $GuesColourResponse->Failed;
      } catch (SOAPException $e) {

         //match
         if ($e->code == $GuesColourResponse->InvalidNumber) {
            return $GuesColourResponse->InvalidNumber;
         }
      }
      return GuesColourResponse::Failed;
   }

   private function _verify_otp($smsapi, $otp)
   {
      $serviceVerify = $smsapi->serviceVerify;
      if ($serviceVerify->verify($otp)) {
         $this->_session->set('signup_otp');
         return $GuesColourResponse->Success;
      }
      $this->_session->set('signup_mobilematched', $this->_session->get('signup_mobile'));
      return $GuesColourResponse->Failed;
   }

   public function verifyNumber(SMSRequest $smsapi)
   {
      $GuesColourResponse = $this->_environ->guescolourresponse;
      if ($this->_request->get('type') == $this->_environ->RequestType->MOBILE) {
         /**
          * FIXME: serverside validate required
          */
         $mobile = $this->_request->get('mobile');
         return $this->_send_otp($smsapi, $mobile);

      } else if ($this->_request->get('type') == $this->_environ->RequestType->OTPVAL) {
         $otp = $this->_request->get('otp');
         return $this->_verify_otp($smsapi, $otp);
      }
      throw new InvalidDataReceivedException();
   }
}
