####Приложение для ведения управленческого учета:
 
* Ввод данных договоров (номер, дата, сумма, заказчик и т. д.), проверка заполнения необходимых полей формы (средствами PHP), запись введенной информации в БД MySQL;
* Ввод справочных данных (реестр заказчиков, способы оплаты, менеджеры, статьи затрат), сохранение информации в БД MySQL;
* Использование выпадающих списков с данными из справочных таблиц для ввода данных новых договоров; в т.ч. использование многоуровневых выпадающих списков по технологии Ajax (chained select);
* Учет доходов и расходов от выполнения договоров, расчет прибыли “на лету” в форме ввода с использованием JavaScript, сохранение данных в БД MySQL;
* Составление различных отчетов по договорам, их прибыльности (запросы к  БД MySQL);
* Сохранение отчетов в форматах CSV (с использованием Javascript) и PDF (с использованием библиотеки Dompdf);
* Поиск договоров в реестре по номерам, а также за период, разбивка списка на страницы, выбор количества договоров на странице;
* Администрирование пользователей (формы добавления и удаления пользователей, шифрование паролей);
* Разделение доступа к частям приложения для различных групп пользователей, использование сессий, cookies. 

####Application for management accounting:
 
* Entering data of agreements (number, date, amount, customer and so on). Verification of filling the necessary fields of the form (PHP methods), recording entered information into MySQL database;
* Entering the reference data (customer registry, payment methods, management personnel, cost items), storing information in a MySQL database;
* Use the drop-down lists with reference tables’ data for the input of new contracts; including of multi-level drop-down lists on Ajax technology (chained select);
* Revenue and expenses accounting, profit calculation "on the fly" in the web-form using the JavaScript, saving data into a MySQL database;
* Preparation of various reports on contracts, their profitability (MySQL database queries);
* Saving reports in CSV format (using JavaScript) and PDF (using Dompdf library);
* Search for contracts by the registry numbers, as well as for period, division of the contracts list into pages, select the number of contracts on the page;
* User administration (forms for add and remove users, password encryption);
* Separation of access to parts of the application for different user groups, use of sessions, cookies.