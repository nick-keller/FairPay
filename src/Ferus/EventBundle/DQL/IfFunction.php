<?php


namespace Ferus\EventBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;

/**
 * IfFunction ::= "IF" "(" ArithmeticPrimary "," ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class IfFunction extends FunctionNode
{
    public $condition = null;
    public $ifTrue = null;
    public $ifFalse = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->condition = $parser->ConditionalExpression();
        $parser->match(Lexer::T_COMMA);
        $this->ifTrue = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->ifFalse = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'IF(' .
        $this->condition->dispatch($sqlWalker) . ', ' .
        $this->ifTrue->dispatch($sqlWalker) . ', ' .
        $this->ifFalse->dispatch($sqlWalker) .
        ')';
    }
}