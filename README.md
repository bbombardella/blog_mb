# Blog - 블로그

Projet de blog suite au cours de PHP Avancé à l'IUT Lyon 1. Le blog a été codé avec le framework Symfony en langage PHP

## Composition du groupe

- Marina Daubigie
- Bastien Bombardella

## Spécifications du projet

### Les fonctionnalités

- une partie "Admin" (/admin/...)  
  - Barre de menu pour les accès à
    - Nom du site (avec un lien sur la homepage: partie Utilisateur)
    - Liste des Post
    - Liste des Catégories
    - Liste des Commentaires
  - Post (depuis la liste des Post)
    - Liste des Post
    - Créer un nouveau Post
      - Pouvoir ajouter des catégories au Post
    - Éditer un Post
    - Supprimer un Post
  - Category (depuis la liste des categories)
    - Liste des catégories
    - Créer une catégorie
    - Éditer une catégorie
    - Supprimer une catégorie
  - Commentaire (depuis la liste des Commentaires)
    - Liste des commentaires
    - Valider un commentaire (Modération)
    - Supprimer un commentaire
  
* une partie "Utilisateur" (/...)
  - Barre de menu (header) pour les accès à :
    - Nom du site (avec un lien sur la homepage)
    - Partie Admin
  - Post
    - Lister les 5 derniers Post (si la date de publication est valide) (ceci est la page d'accueil / homepage)
    - Afficher un Post (si la date de publication est valide) (via le `slug`)
    - Pagination sur la liste des Post
  - Commentaire
    - Ajouter un commentaire à un Post
    - Afficher les commentaires associés à un Post (si le commentaire est valide)
  - Sidebar (sur page homepage ou toutes les pages "partie utilisateur") (voir `render(controller())` => [lien](https://symfony.com/doc/current/templates.html#embedding-controllers))
    - Afficher dans une sidebar les 5 derniers commentaires valides (si le commentaire est valide)
    - Afficher les catégories (si au moins un post avec cette catégorie existe) avec le nombre de Post

La date de publication d'un `post` est valide si la date de publication est YYYY/mm/dd <= now

### Les entités

- Post
    - id
    - title
    - description
    - content
    - slug
    - createdAt
    - updatedAt
    - publishedAt
    - comments (OneToMany)
    - categories (ManyToMany)
  
* Category
    - id
    - name
    - posts (ManyToMany)

- Comment
    - id
    - username
    - content
    - valid
    - createdAt
    - post (ManyToOne)
