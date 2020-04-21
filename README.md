# Api для работы сервисом Memory Lane
## Предисловие
Мы рады что выбрали наш API :-). Если в течение изучение документации возникнут вопросы свяжитесь с нами по электронной почтой [email] hrach@hrach.ru.
## Предварительная регистрация

URL "/user/add" принимает параметры {name} и {email} тип отправки POST ответ в виде JSON

- **error** параметр если в API произошла ошибка то значение параметра будет текст ошибки
- **result** значение true если удачно сделано запись в БД и удачно отправилось почта

### Пример ответа
```json
{"error":"","result":true} //удачное выполнение
```
```json
{"error":"тут текст ошибки","result":false} //неудачное выполнение
```
***

## Получение список пользователей

URL "/db/getUsers/all" API обязательно должен получать ограниченное количество записей. Это связано с оптимизацией к запросу БД. Принимает POST с параметрами:
- **lemit** - сколько записей надо
- **offset** - с какой калонки запросить пользователей
***
API отпровляет ответ в формат JSON
пораметры JSON ответа
```json
[
    {
    "first_name":"{имя пользователя}","last_name":"{фамилия пользователя}",
    "admin_privileges":boolean,"date_created":"{дата создание пользователя}",
    "email":"{email пользователя}"
    },
    ...
]
```
