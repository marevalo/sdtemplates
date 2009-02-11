<?php
/*
 * Test #1 : Basic substitutions
 */
include_once( "SDTRepository.class.php" );

// Set repository
$repo = SDTSetBase ( "templates" );

// We get the file from repository
$mypage = $repo->getPage ( "frontpage.html" );

// Change the window title
$windowtitle = $mypage->getFirstNodeByTagName("title");
$windowtitle->setContent("This is the new window title");

// Change the body title
$title = $mypage->getNodeById("title");
$title->setContent("This is the new title");

// Add some elements inside the code
$body = $mypage->getFirstNodeByTagName("body");
$body->appendChildFromText ( "
	<p>This paragraph <a href='#bogus_added_anchor'>added</a> from inside the code</p>
" );

// Using a node inside the page as a subtemplate, modify and append it
$toc = $body->getNodeByClass( "toc" );
$element = $toc->getNodeByClass( "toc_el" )->cloneNode();
$copy = $element->cloneNode();
$toc->deleteChildren();
$copy->setContent( "New entry 1" );
$toc->appendChild ( $copy->cloneNode() );
$copy->setContent( "New entry 2" );
$toc->appendChild ( $copy->cloneNode() );
$copy->setContent( "New entry 3" );
$toc->appendChild ( $copy->cloneNode() );

// Add a document tree representation for debugging
$body->appendChildFromText ( "<pre>".$mypage->walkThrough()."</pre>" ) ;

// Output the complete page
$mypage->printHTML();

?>