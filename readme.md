# Задача
Написать простой форум на php не применяя готовые библиотеки

# Описание проекта
Проект следует ключевым принципам:
* Все пароли хэшируются bcrypt
* Все запросы к базе делаются с помощью pdo 
* Все куки с пользовательскими данными криптуются серверной солью
* Весь вывод проходит через htmlspecialchars
* Все изменения в схеме базе данных реализованы через миграции
* Все письма отправляются с помощью mbstring и логируются в папку protected/runtime/mails
* Все ошибки и исключения перехватываются и логируются
* Отдельная страница для ошибки 404

# Установка и запуск
Самый простой способ запуска и установки выполнить:
(требуется установленный virtualbox и vagrant на компьютере)
vagrant up

и перейти по адресу http://192.168.33.99

Системные требования - php 5.5, mysql, mbstring

# Данные по демо юзерам:

Логин: test@mail.ru
Пароль: qazwsxedc

Логин: test2@mail.ru
Пароль: qazwsxedc


Затраченное время на написание кода ~ 14 часов
