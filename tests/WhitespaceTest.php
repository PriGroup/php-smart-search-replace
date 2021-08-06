<?php

namespace Imanghafoori\SearchReplace\Tests;

use Imanghafoori\SearchReplace\PatternParser;
use Imanghafoori\LaravelMicroscope\Tests\BaseTestClass;

class WhitespaceTest extends BaseTestClass
{
    /** @test */
    public function match_white_space()
    {
        $patterns = [
            '["<white_space>"]' => [
                'replace' => '[]',
            ]
        ];

        $startFile = '<?php [/**/];[/**/ ];[1,];["s" ];[ ];';
        $resultFile = '<?php [/**/];[];[1,];["s" ];[];';
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));
        $this->assertEquals($resultFile, $newVersion);
    }

    /** @test */
    public function white_space()
    {
        $patterns = [
            "use App\Club;'<white_space>'use App\Events\MemberCommentedClubPost;" =>
                [
                    'replace' => "use App\Club; use App\Events\MemberCommentedClubPost;",
                ],

            "use Illuminate\Http\Request;'<white_space>'" =>
                [
                    'replace' => ''
                ],
        ];
        $startFile = file_get_contents(__DIR__.'/stubs/SimplePostController.stub');

        $resultFile = file_get_contents(__DIR__.'/stubs/EolSimplePostControllerResult.stub');
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([5, 8], $replacedAt);
    }

    /** @test */
    public function white_space_placeholder()
    {
        $patterns = [
            ")'<white_space>'{" => ['replace' => '){'],
        ];
        $startFile = file_get_contents(__DIR__.'/stubs/SimplePostController.stub');
        $resultFile = file_get_contents(__DIR__.'/stubs/NoWhiteSpaceSimplePostController.stub');
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([13, 15, 20], $replacedAt);
    }

    /** @test */
    public function optional_white_space_placeholder()
    {
        $patterns = [
            "response('<white_space>?')'<white_space>?'->json" => ['replace' => 'response()"<2>"->mson'],
        ];
        $startFile = file_get_contents(__DIR__.'/stubs/SimplePostController.stub');

        $resultFile = file_get_contents(__DIR__.'/stubs/OptionalWhiteSpaceSimplePostController.stub');
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([16, 23], $replacedAt);
    }

    /** @test */
    public function optional_comment_placeholder()
    {
        $patterns = [
            ";'<white_space>?''<comment>';" => ['replace' => ';"<1>""<2>"'],
        ];
        $startFile = '<?php ; /*H*/ ;';

        $resultFile = '<?php ; /*H*/';
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([1], $replacedAt);
    }

    /** @test */
    public function optional_comment_placeholder_2()
    {
        $patterns = [
            ";'<white_space>?''<comment>?';" => ['replace' => ';"<1>""<2>""<1>";'],
        ];

        $startFile = '<?php ; ;';
        $resultFile = '<?php ;  ;';
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([1], $replacedAt);
    }

    /** @test */
    public function optional_comment_placeholder_3()
    {
        $patterns = [
            ";'<white_space>?''<comment>?';" => ['replace' => ';"<1>""<2>""<1>";'],
        ];

        $startFile = '<?php ; /**/;';
        $resultFile = '<?php ; /**/ ;';
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([1], $replacedAt);
    }

    /** @test */
    public function optional_comment_placeholder_32()
    {
        $patterns = [
            "'<white_space>?''<comment>?';" => ['replace' => '"<1>""<2>""<1>";'],
        ];

        $startFile = '<?php (1); /**/;';
        $resultFile = '<?php (1); /**/ ;';
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([1, 1], $replacedAt);
    }

    /** @test */
    public function optional_comment_placeholder_4()
    {
        $patterns = [
            ";'<white_space>?''<comment>?';" => ['replace' => ';"<1>""<2>""<1>";'],
        ];

        $startFile = '<?php ;/**/ ;';
        $resultFile = '<?php ;  ;';
        [$newVersion, $replacedAt] = PatternParser::searchReplace($patterns, token_get_all($startFile));

        $this->assertEquals($resultFile, $newVersion);
        $this->assertEquals([1], $replacedAt);
    }
}
