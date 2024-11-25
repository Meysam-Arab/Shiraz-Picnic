<?php

return [

	/* Important Settings */

	// ======================================================================
	// never remove 'web', just put your middleware like auth or admin (if you have) here. eg: ['web','auth']
	'middlewares' => ['web'],
	// you can change default route from sms-admin to anything you want
	'route' => 'sms-admin',
	// SMS.ir Api Key
	//'api-key' => env('SMSIR-API-KEY','50ba3e8d9a9e1f4b1bf44cc'),
	// SMS.ir Secret Key
	//'secret-key' => env('SMSIR-SECRET-KEY','(1)O!@H3$$@*4@5'),
	// Your sms.ir line number
	//'line-number' => env('SMSIR-LINE-NUMBER','30004747471858'),
	
	'api-key' => env('SMSIR-API-KEY','ce9cddac7f04d00ddda8bb53'),
	// SMS.ir Secret Key
	'secret-key' => env('SMSIR-SECRET-KEY','meysam@fardan7e.ir'),
	// Your sms.ir line number
	'line-number' => env('SMSIR-LINE-NUMBER','30004747471858'),
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