# general
Goal of repository is to provide convenient source for getting config for devenv by author and, maybe, people who find it useful.

# usage and, possible, (part of) readme of future project

## container building
there's both instructions in bibucket-pipelines.yml and [hub.docker.com autobuilds](https://docs.docker.com/docker-hub/builds/) can be used for that<br>
to launch phpunit tests in hub.docker.com autobuilds define env var RUN_TESTS in autobuilds config.<br>
_Note that migrations part commented and you may need to uncomment/create own in both solutions_

## local docker
docker should be installed to run commands below

### my user is not 1000
if you have user differ from 1000 (check by `echo $UID`), prepend any following docker-compose commands by `USER_ID=${UID}`
like
```
USER_ID=${UID} docker compose up -d
```

### to build and launch
```
docker compose up -d --force-recreate
```

### to start
```
docker compose start
```
### to stop with preserving internals
```
docker compose stop
```

## database
db name: app<br>
test db name: app_test<br>
user: app<br>
password: pass<br>

## supervisord
at first launch it gonna give up launching stuff due to no migrations and etc
make sure everything migrated and just stop/start containers after project setup
all it logs is inside `var/log` so track it by need
to work with `supervisorctl` as we have non-usual place for `.sock` do not forget to define it explicitly by `supervisorctl -c /etc/supervisor/conf.d/app.conf action`

## memprof
to profile memory we use https://github.com/arnaud-lb/php-memory-profiler
`memprof.output_dir` at container is set to `/var/www/html/var`, so files would appeat at `/var/www/html/var`
to trigger it use `MEMPROF_PROFILE=dump_on_limit php -d memory_limit=10m php highloadscript.php`

## xdebug
to xdebug expect port 9001

### to enter for cli commands
with xdebug
```
docker exec -u${UID} -it -w /var/www/html -e XDEBUG_MODE=debug app /bin/bash
```

without xdebug
```
docker exec -u${UID} -it -w /var/www/html -e app /bin/bash
```
