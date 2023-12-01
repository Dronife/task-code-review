# How to run application

### WEIRD PORTS: To avoid potential conflicts with existing ports
* nginx web server - <b>8001<b>
* mysql - <b>3777</b>
* phpmyadmin - <b>8888<b>

### How to run this project on your machine:
1. Pull the repo
    - On windows: Be sure to have installed wsl for best optimisation and file reading. Place repo in `$wsl//` directory
    - Mac or Linux: Pull the project where you want
2. Open command terminal in your project folder
3. Write `docker-compose up --build` - for first time
    - Write `docker-compose up` if the containers were closed just this command
4. After all the process is done loading write: `docker-compose ps -q symfony`
5. Copy returned value. E.g. of mine: `a2f527561203d53623d3******************************************************`
6. Now you can access the symfony container: `docker exec -it <value_from_4_step> bash`
7. Run following:
   * `cp .env.example .env`
   * `composer install`
   * `php bin/console doctrine:migrations:migrate`
   * run jobs in background `php bin/console messenger:consume async -vv`

# Endpoint Testing

This section details the available API endpoints and how to use them:<br>
1. **Create simple User (POST)**
    - Endpoint: `/api/user `
    - Request Body Format:
      ```json
      {
        "email" : "testemail@gmail.com",
        "phoneNumber" : "+3706606616"
      }
      ```
    - Return:
      ```json
      {
         "id":"1"
      }
      ```
2. **Send notification (POST)**
    - Endpoint: `/api/notification `
    - Request Body Format:
      ```json
      {
        "message": "hello all",
        "subject": "Greetings",
        "recipient": 1,
        "type": "email"
      }
      ```
    - Return:
      ```json
      {
         "id":"1"
      }
      ``` 
# Implement providers
We have these implemented providers
```php 
class AWSEmailProvider implements ProviderInterface, EmailProviderInterface{}

class MailTrapEmailProvider implements ProviderInterface, EmailProviderInterface{}
 ```

### IMPORTANT:
#### If you want to add new provider you just simply make them inherit `ProviderInterface` and also `EmailProviderInterface` or `SmsProviderInterface`.<br>
#### If you want to "turn off" one of concrete providers simply remove specific provider interface(currently `EmailProviderInterface` or `SmsProviderInterface`)<br>
<hr>
* Currently there are only EmailProviderInterface and SmsProviderInterface
* If you want new providers you just simply add new <your_new_provider_type>ProviderInterface
* If you want to hook up new provider type you need to add it in `services.yaml` and `NotificationProviderFactory` file<br>
 
  ```yaml
      _instanceof:
        App\Infrastructure\Notification\Provider\SmsProviders\SmsProviderInterface:
            tags: ['app.sms_providers']

        App\Infrastructure\Notification\Provider\EmailProviders\EmailProviderInterface:
            tags: ['app.email_providers']

        #Adding new Provider Instance:
        #App\Infrastructure\Notification\Provider\<your_new_provider_type>Providers\<your_new_provider_type>ProviderInterface
        #    tags: ['app.<your_new_provider_type>_providers']

    App\Infrastructure\Notification\Provider\Factory\NotificationProviderFactory:
        arguments:
            $smsProviders: !tagged_iterator app.sms_providers
            $emailProviders: !tagged_iterator app.email_providers
        #   $<your_new_provider_type>Providers: !tagged_iterator app.<your_new_provider_type>_providers
  ```

```php
class NotificationProviderFactory
{

    public function __construct(
        private readonly iterable $smsProviders,
        private readonly iterable $emailProviders,
        //private readonly iterable $<your_new_provider_type>Providers,
    ) {
    }

    public function getProvider(string $type): ?ProviderInterface
    {
        return match ($type) {
            NotificationType::EMAIL->value => $this->getAvailableProvider($this->emailProviders),
            NotificationType::SMS->value => $this->getAvailableProvider($this->smsProviders),
            <your_new_provider_type> => $this->getAvailableProvider($this-><your_new_provider_type>Providers)
            default => throw new \InvalidArgumentException("Unsupported notification type: $type"),
        };
    }
 ```

### Run written tests:
* Unit : `php ./vendor/bin/phpunit tests/Unit`
