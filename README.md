# Installation

```
git clone <repo>
cd <dir>
composer install
cp .env.example .env
```

Puis remplir les infos de connexion à la base de données et d'accès à l'application :

```
DB_DRIVER => mysql, pgsql, sqlite
ADMIN_USER => Si vide, pas d'authentification. Sinon, association avec ADMIN_PASS
DISPLAY_ERRORS => 0 en production
```
