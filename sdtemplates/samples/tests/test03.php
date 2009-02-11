<?php
/*
 * Test #3 : Repositories and relative paths inside templates
 */
include_once( "SDTRepository.class.php" );

// Set repository
$repo = new SDTRepository ( "templates" );

// We get the file from repository
$image_page = $repo->getPage ( "image.html" );

// Output the complete page
$image_page->printHTML();

?>