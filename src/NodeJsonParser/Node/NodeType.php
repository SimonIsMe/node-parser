<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser\Node;

enum NodeType: string
{
    case TextNode = 'text';
    case H1 = 'h1';
    case H2 = 'h2';
    case Strong = 'strong';
    case P = 'p';
    case A = 'a';
    case Italic = 'italic';
    case UL = 'ul';
    case OL = 'ol';
    case LI = 'li';
    case Blockquote = 'blockquote';
    case Pre = 'pre';
    case Sub = 'sub';
    case Sup = 'sup';
    case Strike = 'strike';
}
