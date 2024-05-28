# Laravel Job Batching with Realtime Progress

![banner](https://github.com/yogameleniawan/laravel-queue-realtime-progress/assets/64576201/42cf05bb-559d-4f62-950d-c1c66ebb4a8a)




## Preview Realtime Job Batching

![Untitled](https://github.com/yogameleniawan/job-batching-with-realtime-progress/assets/64576201/039aacca-dcab-4fc2-a5e9-b99e1e0202ab)

## List of Contents
- [Laravel Job Batching with Realtime Progress](#laravel-job-batching-with-realtime-progress)
  - [Preview Realtime Job Batching](#preview-realtime-job-batching)
  - [List of Contents](#list-of-contents)
  - [Requirement](#requirement)
  - [Configuration](#configuration)
    - [Migration Table](#migration-table)
    - [Install Package](#install-package)
    - [Pusher Configuration](#pusher-configuration)
  - [Implementation](#implementation)
    - [Create Service Repository](#create-service-repository)
    - [Create Controller Function](#create-controller-function)
    - [Setup javascript](#setup-javascript)
  - [Changelog](#changelog)
  - [Credits](#credits)
  - [License](#license)

## Requirement
- [PHP 7.4 or Higher](https://www.php.net/)
- [Laravel 8 or Higher](https://www.laravel.com/)
- [Laravel Job Queue](https://laravel.com/docs/10.x/queues#jobs-and-database-transactions)
- [Pusher](https://pusher.com/)

## Configuration

### Migration Table
- Make migration job table
```
php artisan queue:table
```

- Make migration job batches table
```
php artisan queue:batches-table
```

- Migrate table
```
php artisan migrate
```

### Install Package

```
composer require yogameleniawan/realtime-job-batching
```

### Pusher Configuration
- Install Pusher PHP SDK
```
composer require pusher/pusher-php-server
```
- Create Channels App [Create Channel Pusher](https://dashboard.pusher.com/channels)
- Setting Up your .env for pusher variable
```
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

## Implementation

### Create Service Repository
- Create repository class on `your_project\app\Repositories`
For example: `VerificationRepository.php`.

So now you have repository class `your_project\app\Repositories\VerificationRepository.php`
- Add `implements RealtimeJobBatchInterface`  in your Repository Class
- Don't forget to import interface `use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;`
- The interface has 2 methods. So, you should implement these methods :
  
  `public function get_all(): Collection`
  In this function you can get all data from your database and return it into Collection. Why you should return into Collection? First of all, the data will be looping with foreach method and then add one by one into job and then the process will executed one by one.
  
  `public function save($data): void`
  In this function you can make your own business logic to update/delete/something else that you want. So, this function doesn't have any return data that's why this function must be void type.
- This is the example of Repository Class

![image](https://github.com/yogameleniawan/job-batching-with-realtime-progress/assets/64576201/a73774ed-6854-4bec-895d-074f0fbf82e8)

### Create Controller Function
- Create function to handle your job process
For example : 
![image](https://github.com/yogameleniawan/job-batching-with-realtime-progress/assets/64576201/cf73e608-a93d-410e-a1bc-5ab98dbd7ca7)

```php
use YogaMeleniawan\JobBatchingWithRealtimeProgress\RealtimeJobBatch;
use App\Repositories\VerificationRepository;

RealtimeJobBatch::setRepository(new VerificationRepository())
                    ->execute(name: 'User Verification')
```

`VerificationRepository()` is your service repository that you have created before so don't forget to import this class `use App\Repositories\VerificationRepository;`
- Explanation about RealtimeJobBatch methods :
  - `setRepository(RealtimeJobBatchInterface $repository):object` is method to implement your repository class that you want to use and return to object. So, you can use `RealtimeJobBatch` class in another service with different repository.
  - `execute($name): object` is method to execute job service with your custom name that you want. This method will return object.

### Setup javascript
- Add this script to your view (blade). For another view (React/Vue/Etc) you can check out from this link [Setup Pusher](https://pusher.com/docs/channels/getting_started/javascript/?ref=docs-index)
- Script Blade :
```html
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('YOUR_PUSHER_APP_KEY', {
        cluster: 'mt1'
        });

        var channel = pusher.subscribe('channel-job-batching');
        channel.bind('broadcast-job-batching', function(data) {
            console.log(data)
        });

        var channel_finish = pusher.subscribe('channel-finished-job');
        channel_finish.bind('request-finished-job', function(data) {
            if (data.finished == true) {
                // reset your progress bar
            }
        });
    </script>
```

- Response from Pusher :

```json
{
  "finished": false,
  "progress": 10,
  "pending": 90,
  "total": 100,
  "data": {}
},
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Yoga Meleniawan Pamungkas](https://github.com/yogameleniawan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/yogameleniawan/laravel-queue-realtime-progress/blob/main/LICENSE) for more information.

