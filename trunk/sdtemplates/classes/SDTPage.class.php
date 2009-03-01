<?php
include_once( "SDTNode.class.php" );

class SDTPage extends SDTNode {
	protected $DOMDocument ;
	protected $parentRepository ;
	
	protected $entryPoints = array (
		/*
		 * array (	"node" => DOMNode , "source" => templateSource,
		 *			"selector" => CSSSelector ) , ... 
		*/
	);
	
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
		parent::__construct( $this , $this->DOMDocument , "" , $filename );
		// Resolve relative URL on attributes
		$this->map( 'resolveRelativePaths' );
		/* TODO: And even maybe for url()s on style attrs (CSS 2.1):
		 * background-image content cue-after cue-before cursor
		 * list-style-image play-during 
		 */
	}
	
	protected function addEntryPoint ( $entryPoint ) {
		array_push ( $this->entryPoints , $entryPoint );
	}
	
	protected function getAncestorEntryPoint ( $DOMNode ) {
		do {
			foreach ( $this->entryPoints as $entryHash ) {
				if ( $DOMNode->isSameNode ( $entryHash["node"]) ) {
					return $entryHash;
				}
			}
		} while ( $DOMNode = $DOMNode->parentNode ) ;
		return array (
			"node" => $this->DOMDocument , "source" => $this->sourceFile ,
			"selector" => ""
		);
	}
	
	function saveHTML () {
		if ( $this->parentRepository->getOption("showInfoInline") ) {
			foreach ( $this->entryPoints as $entry ) {
				$entry["node"]->setAttribute(
					"sdt:template" ,
					$entry["source"]
				);
				$entry["node"]->setAttribute(
					"sdt:selector" ,
					$entry["selector"]
				);
			}
		}
		if ( $this->parentRepository->getOption("showInfoReport") ) {
			$table = "<table border=\"1\"><tbody>";
			$table .=	"<tr><th>Template file</th>".
						"<th>Selector</th></tr>";
			foreach ( $this->entryPoints as $entry ) {
				$table .=	"<tr><td>".
							htmlspecialchars($entry["source"]).
							"</td><td>".
							htmlspecialchars($entry["selector"]).
							"</td></tr>";
			}
			$table .= "</tbody></table>";
			$this->getNodeByTagName("body")->appendChildFromText ($table);
		}
		return $this->DOMDocument->saveHTML();
	}

	function printHTML () {
		print $this->saveHTML();
	}	
}
?>