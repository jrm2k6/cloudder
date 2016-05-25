<?php namespace JD\Cloudder\Test;

use JD\Cloudder\CloudinaryWrapper;
use Mockery as m;

class CloudinaryWrapperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->cloudinary = m::mock('Cloudinary');
        $this->uploader = m::mock('Cloudinary\Uploader');
        $this->api = m::mock('Cloudinary\Api');

        $this->config->shouldReceive('get')->once()->with('cloudder.cloudName')->andReturn('cloudName');
        $this->config->shouldReceive('get')->once()->with('cloudder.apiKey')->andReturn('apiKey');
        $this->config->shouldReceive('get')->once()->with('cloudder.apiSecret')->andReturn('apiSecret');

        $this->cloudinary->shouldReceive('config')->once();

        $this->cloudinary_wrapper = new CloudinaryWrapper($this->config, $this->cloudinary, $this->uploader, $this->api);
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_should_set_uploaded_result_when_uploading_picture()
    {
        // given
        $filename = 'filename';
        $defaults_options = [
            'public_id' => null,
            'tags'      => array()
        ];

        $expected_result = ['public_id' => '123456789'];

        $this->uploader->shouldReceive('upload')->once()->with($filename, $defaults_options)->andReturn($expected_result);

        // when
        $this->cloudinary_wrapper->upload($filename);

        // then
        $result = $this->cloudinary_wrapper->getResult();
        $this->assertEquals($expected_result, $result);
    }

    /** @test */
    public function it_should_set_uploaded_result_when_uploading_private_picture()
    {
        // given
        $filename = 'filename';
        $defaults_options = [
            'public_id' => null,
            'tags'      => array(),
            'type'      => 'private'
        ];

        $expected_result = ['public_id' => '123456789'];

        $this->uploader->shouldReceive('upload')->once()->with($filename, $defaults_options)->andReturn($expected_result);

        // when
        $this->cloudinary_wrapper->upload($filename, null, ['type' => 'private']);

        // then
        $result = $this->cloudinary_wrapper->getResult();
        $this->assertEquals($expected_result, $result);
    }

    /** @test */
    public function it_should_returns_image_url_when_calling_show()
    {
        // given
        $filename = 'filename';
        $this->config->shouldReceive('get')->with('cloudder.scaling')->once()->andReturn(array());
        $this->cloudinary->shouldReceive('cloudinary_url')->once()->with($filename, array());

        // when
        $this->cloudinary_wrapper->show($filename);
    }

    /** @test */
    public function it_should_returns_https_image_url_when_calling_secure_show()
    {
        // given
        $filename = 'filename';
        $this->cloudinary->shouldReceive('cloudinary_url')->once()->with($filename, ['secure' => true]);

        // when
        $this->cloudinary_wrapper->secureShow($filename);
    }

    /** @test */
    public function it_should_returns_image_url_when_calling_show_private_url()
    {
        // given
        $filename = 'filename';
        $this->cloudinary->shouldReceive('private_download_url')->once()->with($filename, 'png', array());

        // when
        $this->cloudinary_wrapper->showPrivateUrl($filename, 'png');
    }

    /** @test */
    public function it_should_call_api_rename_when_calling_rename()
    {
        // given
        $from = 'from';
        $to = 'to';

        $this->uploader->shouldReceive('rename')->with($from, $to, array())->once();

        // when
        $this->cloudinary_wrapper->rename($from, $to);
    }

    /** @test */
    public function it_should_call_api_destroy_when_calling_destroy_image()
    {
        // given
        $pid = 'pid';
        $this->uploader->shouldReceive('destroy')->with($pid, array())->once();

        // when
        $this->cloudinary_wrapper->destroyImage($pid);
    }

    /** @test */
    public function verify_delete_alias_returns_boolean()
    {
        // given
        $pid = 'pid';
        $this->uploader->shouldReceive('destroy')->with($pid, array())->once()->andReturn(['result' => 'ok']);

        // when
        $deleted = $this->cloudinary_wrapper->delete($pid);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_should_call_api_add_tag_when_calling_add_tag()
    {
        $pids = ['pid1', 'pid2'];
        $tag = 'tag';

        $this->uploader->shouldReceive('add_tag')->once()->with($tag, $pids, array());

        $this->cloudinary_wrapper->addTag($tag, $pids);
    }

    /** @test */
    public function it_should_call_api_remove_tag_when_calling_add_tag()
    {
        $pids = ['pid1', 'pid2'];
        $tag = 'tag';

        $this->uploader->shouldReceive('remove_tag')->once()->with($tag, $pids, array());

        $this->cloudinary_wrapper->removeTag($tag, $pids);
    }

    /** @test */
    public function it_should_call_api_rename_tag_when_calling_add_tag()
    {
        $pids = ['pid1', 'pid2'];
        $tag = 'tag';

        $this->uploader->shouldReceive('replace_tag')->once()->with($tag, $pids, array());

        $this->cloudinary_wrapper->replaceTag($tag, $pids);
    }

    /** @test */
    public function it_should_call_api_delete_resources_when_calling_destroy_images()
    {
        $pids = ['pid1', 'pid2'];
        $this->api->shouldReceive('delete_resources')->once()->with($pids, array());

        $this->cloudinary_wrapper->destroyImages($pids);
    }

    /** @test */
    public function it_should_set_uploaded_result_when_uploading_video()
    {
        // given
        $filename = 'filename';
        $defaults_options = [
            'public_id' => null,
            'tags'      => array(),
            'resource_type' => 'video'
        ];

        $expected_result = ['public_id' => '123456789'];

        $this->uploader->shouldReceive('upload')->once()->with($filename, $defaults_options)->andReturn($expected_result);

        // when
        $this->cloudinary_wrapper->uploadVideo($filename);

        // then
        $result = $this->cloudinary_wrapper->getResult();
        $this->assertEquals($expected_result, $result);
    }

    /** @test */
    public function it_should_call_api_create_archive_when_generating_archive()
    {
        // given
        $this->uploader->shouldReceive('create_archive')->once()->with(
          ['tag' => 'kitten', 'mode' => 'create', 'target_public_id' => null]
        );

        // when
        $this->cloudinary_wrapper->createArchive(['tag' => 'kitten']);
    }

    /** @test */
    public function it_should_call_api_create_archive_with_correct_archive_name()
    {
        // given
        $this->uploader->shouldReceive('create_archive')->once()->with(
          ['tag' => 'kitten', 'mode' => 'create', 'target_public_id' => 'kitten_archive']
        );

        // when
        $this->cloudinary_wrapper->createArchive(['tag' => 'kitten'], 'kitten_archive');
    }

    /** @test */
    public function it_should_call_api_download_archive_url_when_generating_archive()
    {
        // given
        $this->cloudinary->shouldReceive('download_archive_url')->once()->with(
          ['tag' => 'kitten', 'target_public_id' => null]
        );

        // when
        $this->cloudinary_wrapper->downloadArchiveUrl(['tag' => 'kitten']);
    }

    /** @test */
    public function it_should_call_api_download_archive_url_with_correct_archive_name()
    {
        // given
        $this->cloudinary->shouldReceive('download_archive_url')->once()->with(
          ['tag' => 'kitten', 'target_public_id' => 'kitten_archive']
        );

        // when
        $this->cloudinary_wrapper->downloadArchiveUrl(['tag' => 'kitten'], 'kitten_archive');
    }
}
