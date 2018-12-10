<?php

interface iDAO{
    public function getById($id);
    public function store($object);
    public function remove($id);
}

?>