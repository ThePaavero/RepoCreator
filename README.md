RepoCreator
=========

Quickly create a git workflow on your server

Version
----

0.5

What does it do?
-----------

* Creates git repository on server where script is run
* Creates a public directory for the project
* Creates a post-receive hook that copies changes to public directory
* Shows you public URL for you to copy to your browser
* Shows you ssh path to git repo for cloning on your local machine

Installation
--------------

* Copy files to your server's repository directory
* Rename config.sample.php to config.php
* Fill in appropriate values in config.php
* Execute script with
```sh
php create.php
```

License
----

MIT