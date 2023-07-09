<?php

declare(strict_types=1);

namespace Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class GetAuthorizedRequestRector extends AbstractRector
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

        $newArgs = [];
        $newArgs[] = new Arg(value: $url->value, name: new Node\Identifier('url'));
        $newArgs[] = new Arg(value: new Variable('token'), name: new Node\Identifier('token'));

        return new MethodCall(
            new Variable('this'),
            'getAuthorizedRequest',
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

        if (count($node->args) !== 5) {
            return true;
        }

        if (!$this->isStringArgument($node->args[0], 'GET')) {
            return true;
        }

        $parameters = $node->args[2];
        if (!$this->isArrayArgument($parameters)) {
            return true;
        }

        $filesArg = $node->args[3];
        if (!$this->isArrayArgument($filesArg)) {
            return true;
        }

        if (!$this->isArrayArgument($node->args[4])) {
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
