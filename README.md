# Authopless

> authentification au top passwordless !

Projet démo d'authentification par mot de passe et/ou passkeys.

## Liens utiles

- https://webauthn-doc.spomky-labs.com/
- https://github.com/web-auth/webauthn-symfony-bundle


## Prérequis

[ ] PHP8


## Installation

```bash
git clone https://github.com/leahpar/authopless
cd authopless
```

```
# .env.local
APP_ENV=dev
APP_DEBUG=true

DATABASE_URL=mysql://root:root@localhost:3306/dbname?serverVersion=8

###> web-auth/webauthn-symfony-bundle ###
RELAYING_PARTY_ID="localhost"
RELAYING_PARTY_NAME="My Application"
###< web-auth/webauthn-symfony-bundle ###
```

```bash
composer install
```

```php
# \vendor\web-auth\webauthn-symfony-bundle\src\Repository\PublicKeyCredentialSourceRepository.php
# line 17

# Replace :
private readonly EntityManagerInterface $manager;
# With :
protected readonly EntityManagerInterface $manager;
```

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force
```

```bash
npm install
```

```bash
npm run dev
```

```bash
symfony server:start --port=8015 -d
```

