<?php

namespace Propel\Map;

use Propel\ImportHistory;
use Propel\ImportHistoryQuery;
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
 * This class defines the structure of the 'import_history' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ImportHistoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Propel.Map.ImportHistoryTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'import_history';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'ImportHistory';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Propel\\ImportHistory';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Propel.ImportHistory';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'import_history.id';

    /**
     * the column name for the file_id field
     */
    public const COL_FILE_ID = 'import_history.file_id';

    /**
     * the column name for the imported_at field
     */
    public const COL_IMPORTED_AT = 'import_history.imported_at';

    /**
     * the column name for the status field
     */
    public const COL_STATUS = 'import_history.status';

    /**
     * the column name for the count_imported_products field
     */
    public const COL_COUNT_IMPORTED_PRODUCTS = 'import_history.count_imported_products';

    /**
     * the column name for the count_errors field
     */
    public const COL_COUNT_ERRORS = 'import_history.count_errors';

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
        self::TYPE_PHPNAME       => ['Id', 'FileId', 'ImportedAt', 'Status', 'CountImportedProducts', 'CountErrors', ],
        self::TYPE_CAMELNAME     => ['id', 'fileId', 'importedAt', 'status', 'countImportedProducts', 'countErrors', ],
        self::TYPE_COLNAME       => [ImportHistoryTableMap::COL_ID, ImportHistoryTableMap::COL_FILE_ID, ImportHistoryTableMap::COL_IMPORTED_AT, ImportHistoryTableMap::COL_STATUS, ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS, ImportHistoryTableMap::COL_COUNT_ERRORS, ],
        self::TYPE_FIELDNAME     => ['id', 'file_id', 'imported_at', 'status', 'count_imported_products', 'count_errors', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'FileId' => 1, 'ImportedAt' => 2, 'Status' => 3, 'CountImportedProducts' => 4, 'CountErrors' => 5, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'fileId' => 1, 'importedAt' => 2, 'status' => 3, 'countImportedProducts' => 4, 'countErrors' => 5, ],
        self::TYPE_COLNAME       => [ImportHistoryTableMap::COL_ID => 0, ImportHistoryTableMap::COL_FILE_ID => 1, ImportHistoryTableMap::COL_IMPORTED_AT => 2, ImportHistoryTableMap::COL_STATUS => 3, ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS => 4, ImportHistoryTableMap::COL_COUNT_ERRORS => 5, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'file_id' => 1, 'imported_at' => 2, 'status' => 3, 'count_imported_products' => 4, 'count_errors' => 5, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'ImportHistory.Id' => 'ID',
        'id' => 'ID',
        'importHistory.id' => 'ID',
        'ImportHistoryTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'import_history.id' => 'ID',
        'FileId' => 'FILE_ID',
        'ImportHistory.FileId' => 'FILE_ID',
        'fileId' => 'FILE_ID',
        'importHistory.fileId' => 'FILE_ID',
        'ImportHistoryTableMap::COL_FILE_ID' => 'FILE_ID',
        'COL_FILE_ID' => 'FILE_ID',
        'file_id' => 'FILE_ID',
        'import_history.file_id' => 'FILE_ID',
        'ImportedAt' => 'IMPORTED_AT',
        'ImportHistory.ImportedAt' => 'IMPORTED_AT',
        'importedAt' => 'IMPORTED_AT',
        'importHistory.importedAt' => 'IMPORTED_AT',
        'ImportHistoryTableMap::COL_IMPORTED_AT' => 'IMPORTED_AT',
        'COL_IMPORTED_AT' => 'IMPORTED_AT',
        'imported_at' => 'IMPORTED_AT',
        'import_history.imported_at' => 'IMPORTED_AT',
        'Status' => 'STATUS',
        'ImportHistory.Status' => 'STATUS',
        'status' => 'STATUS',
        'importHistory.status' => 'STATUS',
        'ImportHistoryTableMap::COL_STATUS' => 'STATUS',
        'COL_STATUS' => 'STATUS',
        'import_history.status' => 'STATUS',
        'CountImportedProducts' => 'COUNT_IMPORTED_PRODUCTS',
        'ImportHistory.CountImportedProducts' => 'COUNT_IMPORTED_PRODUCTS',
        'countImportedProducts' => 'COUNT_IMPORTED_PRODUCTS',
        'importHistory.countImportedProducts' => 'COUNT_IMPORTED_PRODUCTS',
        'ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS' => 'COUNT_IMPORTED_PRODUCTS',
        'COL_COUNT_IMPORTED_PRODUCTS' => 'COUNT_IMPORTED_PRODUCTS',
        'count_imported_products' => 'COUNT_IMPORTED_PRODUCTS',
        'import_history.count_imported_products' => 'COUNT_IMPORTED_PRODUCTS',
        'CountErrors' => 'COUNT_ERRORS',
        'ImportHistory.CountErrors' => 'COUNT_ERRORS',
        'countErrors' => 'COUNT_ERRORS',
        'importHistory.countErrors' => 'COUNT_ERRORS',
        'ImportHistoryTableMap::COL_COUNT_ERRORS' => 'COUNT_ERRORS',
        'COL_COUNT_ERRORS' => 'COUNT_ERRORS',
        'count_errors' => 'COUNT_ERRORS',
        'import_history.count_errors' => 'COUNT_ERRORS',
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
        $this->setName('import_history');
        $this->setPhpName('ImportHistory');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Propel\\ImportHistory');
        $this->setPackage('Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('file_id', 'FileId', 'INTEGER', 'files', 'id', true, null, null);
        $this->addColumn('imported_at', 'ImportedAt', 'DATETIME', true, null, null);
        $this->addColumn('status', 'Status', 'VARCHAR', true, 50, null);
        $this->addColumn('count_imported_products', 'CountImportedProducts', 'INTEGER', true, null, 0);
        $this->addColumn('count_errors', 'CountErrors', 'INTEGER', true, null, 0);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Files', '\\Propel\\Files', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':file_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
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
        return $withPrefix ? ImportHistoryTableMap::CLASS_DEFAULT : ImportHistoryTableMap::OM_CLASS;
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
     * @return array (ImportHistory object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ImportHistoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ImportHistoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ImportHistoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ImportHistoryTableMap::OM_CLASS;
            /** @var ImportHistory $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ImportHistoryTableMap::addInstanceToPool($obj, $key);
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
            $key = ImportHistoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ImportHistoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ImportHistory $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ImportHistoryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ImportHistoryTableMap::COL_ID);
            $criteria->addSelectColumn(ImportHistoryTableMap::COL_FILE_ID);
            $criteria->addSelectColumn(ImportHistoryTableMap::COL_IMPORTED_AT);
            $criteria->addSelectColumn(ImportHistoryTableMap::COL_STATUS);
            $criteria->addSelectColumn(ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS);
            $criteria->addSelectColumn(ImportHistoryTableMap::COL_COUNT_ERRORS);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.file_id');
            $criteria->addSelectColumn($alias . '.imported_at');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.count_imported_products');
            $criteria->addSelectColumn($alias . '.count_errors');
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
            $criteria->removeSelectColumn(ImportHistoryTableMap::COL_ID);
            $criteria->removeSelectColumn(ImportHistoryTableMap::COL_FILE_ID);
            $criteria->removeSelectColumn(ImportHistoryTableMap::COL_IMPORTED_AT);
            $criteria->removeSelectColumn(ImportHistoryTableMap::COL_STATUS);
            $criteria->removeSelectColumn(ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS);
            $criteria->removeSelectColumn(ImportHistoryTableMap::COL_COUNT_ERRORS);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.file_id');
            $criteria->removeSelectColumn($alias . '.imported_at');
            $criteria->removeSelectColumn($alias . '.status');
            $criteria->removeSelectColumn($alias . '.count_imported_products');
            $criteria->removeSelectColumn($alias . '.count_errors');
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
        return Propel::getServiceContainer()->getDatabaseMap(ImportHistoryTableMap::DATABASE_NAME)->getTable(ImportHistoryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a ImportHistory or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or ImportHistory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ImportHistoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Propel\ImportHistory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ImportHistoryTableMap::DATABASE_NAME);
            $criteria->add(ImportHistoryTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ImportHistoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ImportHistoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ImportHistoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the import_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ImportHistoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ImportHistory or Criteria object.
     *
     * @param mixed $criteria Criteria or ImportHistory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImportHistoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ImportHistory object
        }

        if ($criteria->containsKey(ImportHistoryTableMap::COL_ID) && $criteria->keyContainsValue(ImportHistoryTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ImportHistoryTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ImportHistoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
