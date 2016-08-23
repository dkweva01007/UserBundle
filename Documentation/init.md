# Initialisation

## Création de la base de donnée

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