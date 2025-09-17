# to install the project
`docker run -u${UID} -w/app -v${PWD}:/app -it --rm composer:latest create-project fa-fo-a/app-skeleton:1.2.0`<br>
after getting copy of that repository you could by global search-replace replace `appnameplaceholder` to your application name
when its done that part could be removed and following content (below) may be used as project readme

project has example of architecture inside, it could be removed by `make uninstall_example`

To check endpoint that it works from the container you may do `/bin/bash test_request.sh`

# general

## make
for flawless experience you need to install `make`

## CI\CD
inside of `docker` dir there's `hooks` that is configured for container building at hub.docker.com<br>
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
make build
```

### to completely remove
```
make purge
```

### to start
```
make start
```
### to stop
```
make stop
```

## database
in case you decide to use database that project would have following defaults

db name: app<br>
test db name: app_test<br>
user: app<br>
password: pass<br>

### how use with project defaults
to use it you have to:
- uncomment db part in docker-compose
- uncomment db migrations in healthcheck.sh

## memprof
to profile memory we use https://github.com/arnaud-lb/php-memory-profiler
`memprof.output_dir` at container is set to `/var/www/html/var`, so files would appeat at `/var/www/html/var`
to trigger it use `MEMPROF_PROFILE=dump_on_limit php -d memory_limit=10m php highloadscript.php`

## xdebug
to xdebug expect port 9001

## to enter for cli commands
with xdebug
```
make enter-debug
```

without xdebug
```
make enter
```

## perform application checks
```
make healthcheck
```
