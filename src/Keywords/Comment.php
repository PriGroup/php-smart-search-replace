<?php

namespace Imanghafoori\SearchReplace\Keywords;

use Imanghafoori\SearchReplace\TokenCompare;

class Comment
{
    public static function is($pToken)
    {
        return TokenCompare::is($pToken, '<comment>');
    }

    public static function mustStart($tokens, $i)
    {
        return $tokens[$i][0] === T_COMMENT;
    }
}
