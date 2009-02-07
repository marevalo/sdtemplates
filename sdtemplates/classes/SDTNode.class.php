<?php
include_once( "SDTPage.class.php" );

class SDTNode {
	private $DOMNode ;
	private $parentPage ;
	private $sourceFile ;
	
	function __construct ( $parentPage , $DOMNode , $sourceFile = NULL ) {
		$this->parentPage = $parentPage ;
		$this->DOMNode = $DOMNode ;
		if ( is_null ($sourceFile) ) {
			$this->sourceFile = $this->parentPage->getSourceFile();
		} else {
			$this->sourceFile = $sourceFile;
		}
	}
	
	function setContent ( $string ) {
		$this->DOMNode->nodeValue = $string;
	}

	function setAttribute ( $name , $value ) {
		
	}
	
	function getAttribute ( $name ) {
		if ( $this->DOMNode->hasAttributes() ) {
			foreach ($this->DOMNode->attributes as $attrName => $attrNode) {
				if ( $attrName = $name ) {
					return $attrNode;
				}
			}
		} else {
			return NULL;
		}
		return NULL;
	}
	
	function getAttributes ( $name ) {
		$returnArray = array ();
		if ( $this->DOMNode->hasAttributes() ) {
			foreach ($this->DOMNode->attributes as $attrName => $attrNode) {
				$returnArray[attrName]=$attrNode;
			}
		}
		return $returnArray;
	}
	
	function getNodeById ( $id ) {
		return NULL;
	}
	
	function deleteChildren ( ) {
		while ($this->DOMNode->childNodes->length)
			$this->DOMNode->removeChild($this->DOMNode->firstChild);
	}
	
	function appendChild ( $childNode ) {
		$this->DOMNode->appendChild ( $childNode->DOMNode );
	}
	
	function appendChildFromText ( $string ) {
		$this->DOMNode->appendChild(
			$this->DOMNode->ownerDocument->importNode(
				dom_import_simplexml( simplexml_load_string( $string ) ) ,
				true
			)
		);
		
	}
	
	function cloneNode ( $deep = true ) {
		return new SDTNode (
			$this->parentPage ,
			$this->DOMNode->cloneNode( $deep ) ,
			$this->sourceFile );
	}
	
}
?>