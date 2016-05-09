<?php

namespace SimpleObjectCollection;

trait CountableImplementationTrait
{
    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }
}