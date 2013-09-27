RepoCreator
=========

Quickly create a project with automated git workflow on your server

TL;DR
-----------

Use this small package to automatically create both a **git repository** and a **public dev directory** on your linux box with a **post-receive hook for deployment on each push**.

What does it do?
-----------

* Creates a git repository on the server
* Creates a public directory for the project on the server
* Creates a post-receive hook that copies changes to public directory **when pushed to master branch**

Installation
--------------

* Clone this repo or copy files to your server
* Rename config.sample.php to config.php
* Fill in appropriate values in config.php

Usage
--------------
Create project with
```
php repo.php -a create -p project_x
```

Remove project with
```
php repo.php -a remove -p project_y
```

License
----

MIT
