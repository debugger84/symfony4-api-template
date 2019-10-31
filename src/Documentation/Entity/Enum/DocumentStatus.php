<?php

namespace App\Documentation\Entity\Enum;

use MyCLabs\Enum\Enum;

/**
 * The type of event
 * @method static DocumentStatus draft()
 * @method static DocumentStatus published()
 */
class DocumentStatus extends Enum
{
    private const draft = 'draft';
    private const published = 'published';
}
