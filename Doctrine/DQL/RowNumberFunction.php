<?php
namespace Ibram\Core\BaseBundle\Doctrine\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * RowNumberFunction ::= "rownumber()"
 */
class RowNumberFunction extends FunctionNode
{

    public $field;
    public $orderBy;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->orderBy = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'ROW_NUMBER() OVER( PARTITION BY ' .
                $this->field->dispatch($sqlWalker) . ' ORDER BY ' .
                $this->orderBy->dispatch($sqlWalker) . ' DESC) ';
    }

}