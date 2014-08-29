<?php
/**
 * Exception Class for Services_Backlog
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 */

/**
 * Services_Backlog_Exception
 *
 * @package    Services_Backlog
 * @author     devworks <smoochynet@gmail.com>
 */
class Services_Backlog_Exception extends Exception {
    /**
     * コンストラクタ
     * 
     * @param string $message
     * @param integer $code
     */
    public function __construct($message = null, $code = 0) {
        parent::__construct($message, intval($code));
    }
}
?>
