#!/usr/bin/env php
<?php

	// Procedural code makes me cry.

	// Attempt to flush the existing output buffer, used to remove the shebang.
	@ob_clean();

	// Read settings from ini file.
	(array)$settings	= (array)parse_ini_file(__DIR__ . "/xlines.ini", true);

	// Pattern of the xlines.db file.
	(string)$pattern	= (string)"/(*UTF8)LINE\s(\S+)?\s(\S+?)\s(\S+)?\s(\S+)?\s(\S+)\s\:(.*)?/";

	// Arrays to hold our data.
	(array)$data		= array();
	(array)$lines		= (array)array();

	// Strings from the configuration file.
	(string)$filename	= (string)$settings['xlines']['database_path'];
	(string)$dateFormat	= (string)$settings['xlines']['date_format'];

	// Set default timezone.
	if (!empty($settings['xlines']['date_timezone'])) {
		date_default_timezone_set($settings['xlines']['date_timezone']);
	}

	// Whether or not we are currently inside a CLI.
	(bool)$is_cli		= (php_sapi_name() == 'cli' OR defined('STDIN'));

	if ($is_cli) {
		// If stdin read is blocking, user will not be able to proceed.
		stream_set_blocking(STDIN, FALSE);

		// Build an array of lines from stdin. 
		while ($stdin	= fread(STDIN, 1024)) {
			(array)$data= (array)array_merge($data, explode("\n", $stdin));
		}

		// If argv1 is set, attempt to use it as our filename.
		if (!empty($argv[1])) {
			$filename	= (string)$argv[1];
		}

	}

	// If we haven't already built our array, load it from the file.
	if (empty($data)) {
		(array)$data	= file($filename);
	}

	// Loop over each line of our file.
	foreach (file($filename) as $line) {

		// If our (non-greedy) pattern matches, we build our array from it.
		if (preg_match($pattern, $line, $matches)) {

			// Build array from regex matches.
			(array)$line = array(
				'type'		=> (string)$matches[1],
				'host'		=> (string)$matches[2],
				'start'		=> (string)'Dawn of time',
				'end'		=> (string)'Never',
				'reason'	=> (string)$matches[6],
			);

			// Start date might not always be set, if it is, parse it for array.
			if (!empty($matches[4])) {
				(string)$line['start']	= (string)date($dateFormat, $matches[4]);
			}

			// End date might not always be set, if it is, parse it for array.
			if (!empty($matches[5])) {
				(string)$line['end']	= (string)date($dateFormat, $matches[5]);
			}

			// Stow value before our next iteration. 
			(array)$lines[]				= (array)$line;
		}

	}

	// If we haven't been able to extract anything, chances are that the file doesn't
	//   match our expected format, and therefore is invalid.
	if (empty($lines)) {
		(array)$lines = array(
			'Error'			=> (string)"Please check the format of this file.",
		);
	}

	// Build JSON array out of our information.
	(string)$json = json_encode([
		'data'		=> (array)$lines,
	]);

	// Set Content-Type to JSON, encoding to UTF-8 and allow GET CORS.
	header((string)'Content-Type: application/json; charset=utf-8');
	header((string)'Access-Control-Allow-Origin: *');
	header((string)'Access-Control-Allow-Methods: GET');  

	// Output the JSON and die.
	die((string)$json);
