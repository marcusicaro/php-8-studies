<?php

abstract class Expression
{
    private static int $keycount = 0;
    private string $key;
    abstract public function interpret(InterpreterContext $context);
    public function getKey(): string
    {
        if (! isset($this->key)) {
            self::$keycount++;
            $this->key = (string)self::$keycount;
        }
        return $this->key;
    }
}

class LiteralExpression extends Expression
{
    private mixed $value;
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
    public function interpret(InterpreterContext $context): void
    {
        $context->replace($this, $this->value);
    }
}

class InterpreterContext
{
    private array $expressionstore = [];
    public function replace(Expression $exp, mixed $value): void
    {
        $this->expressionstore[$exp->getKey()] = $value;
    }
    public function lookup(Expression $exp): mixed
    {
        return $this->expressionstore[$exp->getKey()];
    }
}

class VariableExpression extends Expression
{
    public function __construct(private string $name, private mixed $val =
    null) {}
    public function interpret(InterpreterContext $context): void
    {
        if (! is_null($this->val)) {
            $context->replace($this, $this->val);
            $this->val = null;
        }
    }
    public function setValue(mixed $value): void
    {
        $this->val = $value;
    }
    public function getKey(): string
    {
        return $this->name;
    }
}
$context = new InterpreterContext();
$myvar = new VariableExpression('input', 'four');
$myvar->interpret($context);
print $context->lookup($myvar) . "\n";
$newvar = new VariableExpression('input');
$newvar->interpret($context);
print $context->lookup($newvar) . "\n";
$myvar->setValue("five");
$myvar->interpret($context);
print $context->lookup($myvar) . "\n";
print $context->lookup($newvar) . "\n";

abstract class OperatorExpression extends Expression
{
    public function __construct(protected Expression $l_op, protected
    Expression $r_op) {}
    public function interpret(InterpreterContext $context): void
    {
        $this->l_op->interpret($context);
        $this->r_op->interpret($context);
        $result_l = $context->lookup($this->l_op);
        $result_r = $context->lookup($this->r_op);
        $this->doInterpret($context, $result_l, $result_r);
    }
    abstract protected function doInterpret(
        InterpreterContext $context,
        $result_l,
        $result_r
    ): void;
}

class BooleanOrExpression extends OperatorExpression
{
    protected function doInterpret(
        InterpreterContext $context,
        mixed $result_l,
        mixed $result_r
    ): void {
        $context->replace($this, $result_l || $result_r);
    }
}

class BooleanEqualsExpression extends OperatorExpression
{
    protected function doInterpret(
        InterpreterContext $context,
        mixed $result_l,
        mixed $result_r
    ): void {
        $context->replace($this, $result_l == $result_r);
    }
}

$context = new InterpreterContext();
$input = new VariableExpression('input');
$statement = new BooleanOrExpression(
    new BooleanEqualsExpression($input, new LiteralExpression('four')),
    new BooleanEqualsExpression($input, new LiteralExpression('4'))
);

foreach (["four", "4", "52"] as $val) {
    $input->setValue($val);
    print "$val:\n";
    $statement->interpret($context);
    if ($context->lookup($statement)) {
        print "top marks\n\n";
    } else {
        print "dunce hat on\n\n";
    }
}
