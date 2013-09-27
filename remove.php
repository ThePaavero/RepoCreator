<?php

/**
 * RepoCreator (Remover)
 *
 * @author Pekka S. <nospam@astudios.org>
 * @link https://github.com/ThePaavero/RepoCreator/
 */

// Get our CLI lib
require 'quickcli.php';

// Get our config array
$config = require 'config.php';

// Create a CLI instance
$cli = new QuickCLI\QuickCLI('RepoCreator');

// Prompt user for project name
$project_name = $cli->prompt('Enter project name (sans ".git")', true);

// Really do this?
$sure = $cli->prompt('Are you sure? All files will be permanently removed. This cannot be undone! (yes/no)', true);
if($sure === 'no')
{
	$cli->line('Aborting.');
}

// Repo dir name
$dirname = $project_name . '.git';

// Build our public directory path
$www_path = $config['html_basedir'] . $project_name;

// If public directory exists, destroy it
if( ! is_dir($www_path))
{
	$cli->line('www-directory doesn\'t exist, aborting...');
	exit;
}

$cli->line('Removing www-directory...');
exec('rm -rf ' . $www_path);

if( ! is_dir($dirname))
{
	$cli->line('Repository doesn\'t exist, aborting...');
	exit;
}

$cli->line('Removing repository...');
exec('rm -rf ' . $dirname);

// We're done
$cli->line('Done!', 2);
