# Configuration

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