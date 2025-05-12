This project demonstrates a basic use case for Stripe Payment Link to create a simple donation page. It utilizes callbacks to integrate Stripe with a Laravel project. Upon successful payment, Stripe sends data on to the Laravel project, which then inserts a new donation record into the database. A custom field, message, is implemented to allow users to add a message with their donation. This message is also sent to the Laravel project via Stripe's webhook.

Features
Simple donation page powered by Stripe Payment Link.

Follow these steps to run the project locally:

Clone the Project

git clone https://github.com/ajay26kumar/donationSystem
cd donationSystem

Configure Environment

Copy the .env.example file to .env:
cp .env.example .env
Update the .env file with your database credentials and Stripe API keys.
Run Migrations

php artisan migrate
Start the Development Server

php artisan serve

![donation](https://github.com/user-attachments/assets/2bf44aa1-a95a-4088-afb6-2a3f4a47f0c1)

