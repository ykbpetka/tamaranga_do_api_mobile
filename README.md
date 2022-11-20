# tamaranga_do_api_mobile
Проект создания API для мобильного приложения для сервиса на движке Tamaranga DO

# Инструкция по установке:
1. Заходите в панель администратора Tamaranga FL;
2. Включаете режим разработчика;
3. В админке идете Дополнения->Плагины и нажимете Создать пллагин
4. Вводите название на русском и английском языке;
5. Открываете на сервере папку с вашим проектом и переходите в папку Plugins и там ищете папку с названием вашего расширения;
6. Файл api.php без изменений копируете в папку tpl;
7. Данные из файла index.php копируете в файл index.php, ккоторый есть в папке вашего расширения. Обратите внимание, что вам потребуется название класса, которое сгенерирует Tamaranga, а также extension_id. 

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

В ответ вернется JSON с информацией о польззователе
```json
{
   "data":{
      "name":"Пётр",
      "surname":"Кушиков",
      "email":"y***********m",
      "phone_number":"+7**6******2"
   },
   "errors":[
      
   ]
}
```

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
{
   "data":[
      {
         "id":"ID ЗАЯВКИ (INT)",
         "user_id":"ID ПОЛЬЗОВАТЕЛЯ КТО СОЗДАЛ (INT)",
         "type":"ТИП ЗАЯВКИ (INT)",
         "service_type":"ПОДТИП ЗАЯВКИ (INT)",
         "status":"СТАТУС (INT)",
         "title":"ЗАГОЛОВОК (STRING)",
         "descr":"ОПИСАНИЕ (STRING)",
         "keyword":"КЛЮЧЕВЫЕ СЛОВА (STRING)",
         "pro":"ДОСТУПНО ДЛЯ ПРО (INT)",
         "fairplay":"ТОЛЬКО БЕЗОПАСНАЯ СДЕЛКА (INT)",
         "created":"ДАТА И ВРЕМЯ СОЗДАНИЯ (DATETIME)",
         "offers_cnt":"КОЛИЧЕСТВО ОТКЛИКОВ (INT)",
         "price":"СТОИМОСТЬ (FLOAT)",
         "price_curr":"2",
         "price_ex":"0",
         "price_rate_text":"",
         "addr_lat":"ШИРОТА МЕСТОПОЛОЖЕНИЯ (FLOAT)",
         "addr_lng":"ДОЛГОТА МЕСТОПОЛОЖЕНИЯ (FLOAT)",
         "views_total":"ВСЕГО ПРОСМОТРОВ (INT)",
         "expire":"ИСТЕКАЕТ (DATETIME)",
         "svc_marked":"0",
         "svc_fixed":"0",
         "startdate":"0000-00-00",
         "starttime":"00:00:00",
         "requestid":"",
         "uk_id":"",
         "buildtype":"",
         "entnum":"",
         "entryconditiondesc":"",
         "instrandmaterialdesc":"",
         "addr_addr":"Ленина ул., д. 33",
         "type_title":"Влажная уборка",
         "type_id":"4",
         "tags":null,
         "reg3_city":"3227",
         "city_data":{
            "id":"3227",
            "country":"1000",
            "pid":"1046",
            "title_ru":"Нижний Новгород",
            "title_en":"",
            "title_uk":"Нижний Новгород",
            "title_alt":"GOJ,Gor'kij,Gor'kiy,Gorkey,Gorki,Gorkii,Gorky,Nijni Novgorod,Nijnii Novgorod,Nischni Nowgorod,Nishni-Nowgorod,Nishnii Nowgorod,Nishnij Nowgorod,Nizhni Novgorod,Nizhnii Novgorod,Nizhnij Novgorod,Nizhniy Novgorod,Nizhny Novgorod,Nizjnij Novgorod,Niznij Nowgorod,Nižnij Nowgorod,Горький,Нижний Новгород",
            "declension":"Нижний Новгород",
            "keyword":"nizhnij-novgorod",
            "ycoords":"56.3269,44.0060",
            "enabled":"1",
            "metro":"1",
            "numlevel":"3",
            "num":"135",
            "main":"26",
            "geo_id":"1956",
            "country_code":"",
            "phone_code":"",
            "filter_noregions":"0",
            "pkey":"nizhegorodskaja-oblast",
            "title":"Нижний Новгород"
         }
      }
   ],
   "errors":[
      
   ]
}
```
набор полей в JSON зависит от ваших доработок на системе, не все поля, приведенные в примере могут быть у вас.

## Получение деталей заказа
GET /api/orderinfo?orderid=:orderid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| orderid | YES | INT |

В ответ вернется JSON с детальной информацией о заказе
```json
{
   "data":{
      "id":"23",
      "type":"1",
      "service_type":"2",
      "user_id":"16",
      "user_ip":"",
      "status":"3",
      "status_prev":"4",
      "status_changed":"2022-11-20 15:03:21",
      "removed":"0",
      "title":"Тут заголовок заявки,",
      "descr":"Тут описание требований по выполнению работ",
      "keyword":"tut-zagolovok-zajavki",
      "enabled":"1",
      "offers_cnt":"1",
      "created":"2021-11-24 09:37:41",
      "modified":"2022-11-20 15:03:26",
      "approved":"2021-11-24 09:37:41",
      "term":"0",
      "expire":"0000-00-00 00:00:00",
      "performer_id":"0",
      "performer_created":"0000-00-00 00:00:00",
      "price":"250",
      "price_curr":"2",
      "price_ex":"0",
      "price_rate":"0",
      "price_rate_text":"",
      "price_search":"250",
      "moderated":"1",
      "blocked_reason":null,
      "addr_addr":"Ленина ул., д. 1",
      "addr_lat":"56.308361",
      "addr_lng":"43.938320",
      "imgfav":"0",
      "imgcnt":"0",
      "attachcnt":"0",
      "activate_key":"",
      "activate_expire":"0000-00-00 00:00:00",
      "pro":"0",
      "visibility":"0",
      "fairplay":"1",
      "invited_users":null,
      "views_total":"7",
      "svc":"0",
      "svc_order":"0000-00-00 00:00:00",
      "svc_fixed_to":"0000-00-00 00:00:00",
      "svc_fixed_order":"0000-00-00 00:00:00",
      "svc_marked_to":"0000-00-00 00:00:00",
      "svc_marked_logo":"",
      "f1":"0",
      "f2":"0",
      "f3":"0",
      "f4":"0",
      "f5":"0",
      "f6":"0",
      "f7":"0",
      "f8":"0",
      "f9":"0",
      "f10":"0",
      "f11":"0",
      "f12":"0",
      "f13":"0",
      "f14":"0",
      "f15":"0",
      "f16":null,
      "f17":null,
      "f18":null,
      "f19":null,
      "f20":null,
      "startdate":"0000-00-00",
      "starttime":"00:00:00",
      "requestid":"",
      "uk_id":"",
      "buildtype":"",
      "entnum":"",
      "entryconditiondesc":"",
      "instrandmaterialdesc":"",
      "order_pro":"0",
      "user_type":"2",
      "user_created":"2021-05-13 13:03:00",
      "opinions_cache":"0;0;0",
      "contacts":"a:1:{i:0;a:2:{s:1:\"t\";i:1;s:1:\"v\";s:11:\"8800*******\";}}",
      "name":"ИМЯ ЗАКАЗЧИКА",
      "surname":"ФАМИЛЛИЯ ЗАКАЗЧИКА",
      "login":"ЛОГИ ЗАКАЗЧИКА",
      "avatar":"",
      "verified":"0",
      "sex":"1",
      "last_activity":"2021-11-26 11:04:40",
      "specs":[
         {
            "order_id":"23",
            "spec_id":"4",
            "cat_id":"4",
            "num":"1",
            "spec_title":"Влажная уборка",
            "keyword":"vlazhnaja-uborka",
            "cat_title":"Уборка",
            "lvl":"1",
            "pid":"1"
         }
      ],
      "regions":[
         {
            "order_id":"23",
            "reg1_country":"1000",
            "reg2_region":"1046",
            "reg3_city":"3227",
            "title":"Нижний Новгород",
            "country_title":"Россия"
         }
      ]
   },
   "errors":[
      
   ]
}
```
набор полей в JSON зависит от ваших доработок на системе, не все поля, приведенные в примере могут быть у вас.

## Получение перечня откликов на заказ
GET /api/orderoffers?orderid=:orderid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| orderid | YES | INT |

В ответ вернется JSON с детальной информацией об откликах к заказу
```json
{
   "data":[
      {
         "id":"14",
         "status":"0",
         "price_from":"0",
         "price_to":"175",
         "price_curr":"2",
         "price_rate":"0",
         "price_rate_text":"",
         "terms_from":"2021-09-28",
         "terms_to":"15-16",
         "terms_type":"0",
         "descr":null,
         "fairplay":"1",
         "client_only":"1",
         "user_id":"30",
         "created":"2021-11-26 10:51:23",
         "chatcnt":"0",
         "examplescnt":"0",
         "is_new":"1",
         "chat_new_client":"0",
         "chat_new_worker":"0",
         "workflow":null,
         "name":"Пётр",
         "surname":"Кушиков",
         "avatar":"a71195.jpg",
         "sex":"2",
         "login":"ya",
         "verified":"0",
         "pro":"0",
         "user_type":"1",
         "opinions_cache":"0;0;0",
         "reg3_city":"3227",
         "reg1_country":"1000",
         "contacts":"a:1:{i:0;a:2:{s:1:\"t\";i:1;s:1:\"v\";s:12:\"+7**6*******2\";}}",
         "spec_id":"4",
         "last_activity":"2022-11-20 15:37:51",
         "user_blocked":"0",
         "spec_title":"Влажная уборка",
         "main_spec_keyword":"vlazhnaja-uborka",
         "main_cat_keyword":"uborka"
      }
   ],
   "errors":[
      
   ]
}
```
набор полей в JSON зависит от ваших доработок на системе, не все поля, приведенные в примере могут быть у вас.

## Публикация отлика на заказ
POST /api/offersaveapp

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| userid | YES | INT |
| orderid | YES | INT |
| orderdate | NO | INT |
| orderperiod | NO | INT |
| offprice | YES | INT |

В ответ вернется JSON с информацией
```JSON
    {
    "data": {
        "id": 0
    },
    "errors": []
}
```

## Получение списка заказов на которые откликался пользователь
GET /api/listmyorders?st=2&userid=:userid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| userid | YES | INT |

В ответ вернется JSON с информацией
```JSON
{
   "data":[
      {
         "id":"3",
         "type":"1",
         "service_type":"2",
         "status":"4",
         "title":"Тут заголовок заявки",
         "descr":"Тут описание требований по выполнению работ",
         "keyword":"vlazhnaja-uborka",
         "pro":"0",
         "fairplay":"1",
         "created":"2021-10-25 11:42:28",
         "offers_cnt":"2",
         "price":"200",
         "price_curr":"2",
         "price_ex":"0",
         "price_rate_text":"",
         "order_user_id":"16",
         "visibility":"0",
         "tags":null,
         "reg3_city":"3227",
         "startdate":"2021-09-28",
         "starttime":"23:23:23",
         "requestid":"ca024b66-08b6-11ec-b53e-9453303dad70",
         "uk_id":"0",
         "buildtype":"1",
         "entnum":"2",
         "entryconditiondesc":"Здесь отображаются параметры ",
         "instrandmaterialdesc":"Здесь будет отображаться перечень материалов",
         "addr_addr":"Ленина ул., д. 1",
         "type_title":"Влажная уборка",
         "type_id":"4",
         "offer_id":"6",
         "offer_status":"0",
         "user_id":"30",
         "chatcnt":"0",
         "user_removed":"0",
         "chat_new_worker":"0",
         "city_data":{
            "id":"3227",
            "country":"1000",
            "pid":"1046",
            "title_ru":"Нижний Новгород",
            "title_en":"",
            "title_uk":"Нижний Новгород",
            "title_alt":"GOJ,Gor'kij,Gor'kiy,Gorkey,Gorki,Gorkii,Gorky,Nijni Novgorod,Nijnii Novgorod,Nischni Nowgorod,Nishni-Nowgorod,Nishnii Nowgorod,Nishnij Nowgorod,Nizhni Novgorod,Nizhnii Novgorod,Nizhnij Novgorod,Nizhniy Novgorod,Nizhny Novgorod,Nizjnij Novgorod,Niznij Nowgorod,Nižnij Nowgorod,Горький,Нижний Новгород",
            "declension":"Нижний Новгород",
            "keyword":"nizhnij-novgorod",
            "ycoords":"56.3269,44.0060",
            "enabled":"1",
            "metro":"1",
            "numlevel":"3",
            "num":"135",
            "main":"26",
            "geo_id":"1956",
            "country_code":"",
            "phone_code":"",
            "filter_noregions":"0",
            "pkey":"nizhegorodskaja-oblast",
            "title":"Нижний Новгород"
         }
      },
      {
         "id":"11",
         "type":"1",
         "service_type":"2",
         "status":"4",
         "title":"Тут заголовок заявки",
         "descr":"Тут описание требований по выполнению работ",
         "keyword":"vlazhnaja-uborka2",
         "pro":"0",
         "fairplay":"1",
         "created":"2021-11-15 12:30:55",
         "offers_cnt":"2",
         "price":"200",
         "price_curr":"2",
         "price_ex":"0",
         "price_rate_text":"",
         "order_user_id":"16",
         "visibility":"0",
         "tags":null,
         "reg3_city":"3515",
         "startdate":"2021-09-03",
         "starttime":"00:00:00",
         "requestid":"6095ed80-0b49-11ec-b53e-9453303rad71",
         "uk_id":"5836683623",
         "buildtype":"1",
         "entnum":"1",
         "entryconditiondesc":"Здесь отображаются параметры",
         "instrandmaterialdesc":"Здесь будет отображаться перечень материалов",
         "addr_addr":"Медицинская ул., д. 2",
         "type_title":"Влажная уборка",
         "type_id":"4",
         "offer_id":"7",
         "offer_status":"0",
         "user_id":"30",
         "chatcnt":"0",
         "user_removed":"0",
         "chat_new_worker":"0",
         "city_data":{
            "id":"3515",
            "country":"1000",
            "pid":"1052",
            "title_ru":"Пенза",
            "title_en":"",
            "title_uk":"Пенза",
            "title_alt":"Penza,Penzovskiy,Пенза",
            "declension":"Пенза",
            "keyword":"penza",
            "ycoords":"",
            "enabled":"1",
            "metro":"0",
            "numlevel":"3",
            "num":"97",
            "main":"72",
            "geo_id":"2211",
            "country_code":"",
            "phone_code":"",
            "filter_noregions":"0",
            "pkey":"penzenskaja-oblast",
            "title":"Пенза"
         }
      },
      {
         "id":"12",
         "type":"1",
         "service_type":"2",
         "status":"4",
         "title":"Тут заголовок заявки",
         "descr":"Тут описание требований по выполнению работ",
         "keyword":"vlazhnaja-uborka3",
         "pro":"0",
         "fairplay":"1",
         "created":"2021-11-16 13:36:17",
         "offers_cnt":"1",
         "price":"200",
         "price_curr":"2",
         "price_ex":"0",
         "price_rate_text":"",
         "order_user_id":"16",
         "visibility":"0",
         "tags":null,
         "reg3_city":"3515",
         "startdate":"2021-09-03",
         "starttime":"00:00:00",
         "requestid":"6095ed80-0b49-11ec-b53e-9453303rad71",
         "uk_id":"5836683623",
         "buildtype":"1",
         "entnum":"1",
         "entryconditiondesc":"Здесь отображаются параметры",
         "instrandmaterialdesc":"Здесь будет отображаться перечень материалов",
         "addr_addr":"Медицинская ул., д. 2",
         "type_title":"Влажная уборка",
         "type_id":"4",
         "offer_id":"9",
         "offer_status":"0",
         "user_id":"30",
         "chatcnt":"0",
         "user_removed":"0",
         "chat_new_worker":"0",
         "city_data":{
            "id":"3515",
            "country":"1000",
            "pid":"1052",
            "title_ru":"Пенза",
            "title_en":"",
            "title_uk":"Пенза",
            "title_alt":"Penza,Penzovskiy,Пенза",
            "declension":"Пенза",
            "keyword":"penza",
            "ycoords":"",
            "enabled":"1",
            "metro":"0",
            "numlevel":"3",
            "num":"97",
            "main":"72",
            "geo_id":"2211",
            "country_code":"",
            "phone_code":"",
            "filter_noregions":"0",
            "pkey":"penzenskaja-oblast",
            "title":"Пенза"
         }
      }
   ],
   "errors":[
      
   ]
}
```
набор полей в JSON зависит от ваших доработок на системе, не все поля, приведенные в примере могут быть у вас.

## Проверка статуса отклика на заказ
GET /api/mwbo?type=2&orderid=:orderid&userid=userid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| userid | YES | INT |
| orderid | YES | INT |

В ответ вернется JSON с информацией
```JSON
{
   "data":"0",
   "errors":[
      
   ]
}
```
Возможные значения в data:
1 ИЛИ 5 - пользователь выбран исполнителем
2 - пользователь кандидат
3 - пользователю отказали
4 - пользователю предлагают стать исполнителем
6 ИЛИ 7 - пользователь отказался

## Проверка кто выбран исполнителем по заказу
GET /api/mwbo?type=1&orderid=:orderid

Request Params	
| Param Name | Required | Type |
|----------------|-----------|-----------------|
| orderid | YES | INT |

В ответ вернется JSON с информацией
```JSON
{
   "data":"16",
   "errors":[
      
   ]
}
```
В параметре data возвращается 
