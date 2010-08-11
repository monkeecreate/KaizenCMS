<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {footer} block plugin
 *
 * Filename: block_footer.php
 * Type:     block
 * Name:     footer
 * Date:     April 28, 2006
 * Purpose:  move all content in footer blocks to the body of the html document
 *
 * Examples:
 * {footer}
 *    <script type="text/javascript">
 * 		// Javascript loads last
 *    </script>
 * {/footer}
 * @author John Hoover <defvayne23@gmail.com>
 * @version  0.1
 * @param array
 * @param string
 * @param Smarty
 * @param boolean
 * @return string
 */
function smarty_block_footer($params, $content, &$smarty, &$repeat){
    if ( empty($content) ) {
        return;
    }
    return '@@@SMARTY:FOOTER:BEGIN@@@'.trim($content).'@@@SMARTY:FOOTER:END@@@';
}
?>