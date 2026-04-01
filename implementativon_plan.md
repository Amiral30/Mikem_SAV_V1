# Plan d'Implémentation V3 : Rapport Financier & PDF

L'objectif de cette version (V3) est d'adapter la devise au contexte local (FCFA) et de fournir un outil analytique et exportable à l'administrateur pour la facturation des déplacements des techniciens (Génération de PDF). Une future version implémentera le filtrage par mois, tel que demandé.

## Changements Proposés

---

### [Configuration & Dépendance]

#### [NOUVEAU] Installation de paquet
Installation du standard de l'industrie pour Laravel afin de manipuler les fichiers PDF : **`barryvdh/laravel-dompdf`**. Cela permettra de prendre de l'HTML (vues Blade) et de le télécharger directement en document imprimable et partageable (`.pdf`).

---

### [Vues Globales : Devise (FCFA)]

Remplacement systématique du symbole (€) et ajustement du formatage monétaire (souvent sans virgules décimales pour le FCFA).

#### [MODIFY] `resources/views/admin/missions/show.blade.php`
- Affichage de la valeur sous la forme "X FCFA".

#### [MODIFY] `resources/views/admin/missions/edit.blade.php`
- Remplacement du label "Prix de déplacement (€)" par "Prix de déplacement (FCFA)".

#### [MODIFY] `resources/views/technicien/missions/show.blade.php`
- Affichage au format "X FCFA".

#### [MODIFY] `resources/views/emails/mission-assignee.blade.php`
- Ajustement sur la notification mail qui est aussi en euros.

---

### [Espace Admin : Dossier Technicien]

#### [MODIFY] `routes/web.php`
- Ajout d'une nouvelle route cliquable : `GET /techniciens/{user}/export`.

#### [MODIFY] `app/Http/Controllers/Admin/TechnicienController.php`
- **Ajout de méthode** : `exportPdf(User $technicien)`.
- Calcul de la **Somme totale des déplacements** à partir de la relation `$technicien->missions()`.
- Appel de la librairie PDF pour retourner le téléchargement à l'utilisateur.

#### [MODIFY] `resources/views/admin/techniciens/show.blade.php`
- Ajout d'un bloc statistique mettant en valeur le cumul des frais de transport du technicien observé.
- Bouton principal : **📄 Exporter le rapport en PDF**.

---

### [Nouveau Template PDF]

#### [NEW] `resources/views/admin/techniciens/pdf.blade.php`
Création de la maquette stricte conçue uniquement pour l'impression A4 :
- En-tête : Logo ou nom de Mikem Technologie.
- Informations : Fiche d'identité du technicien (Nom, Contact) et date d'exportation.
- Tableau des performances : Liste des missions assignées avec leurs détails respectifs (Date, Titre, Adresse) et la colonne `Indemnité (FCFA)`.
- Récapitulatif : Le gros total calculé en FCFA.

---

## User Review Required

> [!IMPORTANT]
> Pour que le système puisse générer des vrais fichiers PDF locaux, nous devons installer la librairie **dompdf**. Dès que vous m'aurez donné l'approbation du plan, je lancerai discrètement la commande d'installation et je modifierai tout le code.
> **Note** : Comme vous l'avez précisé, ce premier jet calculera *Toutes* les missions de l'historique du technicien. Le filtre par période / mois sera ajouté dans la version ultérieure !

**Ce plan vous convient-il ? Donnez-moi votre feu vert pour que je commence à coder la V3.**
