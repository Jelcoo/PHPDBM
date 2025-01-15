# PHPDMB

PHPDMB is a application that allows you to create and manage databases from a web UI.

## Starting the application

You can start the application by running the following command:
```
docker compose up -d
```

## Accessing the application

You can access the application by using the following credentials:
```
Host: mysql
Port: 3306
Username: developer
Password: secret123
```
This will connect you to the default host and allows you to access the demo database `demodb`.

### Root Access

You can also access the application by using the following credentials:
```
Host: mysql
Port: 3306
Username: root
Password: secret123
```
This will connect you to the default host and allows you to modify the entire application database.

## API Endpoint

This is the endpoint that will be used to interact with the PHPDMB API.

The endpoint is located at `/api/databases`. Due to the design of this application, you have to be authenticated via the normal API first in order to use this endpoint.
