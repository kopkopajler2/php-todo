# Todo App - Backend

Ez a projekt egy egyszerű todo alkalmazás amely a Slim PHP micro-keretrendszert használja és egy plain JavaScriptes html oldal frontended is tartalmaz.

## Telepítési útmutató

1. **Legújabb thread safe php telepítése**

    https://windows.php.net/download#php-8.3

2. **Engedélyezd a php.ini fájlban a következőket:**

    extension=pdo_sqlite
    extension=sqlite3

3. **Composer telepítése:**

    https://getcomposer.org/download/


4. **Klónozd le a repo-t:**

    git clone https://github.com/kopkopajler2/php-todo.git

5. **Navigálj a projekten belül a php-todo mappába:**

    cd php-todo

6. **Telepítsd a függőségeket a Composer segítségével:**

    composer install

7. **Indítsd el a beépített PHP-t, ami a localhost:8000 porton fog futni:**


    php -S localhost:8000   app.php



MEGJEGYZÉS: **A CORS policy miatt csak akkor fog működni a frontend, ha a Live Server VS Code bővítménnyel indítod!**




## Használat

Az alkalmazás a következő végpontokat definiálja:

### GET /api/todos
Lekéri az összes todo elemet.

### POST /api/todos
Új todo elemet hoz létre. A kérés törzsében `category` és `description` kulcsokkal kell megadni az új elem kategóriáját és leírását.

### DELETE /api/todos/{id}
Törli a megadott `id`-s  todo elemet.

### PUT /api/todos/{id}
Frissíti a megadott `id`-s todo elemet. A kérés törzsében `category` és `description` kulcsokkal kell megadni a módosított kategóriát és leírást.

A frontend alkalmazás ezeket a végpontokat fogja felhasználni a todo elemek kezelésére.