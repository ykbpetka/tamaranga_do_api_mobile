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

## Получение деталей заказа
GET /api/orderinfo?orderid=:orderid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| orderid | YES | INT |

В ответ вернется JSON со списком всех досутпных заказов
```json
    {"data":{"id":"23","type":"1","service_type":"2","user_id":"16","user_ip":"","status":"3","status_prev":"4","status_changed":"2022-11-20 15:03:21","removed":"0","title":"Тут заголовок заявки, который придёт из ЖКХ 2.0. Если придёт пустота, то автоматом покажем склейку Тип уборки, подъезд, адрес","descr":"Тут описание требований по выполнению работ, которое придёт из ЖКХ 2.0. Если придёт пустота, то автоматом покажем склейку Тип уборки, подъезд, адрес","keyword":"tut-zagolovok-zajavki-kotoryj-pridot-iz-zhkkh-20-jesli-pridot-pustota-to-avtomatom-pokazhem-sklejku-tip-uborki-podjezd-adres","enabled":"1","offers_cnt":"1","created":"2021-11-24 09:37:41","modified":"2022-11-20 15:03:26","approved":"2021-11-24 09:37:41","term":"0","expire":"0000-00-00 00:00:00","performer_id":"0","performer_created":"0000-00-00 00:00:00","price":"250","price_curr":"2","price_ex":"0","price_rate":"0","price_rate_text":"","price_search":"250","moderated":"1","blocked_reason":null,"addr_addr":"Ленина ул., д. 33","addr_lat":"56.308361","addr_lng":"43.938320","imgfav":"0","imgcnt":"0","attachcnt":"0","activate_key":"","activate_expire":"0000-00-00 00:00:00","pro":"0","visibility":"0","fairplay":"1","invited_users":null,"views_total":"7","svc":"0","svc_order":"0000-00-00 00:00:00","svc_fixed_to":"0000-00-00 00:00:00","svc_fixed_order":"0000-00-00 00:00:00","svc_marked_to":"0000-00-00 00:00:00","svc_marked_logo":"","f1":"0","f2":"0","f3":"0","f4":"0","f5":"0","f6":"0","f7":"0","f8":"0","f9":"0","f10":"0","f11":"0","f12":"0","f13":"0","f14":"0","f15":"0","f16":null,"f17":null,"f18":null,"f19":null,"f20":null,"startdate":"0000-00-00","starttime":"00:00:00","requestid":"","uk_id":"","buildtype":"","entnum":"","entryconditiondesc":"","instrandmaterialdesc":"","order_pro":"0","user_type":"2","user_created":"2021-05-13 13:03:00","opinions_cache":"0;0;0","contacts":"a:1:{i:0;a:2:{s:1:\"t\";i:1;s:1:\"v\";s:11:\"88003330303\";}}","name":"Мой дом","surname":"АО","login":"kushikovps","avatar":"","verified":"0","sex":"1","last_activity":"2021-11-26 11:04:40","specs":[{"order_id":"23","spec_id":"4","cat_id":"4","num":"1","spec_title":"Влажная уборка","keyword":"vlazhnaja-uborka","cat_title":"Уборка","lvl":"1","pid":"1"}],"regions":[{"order_id":"23","reg1_country":"1000","reg2_region":"1046","reg3_city":"3227","title":"Нижний Новгород","country_title":"Россия"}]},"errors":[]}
```
набор полей в JSON зависит от ваших доработок на системе, не все поля, приведенные в примере могут быть у вас.
