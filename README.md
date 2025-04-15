# PHP-API README

## Fejlesztői környezet beállítása

A projekt futtatásához az alábbi lépéseket kell követni:

### 1. Beépített PHP szerver indítása

A fejlesztői környezetben a projektet a beépített PHP szerver segítségével futtathatod. Ehhez navigálj a projekt gyökérkönyvtárába, majd futtasd a következő parancsot:

```sh
php -S localhost:2999
```

Ez elindítja a szervert a `localhost` címen, a `2999`-es porton.

### 2. Adatbázis dokumentáció elérése

A projekt adatbázisának dokumentációja az alábbi címen érhető el a futó szerveren:

[http://localhost:2999/doc](http://localhost:2999/doc)

### 3. Mailer szolgáltatás dokumentációja

A mailer komponens működésével kapcsolatos dokumentáció az alábbi címen található:

[http://localhost:2999/doc/mailer](http://localhost:2999/doc/mailer)

### 4. PHP verzió követelmény

A projekt futtatásához minimum **PHP 8.2** verzió szükséges. Ellenőrizheted a telepített PHP verziót az alábbi paranccsal:

```sh
php -v
```

Ha a verzió régebbi, frissítsd a PHP-t a megfelelő verzióra a rendszerednek megfelelő módon.
