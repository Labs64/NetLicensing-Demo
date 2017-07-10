<p align="center"><img src="http://netlicensing.io/img/labs64-logo.png"></p>

<p align="center">
<a href="https://travis-ci.org/Labs64/NetLicensing-Demo"><img src="https://travis-ci.org/Labs64/NetLicensing-Demo.svg" alt="Build Status"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
<a href="https://waffle.io/Labs64/NetLicensing-Demo"><img src="https://badge.waffle.io/Labs64/NetLicensing-Demo.svg?label=ready&title=Ready" alt="Stories in Ready"></a>
</p>

# Labs64 NetLicensing / Demo Application

This _NetLicensing Demo Application_ provides a simple way to explore basic NetLicensing functionalities, as well as integration options with real application code. Feel free to use code snippets from this project as a help for NetLicensing integration in your own product.

# Getting started

## With docker

<tbd>

## Without docker

* install NodeJS
* clone this repo
* copy _.env.example_ to _.env_
* execute _php artisan key:generate_ (command will add APP_KEY to the _.env_)
* modify keys beginning with _NLIC_ in the _.env_ file with your values:
    * NLIC_BASE_URL - NetLicensing API base url
    * NLIC_AUTH_USERNAME - NetLicensing username
    * NLIC_AUTH_PASSWORD - NetLicensing password
    * NLIC_AUTH_API_KEY - API Key for NetLicensing
* execute _npm install_
* execute _npm run dev_
