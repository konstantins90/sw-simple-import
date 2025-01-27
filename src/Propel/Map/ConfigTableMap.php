<?php

namespace Propel\Map;

use Propel\Config;
use Propel\ConfigQuery;
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
 * This class defines the structure of the 'config' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ConfigTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Propel.Map.ConfigTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'config';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Config';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Propel\\Config';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Propel.Config';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'config.id';

    /**
     * the column name for the name field
     */
    public const COL_NAME = 'config.name';

    /**
     * the column name for the prefix field
     */
    public const COL_PREFIX = 'config.prefix';

    /**
     * the column name for the marge field
     */
    public const COL_MARGE = 'config.marge';

    /**
     * the column name for the mapping field
     */
    public const COL_MAPPING = 'config.mapping';

    /**
     * the column name for the csv_headers field
     */
    public const COL_CSV_HEADERS = 'config.csv_headers';

    /**
     * the column name for the mapping_properties field
     */
    public const COL_MAPPING_PROPERTIES = 'config.mapping_properties';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'config.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'config.updated_at';

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
        self::TYPE_PHPNAME       => ['Id', 'Name', 'Prefix', 'Marge', 'Mapping', 'CsvHeaders', 'MappingProperties', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'name', 'prefix', 'marge', 'mapping', 'csvHeaders', 'mappingProperties', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [ConfigTableMap::COL_ID, ConfigTableMap::COL_NAME, ConfigTableMap::COL_PREFIX, ConfigTableMap::COL_MARGE, ConfigTableMap::COL_MAPPING, ConfigTableMap::COL_CSV_HEADERS, ConfigTableMap::COL_MAPPING_PROPERTIES, ConfigTableMap::COL_CREATED_AT, ConfigTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'name', 'prefix', 'marge', 'mapping', 'csv_headers', 'mapping_properties', 'created_at', 'updated_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Name' => 1, 'Prefix' => 2, 'Marge' => 3, 'Mapping' => 4, 'CsvHeaders' => 5, 'MappingProperties' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'name' => 1, 'prefix' => 2, 'marge' => 3, 'mapping' => 4, 'csvHeaders' => 5, 'mappingProperties' => 6, 'createdAt' => 7, 'updatedAt' => 8, ],
        self::TYPE_COLNAME       => [ConfigTableMap::COL_ID => 0, ConfigTableMap::COL_NAME => 1, ConfigTableMap::COL_PREFIX => 2, ConfigTableMap::COL_MARGE => 3, ConfigTableMap::COL_MAPPING => 4, ConfigTableMap::COL_CSV_HEADERS => 5, ConfigTableMap::COL_MAPPING_PROPERTIES => 6, ConfigTableMap::COL_CREATED_AT => 7, ConfigTableMap::COL_UPDATED_AT => 8, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'name' => 1, 'prefix' => 2, 'marge' => 3, 'mapping' => 4, 'csv_headers' => 5, 'mapping_properties' => 6, 'created_at' => 7, 'updated_at' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Config.Id' => 'ID',
        'id' => 'ID',
        'config.id' => 'ID',
        'ConfigTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'Name' => 'NAME',
        'Config.Name' => 'NAME',
        'name' => 'NAME',
        'config.name' => 'NAME',
        'ConfigTableMap::COL_NAME' => 'NAME',
        'COL_NAME' => 'NAME',
        'Prefix' => 'PREFIX',
        'Config.Prefix' => 'PREFIX',
        'prefix' => 'PREFIX',
        'config.prefix' => 'PREFIX',
        'ConfigTableMap::COL_PREFIX' => 'PREFIX',
        'COL_PREFIX' => 'PREFIX',
        'Marge' => 'MARGE',
        'Config.Marge' => 'MARGE',
        'marge' => 'MARGE',
        'config.marge' => 'MARGE',
        'ConfigTableMap::COL_MARGE' => 'MARGE',
        'COL_MARGE' => 'MARGE',
        'Mapping' => 'MAPPING',
        'Config.Mapping' => 'MAPPING',
        'mapping' => 'MAPPING',
        'config.mapping' => 'MAPPING',
        'ConfigTableMap::COL_MAPPING' => 'MAPPING',
        'COL_MAPPING' => 'MAPPING',
        'CsvHeaders' => 'CSV_HEADERS',
        'Config.CsvHeaders' => 'CSV_HEADERS',
        'csvHeaders' => 'CSV_HEADERS',
        'config.csvHeaders' => 'CSV_HEADERS',
        'ConfigTableMap::COL_CSV_HEADERS' => 'CSV_HEADERS',
        'COL_CSV_HEADERS' => 'CSV_HEADERS',
        'csv_headers' => 'CSV_HEADERS',
        'config.csv_headers' => 'CSV_HEADERS',
        'MappingProperties' => 'MAPPING_PROPERTIES',
        'Config.MappingProperties' => 'MAPPING_PROPERTIES',
        'mappingProperties' => 'MAPPING_PROPERTIES',
        'config.mappingProperties' => 'MAPPING_PROPERTIES',
        'ConfigTableMap::COL_MAPPING_PROPERTIES' => 'MAPPING_PROPERTIES',
        'COL_MAPPING_PROPERTIES' => 'MAPPING_PROPERTIES',
        'mapping_properties' => 'MAPPING_PROPERTIES',
        'config.mapping_properties' => 'MAPPING_PROPERTIES',
        'CreatedAt' => 'CREATED_AT',
        'Config.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'config.createdAt' => 'CREATED_AT',
        'ConfigTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'config.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'Config.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'config.updatedAt' => 'UPDATED_AT',
        'ConfigTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'config.updated_at' => 'UPDATED_AT',
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
        $this->setName('config');
        $this->setPhpName('Config');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Propel\\Config');
        $this->setPackage('Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('prefix', 'Prefix', 'VARCHAR', false, 255, null);
        $this->addColumn('marge', 'Marge', 'FLOAT', true, null, 1);
        $this->addColumn('mapping', 'Mapping', 'LONGVARCHAR', false, null, null);
        $this->addColumn('csv_headers', 'CsvHeaders', 'LONGVARCHAR', false, null, null);
        $this->addColumn('mapping_properties', 'MappingProperties', 'LONGVARCHAR', false, null, null);
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
        $this->addRelation('Files', '\\Propel\\Files', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':config_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Filess', false);
    }

    /**
     * Method to invalidate the instance pool of all tables related to config     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool(): void
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        FilesTableMap::clearInstancePool();
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
        return $withPrefix ? ConfigTableMap::CLASS_DEFAULT : ConfigTableMap::OM_CLASS;
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
     * @return array (Config object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ConfigTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ConfigTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ConfigTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ConfigTableMap::OM_CLASS;
            /** @var Config $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ConfigTableMap::addInstanceToPool($obj, $key);
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
            $key = ConfigTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ConfigTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Config $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ConfigTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ConfigTableMap::COL_ID);
            $criteria->addSelectColumn(ConfigTableMap::COL_NAME);
            $criteria->addSelectColumn(ConfigTableMap::COL_PREFIX);
            $criteria->addSelectColumn(ConfigTableMap::COL_MARGE);
            $criteria->addSelectColumn(ConfigTableMap::COL_MAPPING);
            $criteria->addSelectColumn(ConfigTableMap::COL_CSV_HEADERS);
            $criteria->addSelectColumn(ConfigTableMap::COL_MAPPING_PROPERTIES);
            $criteria->addSelectColumn(ConfigTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ConfigTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.prefix');
            $criteria->addSelectColumn($alias . '.marge');
            $criteria->addSelectColumn($alias . '.mapping');
            $criteria->addSelectColumn($alias . '.csv_headers');
            $criteria->addSelectColumn($alias . '.mapping_properties');
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
            $criteria->removeSelectColumn(ConfigTableMap::COL_ID);
            $criteria->removeSelectColumn(ConfigTableMap::COL_NAME);
            $criteria->removeSelectColumn(ConfigTableMap::COL_PREFIX);
            $criteria->removeSelectColumn(ConfigTableMap::COL_MARGE);
            $criteria->removeSelectColumn(ConfigTableMap::COL_MAPPING);
            $criteria->removeSelectColumn(ConfigTableMap::COL_CSV_HEADERS);
            $criteria->removeSelectColumn(ConfigTableMap::COL_MAPPING_PROPERTIES);
            $criteria->removeSelectColumn(ConfigTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(ConfigTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.name');
            $criteria->removeSelectColumn($alias . '.prefix');
            $criteria->removeSelectColumn($alias . '.marge');
            $criteria->removeSelectColumn($alias . '.mapping');
            $criteria->removeSelectColumn($alias . '.csv_headers');
            $criteria->removeSelectColumn($alias . '.mapping_properties');
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
        return Propel::getServiceContainer()->getDatabaseMap(ConfigTableMap::DATABASE_NAME)->getTable(ConfigTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Config or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Config object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ConfigTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Propel\Config) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ConfigTableMap::DATABASE_NAME);
            $criteria->add(ConfigTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ConfigQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ConfigTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ConfigTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the config table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ConfigQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Config or Criteria object.
     *
     * @param mixed $criteria Criteria or Config object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConfigTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Config object
        }

        if ($criteria->containsKey(ConfigTableMap::COL_ID) && $criteria->keyContainsValue(ConfigTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ConfigTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ConfigQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
