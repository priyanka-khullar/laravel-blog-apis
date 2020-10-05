# Laravel Blogs Api

## Steps to setup project 

1. git clone https://github.com/priyanka-khullar/laravel-blog-apis.git
2. cd laravel-api
3. Run `composer install`
4. Run `php artisan migarte`
5. Run `php artisan db:seed`
6. create new file on root named .env
7. Run `php artisan generate:key`
8. Add mail configurations in .env file
9. Run `php artisan jwt:secret`
10. Run `php artisan serve`

### Api Details 
Host: http://localhost:8000/api/v1
-------------------------------------------------------------------------------------
## Users 

# Create User
Endpoint: `\users`
Request: `Post`
Headers: 
   `Accept: application/json,
    Content-Type: application/json,
    Authorization: bearer{{TOKEN}}`
Request Params:
    `name:Priyanka123
     email:p@k.com
     password:123456
     role_id:1
     phone:8888888888
     address:Sundar Nagar Ludhiana`
Response: 
    ```{
          "data": {
              "type": "item",
              "id": "1",
              "attributes": {
                  "role_id": "1",
                  "name": "Priyanka123",
                  "email": "p@k.com",
                  "phone": "9216464824",
                  "address": "Sundar Nagar Ludhiana",
                  "status": true,
                  "profile": "http://localhost:8000/uploads/larges/",
                  "thumbnail": "http://localhost:8000/uploads/thumbnails/",
                  "middle": "http://localhost:8000/uploads/resize/"
              }
          },
          "message": "User Registered!"
      }```


