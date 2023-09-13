# Symfony lab

### Prérequis
Docker, Docker Compose

### Démarrer le projet
```shell
bin/dev/start
bin/dev/composer install
```

To rebuild images
```shell
bin/dev/start --build
```
Le site sera disponible à http://localhost:8000/

### Composer
Par exemple :
```shell
bin/dev/composer --version
```
### PHP Coding Standards Fixer
```shell
bin/dev/phpcs-fix
```
### PHP Coding Standards Fixer
```shell
bin/dev/phpstan analyse
```
### PHPUnit
```shell
bin/dev/phpunit
```

### Arrêter le projet
```shell
bin/dev/stop
```
