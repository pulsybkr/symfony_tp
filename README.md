# 🍲 CulinaryConnect

CulinaryConnect est une plateforme de partage de recettes de cuisine moderne et intuitive, développée avec Symfony et TailwindCSS.

## 🌟 Fonctionnalités

### Pour tous les utilisateurs
- 📱 Interface responsive et moderne
- 🔍 Recherche de recettes
- 👀 Consultation des recettes publiques
- 📱 Application mobile (en développement)

### Pour les utilisateurs connectés
- 👤 Profil utilisateur personnalisé
- 📝 Création et gestion de recettes
- 📸 Upload d'images pour les recettes
- 📱 Synchronisation avec l'application mobile
- 🔖 Gestion des favoris

### Pour les administrateurs
- ⚙️ Tableau de bord administrateur
- 👥 Gestion des utilisateurs
- 📝 Modération des recettes
- 📊 Statistiques et analytics

## 🚀 Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js et npm

### Étapes d'installation

1. Cloner le repository
```bash
git clone [URL_DU_REPO]
cd culinary-connect
```

2. Installer les dépendances PHP
```bash
composer install
```

3. Installer les dépendances JavaScript
```bash
npm install
```

4. Configurer la base de données
```bash
# Créer le fichier .env.local à partir de .env
cp .env .env.local

# Modifier les paramètres de connexion à la base de données dans .env.local
DATABASE_URL="mysql://user:password@127.0.0.1:3306/culinary_connect?serverVersion=8.0.32&charset=utf8mb4"
```

5. Créer la base de données et exécuter les migrations
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. Compiler les assets
```bash
npm run build
```

7. Lancer le serveur de développement
```bash
symfony server:start
```

## 🛠️ Technologies utilisées

- **Backend**
  - Symfony 6.x
  - PHP 8.1+
  - MySQL
  - Doctrine ORM

- **Frontend**
  - TailwindCSS
  - Alpine.js
  - JavaScript moderne

- **Outils de développement**
  - Composer
  - npm
  - Symfony CLI
