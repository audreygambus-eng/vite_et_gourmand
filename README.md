\# Vite \& Gourmand — Déploiement en local



Ce document décrit la démarche à suivre pour installer et exécuter l'application \*\*Vite \& Gourmand\*\* sur votre machine locale.



\---



\## 1. Prérequis



Avant de commencer, il faut avoir installé sur votre machine :



| Outil | Version minimale |

|---|---|

| PHP | 8.3.31 |

| Composer | 2.10.1 |

| MySQL | 8.0 |

| MongoDB | 6.0+ (avec l'extension PHP `mongodb`) |

| Apache | 2.4 (avec `mod\\\_rewrite` activé) |

| Git | — |



Les extensions PHP suivantes doivent être activées dans `php.ini` : `pdo\\\_mysql`, `mongodb`, `intl`, `openssl`, `zip`.



\---



\## 2. Récupérer le projet



```bash

git clone https://github.com/audreygambus-eng/vite\\\_et\\\_gourmand.git

cd vite\\\_et\\\_gourmand

```



\---



\## 3. Installer les dépendances PHP



```bash

composer install

```



\---



\## 4. Configurer les variables d'environnement



Créez un fichier `.env.local` à la racine du projet (non versionné), à partir du modèle `.env`, et renseignez :



```env

\\# Base de données MySQL

DATABASE\\\_URL="mysql://utilisateur:motdepasse@hote.amazonaws.com:3306/nom\\\_base?serverVersion=8.0"



\\# Base de données MongoDB

MONGODB\\\_URI="mongodb+srv://utilisateur:motdepasse@cluster0.xxxxx.mongodb.net/nom\\\_base?retryWrites=true\\\&w=majority"

MONGODB\\\_DB="vite\\\_gourmand\\\_stats"



\\# Cloudinary (stockage des images)

CLOUDINARY\\\_CLOUD\\\_NAME="votre\\\_cloud\\\_name"

CLOUDINARY\\\_API\\\_KEY="votre\\\_api\\\_key"

CLOUDINARY\\\_API\\\_SECRET="votre\\\_api\\\_secret"



\\# Envoi d'emails (exemple avec Mailtrap pour les tests)

MAILER\\\_DSN="smtp://utilisateur:mot\\\_de\\\_passe@sandbox.smtp.mailtrap.io:2525"



\\# Clé secrète de l'application

APP\\\_SECRET="générer\\\_une\\\_valeur\\\_aléatoire"

```



Pour générer une valeur pour `APP\\\_SECRET` :

```bash

php -r "echo bin2hex(random\\\_bytes(16));"

```



\---



\## 5. Créer la base de données et exécuter les migrations



```bash

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

```



Confirmez avec `yes` lorsque la commande le demande.



\---



\## 6. Charger les données de démonstration (fixtures)



```bash

php bin/console doctrine:fixtures:load

```



Confirmez avec `yes` — cette commande purge la base et la remplit avec des données de test (menus, plats, comptes utilisateurs).



\### Comptes de test créés automatiquement



| Rôle | Email | Mot de passe |

|---|---|---|

| Administrateur | admin@vite-et-gourmand.com | Admin@1234! |

| Employé | employe@vite-et-gourmand.com | Employe@1234! |

| Utilisateur | user@example.com | User@1234! |



\---



\## 7. Installer les assets front-end



```bash

php bin/console importmap:install

php bin/console asset-map:compile

```



\---



\## 8. Configurer le serveur web



\### Option A — Symfony CLI (recommandé pour un test rapide)



```bash

symfony server:start

```



L'application est alors accessible sur `https://localhost:8000`.



\### Option B — Apache (configuration en sous-dossier `htdocs`)



Placez le projet dans votre dossier `htdocs`, puis accédez à l'application via :

