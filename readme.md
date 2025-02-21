# sym-solo





running project (default) :
```
symfony server:start
```



<br/> 
<br/> 
<br/> 

---

<br/>
<br/>

To generate a new app secret for your Symfony application, you can use the bin/console command provided by Symfony. Here is how you can do it:
Open your terminal.
Navigate to the root directory of your Symfony project.
Run the following command to generate a new secret:

```
php bin/console secrets:generate-keys
```

This command will generate a new secret key and store it in the .env file. You can then copy this key and replace the APP_SECRET value in your .env file.  Alternatively, you can manually generate a random secret key using PHP:

```
php -r 'echo bin2hex(random_bytes(16));'
```

This will output a random 32-character hexadecimal string that you can use as your APP_SECRET. Replace the APP_SECRET value in your .env file with this new key.


<br/>
<br/>

---

<br/>
<br/>

### `.env` File


```
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=c3503b1ebc2870648d443a7203de3911%
DATABASE_URL="mysql://root:@127.0.0.1:3306/charging_station"
###< symfony/framework-bundle ###
```




---

#### info

```
php bin/console about
```



---

#### install symfony/serializer and symfony/validator

```
composer require symfony/serializer symfony/validator
```