## Работа с Альбомом

### Авторизация
Для выполнение этого запроса нужно авторизация пользователя
в загаловке запроса должен придти токен без этого дальнейшие запросы не будут выполнены.

| Название загаловка |Значение|   Обязательное поле |
| ------------ | ------------ |------------ |
| Authorization|  (токен который был получен во время авторизации пользователя)shd8ysa67t2w65e3yhfdws|Да|

### Запись нового Альбома

Для записи альбома нужно отправить методом POST по адресу  `/db/setAlbum ` в виде json данных

| Параметр  |  Тип  | Обязательное поле |
| ------------ | ------------ |------------ |
| album_name| string  | Да |
| description  |  string  | Нет |

В ответ приходит JSON с параметрами:

| Параметр  |  Тип  | Описание|
| ------------ | ------------ |------------ |
| error | string | Выводит текст ошибки  |
| result  |  boolean  | Выдает результат выполнение  |

Пример выглядит так:
```json
{"error":"","result":true}

{"error":"Описание ошибки","result":false}
```
### Получение данных

Для получение данных  альбома нужно отправить методом POST по адресу `/db/getAlbum` без параметров

В ответ получаете JSON если есть данные

| Параметр  |  Тип  | Описание|
| ------------ | ------------ |------------ |
|content| Object | Все альбомы и необходимые данные в виде обекта  |
| result  |  boolean  | Выдает результат выполнение  |

Пример ответа:

```
{content: Object {1: Object { id: 1, album_name: "Имя альбома", description: "Описание альбома" ,... }, 2: { ..}}
 result: true}
```

### Обнавление альбома :-)

Для обновление отпровляете POST запрос с  json данноми похожее как и при созданиии на адрес `/db/updateAlbum`:

| Параметр  |  Тип  | Обязательное поле |
| ------------ | ------------ |------------ |
| album_name | string  | Да |
| album_id  |  int  | Да |
| description| string| Нет|

Ответ получаем в формат JSON

| Параметр  |  Тип  | Описание|
| ------------ | ------------ |------------ |
| error | string | Выводит текст ошибки  |
| result  |  boolean  | Выдает результат выполнение  |
Пример json ответа
```json
{"error":"","result":true}
```