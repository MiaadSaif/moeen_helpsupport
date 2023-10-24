
# Help Support System



The Help Support System allows clients to contact Moeen support. This package can be installed to set up the full client support system with the following steps.



## Installation



1. First, add the package via Composer:


` composer require moeen1/helpsupport`


 In config/app.php, add the following to the providers section:
  `'providers' => [Moeen\Helpsupport\HelpsupportServiceProvider::class,],`


Run the following commands in your terminal:

` php artisan optimize`

` php artisan vendor:publish --tag=public --force
`

` composer dump-autoload`

` composer update `

In your main project, open the configuration file for Help Support at:
                 vendor\moeen1\helpsupport\src\config\helpsupport.php

```

change the :

base_url = IP address of computer that connect with moeen support system ,

client_id and project_id = check the moeen system , what id you give for your project and you as client .

```
` return ["base_url" => "http://192.168.100.192:1234","client_id" => 1,
"project_id"=>1] `


By this link you will open the system for you

` http://your-website-link/support `

      Example:

     http://127.0.0.1:9999/support
