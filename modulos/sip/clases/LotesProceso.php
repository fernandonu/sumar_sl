<?php
class LotesProceso{
    private $idLote;
    private $cuie;
    private $periodoDesde;
    private $periodoHasta;
    private $fichasNuevas;
    private $fichasReprocesadas;
    private $fichasTotal;
    private $fechaProceso;
    private $usuarioProceso;
    
    public $meses = array();
    
    public function __construct() {
        $this->meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    }

    public function construirResult($result) {
        $this->idLote = $result['id_lote'];
        $this->cuie = $result['cuie'];
        $this->periodoDesde = $result['periodo_desde'];
        $this->periodoHasta = $result['periodo_hasta'];
        $this->fichasNuevas = $result['fichas_nuevas'];
        $this->fichasReprocesadas = $result['fichas_reprocesadas'];
        $this->fichasTotal = $result['fichas_nuevas'] + $result['fichas_reprocesadas'];
        $this->fechaProceso = $result['fecha_proceso'];
        $this->usuarioProceso = $result['usuario_proceso'];
    }
    
    /*
     * integer $idLote
     */
    public function setIdLote($idLote) {
        $this->idLote = $idLote;
    }    
    public function getIdLote()
    {
        return $this->idLote;
    }
    /*
     * string $cuie
     */
    public function setCuie($cuie) {
        $this->cuie = $cuie;
    }    
    public function getCuie()
    {
        return $this->cuie;
    }
    public function getCuieTxt()
    {
        return ( $this->cuie) ? $this->cuie : 'Todos';
    }
    /*
     * string $periodoDesde
     */
    public function setPeriodoDesde($periodoDesde) {
        $this->periodoDesde = $periodoDesde;
    }    
    public function getPeriodoDesde()
    {
        return $this->periodoDesde;
    }
    /*
     * string $periodoHasta
     */
    public function setPeriodoHasta($periodoHasta) {
        $this->periodoHasta = $periodoHasta;
    }    
    public function getPeriodoHasta()
    {
        return $this->periodoHasta;
    }
    
    /*
     * integer $fichasNuevas
     */
    public function setFichasNuevas($fichasNuevas) {
        $this->fichasNuevas = $fichasNuevas;
    }    
    public function getFichasNuevas()
    {
        return $this->fichasNuevas;
    }
    /*
     * integer $fichasReprocesadas
     */
    public function setFichasReprocesadas($fichasReprocesadas) {
        $this->fichasReprocesadas = $fichasReprocesadas;
    }    
    public function getFichasReprocesadas()
    {
        return $this->fichasReprocesadas;
    }
    /*
     * datetime $fechaProceso
     */
    public function setFechaProceso($fechaProceso) {
        $this->fechaProceso = $fechaProceso;
    }    
    public function getFechaProceso()
    {
        return $this->fechaProceso;
    }
    /*
     * string $usuarioProceso
     */
    public function setUsuarioProceso($usuarioProceso) {
        $this->usuarioProceso = $usuarioProceso;
    }    
    public function getUsuarioProceso()
    {
        return $this->usuarioProceso;
    }    
    
    /*
     * integer $fichasTotal
     */
    public function getFichasTotal()
    {
        return $this->fichasNuevas + $this->fichasReprocesadas;
    }
    
    public function getPeriodoDesdeTxt(){
        $anio = substr($this->periodoDesde,0,4);
        $mes = substr($this->periodoDesde,4,2);
        return $mes.'/'.$anio;
        //return $this->meses[ $mes-1 ].' '.$anio;
    }
    public function getPeriodoHastaTxt(){
        $anio = substr($this->periodoHasta,0,4);
        $mes = substr($this->periodoHasta,4,2);
        return $mes.'/'.$anio;
        //return $this->meses[ $mes-1 ].' '.$anio;
    }

    public function getLoteById($idLote){
        $sql = "SELECT * 
                FROM sip_clap.lotes_proceso
                Where id_lote = ".$idLote;
        $result = sql($sql);
        $lote = new LotesProceso();
        $lote->construirResult($result->fields);
        unset($result);
	return $lote; 
    }
    
    public function getLotes(){
        $sql = "SELECT * 
                FROM sip_clap.lotes_proceso
                ORDER BY id_lote DESC";
        $result = sql($sql);
        while (!$result->EOF) {
            $lote = new LotesProceso();
            $lote->construirResult($result->fields);
            $lotes[] = $lote;
            unset($result->fields);
            $result->MoveNext();
        }
        unset($result);
	return $lotes;     
    }
    
    
}