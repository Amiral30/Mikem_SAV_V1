# Dossier de Projet : Système de Gestion SAV Mikem Technologie

Ce document constitue une synthèse exhaustive du projet **SAV Mikem**, de sa conception à son déploiement final, à destination d'un mémoire technique.

---

## 1. Contexte et Problématique

### 1.1 Présentation du Projet
**Mikem Technologie** est une entreprise spécialisée dans les solutions technologiques nécessitant un service après-vente (SAV) rigoureux. La qualité du suivi des interventions est le pilier de la satisfaction client.

### 1.2 La Problématique de Départ
Avant la mise en place du système, la gestion des interventions reposait sur des processus semi-manuels (appels téléphoniques, fiches papier, messages non centralisés). 
Les défis identifiés étaient :
- **Perte d'informations** : Difficulté à conserver un historique fiable des interventions passées.
- **Délai de Réactivité** : Latence entre le signalement d'une panne et l'affectation effective d'un technicien.
- **Manque de Visibilité** : Impossibilité pour l'administration de suivre l'état d'avancement des missions en temps réel sur le terrain.
- **Sécurité** : Absence de validation stricte de l'identité des techniciens accédant aux données clients.

---

## 2. Solution Proposée : Le Système SAV Mikem

La solution est une plateforme **Web & Mobile (PWA)** centralisée, bilingue (Technicien/Admin), conçue pour fluidifier la communication et automatiser le suivi.

### 2.1 Architecture et Choix Techniques
- **Cœur du système** : Framework Laravel 11 (Architecture MVC), garantissant une sécurité native et une structure scalable.
- **Mobilité (PWA)** : Utilisation de Service Workers et d'un manifest PWA pour transformer le site en application installable sur smartphone (Android/iOS) sans passer par les stores.
- **Interface Utilisateur** : Design moderne basé sur le **Glassmorphism**, avec une gestion intelligente du mode sombre pour le confort visuel sur le terrain.

---

## 3. Détail des Fonctionnalités Actuelles (V13)

### 3.1 Espace Administrateur (Pilotage)
- **Dashboard Décisionnel** : Vue d'ensemble des statistiques (missions du jour, taux d'occupation, intervenants actifs).
- **Gestion du Workflow** : Création de missions, définition des priorités, et affectation directe d'équipes avec désignation d'un chef d'équipe par intervention.
- **Validation Qualité** : Module dédié à la revue des rapports envoyés par les techniciens avant clôture officielle de l'intervention.

### 3.2 Espace Technicien (Terrain)
- **Onboarding Sécurisé (OTP)** : Système de vérification par email. Le technicien reçoit un code unique à 6 chiffres pour activer son compte, garantissant que l'identité est liée à une boîte mail d'entreprise valide.
- **Gestion des Interventions** : 
    *   Consultation des détails de mission (adresse client, description du problème).
    *   Flux d'états : Acceptation -> Début d'intervention -> Rédaction du rapport.
- **Reporting Multimédia** : Saisie de compte-rendu textuel et téléchargement de photos avec outil de rognage (Cropping) intégré côté client pour optimiser le poids des fichiers.

---

## 4. Défis Techniques et Résolution (Études de Cas)

Le déploiement sur un hébergement mutualisé (InfinityFree) a présenté des défis que nous avons résolus par des approches d'ingénierie logicielle :

### 4.1 Erreurs 500 et Synchronisation de Base de Données
Certaines erreurs 500 initiales étaient dues à un décalage entre la structure de la base de données locale (SQLite) et celle de production (MySQL). 
- **Solution** : Audit complet des migrations et exportation manuelle du schéma SQL synchronisé pour garantir l'intégrité des données.

### 4.2 Problématique du Stockage (Symlink Bypass)
Les hébergeurs mutualisés bloquent souvent la fonction `storage:link` (lien symbolique).
- **Solution** : Modification de la configuration `config/filesystems.php` pour que le disque public pointe directement vers `public/storage`, évitant ainsi la dépendance aux fonctions système bloquées de l'hébergeur.

---

## 5. Perspectives d'Évolution

Le système est conçu pour évoluer vers une suite complète de gestion d'entreprise :
- **Géo-localisation** : Intégration de Maps pour optimiser les itinéraires des équipes sur le terrain.
- **Gestion des Stocks** : Inventaire en temps réel des pièces détachées consommées lors des interventions.
- **Portail Client** : Espace permettant aux clients de suivre l'avancement de leur demande et de noter la prestation.
- **Automatisation Administrative** : Génération de documents PDF (factures, bons d'intervention) dès la validation du rapport.

---

> [!IMPORTANT]
> Ce système n'est pas qu'un simple outil de gestion, c'est un accélérateur de transformation numérique pour Mikem Technologie, remplaçant l'incertitude du papier par la précision de la donnée numérique.
