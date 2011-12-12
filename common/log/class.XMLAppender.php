<?php

error_reporting(E_ALL);

/**
 * Generis Object Oriented API - common/log/class.XMLAppender.php
 *
 * $Id$
 *
 * This file is part of Generis Object Oriented API.
 *
 * Automatically generated on 09.12.2011, 11:42:37 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package common
 * @subpackage log
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include common_log_BaseAppender
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('common/log/class.BaseAppender.php');

/* user defined includes */
// section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001846-includes begin
// section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001846-includes end

/* user defined constants */
// section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001846-constants begin
// section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001846-constants end

/**
 * Short description of class common_log_XMLAppender
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package common
 * @subpackage log
 */
class common_log_XMLAppender
    extends common_log_BaseAppender
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute filename
     *
     * @access public
     * @var string
     */
    public $filename = '';

    // --- OPERATIONS ---

    /**
     * Short description of method init
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  array configuration
     * @return boolean
     */
    public function init($configuration)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001851 begin
    	if (isset($configuration['file'])) {
    		$this->filename = $configuration['file'];
    		$returnValue = parent::init($configuration);
    	} else {
    		$returnValue = false;
    	}
        // section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001851 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method doLog
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Item item
     * @return mixed
     */
    public function doLog( common_log_Item $item)
    {
        // section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001854 begin
    	$doc = new DOMDocument();
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$success = @$doc->load($this->filename);
		if (!$success)
			$doc->loadXML('<events></events>');

		$event_element = $doc->createElement("event");

		$message = $doc->createElement("description");
		$message->appendChild(
				$doc->createCDATASection($item->getDescription())
		);
		$event_element->appendChild($message);
		
		$file = $doc->createElement("file");
		$file->appendChild(
				$doc->createCDATASection($item->getCallerFile())
		);
		$event_element->appendChild($file);
		
		$line = $doc->createElement("line");
		$line->appendChild(
				$doc->createCDATASection($item->getCallerLine())
		);
		$event_element->appendChild($line);
		
		$datetime = $doc->createElement("datetime");
		$datetime->appendChild(
				$doc->createCDATASection($item->getDateTime())
		);
		$event_element->appendChild($datetime);
		
		$severity = $doc->createElement("severity");
		$severity->appendChild(
				$doc->createCDATASection($item->getSeverity())
		);
		$event_element->appendChild($severity);
		
		$user = $doc->createElement("user");
		$user->appendChild(
				$doc->createCDATASection($item->getUser())
		);
		$event_element->appendChild($user);
		
		$doc->documentElement->appendChild($event_element);
		@$doc->save($this->filename);
        // section 127-0-1-1-56e04748:1341d1d0e41:-8000:0000000000001854 end
    }

} /* end of class common_log_XMLAppender */

?>