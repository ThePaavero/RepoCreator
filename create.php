<?php

/**
 * RepoCreator (Creator)
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

// Repo dir name
$dirname = $project_name . '.git';

// Build our public directory path
$www_path = $config['html_basedir'] . $project_name;

// If public directory doesn't exist, create it
if( ! is_dir($www_path))
{
	if( ! is_writable($config['html_basedir']))
	{
		// Public directory isn't writable, bail
		$cli->line('www-directory is not writable, aborting.');
		exit;
	}

	$cli->line('Creating www-directory...');

	// Create public directory
	mkdir($www_path);

	// Make sure it's writable
	chmod($www_path, 0777);
}
else
{
	// Public directory already exists, that's ok
	$cli->line('www-directory exists...');
}

$cli->line('Creating repo "' . $dirname . '"...');

// Create git repository directory
mkdir($dirname);

// CD into it
chdir($dirname);

// Initalize repository using git
exec('git init --bare');

$cli->line('Doing post-receive hook...');

// CD to hooks directory
chdir('hooks');

// Create our hook bash script
$hook_code = '#!/bin/sh
while read oldrev newrev refname
do
    branch=$(git rev-parse --symbolic --abbrev-ref $refname)
    if [ "master" == "$branch" ]; then
        GIT_WORK_TREE=' . $config['html_basedir'] . $project_name . '
        export GIT_WORK_TREE
        git checkout -f
    fi
done
';

// Write our hook file
file_put_contents('post-receive', $hook_code);

// Make sure it's executable (0777 might be excessive, TODO)
chmod('post-receive', 0777);

// We're done
$cli->line('Done!', 2);

// Print URL to put into browser
$cli->line('http://' . $config['host'] . '/' . $project_name, 1);

// Print line to put into git bash
$cli->line('git clone ssh://' . $config['username'] . '@' . $config['host'] . '/' . $config['repo_basedir'] . '/' . $dirname . ' ./', 2, 'green');
