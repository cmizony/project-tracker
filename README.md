## Project Tracker

Project management application written in php using CodeIgniter Framework.
Official website can be found at http://tracker.cmizony.com

## Demo

Hosted demo is available at [demo.tracker.cmizony.com](http://demo.tracker.cmizony.com).The database is reset daily

## Usage

* Edit **application/config/config.php** to set the base url
* Edit **application/config/database.php** with your database credential
* Run Cli **php index.php cli_db migrate_latest** to create database structure
* Edit **application/config/custom.php** to set the root account password

For troubleshooting please create a support issue on github

## Features

* Projects list
* Tasks And Iterations Tracking
* Comments system
* Time tracking
* Clients accounts
* Ticket system
* Calendar & grid view
* Detailed Log system
* File management for projects
* Markdown to link project entities

## TODO

* Easy deployment (Phing & Chef)
* Upgrade Framework version
* Add reporting capabilities
* Create dev API
