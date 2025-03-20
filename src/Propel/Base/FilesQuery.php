<?php

namespace Propel\Base;

use \Exception;
use \PDO;
use Propel\Files as ChildFiles;
use Propel\FilesQuery as ChildFilesQuery;
use Propel\Map\FilesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `files` table.
 *
 * @method     ChildFilesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFilesQuery orderByFilename($order = Criteria::ASC) Order by the filename column
 * @method     ChildFilesQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     ChildFilesQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildFilesQuery orderByImportType($order = Criteria::ASC) Order by the import_type column
 * @method     ChildFilesQuery orderByProductStatus($order = Criteria::ASC) Order by the product_status column
 * @method     ChildFilesQuery orderByPrefix($order = Criteria::ASC) Order by the prefix column
 * @method     ChildFilesQuery orderByMarge($order = Criteria::ASC) Order by the marge column
 * @method     ChildFilesQuery orderByExchangeRate($order = Criteria::ASC) Order by the exchange_rate column
 * @method     ChildFilesQuery orderByPreorder($order = Criteria::ASC) Order by the preorder column
 * @method     ChildFilesQuery orderByPreorderDeadline($order = Criteria::ASC) Order by the preorder_deadline column
 * @method     ChildFilesQuery orderByPreorderDelivery($order = Criteria::ASC) Order by the preorder_delivery column
 * @method     ChildFilesQuery orderByPreorderState($order = Criteria::ASC) Order by the preorder_state column
 * @method     ChildFilesQuery orderByConfigId($order = Criteria::ASC) Order by the config_id column
 * @method     ChildFilesQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildFilesQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildFilesQuery groupById() Group by the id column
 * @method     ChildFilesQuery groupByFilename() Group by the filename column
 * @method     ChildFilesQuery groupByPath() Group by the path column
 * @method     ChildFilesQuery groupByStatus() Group by the status column
 * @method     ChildFilesQuery groupByImportType() Group by the import_type column
 * @method     ChildFilesQuery groupByProductStatus() Group by the product_status column
 * @method     ChildFilesQuery groupByPrefix() Group by the prefix column
 * @method     ChildFilesQuery groupByMarge() Group by the marge column
 * @method     ChildFilesQuery groupByExchangeRate() Group by the exchange_rate column
 * @method     ChildFilesQuery groupByPreorder() Group by the preorder column
 * @method     ChildFilesQuery groupByPreorderDeadline() Group by the preorder_deadline column
 * @method     ChildFilesQuery groupByPreorderDelivery() Group by the preorder_delivery column
 * @method     ChildFilesQuery groupByPreorderState() Group by the preorder_state column
 * @method     ChildFilesQuery groupByConfigId() Group by the config_id column
 * @method     ChildFilesQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildFilesQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildFilesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFilesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFilesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFilesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFilesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFilesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFilesQuery leftJoinConfig($relationAlias = null) Adds a LEFT JOIN clause to the query using the Config relation
 * @method     ChildFilesQuery rightJoinConfig($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Config relation
 * @method     ChildFilesQuery innerJoinConfig($relationAlias = null) Adds a INNER JOIN clause to the query using the Config relation
 *
 * @method     ChildFilesQuery joinWithConfig($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Config relation
 *
 * @method     ChildFilesQuery leftJoinWithConfig() Adds a LEFT JOIN clause and with to the query using the Config relation
 * @method     ChildFilesQuery rightJoinWithConfig() Adds a RIGHT JOIN clause and with to the query using the Config relation
 * @method     ChildFilesQuery innerJoinWithConfig() Adds a INNER JOIN clause and with to the query using the Config relation
 *
 * @method     ChildFilesQuery leftJoinImportHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the ImportHistory relation
 * @method     ChildFilesQuery rightJoinImportHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ImportHistory relation
 * @method     ChildFilesQuery innerJoinImportHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the ImportHistory relation
 *
 * @method     ChildFilesQuery joinWithImportHistory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ImportHistory relation
 *
 * @method     ChildFilesQuery leftJoinWithImportHistory() Adds a LEFT JOIN clause and with to the query using the ImportHistory relation
 * @method     ChildFilesQuery rightJoinWithImportHistory() Adds a RIGHT JOIN clause and with to the query using the ImportHistory relation
 * @method     ChildFilesQuery innerJoinWithImportHistory() Adds a INNER JOIN clause and with to the query using the ImportHistory relation
 *
 * @method     \Propel\ConfigQuery|\Propel\ImportHistoryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFiles|null findOne(?ConnectionInterface $con = null) Return the first ChildFiles matching the query
 * @method     ChildFiles findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildFiles matching the query, or a new ChildFiles object populated from the query conditions when no match is found
 *
 * @method     ChildFiles|null findOneById(int $id) Return the first ChildFiles filtered by the id column
 * @method     ChildFiles|null findOneByFilename(string $filename) Return the first ChildFiles filtered by the filename column
 * @method     ChildFiles|null findOneByPath(string $path) Return the first ChildFiles filtered by the path column
 * @method     ChildFiles|null findOneByStatus(string $status) Return the first ChildFiles filtered by the status column
 * @method     ChildFiles|null findOneByImportType(string $import_type) Return the first ChildFiles filtered by the import_type column
 * @method     ChildFiles|null findOneByProductStatus(string $product_status) Return the first ChildFiles filtered by the product_status column
 * @method     ChildFiles|null findOneByPrefix(string $prefix) Return the first ChildFiles filtered by the prefix column
 * @method     ChildFiles|null findOneByMarge(double $marge) Return the first ChildFiles filtered by the marge column
 * @method     ChildFiles|null findOneByExchangeRate(double $exchange_rate) Return the first ChildFiles filtered by the exchange_rate column
 * @method     ChildFiles|null findOneByPreorder(int $preorder) Return the first ChildFiles filtered by the preorder column
 * @method     ChildFiles|null findOneByPreorderDeadline(string $preorder_deadline) Return the first ChildFiles filtered by the preorder_deadline column
 * @method     ChildFiles|null findOneByPreorderDelivery(string $preorder_delivery) Return the first ChildFiles filtered by the preorder_delivery column
 * @method     ChildFiles|null findOneByPreorderState(string $preorder_state) Return the first ChildFiles filtered by the preorder_state column
 * @method     ChildFiles|null findOneByConfigId(int $config_id) Return the first ChildFiles filtered by the config_id column
 * @method     ChildFiles|null findOneByCreatedAt(string $created_at) Return the first ChildFiles filtered by the created_at column
 * @method     ChildFiles|null findOneByUpdatedAt(string $updated_at) Return the first ChildFiles filtered by the updated_at column
 *
 * @method     ChildFiles requirePk($key, ?ConnectionInterface $con = null) Return the ChildFiles by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOne(?ConnectionInterface $con = null) Return the first ChildFiles matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFiles requireOneById(int $id) Return the first ChildFiles filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByFilename(string $filename) Return the first ChildFiles filtered by the filename column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPath(string $path) Return the first ChildFiles filtered by the path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByStatus(string $status) Return the first ChildFiles filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByImportType(string $import_type) Return the first ChildFiles filtered by the import_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByProductStatus(string $product_status) Return the first ChildFiles filtered by the product_status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPrefix(string $prefix) Return the first ChildFiles filtered by the prefix column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByMarge(double $marge) Return the first ChildFiles filtered by the marge column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByExchangeRate(double $exchange_rate) Return the first ChildFiles filtered by the exchange_rate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPreorder(int $preorder) Return the first ChildFiles filtered by the preorder column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPreorderDeadline(string $preorder_deadline) Return the first ChildFiles filtered by the preorder_deadline column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPreorderDelivery(string $preorder_delivery) Return the first ChildFiles filtered by the preorder_delivery column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPreorderState(string $preorder_state) Return the first ChildFiles filtered by the preorder_state column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByConfigId(int $config_id) Return the first ChildFiles filtered by the config_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByCreatedAt(string $created_at) Return the first ChildFiles filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByUpdatedAt(string $updated_at) Return the first ChildFiles filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFiles[]|Collection find(?ConnectionInterface $con = null) Return ChildFiles objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildFiles> find(?ConnectionInterface $con = null) Return ChildFiles objects based on current ModelCriteria
 *
 * @method     ChildFiles[]|Collection findById(int|array<int> $id) Return ChildFiles objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildFiles> findById(int|array<int> $id) Return ChildFiles objects filtered by the id column
 * @method     ChildFiles[]|Collection findByFilename(string|array<string> $filename) Return ChildFiles objects filtered by the filename column
 * @psalm-method Collection&\Traversable<ChildFiles> findByFilename(string|array<string> $filename) Return ChildFiles objects filtered by the filename column
 * @method     ChildFiles[]|Collection findByPath(string|array<string> $path) Return ChildFiles objects filtered by the path column
 * @psalm-method Collection&\Traversable<ChildFiles> findByPath(string|array<string> $path) Return ChildFiles objects filtered by the path column
 * @method     ChildFiles[]|Collection findByStatus(string|array<string> $status) Return ChildFiles objects filtered by the status column
 * @psalm-method Collection&\Traversable<ChildFiles> findByStatus(string|array<string> $status) Return ChildFiles objects filtered by the status column
 * @method     ChildFiles[]|Collection findByImportType(string|array<string> $import_type) Return ChildFiles objects filtered by the import_type column
 * @psalm-method Collection&\Traversable<ChildFiles> findByImportType(string|array<string> $import_type) Return ChildFiles objects filtered by the import_type column
 * @method     ChildFiles[]|Collection findByProductStatus(string|array<string> $product_status) Return ChildFiles objects filtered by the product_status column
 * @psalm-method Collection&\Traversable<ChildFiles> findByProductStatus(string|array<string> $product_status) Return ChildFiles objects filtered by the product_status column
 * @method     ChildFiles[]|Collection findByPrefix(string|array<string> $prefix) Return ChildFiles objects filtered by the prefix column
 * @psalm-method Collection&\Traversable<ChildFiles> findByPrefix(string|array<string> $prefix) Return ChildFiles objects filtered by the prefix column
 * @method     ChildFiles[]|Collection findByMarge(double|array<double> $marge) Return ChildFiles objects filtered by the marge column
 * @psalm-method Collection&\Traversable<ChildFiles> findByMarge(double|array<double> $marge) Return ChildFiles objects filtered by the marge column
 * @method     ChildFiles[]|Collection findByExchangeRate(double|array<double> $exchange_rate) Return ChildFiles objects filtered by the exchange_rate column
 * @psalm-method Collection&\Traversable<ChildFiles> findByExchangeRate(double|array<double> $exchange_rate) Return ChildFiles objects filtered by the exchange_rate column
 * @method     ChildFiles[]|Collection findByPreorder(int|array<int> $preorder) Return ChildFiles objects filtered by the preorder column
 * @psalm-method Collection&\Traversable<ChildFiles> findByPreorder(int|array<int> $preorder) Return ChildFiles objects filtered by the preorder column
 * @method     ChildFiles[]|Collection findByPreorderDeadline(string|array<string> $preorder_deadline) Return ChildFiles objects filtered by the preorder_deadline column
 * @psalm-method Collection&\Traversable<ChildFiles> findByPreorderDeadline(string|array<string> $preorder_deadline) Return ChildFiles objects filtered by the preorder_deadline column
 * @method     ChildFiles[]|Collection findByPreorderDelivery(string|array<string> $preorder_delivery) Return ChildFiles objects filtered by the preorder_delivery column
 * @psalm-method Collection&\Traversable<ChildFiles> findByPreorderDelivery(string|array<string> $preorder_delivery) Return ChildFiles objects filtered by the preorder_delivery column
 * @method     ChildFiles[]|Collection findByPreorderState(string|array<string> $preorder_state) Return ChildFiles objects filtered by the preorder_state column
 * @psalm-method Collection&\Traversable<ChildFiles> findByPreorderState(string|array<string> $preorder_state) Return ChildFiles objects filtered by the preorder_state column
 * @method     ChildFiles[]|Collection findByConfigId(int|array<int> $config_id) Return ChildFiles objects filtered by the config_id column
 * @psalm-method Collection&\Traversable<ChildFiles> findByConfigId(int|array<int> $config_id) Return ChildFiles objects filtered by the config_id column
 * @method     ChildFiles[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildFiles objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildFiles> findByCreatedAt(string|array<string> $created_at) Return ChildFiles objects filtered by the created_at column
 * @method     ChildFiles[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildFiles objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildFiles> findByUpdatedAt(string|array<string> $updated_at) Return ChildFiles objects filtered by the updated_at column
 *
 * @method     ChildFiles[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildFiles> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class FilesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Propel\Base\FilesQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Propel\\Files', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFilesQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFilesQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildFilesQuery) {
            return $criteria;
        }
        $query = new ChildFilesQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFiles|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FilesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FilesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFiles A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, filename, path, status, import_type, product_status, prefix, marge, exchange_rate, preorder, preorder_deadline, preorder_delivery, preorder_state, config_id, created_at, updated_at FROM files WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFiles $obj */
            $obj = new ChildFiles();
            $obj->hydrate($row);
            FilesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildFiles|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(FilesTableMap::COL_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(FilesTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the filename column
     *
     * Example usage:
     * <code>
     * $query->filterByFilename('fooValue');   // WHERE filename = 'fooValue'
     * $query->filterByFilename('%fooValue%', Criteria::LIKE); // WHERE filename LIKE '%fooValue%'
     * $query->filterByFilename(['foo', 'bar']); // WHERE filename IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $filename The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFilename($filename = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filename)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_FILENAME, $filename, $comparison);

        return $this;
    }

    /**
     * Filter the query on the path column
     *
     * Example usage:
     * <code>
     * $query->filterByPath('fooValue');   // WHERE path = 'fooValue'
     * $query->filterByPath('%fooValue%', Criteria::LIKE); // WHERE path LIKE '%fooValue%'
     * $query->filterByPath(['foo', 'bar']); // WHERE path IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $path The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPath($path = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PATH, $path, $comparison);

        return $this;
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
     * $query->filterByStatus('%fooValue%', Criteria::LIKE); // WHERE status LIKE '%fooValue%'
     * $query->filterByStatus(['foo', 'bar']); // WHERE status IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $status The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStatus($status = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($status)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_STATUS, $status, $comparison);

        return $this;
    }

    /**
     * Filter the query on the import_type column
     *
     * Example usage:
     * <code>
     * $query->filterByImportType('fooValue');   // WHERE import_type = 'fooValue'
     * $query->filterByImportType('%fooValue%', Criteria::LIKE); // WHERE import_type LIKE '%fooValue%'
     * $query->filterByImportType(['foo', 'bar']); // WHERE import_type IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $importType The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByImportType($importType = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($importType)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_IMPORT_TYPE, $importType, $comparison);

        return $this;
    }

    /**
     * Filter the query on the product_status column
     *
     * Example usage:
     * <code>
     * $query->filterByProductStatus('fooValue');   // WHERE product_status = 'fooValue'
     * $query->filterByProductStatus('%fooValue%', Criteria::LIKE); // WHERE product_status LIKE '%fooValue%'
     * $query->filterByProductStatus(['foo', 'bar']); // WHERE product_status IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $productStatus The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByProductStatus($productStatus = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($productStatus)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PRODUCT_STATUS, $productStatus, $comparison);

        return $this;
    }

    /**
     * Filter the query on the prefix column
     *
     * Example usage:
     * <code>
     * $query->filterByPrefix('fooValue');   // WHERE prefix = 'fooValue'
     * $query->filterByPrefix('%fooValue%', Criteria::LIKE); // WHERE prefix LIKE '%fooValue%'
     * $query->filterByPrefix(['foo', 'bar']); // WHERE prefix IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $prefix The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrefix($prefix = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prefix)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PREFIX, $prefix, $comparison);

        return $this;
    }

    /**
     * Filter the query on the marge column
     *
     * Example usage:
     * <code>
     * $query->filterByMarge(1234); // WHERE marge = 1234
     * $query->filterByMarge(array(12, 34)); // WHERE marge IN (12, 34)
     * $query->filterByMarge(array('min' => 12)); // WHERE marge > 12
     * </code>
     *
     * @param mixed $marge The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMarge($marge = null, ?string $comparison = null)
    {
        if (is_array($marge)) {
            $useMinMax = false;
            if (isset($marge['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_MARGE, $marge['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($marge['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_MARGE, $marge['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_MARGE, $marge, $comparison);

        return $this;
    }

    /**
     * Filter the query on the exchange_rate column
     *
     * Example usage:
     * <code>
     * $query->filterByExchangeRate(1234); // WHERE exchange_rate = 1234
     * $query->filterByExchangeRate(array(12, 34)); // WHERE exchange_rate IN (12, 34)
     * $query->filterByExchangeRate(array('min' => 12)); // WHERE exchange_rate > 12
     * </code>
     *
     * @param mixed $exchangeRate The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByExchangeRate($exchangeRate = null, ?string $comparison = null)
    {
        if (is_array($exchangeRate)) {
            $useMinMax = false;
            if (isset($exchangeRate['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_EXCHANGE_RATE, $exchangeRate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($exchangeRate['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_EXCHANGE_RATE, $exchangeRate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_EXCHANGE_RATE, $exchangeRate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the preorder column
     *
     * Example usage:
     * <code>
     * $query->filterByPreorder(1234); // WHERE preorder = 1234
     * $query->filterByPreorder(array(12, 34)); // WHERE preorder IN (12, 34)
     * $query->filterByPreorder(array('min' => 12)); // WHERE preorder > 12
     * </code>
     *
     * @param mixed $preorder The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPreorder($preorder = null, ?string $comparison = null)
    {
        if (is_array($preorder)) {
            $useMinMax = false;
            if (isset($preorder['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_PREORDER, $preorder['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($preorder['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_PREORDER, $preorder['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PREORDER, $preorder, $comparison);

        return $this;
    }

    /**
     * Filter the query on the preorder_deadline column
     *
     * Example usage:
     * <code>
     * $query->filterByPreorderDeadline('2011-03-14'); // WHERE preorder_deadline = '2011-03-14'
     * $query->filterByPreorderDeadline('now'); // WHERE preorder_deadline = '2011-03-14'
     * $query->filterByPreorderDeadline(array('max' => 'yesterday')); // WHERE preorder_deadline > '2011-03-13'
     * </code>
     *
     * @param mixed $preorderDeadline The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPreorderDeadline($preorderDeadline = null, ?string $comparison = null)
    {
        if (is_array($preorderDeadline)) {
            $useMinMax = false;
            if (isset($preorderDeadline['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_PREORDER_DEADLINE, $preorderDeadline['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($preorderDeadline['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_PREORDER_DEADLINE, $preorderDeadline['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PREORDER_DEADLINE, $preorderDeadline, $comparison);

        return $this;
    }

    /**
     * Filter the query on the preorder_delivery column
     *
     * Example usage:
     * <code>
     * $query->filterByPreorderDelivery('2011-03-14'); // WHERE preorder_delivery = '2011-03-14'
     * $query->filterByPreorderDelivery('now'); // WHERE preorder_delivery = '2011-03-14'
     * $query->filterByPreorderDelivery(array('max' => 'yesterday')); // WHERE preorder_delivery > '2011-03-13'
     * </code>
     *
     * @param mixed $preorderDelivery The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPreorderDelivery($preorderDelivery = null, ?string $comparison = null)
    {
        if (is_array($preorderDelivery)) {
            $useMinMax = false;
            if (isset($preorderDelivery['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_PREORDER_DELIVERY, $preorderDelivery['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($preorderDelivery['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_PREORDER_DELIVERY, $preorderDelivery['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PREORDER_DELIVERY, $preorderDelivery, $comparison);

        return $this;
    }

    /**
     * Filter the query on the preorder_state column
     *
     * Example usage:
     * <code>
     * $query->filterByPreorderState('fooValue');   // WHERE preorder_state = 'fooValue'
     * $query->filterByPreorderState('%fooValue%', Criteria::LIKE); // WHERE preorder_state LIKE '%fooValue%'
     * $query->filterByPreorderState(['foo', 'bar']); // WHERE preorder_state IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $preorderState The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPreorderState($preorderState = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($preorderState)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_PREORDER_STATE, $preorderState, $comparison);

        return $this;
    }

    /**
     * Filter the query on the config_id column
     *
     * Example usage:
     * <code>
     * $query->filterByConfigId(1234); // WHERE config_id = 1234
     * $query->filterByConfigId(array(12, 34)); // WHERE config_id IN (12, 34)
     * $query->filterByConfigId(array('min' => 12)); // WHERE config_id > 12
     * </code>
     *
     * @see       filterByConfig()
     *
     * @param mixed $configId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByConfigId($configId = null, ?string $comparison = null)
    {
        if (is_array($configId)) {
            $useMinMax = false;
            if (isset($configId['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_CONFIG_ID, $configId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($configId['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_CONFIG_ID, $configId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_CONFIG_ID, $configId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_CREATED_AT, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FilesTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Propel\Config object
     *
     * @param \Propel\Config|ObjectCollection $config The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByConfig($config, ?string $comparison = null)
    {
        if ($config instanceof \Propel\Config) {
            return $this
                ->addUsingAlias(FilesTableMap::COL_CONFIG_ID, $config->getId(), $comparison);
        } elseif ($config instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(FilesTableMap::COL_CONFIG_ID, $config->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByConfig() only accepts arguments of type \Propel\Config or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Config relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinConfig(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Config');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Config');
        }

        return $this;
    }

    /**
     * Use the Config relation Config object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Propel\ConfigQuery A secondary query class using the current class as primary query
     */
    public function useConfigQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinConfig($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Config', '\Propel\ConfigQuery');
    }

    /**
     * Use the Config relation Config object
     *
     * @param callable(\Propel\ConfigQuery):\Propel\ConfigQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withConfigQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useConfigQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Config table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Propel\ConfigQuery The inner query object of the EXISTS statement
     */
    public function useConfigExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Propel\ConfigQuery */
        $q = $this->useExistsQuery('Config', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Config table for a NOT EXISTS query.
     *
     * @see useConfigExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Propel\ConfigQuery The inner query object of the NOT EXISTS statement
     */
    public function useConfigNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Propel\ConfigQuery */
        $q = $this->useExistsQuery('Config', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Config table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Propel\ConfigQuery The inner query object of the IN statement
     */
    public function useInConfigQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Propel\ConfigQuery */
        $q = $this->useInQuery('Config', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Config table for a NOT IN query.
     *
     * @see useConfigInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Propel\ConfigQuery The inner query object of the NOT IN statement
     */
    public function useNotInConfigQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Propel\ConfigQuery */
        $q = $this->useInQuery('Config', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Propel\ImportHistory object
     *
     * @param \Propel\ImportHistory|ObjectCollection $importHistory the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByImportHistory($importHistory, ?string $comparison = null)
    {
        if ($importHistory instanceof \Propel\ImportHistory) {
            $this
                ->addUsingAlias(FilesTableMap::COL_ID, $importHistory->getFileId(), $comparison);

            return $this;
        } elseif ($importHistory instanceof ObjectCollection) {
            $this
                ->useImportHistoryQuery()
                ->filterByPrimaryKeys($importHistory->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByImportHistory() only accepts arguments of type \Propel\ImportHistory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ImportHistory relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinImportHistory(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ImportHistory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ImportHistory');
        }

        return $this;
    }

    /**
     * Use the ImportHistory relation ImportHistory object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Propel\ImportHistoryQuery A secondary query class using the current class as primary query
     */
    public function useImportHistoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinImportHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ImportHistory', '\Propel\ImportHistoryQuery');
    }

    /**
     * Use the ImportHistory relation ImportHistory object
     *
     * @param callable(\Propel\ImportHistoryQuery):\Propel\ImportHistoryQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withImportHistoryQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useImportHistoryQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ImportHistory table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Propel\ImportHistoryQuery The inner query object of the EXISTS statement
     */
    public function useImportHistoryExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Propel\ImportHistoryQuery */
        $q = $this->useExistsQuery('ImportHistory', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ImportHistory table for a NOT EXISTS query.
     *
     * @see useImportHistoryExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Propel\ImportHistoryQuery The inner query object of the NOT EXISTS statement
     */
    public function useImportHistoryNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Propel\ImportHistoryQuery */
        $q = $this->useExistsQuery('ImportHistory', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ImportHistory table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Propel\ImportHistoryQuery The inner query object of the IN statement
     */
    public function useInImportHistoryQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Propel\ImportHistoryQuery */
        $q = $this->useInQuery('ImportHistory', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ImportHistory table for a NOT IN query.
     *
     * @see useImportHistoryInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Propel\ImportHistoryQuery The inner query object of the NOT IN statement
     */
    public function useNotInImportHistoryQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Propel\ImportHistoryQuery */
        $q = $this->useInQuery('ImportHistory', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildFiles $files Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($files = null)
    {
        if ($files) {
            $this->addUsingAlias(FilesTableMap::COL_ID, $files->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the files table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FilesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FilesTableMap::clearInstancePool();
            FilesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FilesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FilesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FilesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FilesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
