Usage
-----

There's no need to configure anything to run the application. If you have
installed the [Symfony client][4] binary, run this command to run the built-in
web server and access the application in your browser at <http://localhost:8000>:

```bash
$ cd my_project/
$ composer install
$ symfony server:start
```

If you don't have the Symfony client installed, run `php bin/console server:run`.
Alternatively, you can [configure a web server][3] like Nginx or Apache to run
the application.

Tests
-----

Execute this command to run tests:

```bash
$ cd my_project/
$ php bin/phpunit
```
