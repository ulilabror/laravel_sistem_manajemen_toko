<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{

    public $status;
    public $message;
    public $errors;

    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $resource
     * @param mixed $errors
     * @return void
     */
    public function __construct($status, $resource, $message, $errors = null)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
        $this->errors = $errors;
    }

    public function toArray($request)
    {
        return [
            'status' => 'success',
            'data' => $this->resource,
            'message' => $this->message,
            'errors' => $this->errors
        ];
    }

    protected function getData()
    {
        return $this->resource;
    }

    protected function getMessage()
    {
        return null;
    }

    protected function getErrors()
    {
        return null;
    }
}
