# Cloud 9 Rest API 

Данный API предназначен для работы с данными облачного сервиса 'Cloud 9', исходный код клиентской части которого 
можно получить по адресу [Cloud9]

## Установка и Автозагрузка

Этот пакет сопровождается средой разработкой [Homestead], предустановленной в проект локально.
Для запуска, необходимо провести первоначальную настройку файла [Homestead.yaml].

Прописать директорию в которой находиться проект (заменив сушествующую):

folders: 

    - map: 'C:\WWW\sites\api-service'
    
    
Сгенерировать и указать публичный и приватный SSH ключи:

authorize: ~/.ssh/id_rsa.pub

keys:

    - ~/.ssh/id_rsa
    
А затем выполнить в консоли комманду _vagrant up_. 

Далее инсталировать зависимости с помощью [Composer], выполнив команду _composer install_.

Для корректной работы системы аутентификации данного API, необходимо выполнить команды 

_php artisan migrate --force --seed_

_php artisan passport:install --force_

Которые кроме применения миграции заполнят рабочие таблицы фейковыми данными, и проведут первоначальные настройки системы
аунтетификации.

Так же необходимо, создать новый файл с переменными окружения .env

Это рекомендуется сделать путем, копирования файла .env.example и сохранением под именем .env   
 
#### Используемые в процессе разработки технологии

- Framework [Laravel]
- Система аунтетификации [Laravel Passport]
- Технология Cross-origin resource sharing [CORS]
- Генератор тестовых данных fzaninotto/Faker 

[Cloud9]: https://github.com/Bashka/bricks_cli_routing/releases
[Homestead]: https://laravel.com/docs/5.7/homestead
[Homestead.yaml]: ./Homestead.yaml
[Composer]: http://getcomposer.org/
[Laravel]: https://laravel.com/
[Laravel Passport]: https://laravel.com/docs/5.7/passport#introduction
[CORS]: https://ru.wikipedia.org/wiki/Cross-origin_resource_sharing
[fzaninotto/Faker]: https://github.com/fzaninotto/Faker#fakerproviderlorem