walle-workerman
----
by@Vestin

* background job
* deploy console log
* commandbus design pattern implementation

## dependency
see `composer.json`

## Install
```
composer install
```

Edit config files
```
cp .env.example .env
// then edit .env fit your enviroment
```

## Usage

1. start api service
`php app/start.php start`

2. start queue service
`php queue/start.php start`

3. start background-job service
`php job/start.php start`