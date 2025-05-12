This project demonstrates a basic use case for Stripe Payment Link to create a simple donation page. It utilizes callbacks to integrate Stripe with a Laravel project. Upon successful payment, Stripe sends data on to the Laravel project, which then inserts a new donation record into the database. A custom field, message, is implemented to allow users to add a message with their donation. This message is also sent to the Laravel project via Stripe's webhook.

Features
Simple donation page powered by Stripe Payment Link.
