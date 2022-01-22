<?php

namespace DD\ContactList\Infrastructure\Autoloader;

/**
 * Автозагрузчик классов
 */
final class Autoloader
{
    /**
     * Зарегистрированные пространства имен
     *
     * @var array
     */
    private array $registerNamespaces = [];

    /**
     * @param array $registerNamespaces
     */
    public function __construct(array $registerNamespaces)
    {
        foreach ($registerNamespaces as $nms => $src) {
            $this->registerNamespaces[trim($nms, '\\') . '\\'] = $src;
        }
    }

    /**
     * Получает имя файла, в котором расположен заданный класс
     *
     * @param string $className
     *
     * @return string|null
     */
    private function classNameToPath(string $className): ?string
    {
        $path = null;

        foreach ($this->registerNamespaces as $prefix => $sourcePath) {
            if (strpos($className, $prefix) === 0) {
                $classNameWithoutPrefix = substr($className, strlen($prefix));
                $path = $sourcePath . str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithoutPrefix) . '.php';
                break;
            }
        }

        return $path;
    }

    /**
     * Логика автозагрузки файлов
     *
     * @param string $className
     *
     * @return void
     */
    public function __invoke(string $className): void
    {
        $pathToFile = $this->classNameToPath($className);
        if ($pathToFile !== null && file_exists($pathToFile) && is_dir($pathToFile) === false) {
            require_once $pathToFile;
        }
    }

}