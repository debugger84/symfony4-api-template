<?php

namespace App\Tests\Functional\Documentation;


use App\Documentation\Controller\V1\DocumentController;
use App\Documentation\Entity\Document;
use App\Documentation\Entity\Enum\DocumentStatus;
use App\Tests\FunctionalTester;
use Ramsey\Uuid\Uuid;

class DocumentCest
{
    /**
     * @param FunctionalTester $I
     * @see DocumentController::createDocument()
     */
    public function createDocument(FunctionalTester $I)
    {
        $I->wantToTest('Create a document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendPOST('/api/v1/document', [
            'payload' => [
                'a' => 'b'
            ],
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'document' => [
                'status' => DocumentStatus::draft()->getValue(),
                'payload' => [
                    'a' => 'b'
                ]
            ],
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.document.createAt');
        $I->seeResponseJsonMatchesJsonPath('$.document.modifyAt');
        $I->seeResponseJsonMatchesJsonPath('$.document.id');
        $I->seeInRepository(Document::class, [
            'id' => $resp['document']['id']
        ]);
    }


    /**
     * @param FunctionalTester $I
     * @see DocumentController::updateDocument()
     */
    public function updateDocument(FunctionalTester $I)
    {
        $I->wantToTest('Create a document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendPATCH('/api/v1/document/00000000-0000-0000-0000-000000000001', [
            'document' => [
                'payload' => [
                    'a' => 'b'
                ],
            ]
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'document' => [
                'payload' => [
                    'a' => 'b'
                ]
            ],
        ]);

        /** @var Document $doc */
        $doc = $I->grabEntityFromRepository(Document::class, ['id' => '00000000-0000-0000-0000-000000000001']);

        $I->assertEquals([
            'a' => 'b'
        ], $doc->getPayload());
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::updateDocument()
     */
    public function updateUnavailableDocument(FunctionalTester $I)
    {
        $I->wantToTest('Update unavailable document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendPATCH('/api/v1/document/10000000-0000-0000-0000-000000000001', [
            'document' => [
                'payload' => [
                    'a' => 'b'
                ],
            ]
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => false,
            'error' => 'The document was not found',
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::updateDocument()
     */
    public function updatePublishedDocument(FunctionalTester $I)
    {
        $I->wantToTest('Update published document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->updateEntity(Document::class, [
            'status' => DocumentStatus::published()->getValue()
        ], '00000000-0000-0000-0000-000000000001');

        $I->sendPATCH('/api/v1/document/00000000-0000-0000-0000-000000000001', [
            'document' => [
                'payload' => [
                    'a' => 'b'
                ],
            ]
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => false,
            'error' => 'You can change payload for the draft only',
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::publishDocument()
     */
    public function publishDocument(FunctionalTester $I)
    {
        $I->wantToTest('Publish a document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendPOST('/api/v1/document/00000000-0000-0000-0000-000000000001/publish', []);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'document' => [
                'id' => '00000000-0000-0000-0000-000000000001',
                'status' => DocumentStatus::published()->getValue()
            ],
        ]);

        $I->seeInRepository(Document::class, [
            'id' => '00000000-0000-0000-0000-000000000001',
            'status' => DocumentStatus::published()->getValue()
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::publishDocument()
     */
    public function publishUnavailableDocument(FunctionalTester $I)
    {
        $I->wantToTest('Publish unavailable document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        $I->sendPOST('/api/v1/document/10000000-0000-0000-0000-000000000001/publish', []);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => false,
            'error' => 'The document was not found',
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::getListOfDocuments()
     */
    public function getListOfDocuments(FunctionalTester $I)
    {
        $I->wantToTest('Get list of documents');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        /** @var Uuid $id */
        $id = $I->haveInRepository(Document::class, [
            'status' => DocumentStatus::draft()->getValue(),
            'payload' => [],
        ]);

        $I->sendGET('/api/v1/document', []);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'document' => [
                0 => [
                    'id' => $id->toString(),
                ],
                1 => [
                    'id' => '00000000-0000-0000-0000-000000000001',
                ]
            ],
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::getListOfDocuments()
     */
    public function getListFromTheSecondPage(FunctionalTester $I)
    {
        $I->wantToTest('Get list of documents from the second page');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        /** @var Uuid $id */
        $id = $I->haveInRepository(Document::class, [
            'status' => DocumentStatus::draft()->getValue(),
            'payload' => [],
        ]);

        $I->sendGET('/api/v1/document', [
            'page' => 2,
            'perPage' => 1,
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'document' => [
                0 => [
                    'id' => '00000000-0000-0000-0000-000000000001',
                ]
            ],
            "pagination"  => [
                "page" => 2,
                "perPage" => 1,
                "total" => 2
            ]
        ]);
    }

    /**
     * @param FunctionalTester $I
     * @see DocumentController::getListOfDocuments()
     */
    public function getOneDocument(FunctionalTester $I)
    {
        $I->wantToTest('Get document');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amAuthenticatedAsUser1();

        /** @var Uuid $id */
        $id = $I->haveInRepository(Document::class, [
            'status' => DocumentStatus::draft()->getValue(),
            'payload' => [],
        ]);

        $I->sendGET('/api/v1/document/00000000-0000-0000-0000-000000000001', [
            'page' => 2,
            'perPage' => 1,
        ]);

        $resp = \GuzzleHttp\json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'document' => [
                'id' => '00000000-0000-0000-0000-000000000001',
                'payload' => [],
                'status' => DocumentStatus::draft()->getValue()
            ],
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.document.createAt');
        $I->seeResponseJsonMatchesJsonPath('$.document.modifyAt');
    }
}
