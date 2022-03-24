<p align="center"><img src="https://www.readyworks.com/hs-fs/hubfs/ReadyWorks-Logo-White-Knockout-Large.png?width=2500&height=536&name=ReadyWorks-Logo-White-Knockout-Large.png" width="400"></p>


## Ready Works - Final Project

I am very excited to present my final project to the Ready works family.

## Getting the app up and running

I used laravel sail to dockerize this application. 

Before we can use sail we need to run 
`composer install`

Then: Copy and put what is below in your .env 

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=ReadyWorks_Demo
DB_USERNAME=readyworks
DB_PASSWORD=secret
FORWARD_DB_PORT=3307
```

Then run 

`./vendor/bin/sail up`

`./vendor/bin/sail artisan migrate`

`./vendor/bin/sail artisan db:seed`

`./vendor/bin/sail npm install && npm run dev`

## Using Docker on Mac
You may come across an application key error.

If so just run:

`./vendor/bin/sail artisan key:generate`

`./vendor/bin/sail artisan config:cache`


## Testing the app
Visit this link
 [Canoe Final Project](http://localhost)

## Questions or concerns
[Please reach out to me via email](mailto:hollywoodfl23@gmail.com).

