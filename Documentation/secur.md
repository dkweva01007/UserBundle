# Sécurisation

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