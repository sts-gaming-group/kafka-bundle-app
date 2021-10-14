[[_TOC_]]

Sts/kafka-bundle Symfony 5 example project.

## Quick start

1. Clone the project
   
2. Install Docker and docker-compose in your OS. 
   
- https://docs.docker.com/get-docker/
- https://docs.docker.com/compose/install/


3. Build containers 
   
`docker-compose up -d`

4. Verify containers
   
`docker-compose ps`

It should output something similar to
```
      Name                    Command              State    Ports  
-------------------------------------------------------------------
kafka-bundle-app   docker-php-entrypoint php-fpm   Up      9000/tcp
```
4. Enter the container
   
`docker-compose exec php bash`

5. 

## Examples

