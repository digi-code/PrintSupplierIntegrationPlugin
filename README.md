# GameSeal test plugin
## Installation:
1. Download ZIP-archive from https://github.com/paulfcdd/gameseal_plugin and install it in Shopware admin panel
2. Clone repository and install it locally using composer
    - in `composer.json` in section `repositories` define local repository, for ex
   
    ```
   "repositories": [
        {
            "type": "path",
            "url": "/var/www/plugins/gameseal_plugin"
        }
    ],
   ```
    - next install plugin with command `composer require "gameseal/api-plugin @dev"`
3. Install and activate plugin

## Usage:
1. When plugin is installed and activated go to `/admin#/sw/extension/config/GamesealPlugin` to provide API source config
2. By default API source is currencyAPi. Go to `https://currencyapi.com/`, get your API key and place it in the API key field
3. To get latest currencies exchange rates use CLI command `php bin/console gameseal:currency:update`
4. Plugin provides an API that allows to get list of currencies and their codes

## API endpoint.
1. URL: `/api/_action/gameseal/get-currencies-data`
2. method: `GET`
3. parameters: `no`