# Intallation

Ce projet à été créer sur un Symfony2.8
Vous devez d'abord modifier votre composer.json pour rajouter le projet, les dépendance devraient être installer tous seul.

```json
// composer.json
"repositories" : [{
        "type" : "vcs",
        "url" : "git@bitbucket.org:dakotabox/userbundle.git"
}]
```
Ensuite, rajouter dans votre AppKernel.php

```php
// app/AppKernel.php
new FOS\UserBundle\FOSUserBundle(),
new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
new DB\UserBundle\DBUserBundle(),
```
ajouter les paramètres pour la base de donnée du DBUserBundle

```php
// app/config.yml
    database_name2:     YOU_DB
    database_user2:     YOUR_USER_ACCESS
    database_password2: YOUR_USER_PSWD

//si utiliser plus de paramètre pour l’accès à cette base de donné, créer les indiquer les dans la connexion dans config.php  

```

## Configuration

Rajouter le routing suivant :

```yml
// app/config/routing.yml
DB_User:
    resource: "@DBUserBundle/Resources/config/routing.yml"
    prefix:   /

```

Ensuite rajouter les configurations suivantes selon vos besoin

```yml
// app/config/routing.yml

translator:      { fallbacks: ["%locale%"] } //pour FOSUserBundle

...

doctrine:
    dbal:
        connections:
            service: //représente le connexion à la base de donnée pour votre Bundle principal, à ne pas choisir n'importe comment
                driver:   pdo_mysql
                host:     "%database_host%"
                port:     "%database_port%"
                dbname:   "%database_name%"
                user:     "%database_user%"
                password: "%database_password%"
                charset:  UTF8
            login: //Primordial pour le bundle DBUserBundle
                   //il sont regrouper dans la même base de donnée, c'est ici que vous rajouter les configurations suplémentaires pour l'accès à cette base de donnée
                driver:   pdo_mysql
                host:     "%database_host%"
                port:     "%database_port%"
                dbname:   "%database_name2%"
                user:     "%database_user2%"
                password: "%database_password2%"
                charset:  UTF8

    orm:
        auto_generate_proxy_classes: %kernel.debug%
        default_entity_manager:   login //primordial
        entity_managers:
            login:
                auto_mapping: true
                connection:   login
                mappings:
                    DBUserBundle: ~
                    FOSOAuthServerBundle: ~

            service: // //représente le nom de votre entity manager, à ne pas choisir n'importe comment
                auto_mapping: false //primordial
                connection:   service //indiquer le nom de votre connection principal
                mappings:
                    DBServiceBundle: ~ // le nom de votre Bundle Principal

                //si vous utiliser des extension pour doctrine, voici un exemple de comment les gérer.
                /*
                 * dql:
                 *    datetime_functions:
                 *        year: DoctrineExtensions\Query\Mysql\Year
                 *    string_functions:
                 *        month: DoctrineExtensions\Query\Mysql\Month
                 *    numeric_functions:
                 *        round: DoctrineExtensions\Query\Mysql\Round
                 */

fos_user:
    db_driver: orm
    firewall_name: api
    user_class: DB\UserBundle\Entity\User

fos_oauth_server:
    db_driver: orm
    client_class:        DB\UserBundle\Entity\Client
    access_token_class:  DB\UserBundle\Entity\AccessToken
    refresh_token_class: DB\UserBundle\Entity\RefreshToken
    auth_code_class:     DB\UserBundle\Entity\AuthCode
    service:
        user_provider: fos_user.user_manager

//si vous uttilser nelmio API
/*nelmio_api_doc:
 *   sandbox:
 *       authentication:
 *           name: access_token
 *           type: bearer 
 *           delivery: query
 */
```

### Sécurisation

Rajouter dans votre security.yml

```yml
# app/config/security.yml
security:
    
    encoders:
            FOS\UserBundle\Model\UserInterface: bcrypt

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
            
        oauth_token: // vous pouvez aussi rajouter le chemin "/oauth/v2/auth"
            pattern:    ^/oauth/v2/token
            security:   false
        api: // représente votre Bundle principal
            pattern:   ^/api/v1
            fos_oauth:  true
            stateless:  true // selon votre gout
            anonymous: false // selon votre gout

    access_control:
        - { path: fos_user_security_logout, roles: IS_AUTHENTICATED_ANONYMOUSLY }

```

## Initialisation

### Création de la base de donnée

Nous allons créer la base de donnée pour le bundle:

```shell
php app/console doctrine:schema:update --em login --force
```

Vous devriez avoir ceci comme résultat.

```shell
mysql> describe users;
+-----------------------+--------------+------+-----+---------+----------------+
| Field                 | Type         | Null | Key | Default | Extra          |
+-----------------------+--------------+------+-----+---------+----------------+
| id                    | int(11)      | NO   | PRI | NULL    | auto_increment |
| username              | varchar(255) | NO   |     | NULL    |                |
| username_canonical    | varchar(255) | NO   | UNI | NULL    |                |
| email                 | varchar(255) | NO   |     | NULL    |                |
| email_canonical       | varchar(255) | NO   | UNI | NULL    |                |
| enabled               | tinyint(1)   | NO   |     | NULL    |                |
| salt                  | varchar(255) | NO   |     | NULL    |                |
| password              | varchar(255) | NO   |     | NULL    |                |
| last_login            | datetime     | YES  |     | NULL    |                |
| locked                | tinyint(1)   | NO   |     | NULL    |                |
| expired               | tinyint(1)   | NO   |     | NULL    |                |
| expires_at            | datetime     | YES  |     | NULL    |                |
| confirmation_token    | varchar(255) | YES  |     | NULL    |                |
| password_requested_at | datetime     | YES  |     | NULL    |                |
| roles                 | longtext     | NO   |     | NULL    |                |
| credentials_expired   | tinyint(1)   | NO   |     | NULL    |                |
| credentials_expire_at | datetime     | YES  |     | NULL    |                |
+-----------------------+--------------+------+-----+---------+----------------+
17 rows in set (0.00 sec)

mysql> describe oauth2_clients;
+---------------------+--------------+------+-----+---------+----------------+
| Field               | Type         | Null | Key | Default | Extra          |
+---------------------+--------------+------+-----+---------+----------------+
| id                  | int(11)      | NO   | PRI | NULL    | auto_increment |
| random_id           | varchar(255) | NO   |     | NULL    |                |
| redirect_uris       | longtext     | NO   |     | NULL    |                |
| secret              | varchar(255) | NO   |     | NULL    |                |
| allowed_grant_types | longtext     | NO   |     | NULL    |                |
+---------------------+--------------+------+-----+---------+----------------+
5 rows in set (0.00 sec)

mysql> describe oauth2_access_tokens;
+------------+--------------+------+-----+---------+----------------+
| Field      | Type         | Null | Key | Default | Extra          |
+------------+--------------+------+-----+---------+----------------+
| id         | int(11)      | NO   | PRI | NULL    | auto_increment |
| client_id  | int(11)      | NO   | MUL | NULL    |                |
| user_id    | int(11)      | YES  | MUL | NULL    |                |
| token      | varchar(255) | NO   | UNI | NULL    |                |
| expires_at | int(11)      | YES  |     | NULL    |                |
| scope      | varchar(255) | YES  |     | NULL    |                |
+------------+--------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)

mysql> describe oauth2_auth_codes;
+--------------+--------------+------+-----+---------+----------------+
| Field        | Type         | Null | Key | Default | Extra          |
+--------------+--------------+------+-----+---------+----------------+
| id           | int(11)      | NO   | PRI | NULL    | auto_increment |
| client_id    | int(11)      | NO   | MUL | NULL    |                |
| user_id      | int(11)      | YES  | MUL | NULL    |                |
| token        | varchar(255) | NO   | UNI | NULL    |                |
| redirect_uri | longtext     | NO   |     | NULL    |                |
| expires_at   | int(11)      | YES  |     | NULL    |                |
| scope        | varchar(255) | YES  |     | NULL    |                |
+--------------+--------------+------+-----+---------+----------------+
7 rows in set (0.00 sec)

mysql> describe oauth2_refresh_tokens;
+------------+--------------+------+-----+---------+----------------+
| Field      | Type         | Null | Key | Default | Extra          |
+------------+--------------+------+-----+---------+----------------+
| id         | int(11)      | NO   | PRI | NULL    | auto_increment |
| client_id  | int(11)      | NO   | MUL | NULL    |                |
| user_id    | int(11)      | YES  | MUL | NULL    |                |
| token      | varchar(255) | NO   | UNI | NULL    |                |
| expires_at | int(11)      | YES  |     | NULL    |                |
| scope      | varchar(255) | YES  |     | NULL    |                |
+------------+--------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)
```

### Création des comptes pour les accès

#### Création de clients pour OAUTH


##### Par requête HTML
** A venir **
##### Par shell

l'ajout de client est faite selon de la procédure de FOSOauthServerBundle, une URL de redirection et un/des type d'accès

```shell
php app/console DB:oauth-server:client:create [--redirect-uri REDIRECT-URI] [--grant-type GRANT-TYPE]
```
Pour rajouter les plusieurs type d'accès, il suffit juste d'inscrire autant de [--grant-type GRANT-TYPE] que vous le souhaitez.
Voici les valeur possible pour le type d'accès:
* authorization_code
* password //lier avec le FOSUserBundle, mon bundle lie FOSUserBundle avec FOSOauthServerBundle
* refresh_token
* token //
* client_credentials

#### Création/gestion d'un compte utilisateur

##### par ligne de commande
voir les commande disponible sur la doc de FOSUserBundle

##### par HTML
voir la doc de FOSUserBundle
