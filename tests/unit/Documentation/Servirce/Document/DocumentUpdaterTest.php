<?php

namespace App\Tests\Unit\Documentation\Service\Document;

use App\Documentation\Entity\Document;
use App\Documentation\Entity\Enum\DocumentStatus;
use App\Documentation\Service\Document\DocumentCreator;
use App\Documentation\Service\Document\DocumentUpdater;
use App\Documentation\Service\Document\Exception\DocumentException;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DocumentUpdaterTest extends Unit
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

    private function getMockedUpdater(): DocumentUpdater
    {
        $mock = \Mockery::mock(EntityManager::class)
            ->shouldReceive('flush')
            ->getMock();
        $updater = new DocumentUpdater($mock);

        return $updater;
    }

    // tests
    public function testAddPayload(): void
    {
        $document = new Document();

        $updater = $this->getMockedUpdater();
        $payload = [
            "actor" => "The fox",
            "meta" => [
                "type"=> "quick",
                "color"=> "brown"
            ],
            "actions"=> [
                [
                    "action"=> "jump over",
                    "actor"=> "lazy dog"
                ]
            ]
        ];
        $document = $updater->updatePayload($payload, $document);

        $this->tester->assertEquals($payload, $document->getPayload());
    }

    public function testAddFieldToPayload(): void
    {
        $document = new Document([
            "actor" => "The fox",
        ]);

        $updater = $this->getMockedUpdater();
        $payload = [
            "meta" => [
                "type"=> "quick",
                "color"=> "brown"
            ],
            "actions"=> [
                [
                    "action"=> "jump over",
                    "actor"=> "lazy dog"
                ]
            ]
        ];
        $document = $updater->updatePayload($payload, $document);

        $result = [
            "actor" => "The fox",
            "meta" => [
                "type"=> "quick",
                "color"=> "brown"
            ],
            "actions"=> [
                [
                    "action"=> "jump over",
                    "actor"=> "lazy dog"
                ]
            ]
        ];

        $this->tester->assertEquals($result, $document->getPayload());
    }

    public function testRemoveFieldFromPayload(): void
    {
        $document = new Document([
            "actor" => "The fox",
            "meta" => [
                "type"=> "quick",
                "color"=> "brown"
            ],
            "actions"=> [
                [
                    "action"=> "jump over",
                    "actor"=> "lazy dog"
                ]
            ]
        ]);

        $updater = $this->getMockedUpdater();
        $payload = [
            "meta" => [
                "type"=> "quick",
                "color"=> null
            ],
        ];
        $document = $updater->updatePayload($payload, $document);

        $result = [
            "actor" => "The fox",
            "meta" => [
                "type"=> "quick",
            ],
            "actions"=> [
                [
                    "action"=> "jump over",
                    "actor"=> "lazy dog"
                ]
            ]
        ];

        $this->tester->assertEquals($result, $document->getPayload());
        $this->tester->assertFalse(array_key_exists('color', $document->getPayload()['meta']));
    }

    public function testChangePayloadForPublishedDocument(): void
    {
        $document = new Document([
            "actor" => "The fox",
        ]);
        $document->publish();

        $updater = $this->getMockedUpdater();
        $payload = [
            "meta" => [
                "type"=> "quick"
            ],
        ];
        $this->tester->expectThrowable(DocumentException::class, function () use ($updater, $payload, $document) {
            $updater->updatePayload($payload, $document);
        });
    }

}
