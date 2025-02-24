Miamm - Un Mini MVC avec une Approche DDD 🍽️

📚 Présentation

Miamm est un exemple de projet en PHP utilisant une architecture MVC avec une approche DDD (Domain-Driven Design).
Ce projet montre comment structurer une application proprement avec des entités, des services, et un Entity Manager basé sur PDO.

Chaque commit représente une étape de progression, permettant de suivre l'évolution du projet pas à pas.

🚀 Caractéristiques

✅ Architecture MVC avec DDD (Séparation propre entre Controller, Service, Repository, et Entité)✅ Gestion des Entités avec un Trait Hydrator pour l'auto-hydratation des objets✅ Utilisation de PDO pour gérer la base de données proprement✅ Autoloader personnalisé sans Composer✅ Système de Routing en mode .ini pour une configuration simple✅ Utilisation d'un EntityManager centralisé pour la gestion de la BDD✅ Gestion des erreurs avec un ErrorHandler✅ Fichiers .env pour les configurations sensibles✅ Exemples de requêtes SQL et gestion des migrations manuelles

📁 Structure du projet

/miamm/
│── /app/                  # Code principal (MVC, DDD)
│   │── /Core/             # Gestion de la BDD, autoloader, erreurs, helpers
│   │── /Domain/Recette/   # Module "Recette" (Entité, Repository, Service)
│   │── /Controllers/      # Contrôleurs
│   │── /Views/            # Vues HTML
│── /public/               # Dossier accessible par le navigateur
│   │── /css/              # CSS compilé depuis SCSS
│── /scss/                 # Fichiers SCSS sources
│── /logs/                 # Logs d’erreurs
│── /database.sql          # Fichier SQL pour créer les tables
│── .env                   # Variables d’environnement
│── README.md              # Documentation du projet

✅ Tout est bien séparé pour une meilleure maintenabilité.

🛠 Installation

1️⃣ Cloner le projet

git clone https://github.com/littleblackman/miamm.git
cd miamm

2️⃣ Configurer la base de données

📺 Importer le fichier database.sql dans MySQL

mysql -u root -p miamm < database.sql

📺 Ou exécuter manuellement les requêtes :

CREATE TABLE recette (
id INT AUTO_INCREMENT PRIMARY KEY,
titre VARCHAR(255) NOT NULL,
description TEXT NOT NULL,
categorie VARCHAR(100) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

3️⃣ Configurer .env

Crée un fichier .env à la racine et ajoute :

DB_HOST=localhost
DB_NAME=miamm
DB_USER=root
DB_PASSWORD=
APP_ENV=dev

4️⃣ Lancer le serveur PHP

php -S localhost:8000 -t public

📺 Le projet est accessible à http://localhost:8000

🎯 Fonctionnalités

📈 1️⃣ Le Routing

Les routes sont définies dans /app/routes.ini

Exemple :

[home]
name = "home"
method = GET
path = /
controller = App\Controllers\HomeController
action = index

💎 2️⃣ Gestion des recettes

Action

Route

Méthode

Afficher toutes les recettes

/recettes

GET

Ajouter une recette

/ajouter-une-recette

POST

Afficher une recette

/recette/{id}/voir

GET

🔎 Commandes Git pour suivre l'évolution

Chaque commit correspond à une étape de progression dans le projet.

📺 Lister les commits :

git log --oneline --graph --all

📺 Revenir à une étape précise :

git checkout <ID_du_commit>

📺 Voir les changements entre deux étapes :

git diff <ID_commit1> <ID_commit2>

🙌 Contributions

📺 Fork le projet et propose tes idées !

Forker le repo

Créer une nouvelle branche

git checkout -b feature/nouvelle-fonction

Commit tes changements

git commit -m "✨ Ajout d'une nouvelle fonctionnalité"

Push et propose un Pull Request

git push origin feature/nouvelle-fonction

💚 Licence

Ce projet est sous licence MIT, ce qui signifie que tu peux l’utiliser et le modifier librement.

🚀 To-Do List (Evolutions possibles)

✅ Finaliser CRUD Recette (Update & Delete)

✅ Ajouter un système d’authentification (User, JWT, Sessions)

✅ Implémenter une gestion des erreurs avancée

💡 Créer une API REST avec JSON

💡 Ajouter des tests unitaires avec PHPUnit

🎉 Merci d’avoir testé Miamm ! 🎉
Si tu as des questions, n’hésite pas à ouvrir une issue sur GitHub.
Bon code ! 🚀👨‍💻
