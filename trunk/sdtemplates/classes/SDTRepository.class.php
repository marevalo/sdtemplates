<?php
include_once ( "SDTPage.class.php" ) ;

class SDRepository {
	private $sourceURI ;
	private $publicURI ;
	private $activeTheme ;
	private $availableThemes = array();
	
	function __construct ( $sourceURI , $publicURI = NULL ) {
		$this->sourceURI = $sourceURI ;
		$this->publicURI = $publicURI ;
		if ( $dirh = @openDir($sourceURI) ) {
			while ( $readh = readdir($dirh) ) {
				if ( is_dir($readh) ) {
					array_push ( $this->availableThemes , basename($readh) );
				}				
			}
		} else {
			trigger_error ( "SDT Error: could not read repository dir");
		}
		if ( count( $this->availableThemes ) > 0 ) {
			if ( in_array( "default" , $this->availableThemes ) ) {
				$this->activeTheme = "default" ;
			} else {
				$this->activeTheme = $this->availableThemes[0];
			}
		} else {
			trigger_error ( "SDT Error: no available themes on repository");
		}
	}
	
	function getPage ( $filename ) {
		if ( file_exists(
			$this-sourceURI."/".$this->activeTheme."/".$filename) ) {
				return new SDTPage ( $this , $filename );

		} else {
			trigger_error ( "SDT Error: could not find page on theme");
		}
	}
	
	function getAvailableThemes ( ) {
		return $availableThemes( );	
	}
	
	function getActiveTheme ( ) {
		return $activeTheme ;
	}
	
	function setTheme ( $theme ) {
		if ( in_array ( $theme , $availableThemes ) ) {
			return true;
		} else {
			trigger_error ( "SDT Error: Unavailable theme" );
		}
	}
}
?>