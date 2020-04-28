<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

Container::make('user_meta', 'Public Social Media Info')
	->add_fields(array(
		// Field::make('image', 'profile_image', 'Profile Image'),
		// Field::make('text', 'social_facebook', 'Facebook'),
		Field::make('text', 'social_twitter', 'Twitter'),
		Field::make('text', 'social_instagram', 'Instagram'),
		// Field::make('text', 'social_pinterest', 'Pinterest'),
		// Field::make('text', 'social_linkedin', 'LinkedIn'),
		// Field::make('text', 'social_youtube', 'YouTube'),
	));
