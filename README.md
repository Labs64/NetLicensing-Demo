<p align="center"><img src="http://netlicensing.io/img/labs64-logo.png"></p>

<p align="center">
<a href="https://travis-ci.org/Labs64/NetLicensing-Demo"><img src="https://travis-ci.org/Labs64/NetLicensing-Demo.svg" alt="Build Status"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
<a href="https://waffle.io/Labs64/NetLicensing-Demo"><img src="https://badge.waffle.io/Labs64/NetLicensing-Demo.svg?label=ready&title=Ready" alt="Stories in Ready"></a>
</p>

# Labs64 NetLicensing / Demo Application

This _NetLicensing Demo Application_ provides a simple way to explore basic [NetLicensing](http://netlicensing.io) functionalities, as well as integration options with real application code. Feel free to use code snippets from this project as a help for NetLicensing integration in your own product.

# Getting started

## With Docker

This project is based on [docker-compose](https://docs.docker.com/compose/). By default, the following containers are started: _netlicensing-demo (centos:7 based), nginx_. The `/var/www/netlicensing-demo` directory is the web root which is mapped to the nginx container.
You can directly edit configuration files from within the repo as they are mapped to the correct locations in containers.

### System Requirements
To be able to run Laravel Boilerplate you have to meet the following requirements:
* [docker](https://www.docker.com)
* [docker-compose](https://docs.docker.com/compose/)

### Run

1. Clone repository
```
$ git clone https://github.com/Labs64/NetLicensing-Demo.git
```

2. Copy `.env.example` to `.env` and modify according to your environment
```
$ cp .env.example .env
```

3. Start environment
```
$ docker-compose up -d
```

4. Build project
```
$ docker exec netlicensing-demo ./dockerfiles/bin/prj-build.sh
```

Now you can browse the site [http://localhost:80](http://localhost:80)

---

5. Stop environment
```
$ docker-compose down
```

## Without Docker

* install NodeJS
* clone this repo
* copy _.env.example_ to _.env_
* install dependencies by executing _composer install --prefer-dist_
* execute _php artisan key:generate_ (adds APP_KEY to the _.env_)
* modify keys beginning with _NLIC_ in the _.env_ file with your values:
    * NLIC_BASE_URL - NetLicensing API base url
    * NLIC_AGENT_BASE_URL - NetLicensing Agent base url
    * NLIC_AUTH_USERNAME - NetLicensing username
    * NLIC_AUTH_PASSWORD - NetLicensing password
    * NLIC_AUTH_API_KEY - API Key for NetLicensing
* execute _npm install_
* execute _npm run dev_
