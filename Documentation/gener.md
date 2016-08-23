# Création des comptes pour les accès

## Création de clients pour OAUTH


### Par requête HTML
** A venir **
### Par shell

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

## Création/gestion d'un compte utilisateur

### par ligne de commande
voir les commande disponible sur la doc de FOSUserBundle

### par HTML
voir la doc de FOSUserBundle
