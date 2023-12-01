<?php

namespace App\AjaxifySearch;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class AjaxifySearchController
{
    private $annotationClass = 'App\\AjaxifySearch\\Annotation\\AjaxifySearch';
    private $selectRelations = false;
    private $relationsJoined = false;
    private $ajaxifySearch;
    public static $compteur = 0;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /** @var Environment $templating */
    private $templating;

    public function __construct(EntityManagerInterface $entityManager,Environment $templating)
    {
        $this->entityManager = $entityManager;
        $this->templating = $templating;
    }

    public function count(Request $request)
    {
        $count = $this->executeCount($request);
        return new Response($count);
    }

    public function search(Request $request)
    {
        $entityClass = $request->query->get('entity');
        $perpage = intval($request->query->get('perpage', 10));
        $page = intval($request->query->get('page', 1));

        $queryBuilder = $this->createSearchQuery($request);

        $entities = $queryBuilder->getQuery()->getResult();

        $alias = $this->ajaxifySearch->getAlias();
        $alias = $alias == null ? strtolower((new \ReflectionClass($entityClass))->getShortName()) : $alias;

        $data = $this->getCriteria($request);
        $views = '';

        foreach($entities as $i => $entity) {
            $data[$alias] = $entity;
            $data['index'] = ($page - 1) * $perpage + $i + 1;

            $views .= $this->templating->render($this->ajaxifySearch->getItemTemplate(), $data);
        }

        return new JsonResponse([
            'total'     => $this->executeCount($request),
            'views'     => $views
        ]);
    }

    private function parseAnnotation($entityClass)
    {
        if (!class_exists($entityClass)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $entityClass));
        }

        $reader = new  AnnotationReader();

        $annotation = $reader->getClassAnnotation(new \ReflectionClass($entityClass), $this->annotationClass);

        if(!$annotation) {
            throw new \Exception(sprintf("Entity class %s does not have required annotation AjaxifySearch", $entityClass));
        }

        $this->ajaxifySearch = $annotation;
    }

    private function getQueryBuilder(Request $request)
    {
        $entityClass = $request->query->get('entity');
        $search = $request->query->get('search', '');

        $this->parseAnnotation($entityClass);

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($entityClass, 'Entity');

        if (!empty($search) && !empty($this->ajaxifySearch->getSearchables())) {
            $this->joinRelations($queryBuilder);
        }

        $this->applySearch($queryBuilder, $search);
        $this->applyCriteria($queryBuilder, $this->getCriteria($request));

        return $queryBuilder;
    }

    private function executeCount(Request $request)
    {
        $queryBuilder = $this->getQueryBuilder($request);
        $queryBuilder->select('COUNT(Entity)');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    private function createSearchQuery(Request $request)
    {
        $perpage = intval($request->query->get('perpage', 10));
        $page = intval($request->query->get('page', 1));
       //$poinVente = $request->getSession()->get('pointVente');
        $this->selectRelations = true;

        $queryBuilder = $this->getQueryBuilder($request);
        $queryBuilder->select('Entity');
        $queryBuilder->andWhere('Entity.deletedAt is NULL');
        //$queryBuilder->andWhere('Entity.pointVente = :pointVente OR Entity.pointVente IS NULL')
        //>setParameter('pointVente',$poinVente->getId());

        $this->applyOrderBy($queryBuilder);

        $queryBuilder->setMaxResults($perpage);
        $queryBuilder->setFirstResult(($page - 1) * $perpage);

        return $queryBuilder;
    }

    private function getCriteria(Request $request)
    {
        $criteria = $request->query->all();
        $criteria = array_diff_key($criteria, array_flip(array('entity', 'perpage', 'page', 'search')));

        return $criteria;
    }

    private function joinRelations($queryBuilder)
    {
        $relations = $this->ajaxifySearch->getRelations();
        if (is_array($relations)) {
            foreach ($relations as $relation) {
                $queryBuilder->leftJoin('Entity.' . $relation, $relation);
                $name = $this->allRelations($queryBuilder, $relation);
                if ($this->selectRelations) {
                    $queryBuilder->addSelect($relation);
                }
            }
        }
    }

    private function allRelations($queryBuilder, $relation, $type = null, $name = null) : ?string
    {
        self::$compteur++;
        $name .= ((!empty($name)) ? '.' : '') . $relation;
        if($type !== null)
        {
            $classname = $type;
        }
        else
            $classname = "App\\Entity\\" . ucfirst($relation);

        $class = new $classname();
        $reflect = new \ReflectionObject($class);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        foreach ($props as $prop) {
            $method = "get" . ucfirst($prop->getName());
            $method_reflect = new \ReflectionMethod($class, $method);
            $type = $method_reflect->getReturnType();

            if($type !== null && str_contains($type->getName(), 'Entity'))
            {
                $tabs = explode('\\', $type->getName());
                $queryBuilder->leftJoin($relation . '.' . $prop->getName(), $prop->getName());
                $queryBuilder->addSelect($prop->getName());
                $this->allRelations($queryBuilder, $prop->getName(), $type->getName(), $name);
            }
        }
        return $name;
    }

    private function applySearch($queryBuilder, $search)
    {
        $searchables = $this->ajaxifySearch->getSearchables();

        if (!empty($search) && !empty($searchables)) {
            $keywords = explode(' ', $search);
            foreach ($searchables as $searchable) {
                $property = strpos($searchable, '.') !== false ? $searchable : 'Entity.' . $searchable;

                foreach ($keywords as $i => $keyword) {
                    $parameter = str_replace('.', '_', $property) . '_' . $i;

                    $queryBuilder->orWhere(sprintf('%s LIKE :%s', $property, $parameter));
                    $queryBuilder->setParameter($parameter, '%' . $keyword . '%');
                }
            }
        }
    }

    private function applyCriteria($queryBuilder, $criteria)
    {
        foreach ($criteria as $property => $value) {
            $name = strpos($property, '.') !== false ? $property : 'Entity.' . $property;
            if ($value === null) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif ($value !== '') {
                $parameter = str_replace('.', '_', $name);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':'.$parameter))
                    ->setParameter($parameter, $value)
                ;
            }
        }
    }

    private function applyOrderBy($queryBuilder)
    {
        $orderBy = $this->ajaxifySearch->getOrderBy();
        if (is_array($orderBy)) {
            foreach($orderBy as $property => $order) {
                $name = strpos($property, '.') !== false ? $property : 'Entity.' . $property;
                $queryBuilder->orderBy($name, $order);
            }
        }
    }
}