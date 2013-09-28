<?php

namespace RepoCreator;

class RepoCreator {

	public function __construct($cli, $config)
	{
		$this->config = $config;
		$this->cli = $cli;

		if( ! is_dir($this->config['repo_basedir']))
		{
			$this->cli->line('Repository directory doesn\'t exist, aborting.', 1, 'red');
			exit;
		}

		if( ! is_dir($this->config['html_basedir']))
		{
			$this->cli->line('Public HTML directory doesn\'t exist, aborting.', 1, 'red');
			exit;
		}

	}

	public function doCommand($action = 'create', $project_name = '')
	{
		// Repo dir name
		$this->dirname = $this->config['repo_basedir'] . $project_name . '.git';

		// Public dir path
		$this->www_path = $this->config['html_basedir'] . $project_name;

		switch($action)
		{
			case 'create':
				$this->createRepo($project_name);
				break;

			case 'remove':
				$this->removeRepo($project_name);
				break;
		}
	}

	public function createRepo($project_name)
	{
		// If public directory doesn't exist, create it
		if( ! is_dir($this->www_path))
		{
			if( ! is_writable($this->config['html_basedir']))
			{
				// Public directory isn't writable, bail
				$this->cli->line('www-directory is not writable, aborting.', 1, 'red');
				exit;
			}

			$this->cli->line('Creating www-directory...');

			// Create public directory
			mkdir($this->www_path);

			// Make sure it's writable
			chmod($this->www_path, 0777);
		}
		else
		{
			// Public directory already exists, that's ok
			$this->cli->line('www-directory exists...');
		}

		$this->cli->line('Creating repo "' . $this->dirname . '"...');

		// If repository dir already exists, we're going to bail
		if(is_dir($this->dirname))
		{
			$this->cli->line('Repository with this name already exists!', 1, 'red');
			exit;
		}

		// Create git repository directory
		mkdir($this->dirname);

		// CD into it
		chdir($this->dirname);

		// Initalize repository using git
		exec('git init --bare');

		$this->cli->line('Doing post-receive hook...');

		// CD to hooks directory
		chdir('hooks');

		// Create our hook bash script
		$hook_code = '#!/bin/sh
		while read oldrev newrev refname
		do
		    branch=$(git rev-parse --symbolic --abbrev-ref $refname)
		    if [ "master" == "$branch" ]; then
		        GIT_WORK_TREE=' . $this->config['html_basedir'] . $project_name . '
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
		$this->cli->line('Done!', 2);

		// Print URL to put into browser
		$this->cli->line('http://[DOMAIN]/' . $project_name, 1);

		// Print line to put into git bash
		$this->cli->line('git clone ssh://[USERNAME]@[DOMAIN]' . $this->config['repo_basedir'] . $this->dirname . ' ./', 2, 'green');
	}

	public function removeRepo($project_name)
	{
		if( ! is_dir($this->www_path))
		{
			$this->cli->line('www-directory doesn\'t exist...');
		}
		else
		{
			$this->cli->line('Removing www-directory...');
			exec('rm -rf ' . $this->www_path);
		}

		if( ! is_dir($this->dirname))
		{
			$this->cli->line('Repository doesn\'t exist, aborting...');
			exit;
		}

		$this->cli->line('Removing repository...');
		exec('rm -rf ' . $this->dirname);

		$this->cli->line('Done, everything removed.', 1, 'green');
	}

}
