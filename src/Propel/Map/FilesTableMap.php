<?php

namespace Propel\Map;

use Propel\Files;
use Propel\FilesQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'files' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class FilesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Propel.Map.FilesTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'files';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Files';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Propel\\Files';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Propel.Files';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'files.id';

    /**
     * the column name for the filename field
     */
    public const COL_FILENAME = 'files.filename';

    /**
     * the column name for the path field
     */
    public const COL_PATH = 'files.path';

    /**
     * the column name for the status field
     */
    public const COL_STATUS = 'files.status';

    /**
     * the column name for the config_id field
     */
    public const COL_CONFIG_ID = 'files.config_id';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'files.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'files.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'Filename', 'Path', 'Status', 'ConfigId', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'filename', 'path', 'status', 'configId', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [FilesTableMap::COL_ID, FilesTableMap::COL_FILENAME, FilesTableMap::COL_PATH, FilesTableMap::COL_STATUS, FilesTableMap::COL_CONFIG_ID, FilesTableMap::COL_CREATED_AT, FilesTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'filename', 'path', 'status', 'config_id', 'created_at', 'updated_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'Filename' => 1, 'Path' => 2, 'Status' => 3, 'ConfigId' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'filename' => 1, 'path' => 2, 'status' => 3, 'configId' => 4, 'createdAt' => 5, 'updatedAt' => 6, ],
        self::TYPE_COLNAME       => [FilesTableMap::COL_ID => 0, FilesTableMap::COL_FILENAME => 1, FilesTableMap::COL_PATH => 2, FilesTableMap::COL_STATUS => 3, FilesTableMap::COL_CONFIG_ID => 4, FilesTableMap::COL_CREATED_AT => 5, FilesTableMap::COL_UPDATED_AT => 6, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'filename' => 1, 'path' => 2, 'status' => 3, 'config_id' => 4, 'created_at' => 5, 'updated_at' => 6, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Files.Id' => 'ID',
        'id' => 'ID',
        'files.id' => 'ID',
        'FilesTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'Filename' => 'FILENAME',
        'Files.Filename' => 'FILENAME',
        'filename' => 'FILENAME',
        'files.filename' => 'FILENAME',
        'FilesTableMap::COL_FILENAME' => 'FILENAME',
        'COL_FILENAME' => 'FILENAME',
        'Path' => 'PATH',
        'Files.Path' => 'PATH',
        'path' => 'PATH',
        'files.path' => 'PATH',
        'FilesTableMap::COL_PATH' => 'PATH',
        'COL_PATH' => 'PATH',
        'Status' => 'STATUS',
        'Files.Status' => 'STATUS',
        'status' => 'STATUS',
        'files.status' => 'STATUS',
        'FilesTableMap::COL_STATUS' => 'STATUS',
        'COL_STATUS' => 'STATUS',
        'ConfigId' => 'CONFIG_ID',
        'Files.ConfigId' => 'CONFIG_ID',
        'configId' => 'CONFIG_ID',
        'files.configId' => 'CONFIG_ID',
        'FilesTableMap::COL_CONFIG_ID' => 'CONFIG_ID',
        'COL_CONFIG_ID' => 'CONFIG_ID',
        'config_id' => 'CONFIG_ID',
        'files.config_id' => 'CONFIG_ID',
        'CreatedAt' => 'CREATED_AT',
        'Files.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'files.createdAt' => 'CREATED_AT',
        'FilesTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'files.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'Files.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'files.updatedAt' => 'UPDATED_AT',
        'FilesTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'files.updated_at' => 'UPDATED_AT',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('files');
        $this->setPhpName('Files');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Propel\\Files');
        $this->setPackage('Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('filename', 'Filename', 'VARCHAR', true, 255, null);
        $this->addColumn('path', 'Path', 'VARCHAR', true, 255, null);
        $this->addColumn('status', 'Status', 'VARCHAR', true, 50, null);
        $this->addForeignKey('config_id', 'ConfigId', 'INTEGER', 'config', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'DATETIME', true, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'DATETIME', true, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Config', '\\Propel\\Config', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':config_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('ImportHistory', '\\Propel\\ImportHistory', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':file_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'ImportHistories', false);
    }

    /**
     * Method to invalidate the instance pool of all tables related to files     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool(): void
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ImportHistoryTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? FilesTableMap::CLASS_DEFAULT : FilesTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Files object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = FilesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FilesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FilesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FilesTableMap::OM_CLASS;
            /** @var Files $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FilesTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FilesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FilesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Files $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FilesTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FilesTableMap::COL_ID);
            $criteria->addSelectColumn(FilesTableMap::COL_FILENAME);
            $criteria->addSelectColumn(FilesTableMap::COL_PATH);
            $criteria->addSelectColumn(FilesTableMap::COL_STATUS);
            $criteria->addSelectColumn(FilesTableMap::COL_CONFIG_ID);
            $criteria->addSelectColumn(FilesTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(FilesTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.filename');
            $criteria->addSelectColumn($alias . '.path');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.config_id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(FilesTableMap::COL_ID);
            $criteria->removeSelectColumn(FilesTableMap::COL_FILENAME);
            $criteria->removeSelectColumn(FilesTableMap::COL_PATH);
            $criteria->removeSelectColumn(FilesTableMap::COL_STATUS);
            $criteria->removeSelectColumn(FilesTableMap::COL_CONFIG_ID);
            $criteria->removeSelectColumn(FilesTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(FilesTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.filename');
            $criteria->removeSelectColumn($alias . '.path');
            $criteria->removeSelectColumn($alias . '.status');
            $criteria->removeSelectColumn($alias . '.config_id');
            $criteria->removeSelectColumn($alias . '.created_at');
            $criteria->removeSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(FilesTableMap::DATABASE_NAME)->getTable(FilesTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Files or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Files object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FilesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Propel\Files) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FilesTableMap::DATABASE_NAME);
            $criteria->add(FilesTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = FilesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FilesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FilesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the files table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return FilesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Files or Criteria object.
     *
     * @param mixed $criteria Criteria or Files object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FilesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Files object
        }

        if ($criteria->containsKey(FilesTableMap::COL_ID) && $criteria->keyContainsValue(FilesTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FilesTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = FilesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
