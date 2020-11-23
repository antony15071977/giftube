# Проект «Giftube» 

## 1. Из верстки в шаблоны  

  - создать лейаут(ы);
  - создать шаблоны всех страниц;
  - категории и гифки поместить в массивы и на страницах выводить из массивов.

## 2. Проектируем БД

### 1. Создание схемы БД личного проекта
  Подготовка:

  - Создайте новый файл в корне проекта с именем schema.sql.
  - В этом файле напишите весь SQL-код для создания схемы БД вашего проекта.  

  Требования:

  - весь SQL должен без ошибок выполняться на MySQL 5.7;
  - в каждой таблице должен быть первичный ключ.  

  План работы:

  - Сперва напишите SQL для создания новой базы данных с именем вашего проекта.
  - Напишите SQL для создания всех необходимых таблиц.
  - Добавьте уникальные индексы полям, где должны быть только уникальные значения.
  - Добавьте обычные индексы полям, по которым будет происходить поиск.  

  Как проверить:

  - Запустите HeidiSQL (для Windows) или командную строку MySQL.
  - Попробуйте выполнить ваш файл schema.sql.
  - Файл должен выполниться без ошибок.
  - В БД должны быть таблицы для всех сущностей из ТЗ

### 2. Пишем SQL-запросы

  Задание состоит из двух частей:

  - заполнить БД данными, взяв их из существующих массивов.
  - написать запросы для манипуляции этими данными.

  План работы:

  - Создайте новый файл в корне проекта с именем queries.sql.
  - В начале файла напишите запросы типа INSERT для добавления в БД всех необходимых данных.
  - Ниже этом файле напишите SQL-код всех запросов на выборку данных, каждый с новой строчки.
  - Каждый запрос предваряйте комментарием с названием действия, для которого он предназначен.

## 3. Чтение и показ записей из MySQL

На главной странице показываются карточки девяти популярных гифок, отсортированные по количеству лайков. Есть вкладка со свежими гифками, отсортированные от самых новых к старым. В прошлом задании вы написали SQL-запрос для получения таких записей. Сейчас вам будет необходимо заменить существующий массив с гифками на данные, полученные из MySQL по этому запросу.

Также слева страницы находится список категорий. Пока это статичный список, т. е. его отдельные пункты прописаны прямо в верстке. В этом задании вы должны заменить его на данные из БД.

План работы:
1. В сценарии главной страницы выполните подключение к MySQL;
2. Отправьте SQL-запрос для получения списков популярных и свежих гифок;
3. Используйте эти данные для показа карточек гифок на главной странице;
4. Отправьте SQL-запрос для получения списка категорий;
5. Используйте эти данные для показа списка категорий на главной странице слева страницы.

##4. Формы и протокол HTTP
###1. Работа со строкой запроса
Строка запроса — это адрес текущей страницы в браузере, который состоит из домена сайта, пути к странице и параметров запроса.

Параметры запроса — это как бы набор переменных, которые будут доступны внутри сценария. Параметы запроса часто используют как способ передать сценарию дополнительную информацию, которая потребуется для отображения этой страницы.

Страница gif.php содержит подробную информацию о гифке: название, автор, количество лайков и просмотров, список комментариев к гифке. Попасть сюда можно из списка гифок на главной странице. Ссылка на страницу гифки состоит из имени страницы — gif.php, а также параметра запроса — уникального идентификатора гифки.

Значение этого параметра позволяет сценарию gif.php понять, какую из гифок надо показать.

Потребуется сформировать правильные ссылки на гифки, а также показывать на странице gif.php информацию только по гифке, чей идентификатор был передан в параметрах запроса.

####План работы:

Создайте новый сценарий для показа страницы гифки — gif.php.
Создайте новый шаблон, который будет подключаться в gif.php.
Добавьте карточкам гифок ссылки на сценарий gif.php вместе с параметром запроса.
Проверяйте существование параметра запроса с id гифки.
Сформируйте и выполните SQL на чтение записи из таблицы с гифками, где id гифки равен полученному из параметра запроса.
Покажите информацию о гифке на странице.
Если параметр запроса отсутствует, либо если по этому id не нашли ни одной записи, то вместо содержимого страницы возвращать код ответа 404.
###2. Получение данных из форм
Чтобы добавить на сайт новую гифку пользователь должен заполнить форму. В этой форме он описывает свою гифку, указывает категорию и добавляет гифку. После отправки формы проверяется корректность её заполнения: все обязательные поля должны быть заполнены, а форматы полей соответствовать заданным. В этом задании вам предстоит работать с данными из формы: получать данные из полей и проверять их корректность, а затем показывать отправленную информацию на странице.

####План работы:

Создать новый сценарий: add.php.
Создать шаблон для этого сценария.
Заполните список категорий в дропдауне записями из БД.
Все поля в форме должны иметь имена (атрибут name), а action формы указывать на add.php.
После отправки формы выполните валидацию. Руководствуйтесь правилами, описанными в ТЗ.
Если проверка формы выявила ошибки, то сделать следующее:
для всех полей формы, где найдены ошибки:
добавить контейнеру с этим полем класс form__item--invalid;
в .form__errors записать текст ошибки. Например: «Заполните это поле».
Загруженный файл изображения переместите в папку uploads.
Если проверка прошла успешно, то сформировать и выполнить SQL запрос на добавление новой гифки, а затем переадресовать пользователя на страницу просмотра этой гифки.
## 5. Идентификация пользователя на сайте

### 1. Регистрация на сайте

В этом задании вы добавите на сайт форму регистрации и оживите её. С помощью этой формы пользователи будут заводить на сайте себе аккаунт. В число необходимых действий в этом задании входит валидация формы и сохранение полученной из неё информации в базе данных.  

План работы:
1. Проверить, что отправлена форма.
2. Убедиться, что заполнены все обязательные поля.
3. Проверить, что указанный email уже не используется другим пользователем.
4. Если есть ошибки заполнения формы, то сохранить их в отдельном массиве.
5. Если ошибок нет, то сохранить данные формы в таблице пользователей.
6. Если были допущены ошибки (не заполнены все поля, email занят и т.д.), то не добавлять данные в БД, а показать ошибки в форме под соответствующими полями.
7. Если данные были сохранены успешно, то переадресовать пользователя на страницу входа.

Примечания:  
Необходимо проверять, что значение из поля «email» действительно является валидным E-mail адресом. Для этих целей использовать встроенную функцию filter_var и фильтр FILTER_VALIDATE_EMAIL.

Для хранения пароля в БД, его предварительно нужно обработать встроенной функцией password_hash.
### 2. Вход на сайт

Описание процесса аутентификации:  
Процесс аутентификации состоит в сравнении переданных пользователем данных (email, пароль) с хранящимися на сервере.

Алгоритм сравнения:
1. Среди существующих пользователей найти юзера с переданным email.  
2. Если такой пользователь есть, то сравнить пароли, а точнее, их хеши. Пароли не хранятся в открытом виде, вместо них хранятся отпечатки — хеши. Функция password_verify умеет сравнивать хеш и пароль. Используйте её, чтобы убедиться в корректности пароля.
3. Если пользователь передал верные данные (такой email существует и пароль корректный), то необходимо его идентифицировать: открыть новую сессию, в которой будут данные об этом пользователе.

Общий план работы над заданием:
- Сделать страницу с формой входа.
- Написать код для валидации данных формы.
- Написать код для аутентификации пользователя.
- Показывать имя пользователя в сайдбаре.
- Сделать страницу, которая разлогинит пользователя.
- Разделить показ информации на сайте для анонимных и залогиненных пользователей.  

Список шагов:
- Создайте новую страницу с формой входа.
- При отправке формы проверяйте заполненность двух полей. Если поле не было заполнено, то следует показать ошибку (см. прошлое задание).
- Если форма прошла валидацию, то выполнить процесс аутентификации и переадресовать пользователя на главную страницу. Если пользователь ввёл неверный пароль, то показать ошибку.
- В сайдбаре слева показывать имя пользователя, «Избранное» и «Выход» если есть открытая сессия, иначе показывать ссылки «Регистрация» и «Вход для своих». Ссылка «Вход для своих» должна вести на страницу входа.
- На странице gif.php скрывать кнопки «Мне нравится», «В избранное» и блок для добавления комментария для анонимных пользователей.
- Добавить новый сценарий: logout.php. Сценарий должен обнулять пользовательскую сессию и переадресовывать на главную страницу. Добавить адрес этой страницы в ссылку «Выход» под именем пользователя.
- Закрыть доступ к страницам add.php и favorites.php для анонимных пользователей. При попытке обращения к этой странице анонимному пользователю должен возвращаться HTTP-код ответа 403.

##6. Дополнительная практика с MySQL
 Завершение работы над проектом
Для этого выполните следующие части:

###Добавление комментария к гифке и его вывод под гифкой.
###Добавление лайков - сохранять информацию в базе данных;
###Добавление гифок в избранное - сохранять информацию в базе данных
###Мое избранное - новая страница, где можно посмотреть список своих избранных гифок.
###Подсчет и вывод количества лайков
###Подсчет и вывод количества добавлений в избранное (favs_count)
###Подсчет и вывод количества просмотров (views_count)

##7 Полнотекстовый поиск
Полнотекстовый поиск — это возможность MySQL, которая позволяет искать записи в таблице по нестрогому совпадению текста. Используйте его, когда необходимо добавить на сайт поиск по отдельным сущностям с текстовым наполнением.

Задание:
Оживите форму поиска в шапке сайта. Эта форма будет искать гифки по их названию или описанию. Найденные гифки будут показаны на отдельной странице поиска.

Список действий:

В schema.sql добавьте SQL инструкцию на создание полнотекстового индекса для полей «название» и «описание» в таблице гифок. Этот же запрос выполните в своей MySQL.
Форма поиска должна вести на файл search.php и работать по методу GET.
Сделайте новый шаблон для страницы поиска.
Добавьте новый сценарий search.php, который будет искать гифки и показывать результат в шаблоне.
Если по запросу нет результатов, то на их месте должна быть надпись «Ничего не найдено по вашему запросу».
Проверка:

Выполните запрос на создание полнотекстового индекса.
Введите название одного из существующих гифок в форму поиска и нажмите enter.
Должны перейти на страницу search.php, где будет показан лот с этим именем.
В поле формы поиска должен быть текст заданного поискового запроса.

##8 Транзакции
##9 Пагинация
##10 Файл config общий для всех с параметрами подключения к БД
##11 Валидация формы регистрации с помощью Jquery. 
###Добавляем поле "повторите пароль" у формы регистрации
###Ослеживаем правильность емейла
###Совпадение и число символов полей паролей
###Показать/скрыть пароль кнопка
###С помощью Ajax ослеживаем на лету уникальность полей Емейл и Логин, при уже использованных сразу выводим ошибку
###При этом на бекенде независимо проводится такая же проверка всех полей
##Регистрация с подтверждением почты по емейлу
##сетчик пользователей, находящихся онлайн на сайте
