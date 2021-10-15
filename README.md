[[_TOC_]]

Sts/kafka-bundle Symfony 5 example project.

## Quick start

1. Clone the project
   
2. Install Docker and docker-compose in your OS. 
   
- https://docs.docker.com/get-docker/
- https://docs.docker.com/compose/install/


3. Build containers and verify them
   
`docker-compose up -d`

`docker-compose ps`   


It should output something similar to
```
      Name                    Command              State    Ports  
-------------------------------------------------------------------
kafka-bundle-app   docker-php-entrypoint php-fpm   Up      9000/tcp
```

4. Prepare Kafka server for example using Docker images https://github.com/wurstmeister/kafka-docker
   
5. Modify variables in `.env`. Fill them with your Kafka broker's ips.
```
KAFKA_DEFAULT_BROKER_ONE=your_broker_ip_one
KAFKA_DEFAULT_BROKER_TWO=your_broker_ip_two
KAFKA_DEFAULT_BROKER_THREE=your_broker_ip_three
```

If only one broker is available you should remove `KAFKA_DEFAULT_BROKER_TWO` and `KAFKA_DEFAULT_BROKER_THREE` from `config/packages/sts_kafka.yaml`

```
sts_kafka.yaml

parameters:
    kafka_default_brokers: ['%env(KAFKA_DEFAULT_BROKER_ONE)%']
```

6. Enter the container `docker-compose exec php bash` and execute `bin/console kafka:consumers:consumer health_check -vvv`

You should be able to see message being consumed and produced every 10 seconds.

To see consumer configuration execute `bin/console kafka:consumers:describe` or `bin/console k:c:d`


## Examples

`App\Consumer\HealthCheckConsumer` - consumes and produces messages to 'itself' if no messages are available.
`App\Producer\HealthCheckProducer` - simple message producer for aforementioned consumer

