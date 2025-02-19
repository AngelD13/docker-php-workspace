# Среда разработки PHP на базе Docker

Публикация на **Habr**: https://habr.com/ru/post/519500/.

- [Требования](#Требования)
- [Возможности](#Возможности-и-особенности)
- [Структура проекта](#Структура-проекта)
- [Программы в docker-контейнерах PHP](#Программы-в-docker-контейнерах-PHP)
- [Начало работы](#Начало-работы)
- [Вопросы и ответы](#Вопросы-и-ответы)
- [Развёртывание дампов MySQL, PostgreSQL и MongoDB](#Развёртывание-дампов-MySQL-PostgreSQL-и-MongoDB)

## Требования

- Git.
- Docker engine 19.x и выше. 

## Возможности и особенности

- Несколько версий **PHP** — **7.4**, **7.1** и **8.0** с набором наиболее востребованных расширений. 
- Возможность использовать для web-проектов разные версии **PHP**.
- Готовый к работе монитор процессов **Supervisor**.
- Предварительно сконфигурированный веб-сервер **Nginx**.
- Базы данных:
  - **MySQL 5.7**.
  - **MySQL 8**.
  - **PostgreSQL** (latest).
  - **MongoDB 4.2**.
  - **Redis** (latest).
- Настройка основных параметров окружения через файл **.env**.
- Возможность модификации сервисов через **docker-compose.yml**.
- Последняя версия **docker-compose.yml**.
- Все docker-контейнеры базируются на официальных образах.
- Структурированный **Dockerfile** для создания образов **PHP**.
- Каталоги большинства docker-контейнеров, в которых хранятся пользовательские данные и параметры конфигурации смонтированы на локальную машину.

В целом, среда разработки удовлетворяет требованию — _**«при использовании Docker каждый контейнер должен содержать в себе только один сервис»**_.

## Структура проекта

Рассмотрим структуру проекта.

```
├── .env-example
├── .gitignore
├── .ssh
├── README.md
├── docker-compose.yml
├── mongo
├── mysql-5.7
├── mysql-8
├── nginx
├── php-ini
├── php-workers
├── php-7-workspace
├── php-8-workspace
├── postgres
├── projects
└── redis
```

**Примечание:**

В некоторых каталогах можно встретить пустой файл **.gitkeep**. Он нужен лишь для того, чтобы была возможность добавить каталог под наблюдение **Git**. 

**.gitkeep** — является заполнением каталога, это фиктивный файл, на который не следует обращать внимание.

### .env-example

Пример файла с основными настройками среды разработки.

```dotenv
# Временная зона
WORKSPACE_TIMEZONE='Europe/Moscow'

# XDEBUG
DOCKER_PHP_ENABLE_XDEBUG='on'

# Настройки Nginx
# Порт, который следует использовать
# для соединения с локального компьютера
NGINX_PORT=80

# Настройки Redis
# Порт, который следует использовать
# для соединения с локального компьютера
REDIS_PORT=6379

# Настройки Postgres
POSTGRES_DB=test
POSTGRES_USER=pg_user
POSTGRES_PASSWORD=secret
POSTGRES_PORT=54322

# Настройки общие для MySQL 8.x и MySQL 5.7.x
MYSQL_ROOT_PASSWORD=secret
MYSQL_DATABASE=test

# Настройки MySQL 8.x
# Порт, который следует использовать
# для соединения с локального компьютера
MYSQL_8_PORT=4308

# Настройки MySQL 5.7.x
# Порт, который следует использовать
# для соединения с локального компьютера
MYSQL_5_7_PORT=4307

# Настройки MongoDB
# Порт, который следует использовать
# для соединения с локального компьютера
MONGO_PORT=27017

# Настройки PHP 8.0
# Внешний порт, доступен с локального компьютера
PHP_8_0_PORT=9006

# Настройки PHP 7.4
# Внешний порт, доступен с локального компьютера
PHP_7_4_PORT=9003

# Настройки PHP 7.1
# Внешний порт, доступен с локального компьютера
PHP_7_1_PORT=9001
```

### .gitignore

Каталоги и файлы, в которых хранятся пользовательские данные, код ваших проектов и ssh-ключи внесены в .gitignore.

### .ssh

Этот каталог предназначен для хранения ssh-ключей.

### README.md

Документация, которую вы сейчас читаете.

### docker-compose.yml

Документ в формате YML, в котором определены правила создания и запуска многоконтейнерных приложений Docker. 
В этом файле описана структура среды разработки и некоторые параметры необходимые для корректной работы web-приложений.

### mongo

Каталог базы данных MongoDB.

```
├── configdb
│   └── mongo.conf
├── db
└── dump
```

**mongo.conf** — Файл конфигурации MongoDB. В этот файл можно добавлять параметры, которые при перезапуске MongoDB будут применены.

**db** — эта папка предназначена для хранения пользовательских данных MongoDB.

**dump** — каталог для хранения дампов.  
 
### mysql-5.7

Каталог базы данных MySQL 5.7.

```
├── conf.d
│   └── config-file.cnf
├── data
├── dump
└── logs
```

**config-file.cnf** — файл конфигурации. В этот файл можно добавлять параметры, которые при перезапуске MySQL 5.7 будут применены.

**data** — эта папка предназначена для хранения пользовательских данных MySQL 5.7.

**dump** — каталог для хранения дампов.

**logs** — каталог для хранения логов.

### mysql-8

Каталог базы данных MySQL 8.

```
├── conf.d
│   └── config-file.cnf
├── data
├── dump
└── logs
```

**config-file.cnf** — файл конфигурации. В этот файл можно добавлять параметры, которые при перезапуске MySQL 8 будут применены.

**data** — эта папка предназначена для хранения пользовательских данных MySQL 8.

**dump** — каталог для хранения дампов.

**logs** — каталог для хранения логов.


### nginx

Эта папка предназначена для хранения файлов конфигурации Nginx и логов.

```
├── conf.d
│   ├── default.conf
│   └── vhost.conf
└── logs
```

**default.conf** — файл конфигурации, который будет применён ко всем виртуальным хостам.

**vhost.conf** — здесь хранятся настройки виртуальных хостов web-проектов.

Рассмотрим **vhost.conf** подробнее:

```nginx
server {
    listen 80;
    index index.php index.html;
    server_name project-1.localhost;
    error_log /var/log/nginx/project-1.error.log;
    access_log /var/log/nginx/project-1.access.log combined if=$loggable;
    root /var/www/project-1.ru;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-7.3:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_script_name;
    }
}

server {
    listen 80;
    index index.php index.html;
    server_name project-2.localhost;
    error_log /var/log/nginx/project-2.error.log;
    access_log /var/log/nginx/project-2.access.log combined if=$loggable;
    root /var/www/project-2.ru;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-7.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_script_name;
    }
}
```

В файле конфигурации описаны настройки для 2 web-проектов — **project-1.localhost** и **project-2.localhost**.

Здесь следует обратить внимание на то, как производится перенаправление запросов к нужному docker-контейнеру.

Например, для проекта **project-1.localhost** указано:

```nginx
fastcgi_pass php-7.3:9000;
```

**php-7.3** — название docker-контейнера, а **9000** — порт внутренней сети. Контейнеры между собой связаны через внутреннюю сеть, которая определена в файле **docker-compose.yml**.  
  
### php-ini

В этом каталоге находятся файлы конфигурации PHP.

```
├── 7.1
│   └── php.ini
└── 7.3
    └── php.ini
```
Для каждой версии PHP — свой файл конфигурации.


### php-workers

Место для хранения файлов конфигурации **Supervisor**.

```
├── 7.1
│   └── supervisor.d
│       
└── 7.3
    └── supervisor.d
```

Для каждой версии PHP — могут быть добавлены свои файлы с настройками. 

### php-n-workspace

Здесь хранится файл, в котором описаны действия, выполняемые при создании образов docker-контейнеров PHP.

```
└── Dockerfile
```

**Dockerfile** — это текстовый документ, содержащий все команды, которые следует выполнить для сборки образов PHP.

### postgres

Каталог для системы управления базами данных PostgreSQL.

```
├── .gitkeep
├── data
└── dump 
```

**data** — эта папка предназначена для хранения пользовательских данных PostgreSQL.

**dump** — каталог для хранения дампов.

### projects

Каталог предназначен для хранения web-проектов.

Вы можете создать в это каталоге папки, и поместить в них ваши web-проекты.

Например:

```
project-1.ru
project-2.ru 
...
```

Содержимое каталога **projects** доступно из контейнеров **php-7.1** и **php-7.3**. 

Если зайти в контейнер **php-7.1** или **php-7.3**, то в каталоге **/var/www** будут доступны проекты, которые расположены в **projects** на локальной машине.

### redis

Каталог key-value хранилища Redis.

```
├── conf
└── data
```

**conf** — папка для хранения специфических параметров конфигурации.

**data** — если настройки конфигурации предполагают сохранения данных на диске, то Redis будет использовать именно этот каталог.


## Программы в docker-контейнерах PHP

Полный перечень приложений, которые установлены в контейнерах **php-x.x** можно посмотреть в **php-n-workspace/Dockerfile**.

Здесь перечислим лишь некоторые, наиболее важные:

- bash
- htop
- curl
- Git
- Сomposer
- make
- wget
- NodeJS
- Supervisor
- npm


## Начало работы

<br>

**1**. Выполните клонирование данного репозитория в любое место на вашем компьютере. 

```shell script
git clone https://github.com/drandin/docker-php-workspace
```

Перейдите в директорию, в которую вы клонировали репозиторий. Все дальнейшие команды следует выполнять именно в этой директории.

Удалите файл **.gitkeep** в каталоге **postgres/data/**, иначе он помешает запуску PostgreSQL.

<br>

**2**. Скопируйте файл **.env-example** в **.env**

```shell script
cp .env-example .env
```

Если это необходимо, то внесите изменения в файл **.env**. Измените настройки среды разработки в соответствии с вашими требованиями.

<br>

**3**. Выполните клонирование web-проектов в каталог **projects**.

<br>

Для примера, далее мы будем исходить из предположения, что у вас есть 2 проекта:

```
project-1.ru
project-2.ru
```

**project-1.ru** — будет работать на версии PHP 7.3, **project-2.ru** - на PHP 7.1, а **project-3.ru** - на PHP 8.0.

**4**. Отредактируйте настройки виртуальных хостов **Nginx**.

Файл конфигурации виртуальных хостов находится в каталоге **./nginx/conf.d/**.

<br>

**5**. Настройте хосты (доменные имена) web-проектов на локальной машине. 

Необходимо добавить названия хостов web-проектов в файл **hosts** на вашем компьютере. 

В файле **hosts** следует описать связь доменных имён ваших web-проектов в среде разработки на локальном компьютере и IP docker-контейнера **Nginx**.
 
На Mac и Linux этот файл расположен в **/etc/hosts**. На Windows он находится в **C:\Windows\System32\drivers\etc\hosts**. 

Строки, которые вы добавляете в этот файл, будут выглядеть примерно так:

```
127.0.0.1   project-1.localhost
127.0.0.1   project-2.localhost
```

В данном случае, мы исходим из того, что **Nginx**, запущенный в docker-контейнере, доступен по адресу **127.0.0.1** и web-сервер слушает порт **80**.

Не рекомендуем использовать имя хоста с **.dev** на конце в среде разработки. Лучшей практикой является применение других названий —  **.localhost** или **.test**.

<br>

**6**. _[опционально]_ Настройте маршрутизацию внутри контейнеров web-проектов.

Web-проекты должны иметь возможность отправлять http-запросы друг другу и использовать для этого название хостов. 

Из одного запущенного docker-контейнера **php-7.1** web-приложение №1 должно иметь возможность отправить запрос к другому web-приложению №2, которое работает внутри docker-контейнера **php-7.3**. При этом адресом запроса может быть название хоста, которое указано в файле **/etc/hosts** локального компьютера. 

Чтобы это стало возможным нужно внутри контейнеров так же внести соответствующие записи в файл **/etc/hosts**.

Самый простой способ решить данную задачу — добавить секцию **extra_hosts** в описание сервисов **php-7.1** и **php-7.3** в **docker-compose.yml**.

Пример:

```
  ...  
  php-7.1:  
  ...
    extra_hosts:
      - 'project-1.localhost:IP_HOST_MACHINE'
      - 'project-2.localhost:IP_HOST_MACHINE'
      - 'project-3.localhost:IP_HOST_MACHINE'
  ...
```

**IP_HOST_MACHINE** — IP адрес, по которому из docker-контейнера доступен ваш локальный компьютер.

Если вы разворачиваете среду разработки на **Mac**, то внутри docker-контейнера вам доступен хост **docker.for.mac.localhost**.

Узнать **IP** адрес вашего **Mac** можно при помощи команды, который нужно выполнить на локальной машине: 

```shell script
docker run -it alpine ping docker.for.mac.localhost
```

В результате вы получите, что-то подобное:

``` 
PING docker.for.mac.localhost (192.168.65.2): 56 data bytes
64 bytes from 192.168.65.2: seq=0 ttl=37 time=0.286 ms
64 bytes from 192.168.65.2: seq=1 ttl=37 time=0.504 ms
64 bytes from 192.168.65.2: seq=2 ttl=37 time=0.801 ms
```
 
После того, как вам станет известен IP-адрес, укажите его в секции **extra_hosts** в описание сервисов **php-7.1** **php-7.3** в **docker-compose.yml**.
  
```
  ...  
  php-7.1:  
  ...
    extra_hosts:
      - 'project-1.localhost:192.168.65.2'
      - 'project-2.localhost:192.168.65.2'
  . 
```

<br>

**8**. Настройте параметры соединения с системами хранения данных.

**Хосты и порты сервисов**

Для того, чтобы настроить соединения с базами данных из docker-контейнеров **php-7.1** и **php-7.3** следует использовать следующие названия хостов и порты:

| Сервис     | Название хоста | Порт  |
|------------|----------------|-------|
| MySQL 5\.7 | mysql\-5\.7    | 3306  |
| MySQL 8    | mysql\-8       | 3306  |
| PostgreSQL | postgres       | 5432  |
| MongoDB    | mongo          | 27017 |
| Redis      | redis          | 6379  |

Именно эти параметры следует использовать для конфигурации web-проектов. 

Для соединения с базами данных с локальной машины:

- Хост для всех баз данных — **127.0.0.1**.
- Порты — значения указанные в **.env**.

<br>  
  
**7**. Создайте контейнеры и запустите их.

Выполните команду:

```shell script
docker-compose build && docker-compose up -d
```

Создание контейнеров займёт некоторое время. Обычно, от 10 до 30 минут. Дождитесь окончания процесса. Ваш компьютер не должен во время данного процесса потерять доступ в интернет.  

<br>  

**8**. Создайте SSH-ключи

Для работы web-проектов могут потребоваться SSH-ключи, например для того, чтобы из контейнера при помощи **Composer** можно было установить пакет из приватного репозитория.

Создать SSH-ключи можно при помощи следующей команды:

```shell script
ssh-keygen -f ./.ssh/id_rsa -t rsa -b 2048 -C "your-name@example.com"
```

Вместо **your-name@example.com** укажите свой email. 

В папку **.ssh/** будут сохранены 2 файла — публичный и приватный ключ. 

Если вы скопировали в папку **.ssh** свой ранее созданный ssh-ключ, то убедитесь, что файл **id_rsa** имеет права **700** (-rwx------@).

Установить права можно командой:
 
```
chmod 700 id_rsa.
```
<br>  
  
**9**. Проверьте созданные docker-контейнеры.   
  
Выполните команду:

```shell script
docker ps
```  
  
Если создание контейнеров прошло успешно, то вы увидите примерно такой результат:
  
```
CONTAINER ID        IMAGE                          COMMAND                  CREATED             STATUS              PORTS                               NAMES
8d348959c475        docker-php-workspace_php-7.1   "docker-php-entrypoi…"   6 minuts ago        Up 54 seconds       0.0.0.0:9001->9000/tcp              php-7.1
a93399727ff6        docker-php-workspace_php-7.3   "docker-php-entrypoi…"   6 minuts ago        Up 53 seconds       0.0.0.0:9003->9000/tcp              php-7.3
7d879f796fdc        docker-php-workspace_php-8.0   "docker-php-entrypoi…"   6 minuts ago        Up 52 seconds
5cd80ac95388        nginx:stable-alpine            "/docker-entrypoint.…"   6 minuts ago        Up 51 seconds       0.0.0.0:80->80/tcp                  nginx
70182bc9e44c        mysql:5.7                      "docker-entrypoint.s…"   6 minuts ago        Up 54 seconds       33060/tcp, 0.0.0.0:4307->3306/tcp   mysql-5.7
46f2766ec0b9        mysql:8.0.21                   "docker-entrypoint.s…"   6 minuts ago        Up 53 seconds       33060/tcp, 0.0.0.0:4308->3306/tcp   mysql-8
a59e7f4b3c61        mongo:4.2                      "docker-entrypoint.s…"   6 minuts ago        Up 54 seconds       0.0.0.0:27017->27017/tcp            mongo
eae8d62ac66e        postgres:alpine                "docker-entrypoint.s…"   6 minuts ago        Up 53 seconds       0.0.0.0:54322->5432/tcp             postgres
bba24e86778a        redis:latest                   "docker-entrypoint.s…"   6 minuts ago        Up 54 seconds       0.0.0.0:6379->6379/tcp              redis
```  

<br>   

**10**. Установка зависимостей для web-приложений.

Если для работы web-приложений необходимо установить зависимости, например через менеджер пакетов **Composer** или **NPM**, то сейчас самое время сделать это.

В контейнерах **php-7.1**, **php-7.3** и **php-8.0** уже установлен и **Composer** и **NPM**.

Войдите в контейнер **php-7.1**:

```shell script
docker exec -it php-7.1 bash  
```

или

```shell script
docker exec -it php-7.3 bash  
```

или

```shell script
docker exec -it php-8.0 bash  
```

Перейдите в рабочий каталог необходимого web-проекта и выполните требуемые действия.

Например, установите зависимости через **Composer** при помощи команды:

```shell script
composer install
```

## Вопросы и ответы

Несколько наиболее важных вопросов и ответов на них.

### Как зайти в работающий docker-контейнер?

Выполните команду:

```shell script
docker exec -it container_name bash  
```

**container_name** — имя контейнера.

### Как останавливать и удалить контейнеры и другие ресурсы среды разработки, которые были созданы?

```shell script
docker-compose down
```

### Как получить список всех контейнеров?

```shell script
docker ps -a
```

### Как получить подробную информацию о docker-контейнере?

```shell script
docker inspect container_name
```

**container_name** — имя контейнера.

### Как узнать какие расширения PHP установлены в контейнере php-7.3?

Если контейнер **php-8.0** запущен, то выполните команду:

```shell script
docker exec -it php-8.0 php -m
```

### Как удалить все контейнеры?

Удаление всех контейнеров:

```shell script
docker rm -v $(docker ps -aq)
```

Удаление всех активных контейнеров:

```shell script
docker rm -v $(docker ps -q) # Все активные
```

Удаление всех неактивных контейнеров:

```shell script
docker rm -v $(docker ps -aq -f status=exited) # Все неактивные
```


## Развёртывание дампов MySQL, PostgreSQL и MongoDB
   
Если для работы web-проектов требуются перенести данные в хранилища, то следуйте описанным ниже инструкциям.         
       
### Как развернуть дамп PostgreSQL?

Выполните следующую команду на локальной машине:

```shell script
docker exec -i postgres psql --username user_name database_name < /path/to/dump/pgsql-backup.sql 
```

или зайдите в контейнер postgres и выполните:

```shell script
psql --username user_name database_name < /path/to/dump/pgsql-backup.sql 
```

**user_name** — имя пользователя. Значение *POSTGRES_USER*.

**database_name** — название базы данных. Значение *POSTGRES_DB*.

### Как развернуть дамп MySQL?

**Вариант 1** 

Если требуется создать дополнительных пользователей, то следует это сделать перед началом процедуры загрузки дампа.  

В файле **mysql/conf.d/config-file.cnf** отключите лог медленных запросов **slow_query_log=0** или установите большое значение **long_query_time**, например 1000.

Если дамп сжат утилитой gzip, сначала следует распаковать архив:

```shell script
gunzip databases-dump.sql.gz
```

Затем можно развернуть дамп, выполнив на локальном компьютере команду:

```shell script
docker exec -i mysql mysql --user=root --password=secret --force < databases-dump.sql
````
Указывать пароль в командной строке — плохая практика, не делайте так в производственной среде. 

MySQL выдаст справедливое предупреждение:

>mysql: [Warning] Using a password on the command line interface can be insecure.

Ключ *--force* говорит MySQL, что ошибки следует проигнорировать и продолжить развёртывание дампа. Этот ключ иногда может пригодится, но лучше его без необходимости не применять. 

**Вариант 2**

Воспользоваться утилитой Percona **XtraBackup**. 

Percona **XtraBackup** — это утилита для горячего резервного копирования баз данных MySQL.

О том, как работать с **XtraBackup** можно узнать по ссылке: https://habr.com/ru/post/520458/. 

### Как развернуть дамп MongoDB?

1. Скопируйте фалы дампа в каталог _**mongo/dump**_.

2. Войдите в контейнер mongo:

```shell script
 docker exec -it mongo sh
```
Выполните следующую команду, чтобы развернуть дамп базы _**database_name**_:
 
```shell script
 mongorestore -d database_name /dump/databases/database_name
```


