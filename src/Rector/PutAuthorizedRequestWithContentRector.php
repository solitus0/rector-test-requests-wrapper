<?php

declare(strict_types=1);

namespace Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PutAuthorizedRequestWithContentRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $args = $node->args;
        $url = $args[1];
        $content = $args[5];

        $newArgs = [];
        $newArgs[] = new Arg(value: $url->value, name: new Node\Identifier('url'));
        $newArgs[] = new Arg(value: new Variable('token'), name: new Node\Identifier('token'));
        $contentArray = $content->value->value;
        if (!is_array($contentArray)) {
            $contentArray = json_decode($contentArray, true);
            $array = $this->nodeFactory->createArray($contentArray);
            $newArgs[] = new Arg(value: $array, name: new Node\Identifier('content'));
        } else {
            $newArgs[] = new Arg(value: $content->value, name: new Node\Identifier('content'));
        }

        return new MethodCall(
            new Variable('this'),
            'putAuthorizedRequestWithContent',
            $newArgs
        );
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (!$node->var instanceof Node\Expr\PropertyFetch) {
            return true;
        }

        $name = $node->var->name->name;
        if ($name !== 'client') {
            return true;
        }

        if ($node->name->toString() !== 'request') {
            return true;
        }

        if (count($node->args) !== 6) {
            return true;
        }

        if (!$this->isStringArgument($node->args[0], 'PUT')) {
            return true;
        }

        $parameters = $node->args[2];
        if (!$this->isArrayArgument($parameters)) {
            return true;
        }

        $parametersCount = count($parameters->value->items);
        if ($parametersCount !== 0) {
            return true;
        }

        $filesArg = $node->args[3];
        if (!$this->isArrayArgument($filesArg)) {
            return true;
        }

        $filesCount = count($filesArg->value->items);
        if ($filesCount !== 0) {
            return true;
        }

        if (!$this->isArrayArgument($node->args[4])) {
            return true;
        }

        if (!$node->args[5]->value instanceof Node\Scalar\String_) {
            return true;
        }

        return false;
    }

    private function isStringArgument(Arg $arg, string $value): bool
    {
        return $arg->value instanceof Node\Scalar\String_ && $arg->value->value === $value;
    }

    private function isArrayArgument(Arg $arg): bool
    {
        return $arg->value instanceof Node\Expr\Array_;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        // TODO: Implement getRuleDefinition() method.
    }
}
