<?php
	
	$return_array = array();

	/* Object One */
	$return_array[] = array('id' => 1,
							'username' => 'Shane P.',
							'created' => 1350932577,
							'likes' => 2,
							'description' => 'This is an example description, with escape characters in it! Shane\'s picture',
							'thumbnail' => 'http://rivertowell.com/mobile/gallery/exampleone_thumb.jpg',
							'fullimage' => 'http://rivertowell.com/mobile/gallery/exampleone.jpg');

	/* Object Two */
	$return_array[] = array('id' => 2,
							'username' => 'Shane P.',
							'created' => 1350932700,
							'likes' => 0,
							'description' => 'This is an example description, with escape characters in it! Shane\'s picture',
							'thumbnail' => 'http://rivertowell.com/mobile/gallery/exampletwo_thumb.jpg',
							'fullimage' => 'http://rivertowell.com/mobile/gallery/exampletwo.jpg');

	/* Object Three */
	$return_array[] = array('id' => 3,
							'username' => 'Ken C.',
							'created' => 1350933700,
							'likes' => 20,
							'description' => 'This is an example description, with escape characters in it! Ken\'s picture',
							'thumbnail' => 'http://rivertowell.com/mobile/gallery/examplethree_thumb.jpg',
							'fullimage' => 'http://rivertowell.com/mobile/gallery/examplethree.jpg');

	/* Object Four */
	$return_array[] = array('id' => 4,
							'username' => 'Orion K.',
							'created' => 1350943700,
							'likes' => 10,
							'description' => 'This is an example description, with escape characters in it! Orion\'s picture',
							'thumbnail' => 'http://rivertowell.com/mobile/gallery/examplefour_thumb.jpg',
							'fullimage' => 'http://rivertowell.com/mobile/gallery/examplefour.jpg');

	/* Object Five */
	$return_array[] = array('id' => 5,
							'username' => 'Rusty W.',
							'created' => 1350944000,
							'likes' => 12,
							'description' => 'This is an example description, with escape characters in it! Rusty\'s picture',
							'thumbnail' => 'http://rivertowell.com/mobile/gallery/examplefive_thumb.jpg',
							'fullimage' => 'http://rivertowell.com/mobile/gallery/examplefive.jpg');

	/* Object Six */
	$return_array[] = array('id' => 6,
							'username' => 'River W.',
							'created' => 1350946000,
							'likes' => 0,
							'description' => 'This is an example description, with escape characters in it! River\'s picture',
							'thumbnail' => 'http://rivertowell.com/mobile/gallery/examplesix_thumb.jpg',
							'fullimage' => 'http://rivertowell.com/mobile/gallery/examplesix.jpg');

	echo json_encode($return_array);

?>