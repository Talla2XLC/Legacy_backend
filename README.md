# Legacy_backend

## Предварительное регистрация

принимает пораметры {name} и {email}
тип отправки POST
ответ в виде JSON
- первый порамтр **error** если в API произошла ошибка то значение пораметра будет true
- второй пораметр **result** значение true если удачно сделанно запись в БД и удачно отправилось почта
### Пример ответа
	{"error":"","result":true} //удачное выполнение
	{"error":"тут текст ошибки","result":false} //не удачное выполенение
