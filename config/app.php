<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Application Name
  |--------------------------------------------------------------------------
  |
  | This value is the name of your application, which will be used when the
  | framework needs to place the application's name in a notification or
  | other UI elements where an application name needs to be displayed.
  |
  */
  'name' => getenv('APP_NAME') ?: 'My Application',

  /*
  |--------------------------------------------------------------------------
  | Application Environment
  |--------------------------------------------------------------------------
  |
  | This value determines the "environment" your application is currently
  | running in. This may determine how you prefer to configure various
  | services the application utilizes. Set this in your ".env" file.
  |
  */
  'env' => getenv('APP_ENV') ?: 'production',

  /*
  |--------------------------------------------------------------------------
  | Application Base Path
  |--------------------------------------------------------------------------
  |
  | The base path of your application. This is used to resolve paths
  | throughout the framework.
  |
  */
  'base_path' => dirname(__DIR__),

  /*
  |--------------------------------------------------------------------------
  | Application URL
  |--------------------------------------------------------------------------
  |
  | The base URL of your application. Update this value to match the
  | root URL where your application will be accessed in a browser.
  |
  */
  'url' => getenv('APP_URL') ?: 'http://localhost:8000',

  /*
  |--------------------------------------------------------------------------
  | Vite Development Server URL
  |--------------------------------------------------------------------------
  |
  | This URL is used to load assets from the Vite development server
  | during local development.
  |
  */
  'vite_url' => getenv('VITE_DEV_SERVER_URL') ?: 'http://[::0]:5173',
];
