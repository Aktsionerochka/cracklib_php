<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

    /**
    * CodeIgniter cracklib-check Helper
    *
    * @package      CodeIgniter
    * @subpackage   Helpers
    * @category     Helpers
    * @author       Alexandru Mos
    * @link         http://alexandrumos.com
    */

    /**
    * cracklib-check
    *
    * Password checker using cracklib2 library
    *
    * @access  public
    * @param   string
    * @param   string
    * @param   integer
    * @param   string
    * @return  string
    */

    function cracklib_check($password)
    {
    
        $command = 'echo '.escapeshellcmd($password).' | /usr/sbin/cracklib-check';

        $result = shell_exec($command);
        
        if ($result === null)
        {
            trigger_error('Can\'t run cracklib-check. Make sure that is installed on your system. For DEB based distros this command might help: apt-get install cracklib-runtime', E_USER_ERROR);
        }
        else
        {
            $parts = explode(':', $result);
            $cracklib_msg = trim($parts[1]);
            
            if (preg_match('/it does not contain enough DIFFERENT characters/i', $cracklib_msg))
            {
                return array('good_password' => 0, 'status_code' => 10, 'cracklib_msg' => $cracklib_msg);
            }
            elseif (preg_match('/it is WAY too short/i', $cracklib_msg))
            {
                return array('good_password' => 0, 'status_code' => 11, 'cracklib_msg' => $cracklib_msg);
            }
            elseif (preg_match('/it is too short/i', $cracklib_msg))
            {
                return array('good_password' => 0, 'status_code' => 12, 'cracklib_msg' => $cracklib_msg);
            }
            elseif (preg_match('/it is too simplistic\/systematic/i', $cracklib_msg))
            {
                return array('good_password' => 0, 'status_code' => 13, 'cracklib_msg' => $cracklib_msg);
            }
            elseif (preg_match('/it is based on a dictionary word/i', $cracklib_msg))
            {
                return array('good_password' => 0, 'status_code' => 14, 'cracklib_msg' => $cracklib_msg);
            }
            elseif (preg_match('/ok/i', $cracklib_msg))
            {
                return array('good_password' => 1, 'status_code' => 1, 'cracklib_msg' => $cracklib_msg);
            }
            else
            {
                return array('good_password' => 0, 'status_code' => 15, 'cracklib_msg' => '???');
            }
        }
    }

?>
