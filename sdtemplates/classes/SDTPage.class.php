<?php
include_once( "SDTNode.class.php" );

class SDTPage extends SDTNode {
	protected $DOMDocument ;
	protected $parentRepository ;
	
	function __construct (	$filename ,
							$parentRepo = NULL ,
							$DOMDocument = NULL ) {
		if ( is_null($parentRepo) ) {
			$this -> $parentRepo = new SDTRepository( "." );
		}
		$this->parentRepository = $parentRepo ;
		if ( is_null( $DOMDocument) ) {
			$this->DOMDocument = new DOMDocument();
			$this->DOMDocument->loadHTMLFile(
				$this->parentRepository->getSourceURI()."/".
				$this->parentRepository->getActiveTheme()."/".
				$filename
			);
		} else {
			$this->DOMDocument = $DOMDocument ;
		}
		parent::__construct( $this , $this->DOMDocument , $filename );
		/* After this we should process all relative URI attrs
		 * for TAGS (HTML4.01):
		 * action FORM
		 * background BODY
		 * cite BLOCKQUOTE, Q
		 * cite DEL, INS
		 * classid OBJECT
		 * codebase OBJECT
		 * codebase APPLET
		 * data OBJECT
		 * href A, AREA, LINK
		 * href BASE (base URI for all relative paths)
		 * longdesc IMG
		 * longdesc FRAME, IFRAME
		 * profile HEAD
		 * src SCRIPT
		 * src INPUT
		 * src FRAME, IFRAME
		 * src IMG
		 * usemap IMG, INPUT, OBJECT
		 * 
		 */
		/* TODO: And even maybe for url()s on style attrs (CSS 2.1):
		 * background-image content cue-after cue-before cursor
		 * list-style-image play-during 
		 */
	}
	
	function clonePage ( ) {
		return NULL;
	}
	
	function saveHTML () {
		return $this->DOMDocument->saveHTML();
	}

	function printHTML () {
		print $this->DOMDocument->saveHTML();
	}
	
}
?>