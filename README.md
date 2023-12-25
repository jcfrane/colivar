# Requirements
- Docker
- Docker Compose

# GCP
- Create a service account in IAM dashboard and download private key file.
- Create a bucket in Cloud Storage and make sure the created service account has access to it.
- Create a bucket and name it *colivar*

# Docker

### Copy the private key file
In the project folder, copy the private key file and name it service-account.json
### Creating Docker Network
``docker network create colivar``
### Running the containers
``docker-compose up -d``

Wait until the containers are up and running.

### Checking the container status

On first run, the container has to install all necessary composer packages. 

``docker-compose logs -f php``

### Running Testing
``php artisan test``

### Logging Mechanism
For simplicity, logging is stored inside storage/logs/laravel.log.

