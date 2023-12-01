<?php

namespace App\AjaxifySearch\Annotation;

/**
 * Annotation to automitize ajax search of entities
 *
 * @author Romaric TOHOUNKPIN <tohounkpinr@gmail.com>
 *
 * @Annotation
 * @Target("CLASS")
 */
class AjaxifySearch
{
    private $alias;

    private $itemTemplate;

    private $relations;

    private $searchables;

    private $orderBy;

    /**
     * Constructor.
     *
     * @param array $data An array of key/value parameters.
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        if (isset($data['value'])) {

            if (is_string($data['value'])) {

                $data['itemTemplate'] = $data['value'];
                unset($data['value']);

            } else if (is_array($data['value'])) {

                $data = $data['value'];
            }
        }

        foreach ($data as $key => $value) {

            $method = 'set'.str_replace('_', '', $key);

            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, get_class($this)));
            }

            $this->$method($value);
        }
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getItemTemplate()
    {
        return $this->itemTemplate;
    }

    public function setItemTemplate($itemTemplate)
    {
        $this->itemTemplate = $itemTemplate;
    }

    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function setSearchables($searchables)
    {
        $this->searchables = $searchables;
    }

    public function getSearchables()
    {
        return $this->searchables;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }
}