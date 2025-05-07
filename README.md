# Installation

- copy .env file
```bash
cp .env.example .env
```

- change database credentails

    - using docker
        ```.env
        DB_CONNECTION=mysql
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=hotel_management_system
        DB_USERNAME=root
        DB_PASSWORD=secret
        ```
    - without docker
        ```.env
        DB_CONNECTION=mysql
        DB_HOST=your-host
        DB_PORT=3306
        DB_DATABASE=hotel_management_system
        DB_USERNAME=your_db_username
        DB_PASSWORD=your_user_password
        ```

- key generate 
    ```bash
    php artisan key:generate
    ```

- compser install
    ```bash
    composer i
    ```
- npm install
    ```bash
    npm i  && npm run dev
    ```

- Using Docker
    ```bash
    docker-compose up -d --build
    ```
