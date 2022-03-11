# Overview

    Project uses composer, and package twig (template engine)
    Minimum requirements is php 7.2 (twig 3.) depends on it

# Installation

    After cloning project next steps are required:
    1. Run Composer install
    2. Creatting database 
    3. Triggering query from sql/table to create tables
    4. Setup host, user, pass and database from in config/AppCofig.php

# Basics

    Minimalistic MVC framework.
    Start entry is in public/index.php

    TaskController class handles all the views, 
    1. create for creating new task, 
    2. index for vieving all the tasks and filtering them by user, client or tags
    3. show for showing content task data and all task related data.
    4. tasksAll showing only task list, can be deleted (for now)

    All requests are redirected to index.php (via .htaccess file) and redirected
    to UserController.

    Views are rendered via twig template engine
 
