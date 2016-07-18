<?php
require_once __DIR__ . '/components/App.php';

function usage()
{
    echo "Migration tool \n";
    echo "Usage: \n";
    echo "migrate up  - apply all new migrations (default)\n";
    echo "migrate down  - revert all migrations\n";
}

function ensureMigrationsTable(\app\components\db\Db $connection)
{
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255)
)
SQL;

    $connection->createQuery($sql)->execute();
}

function down(\app\components\db\Db $connection)
{
    $executed = $connection->createQuery('select * from migrations order by id DESC ')->fetchAll();
    echo "Reverting migrations\n";
    foreach ($executed as $row) {
        $migrationName = $row['name'];
        echo "Reverting migration: {$migrationName}\n";
        require_once(__DIR__ . "/migrations/{$migrationName}.php");
        /** @var \app\components\db\Migration $object */
        $object = new $migrationName($connection);
        $object->down();
        $connection->createQuery('delete from migrations WHERE id=?')->execute([$row['id']]);
    }
    echo "All migrations reverted\n";
}

function up(\app\components\db\Db $connection)
{
    echo "Running new migrations:\n";

    $executed = $connection->createQuery('select * from migrations')->fetchColumn(1);

    $available = new GlobIterator(__DIR__ . '/migrations/*.php', FilesystemIterator::CURRENT_AS_FILEINFO);

    /** @var SplFileInfo $fileInfo */
    foreach ($available as $fileInfo) {
        $name = $fileInfo->getBasename('.php');
        if (in_array($name, $executed)) {
            continue;
        }
        echo "Executing migration: {$name}\n";

        require_once($fileInfo->getRealPath());

        /** @var \app\components\db\Migration $migration */
        $migration = new $name($connection);
        $migration->up();
        $connection->createQuery('insert into migrations (`name`) values (:name)')->execute([':name' => $name]);
    }
    echo "All migrations applied\n";

}

usage();

$connection = \app\components\App::instance()->getDb();

/**
 * @param $connection
 */
try {
    ensureMigrationsTable($connection);

    if (isset($argv[1]) && strtolower($argv[1]) == 'down') {
        down($connection);
    } else {
        up($connection);
    }
} catch (Exception $e) {
    echo $e->getMessage()."\n";
    echo $e->getTraceAsString()."\n";
    
}