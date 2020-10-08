<?php

declare(strict_types=1);

namespace App\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Transactional
 * @package App\Annotations
 *
 * @Annotation
 * @Target("METHOD")
 */
class Transactional extends Annotation
{

}
