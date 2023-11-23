[Castellano](readme.es.md)

### Password Manager

This application allows complete password management for multiple types of services (web, ssh, phones, wifi, etc ...).

The data of each application is stored encrypted in the database.

> **The encryption of this data is done using the value of `APP_KEY` as salt, so it is very important not to regenerate this key or you will lose access to all registered applications.**

> **Don't even think about installing this project in an environment without HTTPS protection**

The main features are:

* User Management.
* Team management.
* Access to applications limited by teams.
* Multiple types of data records.
* Encryption in database.
* Authentication by certificate and double factor with Google Authenticator.
* Using certificate, you can to disable password auth.
* Logged every time a user accesses, consults or updates an application.
* Allows private or shared applications.
* Limited access by country.
* It has a chrome extension that connects via API and directly accesses the credentials of the web you are visiting.
* API Password control on every different IP.

This project has an extension for Google Chrome that you can download at https://github.com/ericanderson29/Password-Manager-Chrome

### Requirements

- Apache2 (nginx does not support authentication with certificate limited to certain routes)
- PHP 8.1 or higher (php-curl php-imagick php-mbstring php-mysql php-zip)
- MySQL 8.0
- ImageMagick

If PHP 8.1 is not the default PHP version on your system you must use the binary prefix to execute `composer` and `artisan`, for example:

```bash
php8.1 /usr/local/bin/composer install --no-dev --optimize-autoloader --classmap-authoritative --ansi
```

```bash
php8.1 artisan key:generate
```

### Local Installation

1. Create the database in MySQL.

2. Clone the repository.

```bash
git clone https://github.com/ericanderson29/Password-Manager.git
```

3. Copy the `.env.example` file as `.env` and fill in the necessary variables.

```bash
cp .env.example .env
```

4. Install composer dependencies (remember that we always use the PHP 8.1 binary).

```bash
composer install --no-dev --optimize-autoloader --classmap-authoritative --ansi
```

5. Generate the application key. Remember to backup this key in a secure location (`.env` > `APP_KEY`).

```bash
php artisan key:generate
```

6. Regenerate the caches.

```bash
composer artisan-cache
```

7. Launch the initial migration.

```bash
php artisan migrate
```

8. Launch the seeder.

```bash
php artisan db:seed --class=Database\\Seeders\\Database
```

9. Configure the cron task for the user related to the project:

```
* * * * * cd /var/www/password.domain.com && php artisan schedule:run >> storage/logs/artisan-schedule-run.log 2>&1
```

10. Create the main user.

```bash
php artisan user:create --email=user@domain.com --name=Admin --password=StrongPassword2 --admin
```

11. Configure the server for web access with `DOCUMENT_ROOT` in` public`.

12. Profit!

#### Upgrade

The platform update can be done easily with the `composer deploy` command executed by the user who manages that project (usually` www-data`).

### Docker Installation

Currently only for testing (no certificate support).

1. Clone the repository

```bash
git clone https://github.com/ericanderson29/Password-Manager.git
```

2. [OPTIONAL] Copy file `docker/.env.example` to `.env` and configure your own settings

```bash
cp docker/.env.example .env
```

3. [OPTIONAL] Copy file `docker/docker-compose.yml.example` to `docker/docker-compose.yml` and configure your own settings

```bash
cp docker/docker-compose.yml.example docker/docker-compose.yml
```

4. Build docker images (will ask for the sudo password)

```bash
./docker/build.sh
```

5. Start containers (will ask for the sudo password)

```bash
./docker/run.sh
```

6. Create the admin user (will ask for the sudo password)

```bash
./docker/user.sh
```

7. Open your web browser and goto http://localhost:8080

8. Remember to add a web server (apache2, nginx, etc...) as a proxy to add features as SSL.

#### Upgrade

1. Update the project source

```bash
git pull
```

2. Build docker images (will ask for the sudo password)

```bash
./docker/build.sh
```

3. Start containers (will ask for the sudo password)

```bash
./docker/run.sh
```

4. Open your web browser and goto http://localhost:8080

### Certificate Authentication

In order to authenticate with a certificate, we must add the following configuration in Apache's `VirtualHost`:

```
<Location /user/profile/certificate>
        SSLVerifyClient require
        SSLVerifyDepth 2
        SSLOptions +StdEnvVars +ExportCertData +OptRenegotiate
</Location>

<Location /user/auth/certificate>
        SSLVerifyClient require
        SSLVerifyDepth 2
        SSLOptions +StdEnvVars +ExportCertData +OptRenegotiate
</Location>

SSLCACertificateFile /var/www/password.domain.com/resources/certificates/certificates.pem
```

The `/user/profile/certificate` location allows obtaining the certificate identifier automatically from the user profile itself, and `/user/auth/certificate` is the authentication path by certificate.

The `OptRenegotiate` option allows Apache to independently renegotiate the connection per path, something that nginx does not support.

### Commands

Create User:

```bash
php artisan user:create {--email=} {--name=} {--password=} {--admin} {--readonly} {--teams=}
```

User update:

```bash
php artisan user:update {--id=} {--email=} {--name=} {--password=} {--certificate=} {--tfa_enabled=} {--admin=} {- readonly=} {--enabled=} {--teams=}
```

