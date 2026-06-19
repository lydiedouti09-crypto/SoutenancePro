# SoutenancePro

Application web de gestion des soutenances de fin d'études développée avec Symfony.

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- Symfony CLI
- Git

## Installation

1. Cloner le dépôt :
```bash
git clone https://github.com/lydiedouti09-crypto/SoutenancePro.git
cd SoutenancePro
```

2. Installer les dépendances :
```bash
composer install
```

3. Configurer la base de données dans `.env` :
```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

4. Créer la base de données et exécuter les migrations :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Créer le compte administrateur :
```bash
php bin/console app:create-admin
```

## Lancement

```bash
symfony server:start
```

Accéder à l'application : http://localhost:8000

## Comptes par défaut

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@soutenancepro.tg | admin123 |

## Technologies utilisées

- Symfony 7
- Doctrine ORM
- SQLite
- Bootstrap 5
- Tabler Icons