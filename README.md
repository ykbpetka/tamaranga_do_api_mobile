# tamaranga_do_api_mobile
Проект создания API для мобильного приложения для сервиса на движке Tamaranga DO

# Инструкция по установке:
1. Заходите в панель администратора Tamaranga FL;
2. Включаете режим разработчика;
3. Создаете новое расширение с любым названием, которое вам будет явно говорить, что это расширение с API;
4. Открываете на сервере папку с вашим проектом и переходите в папку Plugins и там ищете папку с названием вашего расширения;
5. Файл api.php без изменений копируете в папку tpl;
6. Данные из файла index.php копируете в файл index.php, ккоторый есть в папке вашего расширения. Обратите внимание, что вам потребуется название класса, которое сгенерирует Tamaranga, а также extension_id. 

# Как использовать
## Авторизация
POST /user/login

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| email | YES | STRING |
| pass | YES | STRING |

В ответ вы получите uid пользователя, если авторизация успешна, либо ошибку.

## Получение информации о пользователе
GET /api/userinfo?userid=:uid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| uid | YES | INT |

В ответе вы получите имя пользователя в ключе name и другие данные, которые могут вам потребоваться

## Сохранение идентификатора устройства для рассылки пушей через Firebird и Onesignal
POST /api/pushmobile

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| user | YES | INT |
| onesignalid | YES | STRING |

Метод не возвращает ответа обратно

## Получение списка доступных заказов
GET /api/listorders

В ответ вернется JSON со списком всех досутпных заказов
```json
    {"data":[{"id":"ID ЗАЯВКИ (INT)","user_id":"ID ПОЛЬЗОВАТЕЛЯ КТО СОЗДАЛ (INT)","type":"ТИП ЗАЯВКИ (INT)","service_type":"ПОДТИП ЗАЯВКИ (INT)","status":"СТАТУС (INT)","title":"ЗАГОЛОВОК (STRING)","descr":"ОПИСАНИЕ (STRING)","keyword":"КЛЮЧЕВЫЕ СЛОВА (STRING)","pro":"ДОСТУПНО ДЛЯ ПРО (INT)","fairplay":"ТОЛЬКО БЕЗОПАСНАЯ СДЕЛКА (INT)","created":"ДАТА И ВРЕМЯ СОЗДАНИЯ (DATETIME)","offers_cnt":"КОЛИЧЕСТВО ОТКЛИКОВ (INT)","price":"СТОИМОСТЬ (FLOAT)","price_curr":"2","price_ex":"0","price_rate_text":"","addr_lat":"ШИРОТА МЕСТОПОЛОЖЕНИЯ (FLOAT)","addr_lng":"ДОЛГОТА МЕСТОПОЛОЖЕНИЯ (FLOAT)","views_total":"ВСЕГО ПРОСМОТРОВ (INT)","expire":"ИСТЕКАЕТ (DATETIME)","svc_marked":"0","svc_fixed":"0","startdate":"0000-00-00","starttime":"00:00:00","requestid":"","uk_id":"","buildtype":"","entnum":"","entryconditiondesc":"","instrandmaterialdesc":"","addr_addr":"Ленина ул., д. 33","type_title":"Влажная уборка","type_id":"4","tags":null,"reg3_city":"3227","city_data":{"id":"3227","country":"1000","pid":"1046","title_ru":"Нижний Новгород","title_en":"","title_uk":"Нижний Новгород","title_alt":"GOJ,Gor'kij,Gor'kiy,Gorkey,Gorki,Gorkii,Gorky,Nijni Novgorod,Nijnii Novgorod,Nischni Nowgorod,Nishni-Nowgorod,Nishnii Nowgorod,Nishnij Nowgorod,Nizhni Novgorod,Nizhnii Novgorod,Nizhnij Novgorod,Nizhniy Novgorod,Nizhny Novgorod,Nizjnij Novgorod,Niznij Nowgorod,Nižnij Nowgorod,Горький,Нижний Новгород","declension":"Нижний Новгород","keyword":"nizhnij-novgorod","ycoords":"56.3269,44.0060","enabled":"1","metro":"1","numlevel":"3","num":"135","main":"26","geo_id":"1956","country_code":"","phone_code":"","filter_noregions":"0","pkey":"nizhegorodskaja-oblast","title":"Нижний Новгород"}}],"errors":[]}
```
набор полей в JSON зависит от ваших доработок на системе, не все поля, приведенные в примере могут быть у вас.

