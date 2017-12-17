#!/bin/sh
docker-compose up -d
docker-compose exec arangodb bash -c 'echo "db._createDatabase(\"user\"); var users = require(\"@arangodb/users\"); users.save(\"user\", \"user\"); users.grantDatabase(\"user\", \"user\", \"rw\");" | arangosh --server.password root'
