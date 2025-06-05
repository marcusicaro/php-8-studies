<?php

return PhpCsFixer\Config::create()
    ->setRules([
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,
        'control_structure_spacing' => true, // garante espaço após if, else, etc.
        'single_space_after_construct' => true, // força espaço após if, for, etc.
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );