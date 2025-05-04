# Система скоринга клиентов (Symfony)

Веб-приложение для регистрации клиентов, расчёта скоринга и управления клиентскими данными

## 🚀 Установка и запуск проекта
```shell
    git clone <ссылка на репозиторий>
```

```shell
    cd <папка проекта>
```

```shell
    composer install
```

```shell
   cp .env
```

# Запуск приложения
```shell
   symfony server:start
```

# При необходимости нужно произвести настройку DATABASE_URL
```shell
   php bin/console doctrine:database:create
```

```shell
   php bin/console doctrine:migrations:migrate
```

# Загрузка тестовых данных (опционально) 
```shell
   php bin/console doctrine:fixtures:load
```


---

## 🔗 Основные маршруты

| Маршрут             | Описание                                 |
|---------------------|------------------------------------------|
| /register         | Форма регистрации клиента                |
| /clients          | Список клиентов с пагинацией и скорингом |
| /clients/{id}     | Просмотр карточки клиента                |
| `/clients/{id}/edit`| Редактирование карточки клиента          |

---

## 📝 Форма регистрации клиента

Доступна по адресу /register.

Поля:
- Имя
- Фамилия
- Телефон (российский формат)
- Email
- Образование (Среднее / Специальное / Высшее)
- Согласие на обработку персональных данных (чекбокс)

![form_register](https://github.com/user-attachments/assets/095c56bc-a3e3-42d4-a6ab-4a9162d66a23)


---

## 📝 Список клиентов

Доступен по адресу /clients.

Поля:
- Id
- Имя
- Фамилия
- Номер телефона
- Э-почта
- Образование
- Согласие на обработку персональных данных
- Скоринг

![clients](https://github.com/user-attachments/assets/caa8c047-b8b6-489c-8735-ed882b770b82)


---

## 📝 Просмотр карточки клиента

Доступен по адресу /clients/{id}.

Поля: Те же, что и при регистрации

![clients-show](https://github.com/user-attachments/assets/08c905e4-d83d-44c8-9d5b-ddf9d14d564c)


---

## 📝 Редактирование карточки клиента

Доступен по адресу /clients/{id}/edit.

Поля: Те же, что и при регистрации

![clients-edit-id](https://github.com/user-attachments/assets/de038a31-4358-436b-b2bc-92192ec2f1dd)





---

## ⚙️ Консольная команда расчёта скоринга
php bin/console app:calculate-scoring           # рассчёт для всех клиентов <br/>
php bin/console app:calculate-scoring <id>      # рассчёт для одного клиента по ID

После выполнения:
- Обновляется скоринг в БД
- В консоли выводится детализация расчёта скоринга по каждому правилу

---

## ✅ Запуск тестов
php bin/phpunit

Тестируется:
- ScoringService

---

## 📦 Используемые технологии

- Symfony 6.x (forms, services, console commands)
- Doctrine ORM
- MySQL (для работы с базой данных)
- Twig
- KnpPaginatorBundle
- PHPUnit
- Faker (для генерации тестовых данных)

---
