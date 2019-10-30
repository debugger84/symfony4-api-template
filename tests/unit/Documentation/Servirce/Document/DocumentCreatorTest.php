<?php

namespace App\Tests\Unit\Documentation\Service\Document;

use App\Documentation\Entity\Document;
use App\Documentation\Entity\Enum\DocumentStatus;
use App\Documentation\Service\Document\DocumentCreator;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Ramsey\Uuid\UuidInterface;

class DocumentCreatorTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateWithPayload()
    {
        /** @var DocumentCreator $creator */
        $creator = $this->tester->grabService(DocumentCreator::class);
        $payload = ['a' => ['a' => 'b']];
        $document = $creator->createDocument($payload);

        $this->tester->assertEquals($payload, $document->getPayload());
        $this->tester->assertInstanceOf(UuidInterface::class, $document->getId());
        $this->tester->assertInstanceOf(\DateTime::class, $document->getCreateAt());
        $this->tester->assertInstanceOf(\DateTime::class, $document->getModifyAt());
        $this->tester->assertEquals(DocumentStatus::draft()->getValue(), $document->getStatus()->getValue());
        $this->tester->seeInRepository(Document::class, [
            'id' => $document->getId()->toString()
        ]);
    }

    public function testCreateWithoutPayload()
    {
        /** @var DocumentCreator $creator */
        $creator = $this->tester->grabService(DocumentCreator::class);
        $payload = [];
        $document = $creator->createDocument($payload);

        $this->tester->assertEquals($payload, $document->getPayload());
        $this->tester->assertInstanceOf(UuidInterface::class, $document->getId());
        $this->tester->assertInstanceOf(\DateTime::class, $document->getCreateAt());
        $this->tester->assertInstanceOf(\DateTime::class, $document->getModifyAt());
        $this->tester->assertEquals(DocumentStatus::draft()->getValue(), $document->getStatus()->getValue());
        $this->tester->seeInRepository(Document::class, [
            'id' => $document->getId()->toString()
        ]);
    }
}
