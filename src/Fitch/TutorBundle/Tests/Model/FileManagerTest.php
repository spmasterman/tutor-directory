<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\CommonBundle\Model\FixturesWebTestCase;
use Fitch\TutorBundle\Model\FileManagerInterface;
use Fitch\TutorBundle\Model\TutorManagerInterface;
use Fitch\UserBundle\Model\UserManagerInterface;
use Oneup\UploaderBundle\Uploader\File\GaufretteFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FileManagerTest.
 */
class FileManagerTest extends FixturesWebTestCase
{
    /** @var  FileManagerInterface */
    protected $modelManager;

    /** @var  TutorManagerInterface */
    protected $tutorManager;

    /** @var  UserManagerInterface */
    protected $userManager;

    /**
     * Runs for every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->modelManager = $this->container->get('fitch.manager.file');
        $this->tutorManager = $this->container->get('fitch.manager.tutor');
        $this->userManager = $this->container->get('fitch.manager.user');
    }

    public function testSetMetaInfo()
    {
        // upload a file... (Note that we cant mock the uploader, because doctrine will actually try and
        // persist the Mock
        $file = $this->modelManager->setMetaInfo(
            $this->getRequestMock(),
            $this->getGaufretteFileMock(),
            [
               'mimeType'=> 'MIMETYPE',
               'uploader' => $this->userManager->findById(1),
               'textContent' => 'TEXTCONTENT'
            ]
        );

        // now check that the meta info is attached
        $tutor = $this->tutorManager->findById(1);
        $files = $tutor->getFiles();

        $this->assertCount(1, $files);

        $this->assertEquals('FILENAME', $file->getName());
        $this->assertEquals('KEY', $file->getFileSystemKey());
        $this->assertEquals('TEXTCONTENT', $files[0]->getTextContent());
        $this->assertEquals('MIMETYPE', $files[0]->getMimeType());
        $this->assertEquals('Plain User', $files[0]->getUploader()->getFullName());
    }

    /**
     * @return Request
     */
    private function getRequestMock()
    {
        $file = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();
        $file->expects($this->once())->method('getClientOriginalName')->willReturn('FILENAME');

        // Create response payloads
        $requestBag = new ParameterBag([
            'tutorPk' => 1,
        ]);

        // Create a response payloads
        $fileBag = new FileBag([
            'file' => $file,
        ]);

        // Set that up in a Stub/mock Request
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->request = $requestBag;
        $request->files = $fileBag;
        return $request;
    }

    /**
     * @return GaufretteFile
     */
    private function getGaufretteFileMock()
    {
        $file = $this
            ->getMockBuilder('Oneup\UploaderBundle\Uploader\File\GaufretteFile')
            ->disableOriginalConstructor()
            ->getMock();
        $file->expects($this->once())->method('getKey')->willReturn('KEY');
        return $file;
    }
}
