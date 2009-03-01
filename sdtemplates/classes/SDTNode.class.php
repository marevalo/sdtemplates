<?php
include_once( "SDTPage.class.php" );

class SDTNode {
	protected $DOMNode ;
	protected $parentPage ;
	protected $sourceFile ;
	protected $selector ;
	
	function __construct ( $parentPage , $DOMNode ,
				$selector = "" ,
				$sourceFile = NULL ) {
		$this->parentPage = $parentPage ;
		$this->DOMNode = $DOMNode ;
		$this->selector = $selector ;
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
	
	function getAttributes ( ) {
		$returnArray = array ();
		if ( $this->DOMNode->hasAttributes() ) {
			foreach ($this->DOMNode->attributes as $attrName => $attrNode) {
				$returnArray[attrName]=$attrNode;
			}
		}
		return $returnArray;
	}
	
	function getSourceFile ( ) {
		return $sourceFile ;
	}
	
	function getNodeByTagName ( $tagName ) {
		$nodeList = $this->DOMNode->getElementsByTagName( $tagName ) ;
		if ( $nodeList->length != 0 ) {
			$node = $nodeList->item(0) ;
			// Create the new entry point for the new node
			$entryPoint = $this->parentPage->getAncestorEntryPoint ( $node );
			$entryPoint["node"] = $node ;
			$entryPoint["selector"] .= " ".$tagName ;
			$entryPoint["selector"] = trim ( $entryPoint["selector"] ) ;
			// add it
			$this->parentPage->addEntryPoint ( $entryPoint );
			// And create the SDTNode
			return new SDTNode ( 
						$this->parentPage ,
						$node ,
						$entryPoint["selector"] ,
						$entryPoint["source"]
					);
			
		}
		return NULL;
	}
	
	function getNodeById ( $id ) {
		$node = $this->parentPage->DOMNode->getElementById( $id );
		// Create the new entry point for the new node
		$entryPoint = $this->parentPage->getAncestorEntryPoint ( $node );
		$entryPoint["node"] = $node ;
		$entryPoint["selector"] .= " #".$id ;
		$entryPoint["selector"] = trim ( $entryPoint["selector"] ) ;
		// add it
		$this->parentPage->addEntryPoint ( $entryPoint );
		return new SDTNode (
						$this->parentPage ,
						$node ,
						$entryPoint["selector"] ,
						$entryPoint["source"]
					);
	}
	
	function getNodeByClass ( $class , $node = NULL ) {
		if ( is_null($node) ) {
			$node = $this->DOMNode;
		}
		if ( $node->hasAttributes() ) {
			$attributes = $node->attributes;
			$Nattributes = $attributes->length ;
			for ( $index = 0 ; $index < $Nattributes ; $index++ ) {
				if ( strtoupper( $attributes->item ( $index )->name ) 
						== "CLASS" &&
				     $attributes->item ( $index )->value == $class ) {
					// Create the new entry point for the new node
					$entryPoint["node"] = $node ;
					$entryPoint["source"] = $this->sourceFile ;
					$entryPoint["selector"] .= " .".$class ;
					$entryPoint["selector"] = trim($entryPoint["selector"]) ;
					// add it
					$this->parentPage->addEntryPoint ( $entryPoint );
				   	return new SDTNode (
						$this->parentPage ,
						$node ,
						$entryPoint["selector"] ,
						$entryPoint["source"]
					);
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
	
	function deleteChildren ( ) {
		while ($this->DOMNode->childNodes->length)
			$this->DOMNode->removeChild($this->DOMNode->firstChild);
	}
	
	function appendChild ( $childNode ) {
		if ( ! $this->parentPage->DOMDocument->isSameNode(
							$childNode->parentPage->DOMDocument ) ) {
			$childDOMNode = $this->DOMNode->ownerDocument->importNode(
				$childNode->DOMNode , true );
		} else {
			$childDOMNode = $childNode->DOMNode;
		}
		// Create the new entry point for the new node
		$entryPoint["node"] = $childDOMNode ;
		$entryPoint["source"] = $childNode->sourceFile ;
		$entryPoint["selector"] = $childNode->selector ;
		// add it
		$this->parentPage->addEntryPoint ( $entryPoint );
		$this->DOMNode->appendChild ( $childDOMNode );
	}
	
	function appendChildFromText ( $string ) {
		$node = $this->DOMNode->ownerDocument->importNode(
				dom_import_simplexml( simplexml_load_string( $string ) ) ,
				true
			); 
		// Create the new entry point for the new node
		$entryPoint["node"] = $node ;
		$entryPoint["source"] = "<code>" ;
		$entryPoint["selector"] = "" ;
		// add it
		$this->parentPage->addEntryPoint ( $entryPoint );
		$this->DOMNode->appendChild( $node );
		
	}
	
	function cloneNode ( $deep = true ) {
		return new SDTNode (
			$this->parentPage ,
			$this->DOMNode->cloneNode( $deep ) ,
			$this->selector ,
			$this->sourceFile );
	}
	
	function walkThrough ( $tab = "\t" , $prefix = "" , $node = NULL ) {
		$return_string = "";
		if ( is_null($node) ) {
			$node = $this->DOMNode;
		}
		$return_string .= "$prefix NAME , TYPE : ".$node->nodeName." , ".
			$node->nodeType."\n";
		if ( $node->nodeType == XML_TEXT_NODE ) {
			$return_string .= "$prefix VALUE: ".$node->nodeValue."\n";
		}
		if ( $node->hasAttributes() ) {
			$attributes = $node->attributes;
			$Nattrs = $attributes->length;
			$return_string .= "$prefix $Nattrs attributes.\n";
			for ( $index = 0 ; $index < $Nattrs ; $index++ ) {
				$return_string .=
					$prefix." ".$attributes->item ( $index )->name.
					": ".$attributes->item ( $index )->value."\n";
			}
		}
		if ( $node->hasChildNodes() ) {
			$mylist = $node->childNodes;
			$Nnodes = $mylist->length;
			$return_string .= "$prefix $Nnodes nodes.\n";
			for ( $index = 0 ; $index < $Nnodes ; $index++ ) {
				$subnode = $mylist->item ( $index );
				$return_string .= $this->walkThrough(
										$tab ,
										$prefix.$tab ,
										$subnode
									);
			}
		}
		return $return_string;
	}
	
	protected function map ( $function , $node = NULL ) {
		if ( is_null($node) ) {
			$node = $this->DOMNode;
		}
		$this->$function ( $node );
		if ( $node->hasChildNodes() ) {
			$mylist = $node->childNodes;
			$Nnodes = $mylist->length;
			for ( $index = 0 ; $index < $Nnodes ; $index++ ) {
				$subnode = $mylist->item ( $index );
				$this->map ( $function , $subnode ) ;
			}
		}
	}

	private function resolveRelativePaths ( $node ) {
		if ( $node->hasAttributes() ) {
			$attributes = $node->attributes;
			$Nattrs = $attributes->length;
			for ( $index = 0 ; $index < $Nattrs ; $index++ ) {
				$name = $attributes->item ( $index )->name;
				$value = $attributes->item ( $index )->value;
				if ( in_array (	$name ,
						$this->parentPage->parentRepository->urlAttributes )
						) {
					if ( $value[0] == '.' || $value[0] == '/' ) {
						trigger_error ( "SDT Error : ".
							"Invalid absolute or . or .. relative path on ".
							"attribute '".$name."'"
						);
					}
					if ( ! strpos ( $value , ':' ) ) {
						$node->setAttribute (
							$name ,
							$this->parentPage->
								parentRepository->getPublicURI()."/".
							$this->parentPage->
								parentRepository->getActiveTheme()."/".
							$value
						);
					}
				}
			}
		}
		
	}
	
}
?>