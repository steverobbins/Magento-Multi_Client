Magento Multi Client
===

Host multiple clients on the same Magento code base.

Each client has their own `local.xml` and `etc/modules.xml`.  This allows you to
specify separate databases and caching services per client, as well as
enable/disable modules as requested.

Each client maintains their independance but shares a code base.

This could be considered a Magento Go-esque approach to hosting Magento
websites.

# Directory Structure

Because of customized directory structure the entire Magento code base is
included in this repository.  The structure may look like:

* magento/app
* magento/var/client1
* magento/var/client2
* magento/public/skin
* magento/public/media/client1
* magento/public/media/client2

Note that each client has a separate media path.  This prevents file collisions
and lets you use a single CDN domain.

# Client Codes

To work correctly Magento has to know which client this visited site is.  This
done by setting the `CLIENT_CODE` environment variable.

See `index.php` or `cron.php` for an example of how `Mage::app()`/`Mage::run()`
is intialized.

## Nginx

```
server {
  listen 80;
  server_name store.example.com;
  root /var/www/html/public;
  location ~ .php$ {
    fastcgi_pass  unix:/var/run/php5-fpm.sock;
    fastcgi_param CLIENT_CODE client1;
  }
}
```

## Apache

```
<VirtualHost *:80>
  ServerName store.example.com
  DocumentRoot /var/www/html/public
  SetEnv CLIENT_CODE client1
</VirtualHost>
```

# Configuration

Each client has a `local.xml` and module declaration folder.  These are located
in

* `app/etc/multiclient/<CLIENT_CODE>/local.xml`
* `app/etc/multiclient/<CLIENT_CODE>/etc/modules/`

Magento will load `app/etc/*.xml` and `app/etc/local.xml` first, then
`app/etc/multiclient/<CLIENT_CODE>/*.xml` and
`app/etc/multiclient/<CLIENT_CODE>/local.xml`, giving the client's `local.xml`
the highest priority.

The same order is used for module declaration.

# Cron

Cron should be run every minute.  The `cron.sh` is located just above `public`.
It will look for folders in `app/etc/multiclient/`.  The folders names must
match a `CLIENT_CODE`.

# Shell Scripting

Create you script in `shell/multiclient/<CLIENT_CODE>/myScript.php` and extend
`Multi_Client_Shell_Abstract` and it will handle setting the `CLIENT_CODE`.

# To Do

* Composerify
* Not sure downloadable products will work (`get.php` in media)
* Tests and CI
* Streamline new client installation process (for now delete `app/etc/local.xml`
to trigger the install, then `mv` it to
`app/etc/multiclient/<CLIENT_CODE>/local.xml`).

---

*This is very much a work in progress.*
