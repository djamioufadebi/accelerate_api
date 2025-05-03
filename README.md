# Accelerate - Sprint 1

Bienvenue dans la documentation de l'**Accelerate API**, une API RESTful développée pour gérer les factures d'une entreprise. Ce projet est réalisé dans le cadre du **Sprint 1** du projet , **Accelerate** et vise à fournir une solution robuste pour la création, la consultation, la suppression, et la génération de PDF pour les factures, avec un accent sur la sécurité, la performance, et la documentation.


## Sommaire

- [Description du Projet](#description-du-projet)
- [Technologies Utilisées](#technologies-utilisées)
- [Prérequis](#prérequis)
- [Installation et Configuration](#installation-et-configuration)
  - [Environnement Local](#environnement-local)
  - [Environnement Docker](#environnement-docker)
- [Structure de l'API](#structure-de-lapi)
  - [Authentification](#authentification)
  - [Endpoints](#endpoints)
- [Tester l'API avec Postman](#tester-lapi-avec-postman)
- [Tests Automatisés](#tests-automatisés)
- [Pipeline CI/CD](#pipeline-cicd)
- [Collaboration avec l'Équipe](#collaboration-avec-léquipe)
- [Gestion des Erreurs](#gestion-des-erreurs)
- [Ressources Supplémentaires](#ressources-supplémentaires)

## Description du Projet

L'**Accelerate API** est une application Laravel conçue pour gérer les factures d'une entreprise. Elle permet aux utilisateurs authentifiés (rôle `admin`) de :
- Créer, lister, consulter, et supprimer des factures.
- Générer des PDF pour les factures.
- Consulter un historique filtré des factures.
- S'authentifier via un système sécurisé basé sur Laravel Sanctum.

**Caractéristiques clés** :
- **Modèle de données** : Factures avec un champ `status` (enum: `draft`, `paid`, `cancelled`), liées à des clients et des lignes de facture.
- **Sécurité** : Authentification via tokens Sanctum (`bearerAuth`), protection contre XSS, CSRF, et injections SQL.
- **Documentation** : Swagger UI accessible à `/api/documentation`.
- **Tests** : Tests unitaires et d'intégration avec PHPUnit, couverture >80%.
- **Déploiement** : Support pour environnements locaux et Docker, avec CI/CD via Jenkins.

Ce projet est développé pour le **Sprint 1**, avec une collaboration étroite avec Malco pour les tests et la validation des endpoints avant le 6 mai 2025.

## Technologies Utilisées

- **Backend** : Laravel 11, PHP 8.2
- **Base de données** : PostgreSQL 16 (MySQL également supporté)
- **Authentification** : Laravel Sanctum
- **Génération PDF** : `barryvdh/laravel-dompdf`
- **Documentation API** : L5-Swagger (OpenAPI 3.0)
- **Tests** : PHPUnit
- **CI/CD** : Jenkins, GitHub Actions
- **Conteneurisation** : Docker, Docker Compose
- **Versionnement** : Git

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :
- PHP >= 8.2
- Composer >= 2.0
- PostgreSQL >= 16 (ou MySQL >= 8.0)
- Node.js >= 18 (pour la compilation des assets, si applicable)
- Docker et Docker Compose (pour l’environnement conteneurisé)
- Git

Pour les tests :
- Postman (pour tester les endpoints)
- Navigateur web (pour accéder à la Swagger UI)

## Installation
1. **Clone the repository**:
   ```bash
   git clone https://gitea.example.com/accelerate/accelerate-backend.git
   cd accelerate-backend
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install front-end dependencies** (if integrating with front-end):
   ```bash
   npm install
   ```

4. **Copy the environment file**:
   ```bash
   cp .env.example .env
   ```

5. **Generate an application key**:
   ```bash
   php artisan key:generate
   ```

## Configuration
1. **Configure the database**:
   Edit `.env` to set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=accelerate
   DB_USERNAME=root
   DB_PASSWORD=secret
   ```

2. **Configure Sanctum**:
   Ensure the following is set in `.env` for API authentication:
   ```env
   SANCTUM_STATEFUL_DOMAINS=localhost:8000
   ```

3. **Install and configure DomPDF** for PDF generation:
   ```bash
   composer require barryvdh/laravel-dompdf
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```

4. **Install and configure Swagger** for API documentation:
   ```bash
   composer require darkaonline/l5-swagger
   php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
   ```

## Running the Application
1. **Run migrations**:
   ```bash
   php artisan migrate
   ```

2. **Seed the database** (to create an admin user):
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```

3. **Start the development server**:
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000`.

4. **Compile front-end assets** (if needed):
   ```bash
   npm run dev
   ```

## Seeding the Database
To create an admin user for testing authentication:
```bash
php artisan db:seed --class=AdminSeeder
```
Default credentials:
- Email: `admin@example.com`
- Password: `password`

You can customize these in `.env`:
```env
ADMIN_NAME="Admin User"
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=password
```

## API Endpoints
The API is prefixed with `/api/v1`. Key endpoints include:
- **POST /api/v1/login**: Authenticate an admin (returns token).
- **GET /api/v1/clients**: List clients (paginated, searchable).
- **POST /api/v1/clients**: Create a client.
- **GET /api/v1/invoices**: List invoices (paginated, filterable).
- **POST /api/v1/invoices**: Create an invoice with lines.
- **GET /api/v1/invoices/{id}/pdf**: Download invoice as PDF.

See the [Swagger documentation](#documentation) for full details.

## Testing
Run the test suite to verify functionality:
```bash
php artisan test
```
Tests cover:
- Authentication (login, logout).
- CRUD operations for clients and invoices.
- PDF generation.

## Documentation
The API is documented using Swagger (OpenAPI). To view the documentation:
1. Generate the Swagger JSON file:
   ```bash
   php artisan l5-swagger:generate
   ```
2. Access the Swagger UI:
   ```
   http://localhost:8000/api/documentation
   ```

