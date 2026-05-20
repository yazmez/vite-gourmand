# Vite & Gourmand — Application Web de Traiteur

## Présentation

Vite & Gourmand est une application web développée dans le cadre de l'ECF du Titre Professionnel Développeur Web et Web Mobile (Studi). Elle permet à l'entreprise de traiteur bordelaise "Vite & Gourmand" de présenter ses menus, de gérer les commandes en ligne et d'administrer l'ensemble de l'activité.

## Stack technique

- **Front-end** : HTML5, CSS3, Bootstrap 5, JavaScript (Fetch API)
- **Back-end** : PHP 8.0 avec PDO
- **Base de données relationnelle** : MySQL
- **Librairie mail** : PHPMailer 7.1
- **Gestion de projet** : Trello
- **Versioning** : Git / GitHub

## Prérequis

- XAMPP (Apache + MySQL)
- PHP 8.0 minimum
- Composer

## Installation en local

**1. Cloner le dépôt**
```bash
git clone https://github.com/yazmez/vite-gourmand.git
```

**2. Placer le projet dans htdocs**

Copier le dossier `vite-gourmand` dans `C:/xampp/htdocs/`

**3. Démarrer XAMPP**

Lancer Apache et MySQL depuis le panneau de contrôle XAMPP.

**4. Créer la base de données**

- Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
- Créer une base de données nommée `vite_gourmand`
- Importer le fichier `vite_gourmand.sql` disponible à la racine du projet

**5. Configurer la connexion**

Ouvrir `config/db.php` et vérifier les paramètres :
```php
$host = 'localhost';
$dbname = 'vite_gourmand';
$username = 'root';
$password = '';
```

**6. Installer les dépendances**
```bash
cd C:/xampp/htdocs/vite-gourmand
composer install
```

**7. Accéder à l'application**

Ouvrir le navigateur et aller sur : `http://localhost/vite-gourmand/`

## Comptes de test

| Rôle | Email | Mot de passe |

| Administrateur | jose@vitegourmand.fr | Jose1234! |
| Employé | julie@vitegourmand.fr | Julie1234! |
| Utilisateur | (créer un compte via l'inscription) ou bien yazi.meziani@gmail.com | @heyYOU2654  |

## Fonctionnalités

- Page d'accueil avec présentation de l'entreprise et avis clients validés
- Catalogue de menus avec filtres dynamiques (prix, thème, régime, nombre de personnes)
- Vue détaillée de chaque menu avec liste des plats et allergènes
- Système de commande avec calcul automatique du prix et réduction
- Inscription et connexion sécurisées (mot de passe hashé bcrypt)
- Espace utilisateur : suivi des commandes, modification, annulation, avis
- Espace employé : gestion des commandes, validation des avis
- Espace administrateur : gestion des menus, des employés, statistiques
- Envoi d'emails automatiques (PHPMailer + Gmail SMTP)
- Page de contact, mentions légales, CGV
- Responsive design (Bootstrap 5)

## Déploiement

Plusieurs tentatives de déploiement ont été effectuées :

| Plateforme | Résultat |

| Railway | Erreur driver PDO MySQL — extensions PHP non compatibles avec la version 8.4.21 imposée |
| Fly.io | Erreur — aucun Dockerfile détecté, runtime PHP non reconnu |
| Heroku | Plan gratuit supprimé depuis novembre 2022 |
| 000webhost | Service définitivement fermé |
| InfinityFree | Fonctionnalités critiques manquantes (pas de Remote MySQL, pas de PHP mail) |
| Alwaysdata | Validation par carte bancaire requise |

L'application est entièrement fonctionnelle en environnement local. Le code de connexion à la base de données est prévu pour fonctionner en environnement de production via variables d'environnement (voir `config/db.php`).

## Structure du projet

vite-gourmand/
├── assets/          # CSS, JS, images
├── config/          # Configuration base de données
├── includes/        # Header, footer, nav, mailer
├── pages/           # Pages publiques
├── espaces/         # Dashboards (utilisateur, employé, admin)
├── actions/         # Traitement des formulaires
├── vendor/          # Dépendances Composer
├── index.php        # Page d'accueil
├── composer.json    # Dépendances
└── vite_gourmand.sql # Script SQL complet

## Sécurité

- Mots de passe hashés avec bcrypt (PASSWORD_BCRYPT)
- Protection contre les injections SQL via PDO et requêtes préparées
- Protection XSS via htmlspecialchars() sur toutes les sorties
- Vérification des rôles sur chaque page protégée
- Validation des mots de passe (10 caractères min, majuscule, minuscule, chiffre, caractère spécial)
- Sessions PHP sécurisées

## Auteur

Développé par Yazid — Formation Titre Professionnel Développeur Web et Web Mobile — Studi 2026
