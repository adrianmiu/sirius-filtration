<?php

namespace Sirius\Filtration;

use Sirius\Filtration\Filtrator;

trait FiltratableTrait {
    protected $filtrator;

    function setFiltrator(Filtrator $filtrator) {
        $this->filtrator = $filtrator;
        return $this;
    }

    function getFiltrator() {
        if (!$this->filtrator) {
            $this->filtrator = new Filtrator();
        }
        return $this->filtrator;
    }
}