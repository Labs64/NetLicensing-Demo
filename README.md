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

### System Requirements
To be able to run NetLicensing Demo Application you have to meet the following requirements:
- PHP > 5.6.4
- PHP Extensions: PDO, cURL, Mbstring, Tokenizer, Mcrypt, XML, GD
- Node.js > 6.0
- Composer > 1.0.0

### Installation
1. Install Composer using detailed installation instructions [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
2. Install Node.js using detailed installation instructions [here](https://nodejs.org/en/download/package-manager/)
3. Clone repository
```
$ git clone https://github.com/Labs64/NetLicensing-Demo.git
```
4. Change into the working directory
```
$ cd NetLicensing-Demo
```
5. Copy `.env.example` to `.env`
```
$ cp .env.example .env
```
modify keys beginning with NLIC in the .env file with your values:
  - _NLIC_BASE_URL_ - NetLicensing API base URL
  - _NLIC_AGENT_BASE_URL_ - NetLicensing Agent base URL
  - _NLIC_AUTH_USERNAME_ - NetLicensing username
  - _NLIC_AUTH_PASSWORD_ - NetLicensing password
  - _NLIC_AUTH_API_KEY_ - API Key for NetLicensing

6. Install composer dependencies
```
$ composer install --prefer-dist
```
7. An application key can be generated with the command
```
$ php artisan key:generate
```
8. Execute following commands to install other dependencies
```
$ npm install
$ npm run dev
```

### Run

To start the PHP built-in server
```
$ php artisan serve --port=8080
or
$ php -S localhost:8080 -t public/
```

Now you can browse the site [http://localhost:8080](http://localhost:8080)
