<?php
declare(strict_types=1);

namespace App\Annotation;

//регурярное выражение для id ,используеться в аннотации Route requirements={"id"=Guid::PATTERN}
//для избежание конфликтов с другими путями
class Guid
{
    public const PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
}