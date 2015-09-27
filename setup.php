<?php

echo "Checking for PHP>=5.5... ";
if (PHP_VERSION_ID < 50500) {
    throw new \RuntimeException(sprintf('You will need at least PHP 5.5 for this workshop. Current version is %s.', phpversion()));
}
echo "[ok]\n";

echo "Checking for pdo_sqlite... ";
if (!extension_loaded('pdo_sqlite')) {
    throw new \RuntimeException('pdo_sqlite extension not found. Make sure it is installed and enabled.');
}
echo "[ok]\n";

echo "Installing composer... ";
if (!file_exists('bin/composer')) {
    echo "\n";
    system('php -r "readfile(\'https://getcomposer.org/installer\');" | php -- --filename=composer --install-dir=bin', $status);
    if ($status !== 0) {
        throw new \RuntimeException('Failed to automatically intall composer. Try to install it yourself in the bin/ directory by following instructions on https://getcomposer.org/download/.');
    }
}
echo "[ok]\n";

echo "Installing dependencies... \n";
system('php bin/composer install -n', $status);
if ($status !== 0) {
    throw new \RuntimeException('Failed to install dependencies with composer. Try to do it yourself by running "php bin/composer install".');
}
echo "[ok]\n";

echo "Setup complete.\n";
