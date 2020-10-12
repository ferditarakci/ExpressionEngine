<?php

return [
	'author'      		=> 'Packet Tide',
	'author_url'  		=> 'http://packettide.com',
	'name'        		=> 'Queue',
	'description' 		=> 'Runs scheduled jobs',
	'version'     		=> '1.0',
	'namespace'   		=> 'Queue',
	'settings_exist'	=> true,
	// Advanced settings
	'services'			=> [
		'QueueService' => function($addon) {
			// Add your dependency injection here
			// See more here: https://docs.expressionengine.com/latest/development/addon-setup-php-file.html#services
		},
		'SerializerService' => function($addon) {
			// Add your dependency injection here
			// See more here: https://docs.expressionengine.com/latest/development/addon-setup-php-file.html#services
		},
	],
	'models'			=> [
		'Job'		=> 'Queue\Models\Job',
		'FailedJob'	=> 'Queue\Models\FailedJob',
	],
	'commands'	=> [
		'queue:test' => Queue\Commands\CommandQueueTest::class,
		'queue:work' => Queue\Commands\CommandQueueWork::class,
	],
];