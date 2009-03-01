<?php
/*
 * Test #5 : Registering template use
 */
include_once( "SDTRepository.class.php" );

// Set repository
$repo = new SDTRepository ( "templates" );

// Set some options on the repository to show template use
// Set true to show the use as attributes under sdt: namespace on tags
$repo->setOption( "showInfoInline" , true );
// Set true to show the use as a report at the end of the html page
$repo->setOption( "showInfoReport" , true );

// We get the files from repository
$frontpage = $repo->getPage ( "frontpage.html" );
$contents = $repo->getPage ( "contents.html" );

// Change the window title
$frontpage->getNodeByTagName("title")
	->setContent("Registering template use");

// Change the body title
$frontpage->getNodeById("title")
	->setContent("Registering template use");

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

// Add some text
$frontpage->getNodeByTagName("body")
	->appendChildFromText(
		"<p>You can have some info on the use of templates on this page
		by looking at the code and looking for attributes unde the sdt:
		namespace, we have added also a report at the end of the page with
		a table with the templates and entry point selector.</p>"
	);

// Output the complete page
$frontpage->printHTML();

?>