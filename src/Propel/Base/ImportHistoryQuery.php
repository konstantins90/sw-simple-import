<?php

namespace Propel\Base;

use \Exception;
use \PDO;
use Propel\ImportHistory as ChildImportHistory;
use Propel\ImportHistoryQuery as ChildImportHistoryQuery;
use Propel\Map\ImportHistoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `import_history` table.
 *
 * @method     ChildImportHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildImportHistoryQuery orderByFileId($order = Criteria::ASC) Order by the file_id column
 * @method     ChildImportHistoryQuery orderByImportedAt($order = Criteria::ASC) Order by the imported_at column
 * @method     ChildImportHistoryQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildImportHistoryQuery orderByCountImportedProducts($order = Criteria::ASC) Order by the count_imported_products column
 * @method     ChildImportHistoryQuery orderByCountErrors($order = Criteria::ASC) Order by the count_errors column
 *
 * @method     ChildImportHistoryQuery groupById() Group by the id column
 * @method     ChildImportHistoryQuery groupByFileId() Group by the file_id column
 * @method     ChildImportHistoryQuery groupByImportedAt() Group by the imported_at column
 * @method     ChildImportHistoryQuery groupByStatus() Group by the status column
 * @method     ChildImportHistoryQuery groupByCountImportedProducts() Group by the count_imported_products column
 * @method     ChildImportHistoryQuery groupByCountErrors() Group by the count_errors column
 *
 * @method     ChildImportHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildImportHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildImportHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildImportHistoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildImportHistoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildImportHistoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildImportHistoryQuery leftJoinFiles($relationAlias = null) Adds a LEFT JOIN clause to the query using the Files relation
 * @method     ChildImportHistoryQuery rightJoinFiles($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Files relation
 * @method     ChildImportHistoryQuery innerJoinFiles($relationAlias = null) Adds a INNER JOIN clause to the query using the Files relation
 *
 * @method     ChildImportHistoryQuery joinWithFiles($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Files relation
 *
 * @method     ChildImportHistoryQuery leftJoinWithFiles() Adds a LEFT JOIN clause and with to the query using the Files relation
 * @method     ChildImportHistoryQuery rightJoinWithFiles() Adds a RIGHT JOIN clause and with to the query using the Files relation
 * @method     ChildImportHistoryQuery innerJoinWithFiles() Adds a INNER JOIN clause and with to the query using the Files relation
 *
 * @method     \Propel\FilesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildImportHistory|null findOne(?ConnectionInterface $con = null) Return the first ChildImportHistory matching the query
 * @method     ChildImportHistory findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildImportHistory matching the query, or a new ChildImportHistory object populated from the query conditions when no match is found
 *
 * @method     ChildImportHistory|null findOneById(int $id) Return the first ChildImportHistory filtered by the id column
 * @method     ChildImportHistory|null findOneByFileId(int $file_id) Return the first ChildImportHistory filtered by the file_id column
 * @method     ChildImportHistory|null findOneByImportedAt(string $imported_at) Return the first ChildImportHistory filtered by the imported_at column
 * @method     ChildImportHistory|null findOneByStatus(string $status) Return the first ChildImportHistory filtered by the status column
 * @method     ChildImportHistory|null findOneByCountImportedProducts(int $count_imported_products) Return the first ChildImportHistory filtered by the count_imported_products column
 * @method     ChildImportHistory|null findOneByCountErrors(int $count_errors) Return the first ChildImportHistory filtered by the count_errors column
 *
 * @method     ChildImportHistory requirePk($key, ?ConnectionInterface $con = null) Return the ChildImportHistory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImportHistory requireOne(?ConnectionInterface $con = null) Return the first ChildImportHistory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildImportHistory requireOneById(int $id) Return the first ChildImportHistory filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImportHistory requireOneByFileId(int $file_id) Return the first ChildImportHistory filtered by the file_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImportHistory requireOneByImportedAt(string $imported_at) Return the first ChildImportHistory filtered by the imported_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImportHistory requireOneByStatus(string $status) Return the first ChildImportHistory filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImportHistory requireOneByCountImportedProducts(int $count_imported_products) Return the first ChildImportHistory filtered by the count_imported_products column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImportHistory requireOneByCountErrors(int $count_errors) Return the first ChildImportHistory filtered by the count_errors column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildImportHistory[]|Collection find(?ConnectionInterface $con = null) Return ChildImportHistory objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildImportHistory> find(?ConnectionInterface $con = null) Return ChildImportHistory objects based on current ModelCriteria
 *
 * @method     ChildImportHistory[]|Collection findById(int|array<int> $id) Return ChildImportHistory objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildImportHistory> findById(int|array<int> $id) Return ChildImportHistory objects filtered by the id column
 * @method     ChildImportHistory[]|Collection findByFileId(int|array<int> $file_id) Return ChildImportHistory objects filtered by the file_id column
 * @psalm-method Collection&\Traversable<ChildImportHistory> findByFileId(int|array<int> $file_id) Return ChildImportHistory objects filtered by the file_id column
 * @method     ChildImportHistory[]|Collection findByImportedAt(string|array<string> $imported_at) Return ChildImportHistory objects filtered by the imported_at column
 * @psalm-method Collection&\Traversable<ChildImportHistory> findByImportedAt(string|array<string> $imported_at) Return ChildImportHistory objects filtered by the imported_at column
 * @method     ChildImportHistory[]|Collection findByStatus(string|array<string> $status) Return ChildImportHistory objects filtered by the status column
 * @psalm-method Collection&\Traversable<ChildImportHistory> findByStatus(string|array<string> $status) Return ChildImportHistory objects filtered by the status column
 * @method     ChildImportHistory[]|Collection findByCountImportedProducts(int|array<int> $count_imported_products) Return ChildImportHistory objects filtered by the count_imported_products column
 * @psalm-method Collection&\Traversable<ChildImportHistory> findByCountImportedProducts(int|array<int> $count_imported_products) Return ChildImportHistory objects filtered by the count_imported_products column
 * @method     ChildImportHistory[]|Collection findByCountErrors(int|array<int> $count_errors) Return ChildImportHistory objects filtered by the count_errors column
 * @psalm-method Collection&\Traversable<ChildImportHistory> findByCountErrors(int|array<int> $count_errors) Return ChildImportHistory objects filtered by the count_errors column
 *
 * @method     ChildImportHistory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildImportHistory> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ImportHistoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Propel\Base\ImportHistoryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Propel\\ImportHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildImportHistoryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildImportHistoryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildImportHistoryQuery) {
            return $criteria;
        }
        $query = new ChildImportHistoryQuery();
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
     * @return ChildImportHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ImportHistoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ImportHistoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildImportHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, file_id, imported_at, status, count_imported_products, count_errors FROM import_history WHERE id = :p0';
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
            /** @var ChildImportHistory $obj */
            $obj = new ChildImportHistory();
            $obj->hydrate($row);
            ImportHistoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildImportHistory|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(ImportHistoryTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(ImportHistoryTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(ImportHistoryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ImportHistoryTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileId(1234); // WHERE file_id = 1234
     * $query->filterByFileId(array(12, 34)); // WHERE file_id IN (12, 34)
     * $query->filterByFileId(array('min' => 12)); // WHERE file_id > 12
     * </code>
     *
     * @see       filterByFiles()
     *
     * @param mixed $fileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFileId($fileId = null, ?string $comparison = null)
    {
        if (is_array($fileId)) {
            $useMinMax = false;
            if (isset($fileId['min'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_FILE_ID, $fileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileId['max'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_FILE_ID, $fileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ImportHistoryTableMap::COL_FILE_ID, $fileId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the imported_at column
     *
     * Example usage:
     * <code>
     * $query->filterByImportedAt('2011-03-14'); // WHERE imported_at = '2011-03-14'
     * $query->filterByImportedAt('now'); // WHERE imported_at = '2011-03-14'
     * $query->filterByImportedAt(array('max' => 'yesterday')); // WHERE imported_at > '2011-03-13'
     * </code>
     *
     * @param mixed $importedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByImportedAt($importedAt = null, ?string $comparison = null)
    {
        if (is_array($importedAt)) {
            $useMinMax = false;
            if (isset($importedAt['min'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_IMPORTED_AT, $importedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($importedAt['max'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_IMPORTED_AT, $importedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ImportHistoryTableMap::COL_IMPORTED_AT, $importedAt, $comparison);

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

        $this->addUsingAlias(ImportHistoryTableMap::COL_STATUS, $status, $comparison);

        return $this;
    }

    /**
     * Filter the query on the count_imported_products column
     *
     * Example usage:
     * <code>
     * $query->filterByCountImportedProducts(1234); // WHERE count_imported_products = 1234
     * $query->filterByCountImportedProducts(array(12, 34)); // WHERE count_imported_products IN (12, 34)
     * $query->filterByCountImportedProducts(array('min' => 12)); // WHERE count_imported_products > 12
     * </code>
     *
     * @param mixed $countImportedProducts The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountImportedProducts($countImportedProducts = null, ?string $comparison = null)
    {
        if (is_array($countImportedProducts)) {
            $useMinMax = false;
            if (isset($countImportedProducts['min'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS, $countImportedProducts['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countImportedProducts['max'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS, $countImportedProducts['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ImportHistoryTableMap::COL_COUNT_IMPORTED_PRODUCTS, $countImportedProducts, $comparison);

        return $this;
    }

    /**
     * Filter the query on the count_errors column
     *
     * Example usage:
     * <code>
     * $query->filterByCountErrors(1234); // WHERE count_errors = 1234
     * $query->filterByCountErrors(array(12, 34)); // WHERE count_errors IN (12, 34)
     * $query->filterByCountErrors(array('min' => 12)); // WHERE count_errors > 12
     * </code>
     *
     * @param mixed $countErrors The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountErrors($countErrors = null, ?string $comparison = null)
    {
        if (is_array($countErrors)) {
            $useMinMax = false;
            if (isset($countErrors['min'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_COUNT_ERRORS, $countErrors['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countErrors['max'])) {
                $this->addUsingAlias(ImportHistoryTableMap::COL_COUNT_ERRORS, $countErrors['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ImportHistoryTableMap::COL_COUNT_ERRORS, $countErrors, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Propel\Files object
     *
     * @param \Propel\Files|ObjectCollection $files The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFiles($files, ?string $comparison = null)
    {
        if ($files instanceof \Propel\Files) {
            return $this
                ->addUsingAlias(ImportHistoryTableMap::COL_FILE_ID, $files->getId(), $comparison);
        } elseif ($files instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ImportHistoryTableMap::COL_FILE_ID, $files->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByFiles() only accepts arguments of type \Propel\Files or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Files relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinFiles(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Files');

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
            $this->addJoinObject($join, 'Files');
        }

        return $this;
    }

    /**
     * Use the Files relation Files object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Propel\FilesQuery A secondary query class using the current class as primary query
     */
    public function useFilesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFiles($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Files', '\Propel\FilesQuery');
    }

    /**
     * Use the Files relation Files object
     *
     * @param callable(\Propel\FilesQuery):\Propel\FilesQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withFilesQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useFilesQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Files table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Propel\FilesQuery The inner query object of the EXISTS statement
     */
    public function useFilesExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Propel\FilesQuery */
        $q = $this->useExistsQuery('Files', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Files table for a NOT EXISTS query.
     *
     * @see useFilesExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Propel\FilesQuery The inner query object of the NOT EXISTS statement
     */
    public function useFilesNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Propel\FilesQuery */
        $q = $this->useExistsQuery('Files', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Files table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Propel\FilesQuery The inner query object of the IN statement
     */
    public function useInFilesQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Propel\FilesQuery */
        $q = $this->useInQuery('Files', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Files table for a NOT IN query.
     *
     * @see useFilesInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Propel\FilesQuery The inner query object of the NOT IN statement
     */
    public function useNotInFilesQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Propel\FilesQuery */
        $q = $this->useInQuery('Files', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildImportHistory $importHistory Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($importHistory = null)
    {
        if ($importHistory) {
            $this->addUsingAlias(ImportHistoryTableMap::COL_ID, $importHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the import_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImportHistoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ImportHistoryTableMap::clearInstancePool();
            ImportHistoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ImportHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ImportHistoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ImportHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ImportHistoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
