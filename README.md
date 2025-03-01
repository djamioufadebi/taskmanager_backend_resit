# Application de gestion de tâches

## Description 
Ce projet est une API RESTful développée avec Laravel pour gérer des tâches (CRUD : Création, Lecture, Mise à jour, Suppression). 
L’API utilise Laravel Sanctum pour l’authentification.

## Prérequis

- PHP 8.1+  
- Composer  
- MySQL   
- Laravel 10+  
- Laragon ou autre serveur local  


## Installation

 ### Cloner le projet
```bash
  git clone https://github.com/djamioufadebi/task_manager_app.git
```

 ### Aller au répertoire du projet
```bash
  cd task_manager_app
```

 ### Installer les dépendances
```bash
  composer install
```

 ### Créer et configurer .env
```bash
  cp .env.example .env
  php artisan key:generate
```

 ### Configurer la base de données
```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gestion_taches
    DB_USERNAME=root
    DB_PASSWORD=
```
 ### Exécuter les migrations et seeders
```bash
    php artisan migrate --seed
```

 ### Démarrer le serveur
```bash
    php artisan serve
```

### Authentification
    - Inscription : POST /api/register
    - Connexion : POST /api/login
    - Déconnexion : POST /api/logout

## Références API 

#### Recupérer toutes les tâches

```http
  GET /api/tasks
```
| Paramètre | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `api_key` | `string` | **Required**. Votre Clé API |



#### Pour récupérer une tâche donnée
```http
  GET /api/tasks/${id}
```
| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la tâche |



#### Pour modifier une tâche donnée
```http
  PUT /api/tasks/${id}
```
| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la tâche |

#### Pour Supprimer une tâche donnée
```http
  DELETE /api/tasks/${id}
```
| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la tâche |


## Feedback
Si vous avez des commentaires, veuillez nous contacter sur djamioufadebi@gmail.com

## Auteur

- [@djamioufadebi](https://www.github.com/djamioufadebi)


