# Reciper

## Requirements
For installing this application for local development, Docker must be installed and enabled.

## Initial install

For the initial instalation, the following command must be executed in order to install the base dependencies, after that, sail will be installed.
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
If using macOS, configure the user and group user for the ones that you might want to use, that could differ from the standard groups.
