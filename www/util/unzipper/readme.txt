PHP Unzipper v1.1
(C) Rinalds Uzkalns, 2003. Licensed under GNU Lesser General Public License
PclZIP module (C) Vincent Blavet (www.phpconcept.net) is used for archive extraction (licensed under GNU Lesser General Public License)

Overview:
This application is useful when there is a need to upload a many files with complicated directory structure to web server, for example, forum systems (like phpBB) or other applications like phpMyAdmin) which consist of many files arranged in complicated directory structure. All you need to do is to upload the archive file and PHP Unzipper will take care of creating the correct directory layot and file extraction. This program is especially helpful when you don't have FTP access to webserver but generally it will be helpful in all cases when there is a need to upload many small files to webserver.

Installation:
1) Upload files index.php and pclzip.lib.php to your webserver (of course, put both files in the same directory, also you can rename index.php to whatever you wish)
2) CHMOD index.php to 777
3) Open index.php in your web browser, it will ask to register first.
4) Type in the username and the password
5) Now you can login and start to use the program.
Note: username and password are stored in the pass.php file and they should be absolutely inaccessible to anyone except you. If you ever forget/want to change them, just open this file and they will be there :)

Usage:
Everything is as simple as possible here:
* Browse through directories and look for zip files
* Once you find a zip file you can either view its contents or unzip it. If you choose to view it, file listing will be displayed. If you choose to unzip it, the archive will be extracted in the same directory.
