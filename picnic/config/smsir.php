<?php

return [

	/* Important Settings */

	// ======================================================================
	// never remove 'web', just put your middleware like auth or admin (if you have) here. eg: ['web','auth']
	'middlewares' => ['web'],
	// you can change default route from sms-admin to anything you want
	'route' => 'sms-admin',
	// SMS.ir Api Key
	//'api-key' => env('SMSIR-API-KEY',''),
	// SMS.ir Secret Key
	//'secret-key' => env('SMSIR-SECRET-KEY',''),
	// Your sms.ir line number
	//'line-number' => env('SMSIR-LINE-NUMBER',''),
	
	'api-key' => env('SMSIR-API-KEY',''),
	// SMS.ir Secret Key
	'secret-key' => env('SMSIR-SECRET-KEY',''),
	// Your sms.ir line number
	'line-number' => env('SMSIR-LINE-NUMBER',''),
	// ======================================================================
	// set true if you want log to the database
	'db-log' => false,
	// if you don't want to include admin panel routes set this to false
	'panel-routes' => false,
	/* Admin Panel Title */
	'title' => 'مدیریت پیامک ها',
	// How many log you want to show in sms-admin panel ?
	'in-page' => '15'
];
