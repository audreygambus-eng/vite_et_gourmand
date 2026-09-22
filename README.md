# Vite & Gourmand — Déploiement en local

Ce document décrit la démarche à suivre pour installer et exécuter l'application **Vite & Gourmand** sur votre machine locale.

---

## 1. Prérequis

Avant de commencer, il faut avoir installé sur votre machine :

| Outil | Version |
|---|---|
| PHP | 8.2 ou supérieur (8.3 recommandé) |
| Composer | 2.x |
| MySQL | 8.0 |
| MongoDB | 6.0 ou supérieur (avec l'extension PHP `mongodb`) |
| Apache | 2.4 (avec `mod_rewrite` activé) |
| Git | — |
| Symfony CLI | facultatif (option A de l'étape 8) |

Les extensions PHP suivantes doivent être activées dans `php.ini` : `pdo_mysql`, `mongodb`, `intl`, `openssl`, `zip`.

---

## 2. Récupérer le projet

```bash
git clone https://github.com/audreygambus-eng/vite_et_gourmand.git
cd vite_et_gourmand
```

---

## 3. Installer les dépendances PHP

```bash
composer install
```

---

## 4. Configurer les variables d'environnement

Créez un fichier `.env.local` à la racine du projet (non versionné), à partir du modèle `.env`, et renseignez :

```env
# Base de données MySQL (serveur local)
DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/vite_et_gourmand?serverVersion=8.0&charset=utf8mb4"

# Base de données MongoDB (serveur local)
MONGODB_URI="mongodb://localhost:27017"
MONGODB_DB="vite_gourmand_stats"

# Cloudinary (stockage des images)
CLOUDINARY_CLOUD_NAME="votre_cloud_name"
CLOUDINARY_API_KEY="votre_api_key"
CLOUDINARY_API_SECRET="votre_api_secret"

# Envoi d'e-mails (exemple avec Mailtrap pour les tests)
MAILER_DSN="smtp://utilisateur:mot_de_passe@sandbox.smtp.mailtrap.io:2525"

# Clé secrète de l'application
APP_SECRET="générer_une_valeur_aléatoire"
```

Les bases peuvent aussi être hébergées à distance (par exemple MongoDB Atlas) : il suffit alors d'adapter `DATABASE_URL` et `MONGODB_URI` avec les chaînes de connexion fournies par le service.

Pour générer une valeur pour `APP_SECRET` :

```bash
php -r "echo bin2hex(random_bytes(16));"
```

---

## 5. Créer la base de données et exécuter les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Confirmez avec `yes` lorsque la commande le demande.

---

## 6. Charger les données de démonstration (fixtures)

```bash
php bin/console doctrine:fixtures:load
```

Confirmez avec `yes` : cette commande **purge la base** puis la remplit avec des données de test (menus, plats, comptes utilisateurs).

### Comptes de test créés automatiquement

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Administrateur | admin@vite-et-gourmand.com | Admin@1234! |
| Employé | employe@vite-et-gourmand.com | Employe@1234! |
| Utilisateur | user@example.com | User@1234! |

Ces mots de passe sont volontairement simples et réservés à la démonstration. Tout mot de passe créé depuis l'application (inscription, réinitialisation, création d'employé) est soumis aux règles de robustesse.

---

## 7. Installer les assets front-end

```bash
php bin/console importmap:install
```

La commande `asset-map:compile` est réservée à la production : en local, elle figerait des copies des fichiers JavaScript et CSS qui masqueraient par la suite toute modification des fichiers sources.

---

## 8. Configurer le serveur web

### Option A — Symfony CLI (recommandé pour un test rapide)

```bash
symfony server:start
```

L'application est alors accessible sur `https://localhost:8000`.

### Option B — Apache (dossier `htdocs`)

Clonez le projet dans votre dossier `htdocs`, puis accédez à l'application via :

```
http://localhost/vite_et_gourmand/public/
```

Adaptez le chemin si le projet se trouve dans un sous-dossier. Vérifiez que le fichier `public/.htaccess` est bien présent (nécessaire au routage des URL).

### Option C — Docker (alternative complète)

Une configuration Docker est disponible, incluant l'application, MySQL et MongoDB.

**Prérequis** : Docker Desktop installé et démarré.

```bash
docker compose up -d --build
```

L'option `-d` lance les conteneurs en arrière-plan. L'application est accessible sur `http://localhost:8080`. Les bases sont exposées sur les ports 3307 (MySQL) et 27018 (MongoDB), afin d'éviter tout conflit avec des serveurs déjà installés en local.

Exécutez ensuite les migrations et les fixtures à l'intérieur du conteneur :

```bash
docker compose exec app php bin/console doctrine:migrations:migrate
docker compose exec app php bin/console doctrine:fixtures:load
```

Pour vérifier l'état des conteneurs :

```bash
docker compose ps
```

Pour arrêter les conteneurs (les données sont conservées dans les volumes) :

```bash
docker compose down
```

---

## 9. Vérifier l'installation

Rendez-vous sur l'URL de l'application (selon l'option choisie à l'étape 8) et vérifiez que :

- la page d'accueil s'affiche correctement avec le catalogue de menus ;
- la connexion fonctionne avec l'un des comptes de test ci-dessus ;
- le graphique de statistiques s'affiche dans l'espace administrateur (ce qui confirme la connexion à MongoDB).

---

## 10. Dépannage courant

| Problème | Solution |
|---|---|
| Erreur de connexion MySQL | Vérifiez `DATABASE_URL` dans `.env.local` et que le service MySQL est démarré |
| Erreur de connexion MongoDB | Vérifiez que le service MongoDB est démarré et que l'extension PHP `mongodb` est chargée (`php --ri mongodb`) |
| Page blanche / erreur 500 | Consultez les logs : `var/log/dev.log` |
| Modifications JavaScript non prises en compte | Supprimez le dossier `public/assets/` s'il existe (créé par `asset-map:compile`) |
| Images non affichées | Vérifiez les identifiants Cloudinary dans `.env.local` (`CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`) |
| E-mails non envoyés | Vérifiez la configuration de `MAILER_DSN` (un compte Mailtrap gratuit peut être créé pour les tests) |
| Mot de passe refusé à l'inscription sans connexion internet | La vérification des mots de passe compromis interroge un service externe : une connexion internet est nécessaire |
| Docker Desktop ne démarre pas | Vérifiez que la virtualisation est activée dans le BIOS, et que Docker Desktop est lancé avant `docker compose up` |

---

## Auteure

Audrey Gambus — Projet réalisé dans le cadre de la certification DWWM (Développeur Web et Web Mobile).