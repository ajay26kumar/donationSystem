This project demonstrates a basic use case for Stripe Payment Link to create a simple donation page. It utilizes callbacks to integrate Stripe with a Laravel project. Upon successful payment, Stripe sends data on to the Laravel project, which then inserts a new donation record into the database. A custom field, message, is implemented to allow users to add a message with their donation. This message is also sent to the Laravel project via Stripe's webhook.

Features
Simple donation page powered by Stripe Payment Link.

Follow these steps to run the project locally:

Clone the Project

git clone https://github.com/ajay26kumar/donationSystem
cd donationSystem![Screenshot 2025-05-12 091446](https://github.com/user-attachments/assets/aa49d166-a1fb-4aef-91c6-9eef9f6d5e61)

Configure Environment

Copy the .env.example file to .env:
cp .env.example .env
Update the .env file with your database credentials and Stripe API keys.
Run Migrations

php artisan migrate
Start the Development Server

php artisan serve


![Screenshot 2025-05-12 091446](https://github.com/user-attachments/assets/616ad976-98b6-4a1b-bbae-2d12bfeadb39)
