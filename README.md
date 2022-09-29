# eurosender-link-shortener

> Dockerized environment of eurosender-link-shortener

## Table of contents

- [Prerequisites](#prerequisites)
    - [Benchmark](#benchmark)
- [Installation](#installation)
    - [Install `eurosender-link-shortener`](#install-eurosender-link-shortener)
    - [Prepare DB](#prepare-db)
- [Use the application!](#use-the-application)
- [Services](#services)
- [Mysql connection](#mysql-connection)
- [F.A.Q.](#faq)
    - [How to execute performance tests with k6](#how-to-execute-performance-tests-with-k6)
      - [Output of execution with 1000 virtual users](#output-of-execution-with-1000-vus)
    - [How to execute tests with coverage report](#how-to-execute-tests-with-coverage-report)
    - [How to fix application access issue after build](#how-to-fix-application-access-issue-after-build)
    
## Prerequisites

- Install `docker-compose`: https://docs.docker.com/compose/install/

### Benchmark

- Install `k6`: https://k6.io/docs/getting-started/installation/

## Installation

### Install `eurosender-link-shortener`

- create the local network running `docker network create local-network`
- build all the images and volumes by running the following command: `docker-compose up -d --build -V``

### Prepare DB 
  In `dev` mode
```bash
docker exec -it eurosender_phpfpm php bin/console mysql:prepare --create
docker exec -it eurosender_phpfpm php bin/console elasticsearch:prepare
```

## Use the application!

You can find the API documentation there `openapi/api_v1.yml`

Example of call to use the application: `http://eurosender.dev.localhost/api/v1/shorten?link=https://www.google.com/search?q=docker`

## Services

| Name                        | URL                                           | Description                          |
|-----------------------------|-----------------------------------------------|--------------------------------------|
| eurosender_nginx            | http://eurosender.dev.localhost               | Eurosender application               |
| eurosender_elasticsearch    | http://elasticsearch.dev.localhost            | Elasticsearch                        |
| eurosender_kibana           | http://kibana.dev.localhost                   | Kibana                               |

## Mysql connection

- Username: `root`
- Password: `root`

**Note**: Mysql can be accessed within its container through the following command
```bash
docker exec -it eurosender_mysql mysql -uroot -proot dev
```

## F.A.Q.

### How to execute performance tests with k6
```bash
k6 run benchmark/bench.js
```
**Note**: To run k6 the script with defining the number of virtual users (example with 1000 VUs)
```bash
k6 run benchmark/bench.js -e VUS=1000
```

#### Output of execution with 1000 VUs

P95 of HTTP request duration: p(95)=280.79ms

```
         /\      |‾‾| /‾‾/   /‾‾/   
    /\  /  \     |  |/  /   /  /    
   /  \/    \    |     (   /   ‾‾\  
  /          \   |  |\  \ |  (‾)  |
 / __________ \  |__| \__\ \_____/ .io

execution: local
script: benchmark/bench.js
output: -

scenarios: (100.00%) 1 scenario, 1000 max VUs, 40s max duration (incl. graceful stop):
* default: 1000 looping VUs for 10s (gracefulStop: 30s)


running (16.9s), 0000/1000 VUs, 2635 complete and 0 interrupted iterations
default ✓ [======================================] 1000 VUs  10s

    ✓ successfully shortened
    ✓ successfully redirected

    checks.....................: 100.00% ✓ 5330   ✗ 0     
    data_received..............: 2.9 MB  169 kB/s
    data_sent..................: 736 kB  43 kB/s
    http_req_blocked...........: avg=95.81µs  min=1.55µs med=4.58µs  max=49.51ms  p(90)=143.2µs  p(95)=184.83µs
    http_req_connecting........: avg=77.77µs  min=0s     med=0s      max=49.46ms  p(90)=80.36µs  p(95)=104.86µs
    http_req_duration..........: avg=117.07ms min=4.58ms med=81.06ms max=317.85ms p(90)=253.25ms p(95)=280.79ms
    http_req_receiving.........: avg=70.55µs  min=17.9µs med=66.09µs max=1.6ms    p(90)=99.32µs  p(95)=112.56µs
    http_req_sending...........: avg=28.92µs  min=7.09µs med=20.81µs max=298.58µs p(90)=52.48µs  p(95)=68.58µs 
    http_req_tls_handshaking...: avg=0s       min=0s     med=0s      max=0s       p(90)=0s       p(95)=0s      
    http_req_waiting...........: avg=116.97ms min=4.5ms  med=81ms    max=317.75ms p(90)=253.17ms p(95)=280.66ms
    http_reqs..................: 5330    312.903051/s
    iteration_duration.........: avg=4.72s    min=2.04s  med=4.7s    max=7.53s    p(90)=6.76s    p(95)=7.02s   
    iterations.................: 2665    156.451526/s
    vus........................: 0       min=0    max=1000
    vus_max....................: 1000    min=1000 max=1000

```

### How to execute tests with coverage report
```bash
php -d xdebug.mode=coverage vendor/bin/phpunit --coverage-html <any_directory>
```

### How to fix application access issue after build
This issue might occur after build
```bash
Fatal error: Uncaught Error: Failed opening required '/var/www/html/vendor/autoload_runtime.php' (include_path='.:/usr/local/lib/php') in /var/www/html/public/index.php:5 Stack trace: #0 {main} thrown in /var/www/html/public/index.php on line 5
```
In this case, libraries need to be reinstalled with composer:
 - connect to the application container `docker exec -it eurosender_phpfpm /bin/bash`
 - run composer to install libraries `composer install`