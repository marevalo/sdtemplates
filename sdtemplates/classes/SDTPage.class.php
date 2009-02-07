<?php
include_once( "SDTNode.class.php" );

class SDTPage {
	private $DOMDocument ;
	private $sourceFile ;
	
	function __construct ( $filename , $DOMDocument = NULL ) {
		$this->sourceFile = $filename ;
		if ( is_null( $DOMDocument) ) {
			$this->DOMDocument = new DOMDocument();
			$this->DOMDocument->loadHTMLFile($filename);
		} else {
			$this->DOMDocument = $DOMDocument ;
		}
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
	
	function getSourceFile ( ) {
		return $sourceFile ;
	}
	
	function getFirstNodeByTagName ( $tagName ) {
		$nodeList = $this->DOMDocument->getElementsByTagName( $tagName ) ;
		if ( $nodeList->length != 0 ) {
			return new SDTNode ( 
						$this ,
						$nodeList->item(0)
					);
			
		}
	}
	
	function getNodeById ( $id ) {
		return new SDTNode (
						$this ,
						$this->DOMDocument->getElementById( $id )
					);
	}
	
	function getNodeByClass ( $class , $node = NULL ) {
		if ( is_null($node) ) {
			$node = $this->DOMDocument;
		}
		if ( $node->hasAttributes() ) {
			$attributes = $node->attributes;
			$Nattributes = $attributes->length ;
			for ( $index = 0 ; $index < $Nattributes ; $index++ ) {
				if ( strtoupper( $attributes->item ( $index )->name ) 
						== "CLASS" &&
				     $attributes->item ( $index )->value == $class ) {
					return new SDTNode ( $this , $node );
				}
			}
		}
		if ( $node->hasChildNodes() ) {
			$mylist = $node->childNodes;
			$Nnodes = $mylist->length;
			for ( $index = 0 ; $index < $Nnodes ; $index++ ) {
				$result =  $this->getNodeByClass (
								$class ,
								$mylist->item ( $index )
							);
				if ( !is_null($result) ) {
					return $result;
				}
			}
		}
		return NULL;
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
	
	function walkThrough ( $tab = "\t" , $prefix = "" , $node = NULL ) {
		if ( is_null($node) ) {
			$node = $this->DOMDocument;
		}
		print "$prefix NAME , TYPE : ".$node->nodeName." , ".
			$node->nodeType."\n";
		if ( $node->nodeType == 3) {
			print "$prefix VALUE: ".$node->nodeValue."\n";
		}
		if ( $node->hasAttributes() ) {
			$attributes = $node->attributes;
			$Nattrs = $attributes->length;
			print "$prefix $Nattrs attributes.\n";
			for ( $index = 0 ; $index < $Nattrs ; $index++ ) {
				print $prefix." ".$attributes->item ( $index )->name.
					": ".$attributes->item ( $index )->value."\n";
			}
		}
		if ( $node->hasChildNodes() ) {
			$mylist = $node->childNodes;
			$Nnodes = $mylist->length;
			print "$prefix $Nnodes nodes.\n";
			for ( $index = 0 ; $index < $Nnodes ; $index++ ) {
				$subnode = $mylist->item ( $index );
				$this->walkThrough( $tab , $prefix.$tab , $subnode );
			}
		}
	}
	
}
?>