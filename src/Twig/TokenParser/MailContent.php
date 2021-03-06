<?php
declare(strict_types = 1);
namespace RabbitCMS\Templates\Twig\TokenParser;

use Twig_Error_Syntax;
use Twig_Node;
use Twig_Node_Block;
use Twig_Node_BlockReference;
use Twig_Token;
use Twig_TokenParser;

/**
 * Class MailContent.
 */
class MailContent extends Twig_TokenParser
{
    /**
     * @inheritdoc
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = 'content';
        if ($this->parser->hasBlock($name)) {
            throw new Twig_Error_Syntax(
                sprintf(
                    "The block '%s' has already been defined line %d.",
                    $name,
                    $this->parser->getBlock($name)->getLine()
                ),
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()->getName()
            );
        }
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $this->parser->setBlock($name, new Twig_Node_Block($name, new Twig_Node([]), $lineno));

        return new Twig_Node_BlockReference($name, $lineno, $this->getTag());
    }

    /**
     * @inheritdoc
     */
    public function getTag()
    {
        return 'content';
    }
}
