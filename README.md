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

# Configuration

[Configurer](./Documentation/config.md).

# Sécurisation

[Sécuriser](./Documentation/secur.md).

# Initialisation

[Initialiser](./Documentation/init.md).

# Les premiers entré en Base de donnée pour DBUserBundle

[commencer](./Documentation/init.md).

# Exemple de fonctionnement.

[Exemple](./Documentation/start.md).