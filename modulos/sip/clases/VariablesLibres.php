<?php
class VariablesLibres{
    private $idHcLibres;
    private $idHcPerinatal;
    private $var0396;
    private $var0397;
    private $var0398;
    private $var0399;
    private $var0400;
    private $var0401;
    private $var0402;
    private $var0403;
    private $var0404;
    private $var0405;
    private $var0426;
    private $var0427;
    private $var0428;
    private $var0429;
    private $var0430;
    private $var0431;
    
    public function construirResult($result) {
        $this->idHcLibres = $result->fields['id_hclibres'];
        $this->idHcPerinatal = $result->fields['id_hcperinatal'];
        $this->var0396 = $result->fields['var_0396'];
        $this->var0397 = $result->fields['var_0397'];
        $this->var0398 = $result->fields['var_0398'];
        $this->var0399 = $result->fields['var_0399'];
        $this->var0400 = $result->fields['var_0400'];
        $this->var0401 = $result->fields['var_0401'];
        $this->var0402 = $result->fields['var_0402'];
        $this->var0403 = $result->fields['var_0403'];
        $this->var0404 = $result->fields['var_0404'];
        $this->var0405 = $result->fields['var_0405'];
        $this->var0426 = $result->fields['var_0426'];
        $this->var0427 = $result->fields['var_0427'];
        $this->var0428 = $result->fields['var_0428'];
        $this->var0429 = $result->fields['var_0429'];
        $this->var0430 = $result->fields['var_0430'];
        $this->var0431 = $result->fields['var_0431'];
    }
    
    /*
     * integer $idLibres
     */
    public function setIdHcLibres($idHcLibres) {
        $this->idHcLibres = $idHcLibres;
    }    
    public function getIdHcLibres()
    {
        return $this->idHcLibres;
    }
    /*
     * integer $idHcPerinatal
     */
    public function setIdHcPerinatal($idHcPerinatal) {
        $this->idHcPerinatal = $idHcPerinatal;
    }    
    public function getIdHcPerinatal()
    {
        return $this->idHcPerinatal;
    }
/*
 * GETTERS
 */  
    /*
     * integer $var0396 -- libre_0
     */
    public function getVar0396()
    {
        return $this->var0396;
    }
    /*
     * integer $var0397 -- libre_1
     */
    public function getVar0397()
    {
        return $this->var0397;
    }
    /*
     * varchar $var0398 -- libre_2
     */
    public function getVar0398()
    {
        return $this->var0398;
    }
    /*
     * varchar $var0399 -- libre_3
     */
    public function getVar0399()
    {
        return $this->var0399;
    }
    /*
     * varchar $var0400 -- libre_4
     */
    public function getVar0400()
    {
        return $this->var0400;
    }
    /*
     * varchar $var0401 -- libre_5
     */
    public function getVar0401()
    {
        return $this->var0401;
    }
    /*
     * varchar $var0402 -- libre_6
     */
    public function getVar0402()
    {
        return $this->var0402;
    }
    /*
     * varchar $var0403 -- libre_7
     */
    public function getVar0403()
    {
        return $this->var0403;
    }
    /*
     * varchar $var0404 -- libre_8
     */
    public function getVar0404()
    {
        return $this->var0404;
    }
    /*
     * varchar $var0405 -- libre_9
     */
    public function getVar0405()
    {
        return $this->var0405;
    }
    /*
     * varchar $var0426 -- libre_10
     */
    public function getVar0426()
    {
        return $this->var0426;
    }
    /*
     * varchar $var0427 -- libre_11
     */
    public function getVar0427()
    {
        return $this->var0427;
    }
    /*
     * varchar $var0428 -- libre_12
     */
    public function getVar0428()
    {
        return $this->var0428;
    }
    /*
     * varchar $var0429 -- libre_13
     */
    public function getVar0429()
    {
        return $this->var0429;
    }
    /*
     * varchar $var0430 -- libre_14
     */
    public function getVar0430()
    {
        return $this->var0430;
    }
    /*
     * varchar $var0431 -- libre_15
     */
    public function getVar0431()
    {
        return $this->var0431;
    }
    
/*
 * SETTERS
 */    
    public function setHcVariablesLibres($libre) {
        $this->var0396 = substr( trim( utf8_decode($libre['var_0396'])), 0, 15);
        $this->var0397 = substr( trim(utf8_decode($libre['var_0397'])), 0, 15);
        $this->var0398 = substr( trim(utf8_decode($libre['var_0398'])), 0, 15);
        $this->var0399 = substr( trim(utf8_decode($libre['var_0399'])), 0, 15);
        $this->var0400 = substr( trim(utf8_decode($libre['var_0400'])), 0, 15);
        $this->var0401 = substr( trim(utf8_decode($libre['var_0401'])), 0, 15);
        $this->var0402 = substr( trim(utf8_decode($libre['var_0402'])), 0, 15);
        $this->var0403 = substr( trim(utf8_decode($libre['var_0403'])), 0, 15);
        $this->var0404 = substr( trim(utf8_decode($libre['var_0404'])), 0, 15);
        $this->var0405 = substr( trim(utf8_decode($libre['var_0405'])), 0, 15);
        $this->var0426 = substr( trim(utf8_decode($libre['var_0426'])), 0, 15);
        $this->var0427 = substr( trim(utf8_decode($libre['var_0427'])), 0, 15);
        $this->var0428 = substr( trim(utf8_decode($libre['var_0428'])), 0, 15);
        $this->var0429 = substr( trim(utf8_decode($libre['var_0429'])), 0, 15);
        $this->var0430 = substr( trim(utf8_decode($libre['var_0430'])), 0, 15);
        $this->var0431 = substr( trim(utf8_decode($libre['var_0431'])), 0, 15);
    }    
    
    public function getLibresByHcPerinatal($idHcPerinatal){
        $sql = "SELECT * 
                FROM sip_clap.hclibres
                WHERE id_hcperinatal='$idHcPerinatal'";
        $result = sql($sql);
        $variablesLibres = new VariablesLibres();
        if($result->RecordCount()>0){
            if (!$result->EOF){
                $variablesLibres->construirResult($result);
            }    
        }
        return $variablesLibres; 
    }
    
    public function getLibresArrayByHcPerinatal($idHcPerinatal){
        $sql = "SELECT * 
                FROM sip_clap.hclibres
                WHERE id_hcperinatal='$idHcPerinatal'";
        $result = sql($sql);
        return $result->fields;
    }


    public function saveVariablesLibres($idHcPerinatal, $str) {
        $variablesLibres = $this->getLibresByHcPerinatal($idHcPerinatal);
        $libres = array();
        parse_str($str, $libres);
        $this->setHcVariablesLibres($libres);
        $this->setIdHcPerinatal($idHcPerinatal);
        
        if( empty($variablesLibres->idHcLibres ) ){
            $this->Insertar();
        }else{
            $this->setIdHcLibres($variablesLibres->idHcLibres);
            $this->Actualizar();
        }        
    }
    
    public function Insertar() {
        // HC VARIABLES LIBRES
        $sql = "INSERT INTO sip_clap.hclibres(
                    id_hcperinatal, var_0396, var_0397, var_0398, var_0399, var_0400, 
                    var_0401, var_0402, var_0403, var_0404, var_0405, var_0426,
                    var_0427, var_0428, var_0429, var_0430, var_0431)
            VALUES ($this->idHcPerinatal, '$this->var0396', '$this->var0397', '$this->var0398', '$this->var0399', '$this->var0400',
                    '$this->var0401', '$this->var0402', '$this->var0403', '$this->var0404', '$this->var0405','$this->var0426', 
                    '$this->var0427', '$this->var0428', '$this->var0429', '$this->var0430', '$this->var0431' )
         RETURNING id_hclibres";
        return sql($sql);
    }    
    
    public function Actualizar(){
        $sql = "UPDATE sip_clap.hclibres
                SET var_0396='$this->var0396', var_0397='$this->var0397', var_0398='$this->var0398', var_0399='$this->var0399', 
                    var_0400='$this->var0400', var_0401='$this->var0401', var_0402='$this->var0402', var_0403='$this->var0403', 
                    var_0404='$this->var0404', var_0405='$this->var0405', var_0426='$this->var0426', var_0427='$this->var0427', 
                    var_0428='$this->var0428', var_0429='$this->var0429', var_0430='$this->var0430', var_0431='$this->var0431'
                WHERE id_hclibres=".$this->idHcLibres;
        return sql($sql);        
    }
    
    

}