# Square One Global Docker

Status: **Alpha**

### Requirements

1. PHP 7.2+
1. composer
1. docker
1. docker-compose

### Development installation

1. Clone this repo.
1. Symlink the `sq1` binary to a directory in your path, e.g. `ln -s sq1 /usr/local/bin/sq1`.
1. If you already have cloned a square-one repo, copy the `global/certs` folder to the `global/certs` folder in this repo.
1. Stop all your existing containers `docker stop $(docker ps -aq)`.
1. Run `composer install`.
1. Run `sq1` for command options (`sq1 create` doesn't do anything yet).

### Release Installation (Do not use)

1. `composer global require consolidation/cgr`
1. Add your composer bin path to your $PATH, e.g. `PATH="$(composer config -g home)/vendor/bin:$PATH"`
1. `cgr moderntribe/square1-global-docker`
1. `composer install`

### Goals/Roadmap

Below are all the ideas for this project. These aren't currently ordered by importance or necessity but just a list of ideas for now.

#### Macro Goals
- Make sq1 easy to install and use, by abstracting a lot of the manual effort into simple commands.
- Make the confusion of global/local docker projects go away.
- Make spinning up new pro

#### General
- Should be easy to install, with the most minimum requirements as possible.
- Should have the ability to be automatically updated, e.g. using something like [Box](https://github.com/humbug/box).

#### Project Functionality
- Should have the ability to fully create/clone and configure a new project from the square-one repository. 
..- Automatically configure: Nginx, .projectID. 
..- Determine which local Docker Containers are required, e.g. Redis, Memcached, ElasticSearch.
..- Automatically create the required databases (local dev + tests).
..- Automatically configure codeception.
..- Automatically configure local-config.php, local-config.json.
- Should be able to automatically clone and configure existing projects.

#### Global Docker
- Should contain all Global Docker related functionality in one repository.
- Should automatically recognize changes when updated.
- Should automatically generate certificates for the Nginx proxy in addition to configuring this across multiple OS's.
- Should have the ability to display debugging information.
- Should include more diagnostic Docker Containers that can be run on demand: Mailhog, PHPMemcachedAdmin, PhpMyAdmin (already included).

#### Local Docker
- Start/stop/restart projects and any dependencies: Generating a Global Certificate, ensuring Global Containers are started...
- Run WP CLI commands in the container.
- Run WP CLI commands in the container with Xdebug enabled.
- Run composer commands in the container.
- Run automated tests.
- View docker logs.
- Automatically import/export databases for sharing, including any automation required there, e.g. search-replace, user data scrambling etc...
- Abstract other common development tasks like: Cache Clearing, Image generation, Nginx proxy to production for images etc...

#### Nice to have
- Ability to deploy from this (build from a docker container or perhaps communicate with Jenkins).
- Any required configurations for GitHub Workflows?
- 
