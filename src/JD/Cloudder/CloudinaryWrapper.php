<?php namespace JD\Cloudder;

use Cloudinary;
use Illuminate\Config\Repository;

class CloudinaryWrapper {

    /**
     * Cloudinary lib.
     *
     * @var \Cloudinary
     */
    protected $cloudinary;

    /**
     * Cloudinary uploader.
     *
     * @var \Cloudinary\Uploader
     */
    protected $uploader;

    /**
     * Repository config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Uploaded result.
     *
     * @var array
     */
    protected $uploadedResult;

    /**
     * Create a new cloudinary instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @return void
     */
    public function __construct(Repository $config, Cloudinary $cloudinary, Cloudinary\Uploader $uploader,
                                Cloudinary\Api $api)
    {
        $this->cloudinary = $cloudinary;

        $this->uploader = $uploader;

        $this->api = $api;

        $this->config = $config;

        $this->cloudinary->config(array(
            'cloud_name' => $this->config->get('cloudder.cloudName'),
            'api_key'    => $this->config->get('cloudder.apiKey'),
            'api_secret' => $this->config->get('cloudder.apiSecret')
        ));
    }

    /**
     * Get cloudinary class.
     *
     * @return \Cloudinary
     */
    public function getCloudinary()
    {
        return $this->cloudinary;
    }

    /**
     * Get cloudinary uploader.
     *
     * @return \Cloudinary\Uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * Get cloudinary api
     *
     * @return \Cloudinary\Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Upload image to cloud.
     *
     * @param  mixed $source
     * @param  string $publicId
     * @param  array $uploadOptions
     * @param  array $tags
     * @return CloudinaryWrapper
     */
    public function upload($source, $publicId = null, $uploadOptions = array(), $tags = array())
    {
        $defaults = array(
            'public_id' => null,
            'tags'      => array()
        );

        $options = array_merge($defaults, array(
            'public_id' => $publicId,
            'tags'      => $tags
        ));

        $options = array_merge($options, $uploadOptions);

        $this->uploadedResult = $this->getUploader()->upload($source, $options);

        return $this;
    }

    /**
     * Upload video to cloud.
     *
     * @param  mixed $source
     * @param  string $publicId
     * @param  array $uploadOptions
     * @param  array $tags
     * @return CloudinaryWrapper
    */
    public function uploadVideo($source, $publicId = null, $uploadOptions = array(), $tags = array())
    {
        $options = array_merge($uploadOptions, ['resource_type' => 'video']);
        return $this->upload($source, $publicId,  $options, $tags);
    }

    /**
     * Uploaded result.
     *
     * @return array
     */
    public function getResult()
    {
        return $this->uploadedResult;
    }

    /**
     * Uploaded public ID.
     *
     * @return string
     */
    public function getPublicId()
    {
        return $this->uploadedResult['public_id'];
    }

    /**
     * Display resource through https.
     *
     * @param  string $publicId
     * @param  array  $options
     * @return string
     */
    public function show($publicId, $options = array())
    {
        $defaults = $this->config->get('cloudder.scaling');

        $options = array_merge($defaults, $options);

        return $this->getCloudinary()->cloudinary_url($publicId, $options);
    }

    /**
     * Display resource through https.
     *
     * @param  string $publicId
     * @param  array  $options
     * @return string
     */
    public function secureShow($publicId, $options = array())
    {
        $showOptions = array_merge(['secure' => true], $options);
        return $this->getCloudinary()->cloudinary_url($publicId, $showOptions);
    }

    /**
     * Display private image
     *
     * @param string $publicId
     * @param string $format
     * @param array $options
     * @return string
     */

    public function showPrivateUrl($publicId, $format, $options = array())
    {
        return $this->getCloudinary()->private_download_url($publicId, $format, $options);
    }

    /**
     * Rename public ID.
     *
     * @param  string $publicId
     * @param  string $toPublicId
     * @param  array  $options
     * @return array
     */
    public function rename($publicId, $toPublicId, $options = array())
    {
        try
        {
            return $this->getUploader()->rename($publicId, $toPublicId, $options);
        }
        catch (\Exception $e) { }

        return false;
    }

    /**
     * Destroy image from Cloudinary
     *
     * @param  string $publicId
     * @param  array  $options
     * @return array
     */
    public function destroyImage($publicId, $options = array())
    {
        return $this->getUploader()->destroy($publicId, $options);
    }

    /**
     * Destroy images from Cloudinary
     * @param  array $publicIds
     * @param  array  $options
     * @return null
     */
    public function destroyImages($publicIds, $options = array())
    {
        return $this->getApi()->delete_resources($publicIds, $options);
    }

    /**
     * Alias of destroyImage.
     *
     * @return array
     */
    public function delete($publicId, $options = array())
    {
        $response = $this->destroyImage($publicId, $options);

        return (boolean) ($response['result'] == 'ok');
    }

    /**
     * Add tag to images.
     *
     * @param string $tag
     * @param array  $publicIds
     * @param array  $options
     */
    public function addTag($tag, $publicIds = array(), $options = array())
    {
        return $this->getUploader()->add_tag($tag, $publicIds, $options);
    }

    /**
     * Remove tag from images.
     *
     * @param string $tag
     * @param array  $publicIds
     * @param array  $options
     */
    public function removeTag($tag, $publicIds = array(), $options = array())
    {
        return $this->getUploader()->remove_tag($tag, $publicIds, $options);
    }

    /**
     * Replace image's tag.
     *
     * @param string $tag
     * @param array  $publicIds
     * @param array  $options
     */
    public function replaceTag($tag, $publicIds = array(), $options = array())
    {
        return $this->getUploader()->replace_tag($tag, $publicIds, $options);
    }

    /**
     * Create a zip file containing images matching options.
     *
     * @param array  $options
     * @param string $archiveName
     * @param string $mode
     */
    public function createArchive($options = array(), $nameArchive = null, $mode = 'create')
    {
        $options = array_merge($options, ['target_public_id' => $nameArchive, 'mode' => $mode]);
        return $this->getUploader()->create_archive($options);
    }

    /**
     * Download a zip file containing images matching options.
     *
     * @param array  $options
     * @param string $archiveName
     * @param string $mode
     */
    public function downloadArchiveUrl($options = array(), $nameArchive = null)
    {
        $options = array_merge($options, ['target_public_id' => $nameArchive]);
        return $this->getCloudinary()->download_archive_url($options);
    }
}
