# 📅 Symfony Events CSV Reader

This is a minimal Symfony service that reads event data from a CSV file and exposes it via a simple RESTful API.

## 🚀 Getting Started

Start the local server:

```shell
symfony server:start
```

Or using a PHP server:

```shell
php -S localhost:8000 -t public
```

## 🌐 API Endpoints

`GET /events`: [Returns all events](https://localhost:8000/events)

`GET /events/1`: [Returns the event with a given ID](https://localhost:8000/events/1)

## 📁 Project Structure

```shell
├── config
├── data # initial challenge repo
│   ├── exercise.php
│   ├── README.md
│   └── seeds.csv # CSV file with event data
├── src
│   ├── Controller # Symfony controller & routing
│   ├── Dto # Data Transfer Objects
│   └── Service # Core business logic (CSV reading)
└── tests # PHPUnit tests
```

## 🧪 Running Tests

```shell
# php bin/phpunit
composer test
```

## 🧠 Static Analysis

```shell
# phpstan analyse
composer analyze
```

## 🎨 Code Style

Check formatting:

```shell
# php-cs-fixer fix --dry-run --diff
composer lint
```

Fix formatting issues:

```shell
# php-cs-fixer fix --diff
composer fix
```
