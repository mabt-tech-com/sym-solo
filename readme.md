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

<br/>
<br/>
<br/>

---

<br/>
<br/>
<br/>


# Loyaltiy points testing : 


To use this system:

### Create Products with points values:


```
POST /product/new

{
"name": "Premium Widget",
"product_points": 100,
"prix": 49.99
}
```

### Purchase (earn points):


```
POST /checkout


{
"use_points": 0
}
```

### Redeem points next purchase:

```
POST /checkout


{
"use_points": 500
}
```

### Admin grant points:

```
POST /admin/grant-points


{
"user_id": 123,
"points": 1000
}
```

### Migration Steps:
Run `symfony console make:migration` to generate migrations for new fields

Update your Postman collection with new endpoints

### Create initial LoyaltySettings entry:

```
INSERT INTO loyalty_settings (id, points_to_money_ratio) VALUES (1, 0.1);
```
 


---