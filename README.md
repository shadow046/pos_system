

## Web POS

This project will be used for taking customer's order. It was built with Laravel, Vuejs, Inertia and MySQL

## Get Started

### Current Features
 - Creation of accounts
 - Forgot password
 - Device verification
 - Updating profile information
 - Point of sale, taking customer's order on dashboard.
 - Automated generation of receipt
 - Generating reports
 - Activity logs per user
 - Direct printing of receipt
 - Configurations:
   - Category
   - Products
   - Roles
   
<img width="757" alt="Screenshot 2023-05-08 at 4 46 02 PM" src="https://user-images.githubusercontent.com/60385109/236784298-d0d267e6-a12c-4e10-b4fb-4582790d799f.png">

 

### Clone Repo

```shell
https://github.com/ormelflores/pos.git
cd pos
```

### Install composer

```shell
comoposer install
```

### Install npm

```shell
npm install
npm run build
```

### Local env
copy your .env.example to .env and setup your desired database and mailer for sending email notifications.

### Development Server
You can use any development server locally that is compatible with laravel.

```shell
php artisan key:generate
```
### Account setup
To setup account, run seeder on your local. Check <b>DatabaseSeeder.php</b> file to access the account

```shell
php artisan db:seed
```

### Git Convention

### Branch

- `feature/*` - for new feature and breaking changes
- `hotfixes/*` - for bug fixes

### Additional Note

Always rebase the current branch to the main branch before pusing to the origin.

There are two roles implemented on the current system, <b>admin and cashier</b>
