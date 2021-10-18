![sts_480](/uploads/e34c20ee3ed116a9d506796c801f966c/sts_480.png)
![apache_kafka](/uploads/808ac0b899b8d17715588b3c7d886289/apache_kafka.png)

# Sts/kafka-bundle Symfony 5 example project.

## Quick start

**1. Clone the project**

**2. Install Docker and docker-compose in your OS.** 
   
- https://docs.docker.com/get-docker/
- https://docs.docker.com/compose/install/

**3. Build containers and verify them**
   
`docker-compose up -d`

`docker-compose ps`   

It should output something similar to
```
      Name                    Command              State    Ports  
-------------------------------------------------------------------
kafka-bundle-app   docker-php-entrypoint php-fpm   Up      9000/tcp
```

**4. Prepare Kafka server. Probably the most convenient way is a Docker image at https://github.com/wurstmeister/kafka-docker**

**5. Modify variables in `.env` (optionally copy that file as `.env.local`). Fill them with your Kafka broker's ips.**
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

**6. Enter the container `docker-compose exec php bash` and execute `bin/console kafka:consumers:consumer health_check -vvv`**

You should be able to see message being consumed and produced every 10 seconds.

```
[info] Consumer: no more messages available. Let's produce one.
[notice] Producer: message produced. Payload 2021-15-34 12:29:34 | Partition 0 | Waiting 10 seconds.
[notice] Consumer: got message with time 2021-15-34 12:29:34

```

To see consumer configuration execute `bin/console kafka:consumers:describe` or `bin/console k:c:d`

```
┌───────────────────────────┬────────────────────────────────────────────────┐
│ configuration             │ value                                          │
├───────────────────────────┼────────────────────────────────────────────────┤
│ class                     │ App\Consumer\HealthCheckConsumer               │
│ name                      │ health_check                                   │
│ topics                    │ sts_kafka_health_check_topic                   │
│ group_id                  │ sts_kafka_testing_app                          │
│ brokers                   │ 127.0.0.1:9092                                 │
│                           │ 127.0.0.2:9092                                 │
│                           │ 127.0.0.3:9092                                 │
│ timeout                   │ 1000                                           │
│ auto_offset_reset         │ smallest                                       │
│ auto_commit_interval_ms   │ 50                                             │
│ decoder                   │ Sts\KafkaBundle\Decoder\PlainDecoder           │
│ schema_registry           │ http://127.0.0.1:8081                          │
│ enable_auto_offset_store  │ true                                           │
│ enable_auto_commit        │ true                                           │
│ log_level                 │ 3                                              │
│ register_missing_schemas  │ false                                          │
│ register_missing_subjects │ false                                          │
│ denormalizer              │ Sts\KafkaBundle\Denormalizer\PlainDenormalizer │
│ max_retries               │ 0                                              │
│ retry_delay               │ 200                                            │
│ retry_multiplier          │ 2                                              │
│ max_retry_delay           │ 2000                                           │
│ validators                │ Sts\KafkaBundle\Validator\Type\PlainValidator  │
└───────────────────────────┴────────────────────────────────────────────────┘
```

## Examples

- `App\Consumer\HealthCheckConsumer` - consumes and produces messages to 'itself' if no messages are available.
- `App\Producer\HealthCheckProducer` - simple message producer for aforementioned consumer

