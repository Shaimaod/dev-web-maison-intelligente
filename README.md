# Maison Intelligente

Une application web de gestion d'objets connectés pour maison intelligente, permettant de contrôler et d'automatiser vos appareils domestiques.

## 🌟 Fonctionnalités

- Gestion complète des objets connectés (ajout, modification, suppression)
- Catégorisation des objets par type et pièce
- Modes manuel et automatique
- Surveillance de l'état des objets (actif/inactif)
- Interface responsive pour une utilisation sur tous les appareils

## 📋 Prérequis

- PHP 8.0 ou supérieur
- Composer
- MySQL ou MariaDB
- Node.js et NPM (pour les assets frontend)
- Serveur web (Apache, Nginx)

## 🚀 Installation

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/votre-utilisateur/dev-web-maison-intelligente.git
   cd dev-web-maison-intelligente
   ```

2. Installez les dépendances PHP :
   ```bash
   composer install
   ```

3. Installez les assets frontend :
   ```bash
   npm install
   ```

4. Faire la migration :
   ```bash
   php artisan migrate
   ```

5. Générer les données de base :
   ```bash
   php artisan migrate:fresh --seed
   ```

6. Copier le '.env'

7. Lancer le serveur local :
   ```bash
   php artisan serve
   npm run dev
   ```

6. Configurez votre base de données dans le fichier `.env`

L'application sera accessible à l'adresse : http://localhost:8000

## 📊 Structure de la base de données

- **connected_objects** : Stocke les informations sur tous les objets connectés
- **users** : Gère les utilisateurs et leurs autorisations
- **automations** : Configure les règles d'automatisation pour les objets

## 🔧 Technologies utilisées

- **Backend** : Laravel (PHP)
- **Frontend** : Bootstrap, Vue.js
- **Base de données** : MySQL/MariaDB
- **Authentification** : Laravel Fortify

## 🖼️ Captures d'écran

![Dashboard](path/to/dashboard-screenshot.png)
![Ajout d'objet](path/to/add-object-screenshot.png)

## 📱 Fonctionnalités mobiles

L'application est entièrement responsive et offre une expérience optimisée sur les appareils mobiles, permettant de :
- Contrôler vos objets connectés à distance
- Recevoir des notifications sur l'état de vos appareils
- Gérer les automatisations depuis n'importe où

## 🔒 Sécurité

- Authentification sécurisée
- Protection CSRF
- Données chiffrées
- Validation des entrées utilisateur

## 📜 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👥 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou à soumettre une pull request.

1. Forkez le projet
2. Créez votre branche de fonctionnalité (`git checkout -b feature/amazing-feature`)
3. Committez vos changements (`git commit -m 'Add some amazing feature'`)
4. Poussez vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrez une Pull Request
