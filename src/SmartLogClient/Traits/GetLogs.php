<?php

namespace SmartLogClient\SmartLogClient\Traits;

use Seagulltools\Http\Client as HttpClient;

trait GetLogs
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    private $query;

    public function get()
    {
        $this->httpClient->method = 'GET';

        try {
            $this->httpClient->query('filter', json_encode($this->query));

            $this->httpClient->send();

            if ( $this->httpClient->getStatusCode() === 200 ) {
                return $this->httpClient->getResponse();
            } else {
                return [
                    'code' => $this->httpClient->getStatusCode(),
                    'message' => $this->httpClient->getResponse()
                ];
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string
     * @param  string  $operator
     * @param  mixed   $value
     * @return $this
     */
    public function where($column, $operator = NULL, $value = NULL)
    {
        $this->query[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];

        return $this;
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param  string  $column
     * @param  mixed   $values
     * @return $this
     */
    public function whereIn($column, array $values)
    {
        $this->query[] = [
            'column' => $column,
            'operator' => 'in',
            'value' => $values
        ];

        return $this;
    }

    /**
     * Add a where between statement to the query.
     *
     * @param  string  $column
     * @param  array   $values
     * @return $this
     */
    public function whereBetween($column, array $values)
    {
        $this->query[] = [
            'column' => $column,
            'operator' => 'between',
            'value' => $values
        ];

        return $this;
    }

}
