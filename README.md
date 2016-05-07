# README #
This is the repository for **tetragon** software by **tetracode**.

![tetragon](https://bitbucket.org/account/user/tetra-code/projects/TET/avatar/256)
![tetracode](https://bitbucket.org/account/tetra-code/avatar/)

![tests:failing](https://img.shields.io/badge/tests-failing-red.svg)
![product:maintained](https://img.shields.io/badge/project-on--going-blue.svg)
![documentation:not-yet](https://img.shields.io/badge/documentation-not--yet-red.svg)
___

## What is this repository for? ##

* This contains the development sources for the shop management system **tetragon**.
* Built with [![symfony](http://symfony.com/logos/symfony_black_02.svg?v=4)](http://symfony.com/) and :heart:
* Join the [hipchat](https://tetracode.hipchat.com/home)!
___

## How to run the program locally? ##

To run the program,

1. Have set up a server. Easiest way is to install [XAMPP](https://www.apachefriends.org/).
2. Install [git](https://git-scm.com/). 
3. **Clone** to computer. (You **must** have access to the repository. You will need to enter *username* and *password*. )
   >     git clone https://bitbucket.org/tetra-code/tetragon 
4. Install [composer](https://getcomposer.org/).
5. Go to the project folder (tetragon) with your terminal. 
6. **Update** dependencies with composer. (Be patient. Skip any (all?) parameters you don't know.)
   >     composer install
7. Start your database server. Here, we are running MariaDB with `root` and a *null* password. Then run
   >     php bin/console doctrine:database:create
   >     php bin/console doctrine:schema:create
   >     php bin/console doctrine:schema:update
9. Run with php. (You may need to add `php.exe` location to path.)
   >     php bin/console server:run
10. Go to [`http://127.0.0.1:8000`](http://127.0.0.1:8000) and enjoy. :smile:
___

## Any online version? ##

Of course. Go to [test-prod on Heroku](http://tetragon.heroku.com/)

---

## How to get started developing? ##

1. Follow the above steps, 1 to 7.
2. Install a good IDE. 
   > You can obtain JetBrains [PhpStorm](https://www.jetbrains.com/phpstorm/) 1 year license proving your student status. (HIGHLY RECOMMENDED.)
   > [NetBeans](https://netbeans.org/) is a good IDE too. :smiley:
   >
   > You may want to checkout other text editors like [Notepad++](https://notepad-plus-plus.org/), [Visual Studio Code](https://code.visualstudio.com/), [Sublime Text](http://www.sublimetext.com/3) and [Brackets](http://brackets.io/). 
3. Install/Activate plugins for Git, Symfony and twig template language. 
4. Go to tetragon local copy and check git status. 
   >     git status
   > It should return something like `On branch develop`.
5. Switch to a branch according to what you do.
   > General development: `develop`. 
   >
   > Adding a feature: `<feature name>`.
   >
   > Fixing a bug: `hotfix`.
   > 
   > Fine tuning for a release: `release`.
   >
   > Please do not use the `master` branch unless required.
   >
   >     git checkout <branch-name>
6. Read [The Symfony Book](http://symfony.com/doc/current/book/index.html).
7. Start editing.
8. ``` git commit ```
9. ``` git push ```
10. Enjoy!