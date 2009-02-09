<?php
/*
 * Test #1 : Basic substitutions
 */
include_once( "SDTPage.class.php" );

// At this moment we don't have any SDTModule class
//SDTSetBase ( "templates" );
//SDTSetCurrentLibrary ( "basico" );

// We create the page directly from file
$frontpage = new SDTPage ( "templates/basic/frontpage.html" );
$contents = new SDTPage ( "templates/basic/contents.html" );

// Change the window title
$frontpage->getFirstNodeByTagName("title")
	->setContent("Moving subtemplates between pages");

// Change the body title
$frontpage->getNodeById("title")
	->setContent("Moving subtemplates between pages");

// We can make optional come blocks with if ( $ = ... ->getNode ) structures
if ( $postsplace = $frontpage->getNodeByClass("posts") ) {
	
	// Clean the postsplace
	$postsplace->deleteChildren();
	
	// Using a node inside another template
	$post = $contents->getNodeByClass( "post" )->cloneNode();
	
	// Modify it as we need
	$post->getNodeByClass("posttitle")->setContent( "New title");
	$post->getNodeByClass("postcontent")
		->appendChildFromText (
			"<p>And we even added another paragraph</p>"
		) ;

	// And add it to the first template
	$postsplace->appendChild ( $post );
}
// Add a document tree representation for debugging
$frontpage->getFirstNodeByTagName("body")
	->appendChildFromText ( "<pre>".$frontpage->walkThrough()."</pre>" ) ;

// Output the complete page
$frontpage->printHTML();

?>