<?php
class ControlPuerperio{
    private $idControlPuerperio;
    private $idHcPerinatal;
    private $var0349;
    private $var0350;
    private $var0351;
    private $var0352;
    private $var0406;
    private $var0353;
    private $var0354;
    private $var0355;

    public function construirResult($result) {
        $this->idControlPuerperio = $result['id_control_puerperio'];
        $this->idHcPerinatal = $result['id_hcperinatal'];
        $this->var0349 = $result['var_0349'];
        $this->var0350 = $result['var_0350'];
        $this->var0351 = $result['var_0351'];
        $this->var0352 = $result['var_0352'];
        $this->var0406 = $result['var_0406'];
        $this->var0353 = $result['var_0353'];
        $this->var0354 = $result['var_0354'];
        $this->var0355 = $result['var_0355'];
    }
    
    /*
     * integer $idControlPuerperio
     */
    public function setIdControlPuerperio($idControlPuerperio) {
        $this->idControlPuerperio = $idControlPuerperio;
    }    
    public function getIdControlPuerperio()
    {
        return $this->idControlPuerperio;
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
     * integer $var0349 -- post_parto_dia
     */
    public function getVar0349()
    {
        return $this->var0349;
    }
    /*
     * integer $var0350 -- post_parto_hora
     */
    public function getVar0350()
    {
        return $this->var0350;
    }
    /*
     * integer $var0351 -- post_parto_temperatura
     */
    public function getVar0351()
    {
        return $this->var0351;
    }
    /*
     * integer $var0352 -- post_parto_pa_sistolica
     */
    public function getVar0352()
    {
        return $this->var0352;
    }
    /*
     * integer $var0406 -- post_parto_pa_diastolica
     */
    public function getVar0406()
    {
        return $this->var0406;
    }
    /*
     * integer $var0353 -- post_parto_pulso
     */
    public function getVar0353()
    {
        return $this->var0353;
    }
    /*
     * integer $var0354 -- post_parto_involuterina
     */
    public function getVar0354()
    {
        return $this->var0354;
    }
    /*
     * integer $var0355 -- post_parto_loquios
     */
    public function getVar0355()
    {
        return $this->var0355;
    }
/*
 * SETTERS
 */    
    public function setControlPuerperio($control) {
        $this->idControlPuerperio = $control['id_control_puerperio'];
        $this->var0349 = ($control['var_0349']=='') ? 'NULL' : $control['var_0349'];
        $this->var0350 = ($control['var_0350']=='') ? 'NULL' : $control['var_0350'];
        $this->var0351 = ($control['var_0351']=='') ? 'NULL' : $control['var_0351'];
        $this->var0352 = ($control['var_0352']=='') ? 'NULL' : $control['var_0352'];
        $this->var0406 = ($control['var_0406']=='') ? 'NULL' : $control['var_0406'];
        $this->var0353 = ($control['var_0353']=='') ? 'NULL' : $control['var_0353'];
        $this->var0354 = $control['var_0354'];
        $this->var0355 = $control['var_0355'];
    }
    
    public function getPuerperioByHcPerinatal($idHcPerinatal){
        $sql = "SELECT * 
                FROM sip_clap.control_puerperio
                WHERE id_hcperinatal='$idHcPerinatal'";
        $result = sql($sql);
        $controlesPuerperio = array();
        while (!$result->EOF) {
		$controlesPuerperio[] = $result->fields;
		$result->MoveNext();
	}
	return $controlesPuerperio; 
    }
    
    public function saveControlesPuerperio($idHcPerinatal, $controles) {
        /* Setear valores del formulario */
        if($controles){
            $arrayControles = array();
            foreach ($controles as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $arrayControles[$key][$variable] = $dato;
                }
            }
            foreach ($arrayControles as $key => $control) {
                if( !empty($control['var_0349']) || !empty($control['var_0350']) || !empty($control['var_0351']) || !empty($control['var_0352']) 
                    || !empty($control['var_0406']) || !empty($control['var_0353']) || !empty($control['var_0354']) || !empty($control['var_0355']) ){
                    $this->setControlPuerperio($control);
                    $this->setIdHcPerinatal($idHcPerinatal);
                    if($this->idControlPuerperio==NULL){
                        $this->Insertar();
                    }else{
                        $this->Actualizar();
                    }
                }    
            }       
        }
    }
    
    public function Insertar() {
        // HC CONTROL PUERPERIO
        $sql = "INSERT INTO sip_clap.control_puerperio(
                    id_hcperinatal, var_0349, var_0350, var_0351, var_0352,
                    var_0406, var_0353, var_0354, var_0355)
            VALUES ($this->idHcPerinatal, $this->var0349, $this->var0350, $this->var0351, $this->var0352 ,
                    $this->var0406, $this->var0353, '$this->var0354', '$this->var0355' )
         RETURNING id_control_puerperio";
        return sql($sql);
    }    
    public function Actualizar() {
        $sql = "UPDATE sip_clap.control_puerperio
                SET var_0349=$this->var0349, var_0350=$this->var0350, var_0351=$this->var0351, var_0352=$this->var0352,
                    var_0406=$this->var0406, var_0353=$this->var0353, var_0354='$this->var0354', var_0355='$this->var0355'
                WHERE id_control_puerperio=".$this->idControlPuerperio;
        return sql($sql);   
    }
}