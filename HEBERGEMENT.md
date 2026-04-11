# 🚀 Guide d'Hébergement Gratuit - SAV MIKEM

Félicitations ! Ton application est maintenant robuste et moderne. Pour la mettre en ligne gratuitement, voici les meilleures options actuelles et la procédure à suivre.

---

## 🏗️ Recommandations d'Hébergeurs

| Hébergeur | Avantages | Inconvénients |
| :--- | :--- | :--- |
| **Render.com** (Recommandé) | Moderne, supporte Git, SSL automatique. | Nécessite un peu de config (Dockerfile ou Script). |
| **InfinityFree** | Hébergement PHP/MySQL classique, illimité en temps. | Plus lent, pas de support direct de Git (FTP). |
| **Railway.app** | Très simple, déploiement en 1 clic. | Offre gratuite limitée à 5$ de crédit (essai). |

---

## 🛠️ Option 1: Déploiement sur Render.com (Le plus pro)

Render est idéal car il déploie directement depuis ton compte GitHub.

### 1. Préparer ton projet (Local)
*   Assure-toi que tout est envoyé sur un dépôt **GitHub (Privé)**.
*   Crée un fichier nommé `build.sh` à la racine pour automatiser les étapes :
    ```bash
    #!/usr/bin/env bash
    exit on error
    set -o errexit

    composer install --no-dev --optimize-autoloader
    npm install
    npm run build
    php artisan migrate --force
    php artisan storage:link
    ```

### 2. Créer une Database
*   Sur Render, crée une **PostgreSQL** ou **MySQL** (gratuite pendant 90 jours ou utilise un service externe comme *Aiven.io* pour du MySQL gratuit illimité).

### 3. Créer le "Web Service"
*   Connecte ton GitHub à Render.
*   **Runtime** : PHP
*   **Build Command** : `./build.sh`
*   **Start Command** : `vendor/bin/heroku-php-apache2 public/`

### 4. Variables d'Environnement (Crucial)
Dans l'onglet **Environment** de Render, ajoute :
*   `APP_KEY` : (Celle de ton .env)
*   `APP_ENV` : production
*   `APP_DEBUG` : false
*   `APP_URL` : https://ton-app.onrender.com
*   `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Infos de ta DB en ligne).

---

## 🛠️ Option 2: Déploiement sur InfinityFree (Le plus simple)

C'est un hébergement classique via FTP.

### 1. Exportation
*   Exporte ta base de données locale via **phpMyAdmin** (`savmikem_bd.sql`).
*   Zippe tout ton dossier de projet (sauf `node_modules` et `vendor`).

### 2. Configuration
*   Crée un compte sur InfinityFree et une base de données MySQL vide.
*   Importe ton fichier `.sql`.
*   Télécharge tes fichiers via un logiciel comme **FileZilla** dans le dossier `htdocs`.

> [!IMPORTANT]
> Sur un hébergement classique, tu devras peut-être déplacer le contenu du dossier `public` à la racine ou modifier le fichier `.htaccess` pour pointer vers le dossier public.

---

## 📧 Configuration des Emails en Production

> [!TIP]
> Pour que les emails de mission continuent de partir en ligne, ne touche pas à ton `MAIL_PASSWORD` Gmail (le mot de passe d'application) dans tes variables d'environnement, il fonctionnera aussi sur le serveur !

---

## 📝 Check-list de mise en ligne

1.  **Fermer le Debug** : Vérifie que `APP_DEBUG=false`.
2.  **Lien de stockage** : Assure-toi que la commande `php artisan storage:link` est exécutée sur le serveur pour que les photos des rapports s'affichent.
3.  **HTTPS** : Utilise toujours le lien en `https://` pour la sécurité des mots de passe.

> [!SUCCESS]
> Une fois en ligne, n'oublie pas de mettre à jour la variable `APP_URL` dans ton `.env` pour que le bouton dans les emails de mission renvoie vers le bon site !
