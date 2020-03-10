# Square One Global Docker

Status: **Alpha**

### Requirements

1. PHP 7.2+
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
