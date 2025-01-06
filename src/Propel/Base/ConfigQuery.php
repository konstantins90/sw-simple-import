<?php

namespace Propel\Base;

use \Exception;
use \PDO;
use Propel\Config as ChildConfig;
use Propel\ConfigQuery as ChildConfigQuery;
use Propel\Map\ConfigTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `config` table.
 *
 * @method     ChildConfigQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildConfigQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildConfigQuery orderByPrefix($order = Criteria::ASC) Order by the prefix column
 * @method     ChildConfigQuery orderByMarge($order = Criteria::ASC) Order by the marge column
 * @method     ChildConfigQuery orderByMapping($order = Criteria::ASC) Order by the mapping column
 * @method     ChildConfigQuery orderByMappingProperties($order = Criteria::ASC) Order by the mapping_properties column
 * @method     ChildConfigQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildConfigQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildConfigQuery groupById() Group by the id column
 * @method     ChildConfigQuery groupByName() Group by the name column
 * @method     ChildConfigQuery groupByPrefix() Group by the prefix column
 * @method     ChildConfigQuery groupByMarge() Group by the marge column
 * @method     ChildConfigQuery groupByMapping() Group by the mapping column
 * @method     ChildConfigQuery groupByMappingProperties() Group by the mapping_properties column
 * @method     ChildConfigQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildConfigQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildConfigQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildConfigQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildConfigQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildConfigQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildConfigQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildConfigQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildConfigQuery leftJoinFiles($relationAlias = null) Adds a LEFT JOIN clause to the query using the Files relation
 * @method     ChildConfigQuery rightJoinFiles($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Files relation
 * @method     ChildConfigQuery innerJoinFiles($relationAlias = null) Adds a INNER JOIN clause to the query using the Files relation
 *
 * @method     ChildConfigQuery joinWithFiles($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Files relation
 *
 * @method     ChildConfigQuery leftJoinWithFiles() Adds a LEFT JOIN clause and with to the query using the Files relation
 * @method     ChildConfigQuery rightJoinWithFiles() Adds a RIGHT JOIN clause and with to the query using the Files relation
 * @method     ChildConfigQuery innerJoinWithFiles() Adds a INNER JOIN clause and with to the query using the Files relation
 *
 * @method     \Propel\FilesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildConfig|null findOne(?ConnectionInterface $con = null) Return the first ChildConfig matching the query
 * @method     ChildConfig findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildConfig matching the query, or a new ChildConfig object populated from the query conditions when no match is found
 *
 * @method     ChildConfig|null findOneById(int $id) Return the first ChildConfig filtered by the id column
 * @method     ChildConfig|null findOneByName(string $name) Return the first ChildConfig filtered by the name column
 * @method     ChildConfig|null findOneByPrefix(string $prefix) Return the first ChildConfig filtered by the prefix column
 * @method     ChildConfig|null findOneByMarge(double $marge) Return the first ChildConfig filtered by the marge column
 * @method     ChildConfig|null findOneByMapping(string $mapping) Return the first ChildConfig filtered by the mapping column
 * @method     ChildConfig|null findOneByMappingProperties(string $mapping_properties) Return the first ChildConfig filtered by the mapping_properties column
 * @method     ChildConfig|null findOneByCreatedAt(string $created_at) Return the first ChildConfig filtered by the created_at column
 * @method     ChildConfig|null findOneByUpdatedAt(string $updated_at) Return the first ChildConfig filtered by the updated_at column
 *
 * @method     ChildConfig requirePk($key, ?ConnectionInterface $con = null) Return the ChildConfig by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOne(?ConnectionInterface $con = null) Return the first ChildConfig matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConfig requireOneById(int $id) Return the first ChildConfig filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByName(string $name) Return the first ChildConfig filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByPrefix(string $prefix) Return the first ChildConfig filtered by the prefix column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByMarge(double $marge) Return the first ChildConfig filtered by the marge column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByMapping(string $mapping) Return the first ChildConfig filtered by the mapping column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByMappingProperties(string $mapping_properties) Return the first ChildConfig filtered by the mapping_properties column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByCreatedAt(string $created_at) Return the first ChildConfig filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConfig requireOneByUpdatedAt(string $updated_at) Return the first ChildConfig filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConfig[]|Collection find(?ConnectionInterface $con = null) Return ChildConfig objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildConfig> find(?ConnectionInterface $con = null) Return ChildConfig objects based on current ModelCriteria
 *
 * @method     ChildConfig[]|Collection findById(int|array<int> $id) Return ChildConfig objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildConfig> findById(int|array<int> $id) Return ChildConfig objects filtered by the id column
 * @method     ChildConfig[]|Collection findByName(string|array<string> $name) Return ChildConfig objects filtered by the name column
 * @psalm-method Collection&\Traversable<ChildConfig> findByName(string|array<string> $name) Return ChildConfig objects filtered by the name column
 * @method     ChildConfig[]|Collection findByPrefix(string|array<string> $prefix) Return ChildConfig objects filtered by the prefix column
 * @psalm-method Collection&\Traversable<ChildConfig> findByPrefix(string|array<string> $prefix) Return ChildConfig objects filtered by the prefix column
 * @method     ChildConfig[]|Collection findByMarge(double|array<double> $marge) Return ChildConfig objects filtered by the marge column
 * @psalm-method Collection&\Traversable<ChildConfig> findByMarge(double|array<double> $marge) Return ChildConfig objects filtered by the marge column
 * @method     ChildConfig[]|Collection findByMapping(string|array<string> $mapping) Return ChildConfig objects filtered by the mapping column
 * @psalm-method Collection&\Traversable<ChildConfig> findByMapping(string|array<string> $mapping) Return ChildConfig objects filtered by the mapping column
 * @method     ChildConfig[]|Collection findByMappingProperties(string|array<string> $mapping_properties) Return ChildConfig objects filtered by the mapping_properties column
 * @psalm-method Collection&\Traversable<ChildConfig> findByMappingProperties(string|array<string> $mapping_properties) Return ChildConfig objects filtered by the mapping_properties column
 * @method     ChildConfig[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildConfig objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildConfig> findByCreatedAt(string|array<string> $created_at) Return ChildConfig objects filtered by the created_at column
 * @method     ChildConfig[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildConfig objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildConfig> findByUpdatedAt(string|array<string> $updated_at) Return ChildConfig objects filtered by the updated_at column
 *
 * @method     ChildConfig[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildConfig> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ConfigQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Propel\Base\ConfigQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Propel\\Config', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildConfigQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildConfigQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildConfigQuery) {
            return $criteria;
        }
        $query = new ChildConfigQuery();
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
     * @return ChildConfig|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConfigTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ConfigTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildConfig A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, prefix, marge, mapping, mapping_properties, created_at, updated_at FROM config WHERE id = :p0';
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
            /** @var ChildConfig $obj */
            $obj = new ChildConfig();
            $obj->hydrate($row);
            ConfigTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildConfig|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(ConfigTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(ConfigTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(ConfigTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ConfigTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_NAME, $name, $comparison);

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

        $this->addUsingAlias(ConfigTableMap::COL_PREFIX, $prefix, $comparison);

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
                $this->addUsingAlias(ConfigTableMap::COL_MARGE, $marge['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($marge['max'])) {
                $this->addUsingAlias(ConfigTableMap::COL_MARGE, $marge['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_MARGE, $marge, $comparison);

        return $this;
    }

    /**
     * Filter the query on the mapping column
     *
     * Example usage:
     * <code>
     * $query->filterByMapping('fooValue');   // WHERE mapping = 'fooValue'
     * $query->filterByMapping('%fooValue%', Criteria::LIKE); // WHERE mapping LIKE '%fooValue%'
     * $query->filterByMapping(['foo', 'bar']); // WHERE mapping IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $mapping The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMapping($mapping = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mapping)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_MAPPING, $mapping, $comparison);

        return $this;
    }

    /**
     * Filter the query on the mapping_properties column
     *
     * Example usage:
     * <code>
     * $query->filterByMappingProperties('fooValue');   // WHERE mapping_properties = 'fooValue'
     * $query->filterByMappingProperties('%fooValue%', Criteria::LIKE); // WHERE mapping_properties LIKE '%fooValue%'
     * $query->filterByMappingProperties(['foo', 'bar']); // WHERE mapping_properties IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $mappingProperties The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMappingProperties($mappingProperties = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mappingProperties)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_MAPPING_PROPERTIES, $mappingProperties, $comparison);

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
                $this->addUsingAlias(ConfigTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ConfigTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_CREATED_AT, $createdAt, $comparison);

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
                $this->addUsingAlias(ConfigTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ConfigTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ConfigTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Propel\Files object
     *
     * @param \Propel\Files|ObjectCollection $files the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFiles($files, ?string $comparison = null)
    {
        if ($files instanceof \Propel\Files) {
            $this
                ->addUsingAlias(ConfigTableMap::COL_ID, $files->getConfigId(), $comparison);

            return $this;
        } elseif ($files instanceof ObjectCollection) {
            $this
                ->useFilesQuery()
                ->filterByPrimaryKeys($files->getPrimaryKeys())
                ->endUse();

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
     * @param ChildConfig $config Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($config = null)
    {
        if ($config) {
            $this->addUsingAlias(ConfigTableMap::COL_ID, $config->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the config table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConfigTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ConfigTableMap::clearInstancePool();
            ConfigTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ConfigTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ConfigTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ConfigTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ConfigTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
