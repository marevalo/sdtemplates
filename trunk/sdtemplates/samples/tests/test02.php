<?php
/*
 * Test #1 : Basic substitutions
 */
include_once( "SDTRepository.class.php" );

// Set repository
$repo = new SDTRepository ( "templates" );

// We get the files from repository
$frontpage = $repo->getPage ( "frontpage.html" );
$contents = $repo->getPage ( "contents.html" );

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