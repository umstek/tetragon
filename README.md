# README #

This is the repository for **tetragon** software by **tetracode**.

### What is this repository for? ###

* This contains the development sources for the shop management system **tetragon**.
* Only the [Symfony](http://symfony.com/) boilerplate code is committed yet. 
* [hipchat](https://tetracode.hipchat.com/home)

### How to run the program? ###

There's nothing to run yet, :frowning: but, 

0. Have set up a server. Easiest way is to install [XAMPP](https://www.apachefriends.org/).
1. Install [git](https://git-scm.com/). 
2. **Clone** to computer. (You **must** have access to the repository. You will need to enter *username* and *password*. )
>     git clone https://bitbucket.org/tetra-code/tetragon
3. Install [composer](https://getcomposer.org/).
4. Go to the project folder (tetragon) with your terminal. 
5. **Update** dependencies with composer. (Be patient. Skip any (all?) parameters you don't know.)
>     composer update
6. Run with php. (You may need to add `php.exe` location to path.)
>     php bin/console server:run
7. Go to [`http://127.0.0.1:8000`](http://127.0.0.1:8000) and enjoy. :smile: