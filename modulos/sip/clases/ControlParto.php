<?php
class ControlParto{
    private $idControlParto;
    private $idHcPerinatal;
    private $var0207;
    private $var0208;
    private $var0209;
    private $var0210;
    private $var0407;
    private $var0392;
    private $var0211;
    private $var0212;
    private $var0213;
    private $var0214;
    private $var0215;
    private $var0216;
    
    public function construirResult($result) {
        $this->idControlParto = $result['id_control_parto'];
        $this->idHcPerinatal = $result['id_hcperinatal'];
        $this->var0207 = $result['var_0207'];
        $this->var0208 = $result['var_0208'];
        $this->var0209 = $result['var_0209'];
        $this->var0210 = $result['var_0210'];
        $this->var0407 = $result['var_0407'];
        $this->var0392 = $result['var_0392'];
        $this->var0211 = $result['var_0211'];
        $this->var0212 = $result['var_0212'];
        $this->var0213 = $result['var_0213'];
        $this->var0214 = $result['var_0214'];
        $this->var0215 = $result['var_0215'];
        $this->var0216 = $result['var_0216'];
    }
    
    /*
     * integer $idControlParto
     */
    public function setIdControlParto($idControlParto) {
        $this->idControlParto = $idControlParto;
    }    
    public function getIdControlParto()
    {
        return $this->idControlParto;
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
     * integer $var0207 -- control_parto_hora
     */
    public function getVar0207()
    {
        return $this->var0207;
    }
    /*
     * integer $var0208 -- control_parto_minuto
     */
    public function getVar0208()
    {
        return $this->var0208;
    }
    /*
     * varchar $var0209 -- control_parto_posicion
     */
    public function getVar0209()
    {
        return $this->var0209;
    }
    /*
     * varchar $var0210 -- control_parto_pa_sistolica
     */
    public function getVar0210()
    {
        return $this->var0210;
    }
    /*
     * varchar $var0407 -- control_parto_pa_diastolica
     */
    public function getVar0407()
    {
        return $this->var0407;
    }
    /*
     * varchar $var0392 -- control_parto_pulso
     */
    public function getVar0392()
    {
        return $this->var0392;
    }
    /*
     * varchar $var0211 -- control_parto_contracciones
     */
    public function getVar0211()
    {
        return $this->var0211;
    }
    /*
     * varchar $var0212 -- control_parto_dilatacion
     */
    public function getVar0212()
    {
        return $this->var0212;
    }
    /*
     * varchar $var0213 -- control_parto_altura_presentacion
     */
    public function getVar0213()
    {
        return $this->var0213;
    }
    /*
     * varchar $var0214 -- control_parto_variacion_posicion
     */
    public function getVar0214()
    {
        return $this->var0214;
    }
    /*
     * varchar $var0215 -- control_parto_meconio
     */
    public function getVar0215()
    {
        return $this->var0215;
    }
    /*
     * varchar $var0216 -- control_parto_fcf_dips
     */
    public function getVar0216()
    {
        return $this->var0216;
    }
    
/*
 * SETTERS
 */    
    public function setControlParto($control) {
        $this->idControlParto = $control['id_control_parto'];
        $this->var0207 = ($control['var_0207']=='') ? 'NULL' : $control['var_0207'];
        $this->var0208 = ($control['var_0208']=='') ? 'NULL' : $control['var_0208'];
        $this->var0209 = substr( trim( $control['var_0209'] ), 0, 3); 
        $this->var0210 = ($control['var_0210']=='') ? 'NULL' : $control['var_0210'];
        $this->var0407 = ($control['var_0407']=='') ? 'NULL' : $control['var_0407'];
        $this->var0392 = ($control['var_0392']=='') ? 'NULL' : $control['var_0392'];
        $this->var0211 = ($control['var_0211']=='') ? 'NULL' : $control['var_0211'];
        $this->var0212 = ($control['var_0212']=='') ? 'NULL' : $control['var_0212'];
        $this->var0213 = substr( trim( $control['var_0213'] ), 0, 4); 
        $this->var0214 = substr( trim( $control['var_0214'] ), 0, 4); 
        $this->var0215 = $control['var_0215'];
        $this->var0216 = ($control['var_0216']=='') ? 'NULL' : $control['var_0216'];
    }    
    
    public function getControlesByHcPerinatal($idHcPerinatal){
        $sql = "SELECT * 
                FROM sip_clap.control_parto
                WHERE id_hcperinatal='$idHcPerinatal'";
        $result = sql($sql);
        while (!$result->EOF) {
		$controlesParto[] = $result->fields;
		$result->MoveNext();
	   }
	return $controlesParto;     
    }
    
    public function saveControlesParto($idHcPerinatal, $controles) {
        /* Setear valores del formulario */
        if($controles){
            $arrayControles = array();
            foreach ($controles as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $arrayControles[$key][$variable] = $dato;
                }
            }
            foreach ($arrayControles as $key => $control) {
                if(!empty($control['var_0207']) || !empty($control['var_0208']) || !empty($control['var_0209']) || !empty($control['var_0210'])
                        || !empty($control['var_0407']) || !empty($control['var_0392']) || !empty($control['var_0211']) || !empty($control['var_0212'])
                        || !empty($control['var_0213']) || !empty($control['var_0214']) || !empty($control['var_0215']) || !empty($control['var_0216'])){
                    $this->setControlParto($control);
                    $this->setIdHcPerinatal($idHcPerinatal);
                    if($this->idControlParto==NULL){
                        $this->Insertar();
                    }else{
                        $this->Actualizar();
                    }                        
                }    
            }       
        }
    }
    
    public function Insertar() {
        // HC CONTROL PARTO
        $sql = "INSERT INTO sip_clap.control_parto(
                    id_hcperinatal, var_0207, var_0208, var_0209, var_0210,
                    var_0407, var_0392, var_0211, var_0212, var_0213, 
                    var_0214, var_0215, var_0216)
            VALUES ($this->idHcPerinatal, $this->var0207, $this->var0208, '$this->var0209', $this->var0210 ,
                    $this->var0407, $this->var0392, $this->var0211, $this->var0212, '$this->var0213',
                    '$this->var0214', '$this->var0215', $this->var0216 )
         RETURNING id_control_parto";
        return sql($sql);
    }   
    
    public function Actualizar() {
        $sql = "UPDATE sip_clap.control_parto
                SET var_0207=$this->var0207, var_0208=$this->var0208, var_0209='$this->var0209', var_0210=$this->var0210,
                    var_0407=$this->var0407, var_0392=$this->var0392, var_0211=$this->var0211, var_0212=$this->var0212, var_0213='$this->var0213', 
                    var_0214='$this->var0214', var_0215='$this->var0215', var_0216=$this->var0216
                WHERE id_control_parto=".$this->idControlParto;
        return sql($sql);   
    }
    
}