<?php

require_once("../../../include/include.inc.php");

function getCompanyDB(){
    $objDb = new ncadb();
    $sql = "SELECT * FROM m_comp WHERE m_comp_active = 1 ORDER BY m_comp ASC";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data;
}

function getFuncDB($comp){
    $objDb = new ncadb();
    $sql = "SELECT * FROM m_compfunc WHERE m_compfunc_comp = " . $comp . " AND m_compfunc_active = 1 ORDER BY m_compfunc ASC";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data;
}

function getDepDB($func){
    $objDb = new ncadb();
    $sql = "SELECT * FROM m_compfuncdep WHERE m_compfuncdep_compfunc = " . $func . " AND m_compfuncdep_active = 1 ORDER BY m_compfuncdep ASC";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data;
}

function getSecDB($dep){
    $objDb = new ncadb();
    $sql = "SELECT * FROM m_compfuncdepsec WHERE m_compfuncdepsec_compfuncdep = " . $dep . " AND m_compfuncdepsec_active = 1 ORDER BY m_compfuncdepsec ASC";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data;
}