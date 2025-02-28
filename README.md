# test_technique

Stack technique PHP - Symfony

- [ ] Creer une base de donnees  test_cisad

- [ ] Faire une table user 

| id  | username     | email        | password     | roles |
|-----|--------------|--------------|--------------|-------|
| int | varchar(255) | varchar(255) | varchar(255) | json  |

- [ ] Faire une page de connexion et d’enregistrement d’un utilisateur

- [ ] Pouvoir se connecter en tant qu’username

- [ ] Faire le crud de la table user 

- [ ] Faire une table infos

  | id  | rank         | victoire     | defaite      | user_id    |
  |-----|--------------|--------------|--------------|------------|
  | int | varchar(255) | varchar(255) | varchar(255) | One To One |

Quand nous sommes connectes en tant que ROLE_ADMIN

- [ ] Pouvoir importer un fichier csv pour ajouter plusieurs users
- [ ] Faire le crud de la table infos

Quand nous sommes une connectes en tant que ROLE_USER

- [ ] Pouvoir consulter son profil
