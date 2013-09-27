<?php

require 'quickcli.php';
$cli = new QuickCLI\QuickCLI('RepoCreator');

$config = require 'config.php';

require 'RepoCreator.php';
$repo = new RepoCreator\RepoCreator($cli, $config);

$opts = getopt('a:p:');

if( ! isset($opts['a']) || empty($opts['a']))
{
	$cli->line('-a (action) is required', 1, 'red');
	doHelp();
	exit;
}

if( ! isset($opts['p']) || empty($opts['p']))
{
	$cli->line('-p (project name) is required', 1, 'red');
	doHelp();
	exit;
}

if($opts['a'] === 'remove')
{
	$sure = $cli->prompt('Are you sure? All files will be permanently removed. This cannot be undone! (yes/no)', true);

	if($sure !== 'yes')
	{
		$cli->line('Aborting.');
		exit;
	}
}

$repo->doCommand($opts['a'], $opts['p']);

// ---------------------------------------------------------------------------

function doHelp()
{
	global $cli;

	$cli->line('Usage example:', 1, 'green');
	$cli->line('php repo.php -a create -p new_project', 1, 'green');
	$cli->line('php repo.php -a remove -p old_project', 1, 'green');
}
