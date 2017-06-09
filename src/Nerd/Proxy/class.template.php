<?php /* @codingStandardsIgnoreStart */ ?>
return function (\Nerd\Proxy\Handler $handler)
{
    return new class($handler)
    <?= $parentClass ? ' extends ' . $parentClass . ' ' : '' ?>
    <?= sizeof($interfaceList) ? ' implements ' : '' ?> <?= implode(', ', $interfaceList) ?>
    {
        private $proxyHandler;

        public function __construct(\Nerd\Proxy\Handler $proxyHandler)
        {
            $this->proxyHandler = $proxyHandler;
        }

    <?php foreach ($methodList as $method): ?>
        public function <?= $method['name'] ?>(<?= implode(', ', $method['args']) ?>)<?= $method['return'] ? ': ' . $method['return'] : '' ?> {
        $args = func_get_args();
        $reflection = new \ReflectionMethod($this, '<?= $method['name'] ?>');
        <?= $method['return'] != 'void' ? 'return' : '' ?> call_user_func([$this->proxyHandler, 'invoke'], $reflection, $args, $this);
        }

    <?php endforeach ?>
    };
};
<?php /* @codingStandardsIgnoreEnd */ ?>