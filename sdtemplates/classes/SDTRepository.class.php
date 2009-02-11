<?php
include_once ( "SDTPage.class.php" ) ;

class SDTRepository {
	private $sourceURI ;
	private $publicURI ;
	private $activeTheme ;
	private $availableThemes = array();
	
	function __construct ( $sourceURI , $publicURI = NULL ) {
		$this->sourceURI = $sourceURI ;
		$this->publicURI = $publicURI ;
		if ( $dirh = @openDir($sourceURI) ) {
			while ( $readh = readdir($dirh) ) {
				if ( is_dir($this->sourceURI."/".$readh) &&
						$readh[0] != '.') {
					array_push ( $this->availableThemes , basename($readh) );
				}				
			}
		} else {
			trigger_error ( "SDT Error: could not read repository dir");
		}
		if ( count( $this->availableThemes ) != 0 ) {
			if ( in_array( "default" , $this->availableThemes ) ) {
				$this->activeTheme = "default" ;
			} else {
				$this->activeTheme = $this->availableThemes[0];
			}
		} else {
			$this->activeTheme = "." ;
		}
	}
	
	function getPage ( $filename ) {
		if ( file_exists(
			$this->sourceURI."/".$this->activeTheme."/".$filename) ) {
				return new SDTPage ( $filename , $this );

		} else {
			trigger_error ( "SDT Error: could not find page ".$this->sourceURI."/".$this->activeTheme."/".$filename." on theme");
		}
	}
	
	function getAvailableThemes ( ) {
		return $this->availableThemes( );	
	}
	
	function getActiveTheme ( ) {
		return $this->activeTheme ;
	}
	
	function getSourceURI ( ) {
		return $this->sourceURI ;
	}
	
	function getPublicURI ( ) {
		return $this->publicURI ;
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