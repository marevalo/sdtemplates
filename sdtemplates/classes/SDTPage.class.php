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
		// Resolve relative URL on attributes
		$this->map( 'resolveRelativePaths' );
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