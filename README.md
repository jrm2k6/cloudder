## Cloudinary API Wrapper

Cloudinary streamlines your entire image management pipeline - upload, storage, administration, manipulation and delivery.

### Installation

- [Theme on Packagist](https://packagist.org/packages/teepluss/cloudinary)
- [Theme on GitHub](https://github.com/teepluss/laravel4-cloudinary)

To get the lastest version of Theme simply require it in your `composer.json` file.

~~~
"teepluss/cloudinary": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Theme is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~
'providers' => array(

    'Teepluss\Cloudinary\CloudinaryServiceProvider'

)
~~~

Theme also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~
'aliases' => array(

    'Cloudy' => 'Teepluss\Cloudinary\Facades\Cloudy'

)
~~~

Publish config using artisan CLI.

~~~
php artisan config:publish teepluss/cloudinary
~~~

## Usage

After published cloudinary config you need to set up api detail, such as key, secrey, url, etc.

This wrapper api provide simple methods to upload, rename, delete, tag manage and full features from original cloudinary class methods.

~~~php
$tags = array(
    'tag_a',
    'tag_b',
    'tag_c'
);

Cloudy::upload($_FILES['tmp_name'], 'custom_public_name', $tags);

//Cloudy::upload('/path/to/local/image', 'custom_public_name', $tags);

//Cloudy::upload('http://domain.com/remote.jpg', 'custom_public_name', $tags);
~~~

Display an image.
~~~php
Cloudy::show('custom_public_name', array('width' => 150, 'height' => 150, 'crop' => 'fit', 'radius' => 20));
~~~
> More document from [cloudinary.com](http://cloudinary.com/documentation/image_transformations)

Rename file, Delete file.

~~~php
Cloudy::rename('from_public_id', 'to_public_id');

Cloudy::destroy('public_id');
~~~

Manage with tag.

~~~php
Cloudy::addTag('tag_d', array('public_id_1', 'public_id_2'));

Cloudy::removeTag('tag_d', array('public_id_1', 'public_id_2'));

Cloudy::replaceTag('tag_e', array('public_id_1', 'public_id_2'));
~~~

You can use original library from cloudinary also.

~~~php
// Get cloudinary.
$cloudinary = Cloudy::getCloudinary();

// Get cloudinary uploader
$uploader = Cloudy::getUploader();
~~~
> To see more detail visit @ https://github.com/cloudinary/cloudinary_php

## Support or Contact

If you have some problem, Contact teepluss@gmail.com


[![Support via PayPal](https://rawgithub.com/chris---/Donation-Badges/master/paypal.jpeg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9GEC8J7FAG6JA)