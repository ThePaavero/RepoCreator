<?php

require 'cli.php';
$config = require 'config.php';

$cli = new QuickCLI\CLI('RepoCreator');

$project_name = $cli->prompt('Enter project name (sans ".git")', true);

$dirname = $project_name . '.git';

$www_path = $config['html_basedir'] . $project_name;
if( ! is_dir($www_path))
{
	if( ! is_writable($config['html_basedir']))
	{
		$cli->line('www-directory is not writable, aborting.');
		exit;
	}

	$cli->line('Creating www-directory...');
	mkdir($www_path);
	chmod($www_path, 0777);
}
else
{
	$cli->line('www-directory exists...');
}

$cli->line('Creating repo "' . $dirname . '"...');
mkdir($dirname);
chdir($dirname);
exec('git init --bare');

$cli->line('Doing post-receive hook...');
chdir('hooks');
$hook_code = '#!/bin/sh
GIT_WORK_TREE=' . $config['html_basedir'] . $project_name . '
export GIT_WORK_TREE
git checkout -f
';
file_put_contents('post-receive', $hook_code);
chmod('post-receive', 0777);

$cli->line('Done!', 2);

$cli->line('http://' . $config['host'] . '/' . $project_name, 1);
$cli->line('git clone ssh://' . $config['username'] . '@' . $config['host'] . '/' . $config['repo_basedir'] . '/' . $dirname . ' ./', 2, 'green');
