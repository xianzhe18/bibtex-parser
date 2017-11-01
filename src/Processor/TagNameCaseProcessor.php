<?php declare(strict_types=1);

/*
 * This file is part of the BibTex Parser.
 *
 * (c) Renan de Lima Barbosa <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RenanBr\BibTexParser\Processor;

/**
 * Change the case of all tag names.
 */
class TagNameCaseProcessor
{
    /** @var int */
    private $case;

    public function __construct(int $case)
    {
        $this->case = $case;
    }

    public function __invoke(array $entry): array
    {
        return array_change_key_case($entry, $this->case);
    }
}