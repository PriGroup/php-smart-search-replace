<?php

namespace Imanghafoori\SearchReplace\Keywords;

use Imanghafoori\SearchReplace\PatternParser;
use Imanghafoori\SearchReplace\Finder;

class FullClassRef
{
    public static function is($pToken)
    {
        return Finder::is($pToken, '<full_class_ref>');
    }

    public static function getValue($tokens, &$startFrom, &$placeholderValues)
    {
        $tToken = $tokens[$startFrom] ?? '_';

        if ($tToken[0] !== T_NS_SEPARATOR) {
            return false;
        }

        $absClassRef = ['classRef' => '\\"<name>"'];
        $repeatingClassRef = PatternParser::tokenize('"<repeating:classRef>"');

        $isMatch = Finder::compareTokens($repeatingClassRef, $tokens, $startFrom, $absClassRef);

        if (! $isMatch) {
            return false;
        }

        $placeholderValues[] = Finder::extractValue($isMatch[2][0]);
        $startFrom = $isMatch[0];
    }
}
