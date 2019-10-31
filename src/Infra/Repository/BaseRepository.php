<?php

namespace App\Infra\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Rb\Specification\Doctrine\Query\Select;
use Rb\Specification\Doctrine\Specification;
use Rb\Specification\Doctrine\SpecificationRepositoryTrait;

class BaseRepository extends ServiceEntityRepository
{
    use SpecificationRepositoryTrait;

    /**
     * @param Specification $specification
     * @param int|null $limit
     * @param int|null $offset
     * @return \stdClass[]
     * @throws \Rb\Specification\Doctrine\Exception\LogicException
     */
    public function findAllBySpecification(Specification $specification, $limit = null, $offset = null): array
    {
        $query = $this->match($specification);

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }
        if ($offset !== null) {
            $query->setFirstResult($offset);
        }

        $dql = $query->getDQL();
        $sql = $query->getSQL();

        return $query->getResult();
    }

    /**
     * @param Specification $specification
     * @param int|null $limit
     * @param int|null $offset
     * @return array[]
     * @throws \Rb\Specification\Doctrine\Exception\LogicException
     */
    public function findArrayResultBySpecification(Specification $specification, $limit = null, $offset = null): array
    {
        $query = $this->match($specification);

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }
        if ($offset !== null) {
            $query->setFirstResult($offset);
        }

        $dql = $query->getDQL();
        $sql = $query->getSQL();

        return $query->getArrayResult();
    }

    /**
     * @param Specification $specification
     * @param int|null $limit
     * @param int|null $offset
     * @param string $field
     * @return \stdClass[]
     * @throws \Rb\Specification\Doctrine\Exception\LogicException
     */
    public function findMapBySpecification(
        Specification $specification,
        ?int $limit = null,
        ?int $offset = null,
        string $field = 'id'
    ) {
        $entities = $this->findAllBySpecification($specification, $limit, $offset);

        $map = [];
        foreach ($entities as $entity) {
            $method = 'get' . ucfirst($field);
            $map[$entity->$method] = $entity;
        }

        return $map;
    }

    /**
     * @param Specification $specification
     * @param string $columnName
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws \Rb\Specification\Doctrine\Exception\LogicException
     */
    public function findColumnBySpecification(Specification $specification, string $columnName, $limit = null, $offset = null): array
    {
        $rows = $this->findArrayResultBySpecification($specification, $limit, $offset);

        $columnValues = [];
        foreach ($rows as $row) {
            if (isset($row[$columnName])) {
                $columnValues[] = $row[$columnName];
            }
        }

        return $columnValues;
    }



    /**
     * @param Specification $specification
     * @return \stdClass|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Rb\Specification\Doctrine\Exception\LogicException
     */
    public function findOneBySpecification(Specification $specification)
    {
        $query = $this->match($specification);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Specification $specification
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Rb\Specification\Doctrine\Exception\LogicException
     */
    public function findCountBySpecification(Specification $specification): int
    {
        $query = $this->match($specification);
        $paginator = new Paginator($query);

        try {
            $count = $paginator->count();
            return $count;
        } catch (\Exception $e) {}

        //Доктриновский пагинатор не со всеми запросами может совладать.
        //Если в запросе нет сущностей, а просто набор полей - то он просто падает
        //подстраховываемся самодельной реализацией, которая плохо работает с джойнами,
        //в отличии от стандартного пагинатора
        $specification[] = new Select('COUNT(' .
            $this->dqlAlias . '.id)', Select::SELECT);
        $query = $this->match($specification);

        return $query->getSingleScalarResult();
    }

}
