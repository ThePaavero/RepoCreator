RepoCreator
=========

Quickly create a project with automated git workflow on your server

TL;DR
-----------

Use this three file PHP script to automatically create both a **git repository** and a **public dev directory** on your linux box with a **post-receive hook for deployment on each push**.

What does it do?
-----------

* Creates git repository on server where script is run
* Creates a public directory for the project
* Creates a post-receive hook that copies changes to public directory **when pushed to master branch**
* Shows you public URL for you to copy to your browser
* Shows you SSH path to git repo for cloning project onto your local machine

Installation
--------------

* Copy files to your server's repository directory
* Rename config.sample.php to config.php
* Fill in appropriate values in config.php

Usage
--------------
Create project with
```
php create.php
```

Remove project with
```
php remove.php
```

License
----

MIT

Example flow
----

![flow](http://i.imgur.com/2rBzKb8.png "Flow")