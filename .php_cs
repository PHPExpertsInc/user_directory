<?php

$header = <<<HEADER
User Directory
  Copyright (c) 2008, 2011, 2019 Theodore R. Smith <theodore@phpexperts.pro>
  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690

  https://www.phpexperts.pro/
  https://gitlab.com/phpexperts/user_directory

The following code is licensed under a modified BSD License.
All of the terms and conditions of the BSD License apply with one
exception:

1. Every one who has not been a registered student of the "PHPExperts
   From Beginner To Pro" course (http://www.phpexperts.pro/) is forbidden
   from modifing this code or using in an another project, either as a
   deritvative work or stand-alone.

BSD License: http://www.opensource.org/licenses/bsd-license.php
HEADER;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'       => true,
        'elseif'         => false,
        'yoda_style'     => false,
        'list_syntax'    => ['syntax'  => 'short'],
        'concat_space'   => ['spacing' => 'one'],
        'binary_operator_spaces' => array(
            'align_equals'       => true,
            'align_double_arrow' => true,
        ),
        'no_superfluous_elseif'        => true,
        'blank_line_after_opening_tag' => false,
        'header_comment' => [
            'header'       => $header,
            'location'     => 'after_declare_strict',
            'comment_type' => 'PHPDoc',
        ]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('packages')
            ->in(__DIR__)
    );
