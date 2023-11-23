# GPS Tracker (Laravel 10 + PHP 8.1 + MySQL 8)

This project is a management platform for Sinotrack ST-90x OsmAnd and Queclink devices developed using Laravel 10, PHP 8.1 and MySQL 8. This comprehensive GPS tracking solution is designed to offer robust performance and an intuitive user interface, suitable for both individual users and companies that need to manage multiple tracking devices. This platform is intended as a possible alternative to [Traccar](https://github.com/traccar/traccar).

### Features

* **Modern platform with user-friendly interface:** The platform uses Laravel 10 to provide a smooth user experience and an attractive graphical interface.
* **PHP 8.1 compatibility:** Leverages the latest features of PHP 8.1, including performance and security enhancements. It is also compatible with higher versions of PHP.
* **Data Management with MySQL 8:** Uses MySQL 8.0.12 or higher for efficient and secure management of large volumes of tracking data, as well as extensive support for GIS functionality.
* **Real-Time Tracking:** Allows users to track the location and status of their Sinotrack ST-90x devices in real time.
* **Detailed Reporting:** Generates comprehensive reports that aid in decision making and data analysis.
* **Alarms and Notifications:** Configure custom alarms (geofence, motion, speed, etc...) for specific events related to the tracking devices. Notifications can be configured via Telegram.
* **Multi-User Support:** Supports the creation of multiple user accounts with different levels of access and permissions.
* **Public Environment:** If you wish you can generate links for individual trips and share them publicly. You can also directly share a device where all its trips will be publicly visible.

### Requirements

- PHP 8.1 or higher (bcmath bz2 intl mbstring opcache pdo_mysql pcntl redis sockets xsl zip)
- MySQL 8.0.12 or higher
- Redis

### Demo

You can test the demo version at https://tracker-demo.lito.com.es/

### Local Installation

1. Create the database in MySQL.

2. Clone the repository.

```bash
git clone https://github.com/ericanderson29/GPS-Tracker.git
```

3. Copy the `.env.example` file as `.env` and fill in the necessary variables.

```bash
cp .env.example .env
```

4. Install composer dependencies (remember that we always use the PHP 8.1 binary).

```bash
./composer install --no-dev --optimize-autoloader --classmap-authoritative --ansi
```

5. Generate the application key.

```bash
php artisan key:generate
```

6. Regenerate the caches.

```bash
./composer artisan-cache
```

7. Launch the initial migration.

```bash
php artisan migrate --path=database/migrations
```

8. Launch the seeder.

```bash
php artisan db:seed --class=Database\\Seeders\\Database
```

9. Fill Timezones GeoJSON.

```bash
php artisan timezone:geojson
```

10. Configure the cron job for the user related to the project:

```
* * * * * cd /var/www/tracker.domain.com && install -d storage/logs/artisan/$(date +"\%Y-\%m-\%d") && /usr/bin/php artisan schedule:run >> storage/logs/artisan/$(date +"\%Y-\%m-\%d")/schedule-run.log 2>&1
```

11. Create the main user.

```bash
php artisan user:create --email=user@domain.com --name=Admin --password=StrongPassword2 --enabled --admin
```

12. Configure the web server `DocumentRoot` to `/var/www/project/public`.

13. Profit!

#### Upgrade

Updating the platform can be done in a simple way with the `./composer deploy` command executed by the user who manages that project (usually `www-data`).

### Docker Installation

1. Clone the repository.

```bash
git clone https://github.com/ericanderson29/GPS-Tracker.git
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

7. Launch the Timezone GeoJSON fill (will ask for the sudo password)

```bash
./docker/timezone-geojson.sh
```

8. Open your web browser and goto http://localhost:8080

9. Remember to add a web server (apache2, nginx, etc...) as a proxy to add features as SSL.

10. If you are going to add or change the default ports for GPS Devices (`8091`) you must edit the `gpstracker-app` properties in `docker-compose.yml` to adapt them to your own configuration.

#### Docker Upgrade

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

### Initial Configuration

1. By default a server is created for protocol `H02` (Sinotrack) on port `8091`. If you wish you can customize this configuration in `Servers` > `List`.
2. In `Servers` > `Status`, we select the server we just created and press the `Start/Restart` button.
3. The server should appear started in the upper listing of `Servers` > `Status`.
4. If the server does not start, we can check the logs generated in the `laravel` folder from the `Servers` > `Logs` menu.
5. Now we can create a vehicle in `Vehicles` > `Create`. We fill in the necessary fields and save it.
6. Once we have a vehicle, we go to create a device from `Devices` > `List` > `Create`. It is important to correctly indicate the `Serial Number` as it is the identifier that the device will send to the server and by which it can be recognized. We associate it with the vehicle we just created and save.
7. From here, we only have to wait to receive the first connections from the device to generate the first trips.
8. To configure the connection to our server for a Sinotrack device, follow the steps below.
9. If you have problems receiving the connection from the device you can go to `Servers` > `List` > `Edit` and enable debug mode. Once the change is saved remember to restart the server in `Servers` > `Status`.

### Device connection

To configure your device via SMS you can do it with the following command:

```
804{PASSWORD} {IP/HOST} {PORT}
```

You can configure the connection server in the device using either the IP or a HOST that will resolve internally BUT ONLY AT THE TIME OF RECEIVING THE COMMAND, so if the server does not have a fixed IP as soon as it changes you will stop receiving data from the device.

### Common Sinotrack ST-901 SMS commands

#### Configuring the Phone from which you can connect to the device

```
{PHONE}{PASSWORD} 1
```

#### Set the time zone to UTC to delegate the time adjustment to the platform.

```
896{PASSWORD}E00
```

#### Enable GPRS Mode

```
710{PASSWORD}
```

#### Configure APN Operator

```
803{PASSWORD} {OPERATOR}
```

#### Configure Server

```
804{PASSWORD} {IP/HOST} {PORT}
```

#### Configure frequency in seconds of sending position reports with the car ignition on

```
805{PASSWORD} {SECONDS}
```

#### Configure frequency in seconds of sending position reports with the car ignition off

```
809{PASSWORD} {SECONDS}
```

#### Set timeout before switching to SLEEP mode when the car is stopped

```
SLEEP{PASSWORD} {MINUTES}
```

#### Deactivate call in case of alarm

```
151{PASSWORD}
```

#### Enable Device Low Battery SMS

```
011{PASSWORD}
```

#### Disable Device Low Battery SMS

```
010{PASSWORD}
```

#### Password change

```
777{PASSWORD-NEW}{PASSWORD-OLD}
```

#### Device restart

```
RESTART
```

#### Show current configuration

```
RCONF
```

### Platform Commands

#### User Creation:

```bash
php artisan user:create {--email=} {--name=} {--password=} {--enabled} {--admin}
```

#### Start or Restart all configured servers:

The `reset` option allows you to reset the port in case it is being used.

```bash
php artisan server:start:all {--reset}
```

#### Start or Restart only one server port:

The `reset` option allows you to reset the port in case it is being used.

```bash
php artisan server:start {--port=} {--reset}
```
