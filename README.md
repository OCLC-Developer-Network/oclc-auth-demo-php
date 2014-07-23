# OCLC Auth Explicit Authorization Code Sample App

A simple PHP application that demonstrates a simple WAYF (Where are your from) login using the Explicit Authorization code flow and returns data from the WMS Circulation web service.
For demonstration purposes only. No tests provided.

## Installation

### Step 1: Install from GitHub

In a Terminal Window

```bash
$ cd {YOUR-APACHE-DOCUMENT-ROOT}
$ git clone https://github.com/OCLC-Developer-Network/oclc-auth-demo-php.git
$ cd oclc-auth-demo-php
```

### Step 2: Use composer to install the dependencies

```bash
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
```

[Composer](https://getcomposer.org/doc/00-intro.md) is a dependency management library for PHP. It is used to install the required libraries for testing and parsing RDF data. The dependencies are configured in the file `composer.json`.

### Step 3: Comfigure your environment file with your WSKey/secret and other info based on the sample file

```bash
$ cd app/config
$ cp sampleConfig.yaml config.yaml
$ vi config.yaml
```

Enter your WSKey, secret and other information

## Usage

To run the app, point your web browser at the localhost address where these instructions will install it by default. 

[http://localhost/oclc-auth-demo-php/](http://localhost/oclc-auth-demo-php/)