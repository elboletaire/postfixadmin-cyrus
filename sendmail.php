<?php
/** 
 * Postfix Admin 
 * 
 * LICENSE 
 * This source file is subject to the GPL license that is bundled with  
 * this package in the file LICENSE.TXT. 
 * 
 * Further details on the project are available at : 
 *     http://www.postfixadmin.com or http://postfixadmin.sf.net 
 * 
 * @version $Id: sendmail.php 1094 2011-07-19 21:41:37Z christian_boltz $ 
 * @license GNU GPL v2 or later. 
 * 
 * File: sendmail.php
 * Used to send an email to a user.
 * Template File: sendmail.tpl
 *
 * Template Variables:
 *
 * tFrom
 * tSubject
 * tBody
 *
 * Form POST \ GET Variables:
 *
 * fTo
 * fSubject
 * fBody
 */

require_once('common.php');

authentication_require_role('admin');

(($CONF['sendmail'] == 'NO') ? header("Location: main.php") && exit : '1');

$smtp_from_email = smtp_get_admin_email();


if ($_SERVER['REQUEST_METHOD'] == "POST")
{
   $fTo = safepost('fTo');
   $fFrom = $smtp_from_email;
   $fSubject = safepost('fSubject');

   $tBody = $_POST['fBody'];
   if (get_magic_quotes_gpc ())
   {
      $tBody = stripslashes($tBody); # TODO: check for get_magic_quotes_gpc inside safepost/safeget
   }

   if (empty ($fTo) or !check_email ($fTo))
   {
      $error = 1;
      $tTo = escape_string ($_POST['fTo']);
      $tSubject = escape_string ($_POST['fSubject']);
      flash_error($PALANG['pSendmail_to_text_error']);
   }

   if ($error != 1)
   {
      if (!smtp_mail ($fTo, $fFrom, $fSubject, $tBody)) {
         flash_error($PALANG['pSendmail_result_error']);
      } else {
         flash_info($PALANG['pSendmail_result_success']);
      }
   }
}
$smarty->assign ('smtp_from_email', $smtp_from_email);
$smarty->assign ('smarty_template', 'sendmail');
$smarty->display ('index.tpl');


/* vim: set expandtab softtabstop=3 tabstop=3 shiftwidth=3: */
?>
