# Cloudder - Cloudinary wrapper for Laravel 5 / Lumen

[![Build Status](http://img.shields.io/travis/jrm2k6/cloudder/master.svg?style=flat-square)](https://travis-ci.org/jrm2k6/cloudder)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)
[![Latest Version](http://img.shields.io/packagist/v/jrm2k6/cloudder.svg?style=flat-square)](https://packagist.org/packages/jrm2k6/cloudder)
[![Total Downloads](https://img.shields.io/packagist/dt/jrm2k6/cloudder.svg?style=flat-square)](https://packagist.org/packages/jrm2k6/cloudder)

> Initially forked from https://github.com/teepluss/laravel4-cloudinary.

> As it doesn't seem to be maintained anymore, and facing the lack of response from the original maitainer (issue opened + pull request opened, last commit on August last year), I decided to create a new fork that I plan on maintaining.

> **If there is any feature you would like feel free to open an issue or send me an email!**

## Installation

`composer require jrm2k6/cloudder`

For people still using Laravel 4.2: `composer require jrm2k6/cloudder:0.1.*` and check the branch l4 for the installation instructions.


## Configuration

Modify your `.env` file to add the following information from [Cloudinary](http://www.cloudinary.com)

### Required


```
CLOUDINARY_API_KEY=012345679890123
CLOUDINARY_API_SECRET=foobarfoobarfoob-arfoobarfo
CLOUDINARY_CLOUD_NAME=foobarcorp
```

### Optional

```
CLOUDINARY_BASE_URL
CLOUDINARY_SECURE_URL
CLOUDINARY_API_BASE_URL
```
Laravel 5.5+ uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.
If you don't use auto-discovery follow the next steps:

Add the following in config/app.php:

```php
'providers' => array(
  'JD\Cloudder\CloudderServiceProvider'
);

'aliases' => array(
  'Cloudder' => 'JD\Cloudder\Facades\Cloudder'
);
```

Run `php artisan vendor:publish --provider="JD\Cloudder\CloudderServiceProvider"`

## Usage

### upload()

```php
Cloudder::upload($filename, $publicId, array $options, array $tags);
```

with:

* `$filename`: path to the image you want to upload
* `$publicId`: the id you want your picture to have on Cloudinary, leave it null to have Cloudinary generate a random id.
* `$options`: options for your uploaded image, check the [Cloudinary documentation](http://cloudinary.com/documentation/php_image_upload#all_upload_options) to know more
* `$tags`: tags for your image

returns the `CloudinaryWrapper`.

### uploadVideo()

```php
Cloudder::uploadVideo($filename, $publicId, array $options, array $tags);
```

with:

* `$filename`: path to the video you want to upload
* `$publicId`: the id you want your video to have on Cloudinary, leave it null to have Cloudinary generate a random id.
* `$options`: options for your uploaded video, check the Cloudinary documentation to know more
* `$tags`: tags for your image

returns the `CloudinaryWrapper`.

### getPublicId()

```php
Cloudder::getPublicId()
```

returns the `public id` of the last uploaded resource.

### getResult()

```php
Cloudder::getResult()
```

returns the result of the last uploaded resource.

### show() + secureShow()

```php
Cloudder::show($publicId, array $options)
Cloudder::secureShow($publicId, array $options)
```

with:

* `$publicId`: public id of the resource to display
* `$options`: options for your uploaded resource, check the Cloudinary documentation to know more

returns the `url` of the picture on Cloudinary (https url if secureShow is used).

### showPrivateUrl()

```php
Cloudder::showPrivateUrl($publicId, $format, array $options)
```

with:

* `$publicId`: public id of the resource to display
* `$format`: format of the resource your want to display ('png', 'jpg'...)
* `$options`: options for your uploaded resource, check the Cloudinary documentation to know more

returns the `private url` of the picture on Cloudinary, expiring by default after an hour.

### rename()

```php
Cloudder::rename($publicId, $toPublicId, array $options)
```

with:

* `$publicId`: publicId of the resource to rename
* `$toPublicId`: new public id of the resource
* `$options`: options for your uploaded resource, check the cloudinary documentation to know more

renames the original picture with the `$toPublicId` id parameter.

### destroyImage() + delete()

```php
Cloudder::destroyImage($publicId, array $options)
Cloudder::delete($publicId, array $options)
```

with:

* `$publicId`: publicId of the resource to rename
* `$options`: options for your uploaded image, check the cloudinary documentation to know more

removes image from Cloudinary.

### destroyImages()

```php
Cloudder::destroyImages(array $publicIds, array $options)
```

with:

* `$publicIds`: array of ids, identifying the pictures to remove
* `$options`: options for the images to delete, check the cloudinary documentation to know more

removes images from Cloudinary.

### addTag()

```php
Cloudder::addTag($tag, $publicIds, array $options)
```

with:

* `$tag`: tag to apply
* `$publicIds`: images to apply tag to
* `$options`: options for your uploaded resource, check the cloudinary documentation to know more

### removeTag()

```php
Cloudder::removeTag($tag, $publicIds, array $options)
```

with:

* `$tag`: tag to remove
* `$publicIds`: images to remove tag from
* `$options`: options for your uploaded image, check the Cloudinary documentation to know more

### createArchive()

```php
Cloudder::createArchive(array $options, $archiveName, $mode)
```

with:

* `$options`: options for your archive, like name, tag/prefix/public ids to select images
* `$archiveName`: name you want to give to your archive
* `$mode`: 'create' or 'download' ('create' will create an archive and returns a JSON response with the properties of the archive, 'download' will return the zip file for download)

creates a zip file on Cloudinary.

### downloadArchiveUrl()

```php
Cloudder::downloadArchiveUrl(array $options, $archiveName)
```

with:

* `$options`: options for your archive, like name, tag/prefix/public ids to select images
* `$archiveName`: name you want to give to your archive

returns a `download url` for the newly created archive on Cloudinary.

## Running tests

`phpunit`

## Example

You can find a working example in the repo [cloudder-l5-example](https://github.com/jrm2k6/cloudder-l5-sample-project)
