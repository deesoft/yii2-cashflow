<?php

namespace rest\classes;

use Yii;
use yii\base\Model;
use yii\db\QueryInterface;
use yii\web\Request;
use yii\web\Response;
use yii\base\ActionFilter;
use yii\data\DataProviderInterface;
use common\helpers\Serializer;

/**
 * Description of SerializeFilter
 *
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SerializeFilter extends ActionFilter
{
    /**
     *
     * @var Request
     */
    public $request;
    /**
     *
     * @var Response
     */
    public $response;
    /**
     * @var string the name of the HTTP header containing the information about total number of data items.
     * This is used when serving a resource collection with pagination.
     */
    public $totalCountHeader = 'X-Pagination-Total-Count';
    /**
     * @var string the name of the HTTP header containing the information about total number of pages of data.
     * This is used when serving a resource collection with pagination.
     */
    public $pageCountHeader = 'X-Pagination-Page-Count';
    /**
     * @var string the name of the HTTP header containing the information about the current page number (1-based).
     * This is used when serving a resource collection with pagination.
     */
    public $currentPageHeader = 'X-Pagination-Current-Page';
    /**
     * @var string the name of the HTTP header containing the information about the number of data items in each page.
     * This is used when serving a resource collection with pagination.
     */
    public $perPageHeader = 'X-Pagination-Per-Page';
    /**
     * @var string the name of the envelope (e.g. `items`) for returning the resource objects in a collection.
     * This is used when serving a resource collection. When this is set and pagination is enabled, the serializer
     * will return a collection in the following format:
     *
     * ```php
     * [
     *     'items' => [...],  // assuming collectionEnvelope is "items"
     *     '_links' => {  // pagination links as returned by Pagination::getLinks()
     *         'self' => '...',
     *         'next' => '...',
     *         'last' => '...',
     *     },
     *     '_meta' => {  // meta information as returned by Pagination::toArray()
     *         'totalCount' => 100,
     *         'pageCount' => 5,
     *         'currentPage' => 1,
     *         'perPage' => 20,
     *     },
     * ]
     * ```
     *
     * If this property is not set, the resource arrays will be directly returned without using envelope.
     * The pagination information as shown in `_links` and `_meta` can be accessed from the response HTTP headers.
     */
    public $collectionEnvelope;
    /**
     * @var string the name of the envelope (e.g. `_links`) for returning the links objects.
     * It takes effect only, if `collectionEnvelope` is set.
     */
    public $linksEnvelope = 'links';
    /**
     * @var string the name of the envelope (e.g. `_meta`) for returning the pagination object.
     * It takes effect only, if `collectionEnvelope` is set.
     */
    public $metaEnvelope = 'meta';
    /**
     * @var string name of the parameter storing the current page index.
     * @see params
     */
    public $pageParam = 'page';
    /**
     * @var string name of the parameter storing the page size.
     * @see params
     */
    public $pageSizeParam = 'per-page';
    /**
     * @var integer the default page size. This property will be returned by [[pageSize]] when page size
     * cannot be determined by [[pageSizeParam]] from [[params]].
     */
    public $defaultPageSize = 20;
    /**
     *
     * @var array
     */
    public $properties = [];
    /**
     *
     * @var array expanded field
     */
    private $_expands;
    private $_excepts = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->request === null) {
            $this->request = Yii::$app->getRequest();
        }
        if ($this->response === null) {
            $this->response = Yii::$app->getResponse();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($result instanceof Response) {
            return $result;
        }
        $result = $this->serializeData($result);
        return $result;
    }

    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * This method mainly converts the objects of recognized types into array representation.
     * It will not do conversion for unknown object types or non-object data.
     * The default implementation will handle [[Model]] and [[DataProviderInterface]].
     * You may override this method to support more object types.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serializeData($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            $errors = $data->getFirstErrors();
            $this->response->setStatusCode(422, reset($errors));
            return $errors;
        } elseif ($data instanceof QueryInterface) {
            return $this->serializeQuery($data);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->serializeDataProvider($data);
        } elseif (is_array($data) || is_object($data)) {
            return Serializer::serializeObject($data, $this->getExpands(), $this->_excepts, $this->properties);
        }
        return $data;
    }

    /**
     * Serializes a query.
     * @param QueryInterface $query
     * @return array the array representation of the data provider.
     */
    protected function serializeQuery($query)
    {
        $meta = [];
        if (($pagination = $this->getPagination()) !== false) {
            list($limit, $offset, $page) = $pagination;
            $total = $query->count();
            $query->limit($limit)->offset($offset);
            $pageCount = $limit < 1 ? ($total > 0 ? 1 : 0) : (int) (($total + $limit - 1) / $limit);
            $links = $this->generateLinks($page, $limit, $pageCount, true);
            $meta = $this->addPaginationHeaders($page, $limit, $pageCount, $total, $links);
        }
        if ($this->request->getIsHead()) {
            return null;
        }
        $models = $this->serializeModels($query->all());
        return $this->serializeCollection($models, $meta);
    }

    protected function generateLinks($currentPage, $pageSize, $pageCount, $absolute = false)
    {
        $links = [
            'self' => $this->createUrl($currentPage, $pageSize, $absolute),
        ];
        if ($currentPage > 0) {
            $links['first'] = $this->createUrl(0, $pageSize, $absolute);
            $links['prev'] = $this->createUrl($currentPage - 1, $pageSize, $absolute);
        }
        if ($currentPage < $pageCount - 1) {
            $links['next'] = $this->createUrl($currentPage + 1, $pageSize, $absolute);
            $links['last'] = $this->createUrl($pageCount - 1, $pageSize, $absolute);
        }
        return $links;
    }

    /**
     * Creates the URL suitable for pagination with the specified page number.
     * This method is mainly called by pagers when creating URLs used to perform pagination.
     * @param integer $page the zero-based page number that the URL should point to.
     * @param integer $pageSize the number of items on each page. If not set, the value of [[pageSize]] will be used.
     * @param boolean $absolute whether to create an absolute URL. Defaults to `false`.
     * @return string the created URL
     * @see params
     * @see forcePageParam
     */
    public function createUrl($page, $pageSize, $absolute = false)
    {
        $page = (int) $page;
        $pageSize = (int) $pageSize;
        $params = $this->request->getQueryParams();
        $params[$this->pageParam] = $page + 1;

        if ($pageSize != $this->defaultPageSize) {
            $params[$this->pageSizeParam] = $pageSize;
        } else {
            unset($params[$this->pageSizeParam]);
        }
        $params[0] = Yii::$app->controller->getRoute();
        $urlManager = Yii::$app->getUrlManager();
        if ($absolute) {
            return $urlManager->createAbsoluteUrl($params);
        } else {
            return $urlManager->createUrl($params);
        }
    }

    /**
     * Serializes a data provider.
     * @param DataProviderInterface $dataProvider
     * @return array the array representation of the data provider.
     */
    protected function serializeDataProvider($dataProvider)
    {
        $meta = [];
        if (($pagination = $dataProvider->getPagination()) !== false) {
            $dataProvider->prepare();
            $meta = $this->addPaginationHeaders($pagination->page, $pagination->pageSize, $pagination->pageCount, $pagination->totalCount, $pagination->getLinks(true));
        }

        if ($this->request->getIsHead()) {
            return null;
        }
        $models = $this->serializeModels(array_values($dataProvider->getModels()));
        return $this->serializeCollection($models, $meta);
    }

    protected function serializeCollection(array $models, $meta = [])
    {
        if ($this->collectionEnvelope === null) {
            return $models;
        } else {
            $result = [
                $this->collectionEnvelope => $models,
            ];
            if (!empty($meta)) {
                $result[$this->metaEnvelope] = $meta;
            }
            return $result;
        }
    }

    /**
     * Get limit offset
     * @return array|boolean
     */
    protected function getPagination()
    {
        $request = $this->request;
        if (($limit = $request->get($this->pageSizeParam, $this->defaultPageSize))) {
            $page = $request->get($this->pageParam, 1) - 1;
            $offset = $page * $limit;
            return [$limit, $offset, $page];
        }
        return false;
    }

    /**
     * Adds HTTP headers about the pagination to the response.
     * @param Pagination $pagination
     */
    protected function addPaginationHeaders($page, $pageSize, $pageCount, $totalCount, $links = [])
    {
        $_links = [];
        foreach ($links as $rel => $url) {
            $_links[] = "<$url>; rel=$rel";
            //$links[$rel] = ['href' => $url];
            $links[$rel] = $url;
        }
        $this->response->getHeaders()
            ->set($this->totalCountHeader, $totalCount)
            ->set($this->pageCountHeader, $pageCount)
            ->set($this->currentPageHeader, $page + 1)
            ->set($this->perPageHeader, $pageSize)
            ->set('Link', implode(', ', $_links));

        return [
            'totalCount' => $totalCount,
            'pageCount' => $pageCount,
            'currentPage' => $page + 1,
            'perPage' => $pageSize,
            $this->linksEnvelope => $links,
        ];
    }

    /**
     * Serializes a set of models.
     * @param array $models
     * @return array the array representation of the models
     */
    protected function serializeModels(array $models)
    {
        $expands = $this->getExpands();
        $excepts = $this->_excepts;
        foreach ($models as $i => $model) {
            $models[$i] = Serializer::serializeObject($model, $expands, $excepts);
        }
        return $models;
    }

    /**
     * Set expand field
     * @param array $expands
     * @param boolean $replace
     */
    public function setExpands($expands, $replace = false)
    {
        if (!is_array($expands)) {
            $expands = preg_split('/\s*,\s*/', $expands, -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->_expands = $replace ? $expands : array_merge($this->_excepts, $expands);
    }

    /**
     * Set expand field
     * @param array $fields
     * @param boolean $replace
     */
    public function setExceptField($fields, $replace = false)
    {
        if (!is_array($fields)) {
            $fields = preg_split('/\s*,\s*/', $fields, -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->_excepts = $replace ? $fields : array_merge($this->_excepts, $fields);
    }

    /**
     * Get expand field
     * @return array
     */
    protected function getExpands()
    {
        if ($this->_expands === null) {
            $this->_expands = preg_split('/\s*,\s*/', $this->request->get('expands'), -1, PREG_SPLIT_NO_EMPTY);
        }
        return $this->_expands;
    }

    /**
     *
     * @param array $expands
     * @return array Description
     */
    protected function resolveExpand(array $expands, $olds = [])
    {
        $olds = [];
        foreach ($expands as $field) {
            $fields = explode('.', $field, 2);
            $olds[$fields[0]][] = isset($fields[1]) ? $fields[1] : false;
        }
        return array_map('array_filter', $olds);
    }
}
