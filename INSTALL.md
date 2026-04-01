# Installation - SAV Mikem

## Prérequis
- WAMP Server avec MySQL actif
- PHP 8.2+ (vérifié)
- Composer installé
- Node.js + npm (pour les assets si besoin)

## Étape 1 : Initialiser Laravel

Ouvrez un terminal (CMD ou PowerShell) dans le dossier du projet :

```powershell
cd C:\Users\Amiral\Documents\Mikem
```

Exécutez le script d'installation automatique :

```powershell
powershell -ExecutionPolicy Bypass -File setup.ps1
```

## Étape 2 : Configurer la base de données

1. Ouvrez phpMyAdmin (http://localhost/phpmyadmin)
2. Créez la base de données : `savmikem_bd` (utf8mb4_unicode_ci)
3. Modifiez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=p
DB_USERNAME=root
DB_PASSWORD=
```

## Étape 3 : Migrations et Seeders

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

## Étape 4 : Lancer le serveur

```bash
php artisan serve
```

Accédez à : http://localhost:8000

## Comptes par défaut

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@savmikem.com | admin123 |
| Technicien (test) | technicien@savmikem.com | tech123 |

## Configuration Email (optionnel)

Par défaut, les emails sont enregistrés dans `storage/logs/laravel.log`.
Pour envoyer de vrais emails, configurez dans `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="SAV Mikem"
```
