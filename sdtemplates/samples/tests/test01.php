<?php
include_once( "SDTPage.class.php" );

//SDTSetBase ( "templates" );
//SDTSetCurrentLibrary ( "basico" );

$mypage = new SDTPage ( "templates/basic/frontpage.html" );

$windowtitle = $mypage->getFirstNodeByTagName("title");

$windowtitle->setContent("This is the new title (window title)");

$title = $mypage->getNodeById("title");

$title->setContent("This is the new title");

$toc = $mypage->getNodeByClass( "toc" );
//$element = $mypage->getNodeByClass( "toc_el" );
$toc->deleteChildren();

//$element->setContent( "New entry 1" );
//$toc->appendChild ( $element );
//$element->setContent( "New entry 2" );
//$toc->appendChild ( $element );
//$element->setContent( "New entry 3" );
//$toc->appendChild ( $element );

//$cuerpo = $mipagina->getNodeByTagName("body");

/*$cuerpo->addChildFromHash ( 
	array (
		"name" => "p" ,
		"content" => "Esto sería otro párrafo de prueba" ,
		"attributes" , array ( "aligh" => "center" ) ,
		"children" => array (
			"name" => "img" ,
			"attributes" => array (
				"src" => "http://test.com/img.jpg" ,
			)
		)
	)
);*/

$mypage->printHTML();

print "<pre>";
$mypage->walkThrough();
print "</pre>";

?>