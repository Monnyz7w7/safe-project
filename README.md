# Safe Project

## Getting Started
How to run in your local development server.

1. Go to your project folder where your local development is and clone this repo using your command line
```
git clone git@github.com:carlopaa/safe-monnycraft.git
```

2. Go to your newly created project
```
cd safe-monnycraft
```

3. Install dependencies needed
```
npm install
composer install
```

4. Create a database `monnycraft` or whatever name you prefer.

5. Copy `.env.example` to `.env` and change `DB_DATABASE` matching the database name you created.

6. Run `php artisan key:generate` and `php artisan migrate` in your command line.

7. Run `npm run dev` in your command line every time you're working locally. However, if you're not going to work on front-end-related tasks, you can simply run `npm run build` to compile your assets.

## Discord authentication
This app allows user to login / register with their Discord account.

The fetch discord info is now built in this app, just add the `DISCORD_BOT_TOKEN` to make it work.

You have to setup a Discord developer account and get the `client id` and `client secret` and add it on your `.env`
```env
DISCORD_CLIENT_ID=YOUR_CLIENT_ID
DISCORD_CLIENT_SECRET=YOUR_CLIENT_SECRET_KEY
DISCORD_BOT_TOKEN=YOUR_DISCORD_BOT_TOKEN
```

## Contributing
**For team members who want to contribute to this repository**

Please create a new branch for your changes to keep the `main` branch clean and to avoid potential issues.
> Note that all commits pushed directly to the `main` branch will be automatically deployed. To prevent unintended deployments and maintain code stability, ensure that your changes are made in a separate branch and submitted via a pull request.
