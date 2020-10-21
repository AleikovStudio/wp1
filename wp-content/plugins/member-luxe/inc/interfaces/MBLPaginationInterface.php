<?php

interface MBLPaginationInterface
{
    public function getCurrentPage();

    public function getTotalPages();

    public function hasToPaginate();

    public function getFirstPageLink();

    public function getLastPageLink();

    public function getPageLink($pageNumber);

    public function getPrevPageLink();

    public function getNextPageLink();
}