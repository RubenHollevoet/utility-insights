<?php
class RequestHandler{

    // specify your own database credentials
    private array $parameters;

    public function __construct(array $requiredParameters)
    {
        $this->parameters = $_GET;

        foreach ($requiredParameters as $parameter) {
            if(!array_key_exists($parameter, $this->parameters)) {
                die(printf('missing mandatory parameter \'%s\'', $parameter));
            }
        }
    }

    public function getParameter(string $key){
        return $this->parameters[$key] ?? null;
    }
}
