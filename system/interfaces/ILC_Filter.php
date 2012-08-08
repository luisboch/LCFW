<?php

/**
 *
 * @author luis.boch
 * @since Aug 7, 2012
 */
interface ILC_Filter {
    /**
     * The implementation must be check if allow to user the requested url,
     * if is allowed return true, false otherwise
     * @return boolean true if allow, false otherwise
     */
    function check($url);
    /**
     * The implementation need return the pathern to System can check if requested
     * url matches to pattern
     * @return string pathern of Filter
     */
    function getPattern();
}

?>
