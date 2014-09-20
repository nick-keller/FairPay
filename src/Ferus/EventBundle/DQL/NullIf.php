<?php


namespace Ferus\EventBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;

/**
 * NullIfFunction ::= "NULLIF" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class NullIf extends FunctionNode
{
    public $exp1 = null;
    public $exp2 = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->exp1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->exp2 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'NULLIF(' .
        $this->exp1->dispatch($sqlWalker) . ', ' .
        $this->exp2->dispatch($sqlWalker) .
        ')';
    }
}