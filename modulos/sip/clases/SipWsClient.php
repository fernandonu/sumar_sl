<?php
/**
 * Cliente para el Web Service SIP-CLAP (Health Connector Server)
 * San Luis - Módulo SIP
 * Compatible con PHP 5.3+
 */
class SipWsClient {

    private $loginUrl = 'http://busgpsl.sanluis.gob.ar:8080/healthConnectorServer/hcd/login';
    private $searchUrl = 'http://busgpsl.sanluis.gob.ar:8080/healthConnectorServer/sumar/consultations/search';
    
    private $userName = 'prod_user_sumar';
    private $password = '5A(4X0(}b<!ZmIlB4)0F';
    
    private $token = null;
    private $lastError = null;
    private $lastHttpCode = null;
    private $lastResponseRaw = null;
    private $lastRequestPayload = null;

    public function __construct($userName = null, $password = null) {
        if ($userName !== null) {
            $this->userName = $userName;
        }
        if ($password !== null) {
            $this->password = $password;
        }
    }

    /**
     * Solicita un token Bearer al servicio de autenticación
     * @return string|false Token de autenticación o false en caso de error
     */
    public function login() {
        $this->lastError = null;
        $this->lastHttpCode = null;

        $loginPayload = json_encode(array(
            "userName" => $this->userName,
            "password" => $this->password
        ));

        $ch = curl_init($this->loginUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $loginPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'origin: null',
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $response = curl_exec($ch);
        $this->lastHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        $this->lastResponseRaw = $response;

        if ($curlErr) {
            $this->lastError = "Error de conexión cURL al autenticar: " . $curlErr;
            return false;
        }

        if ($this->lastHttpCode != 200) {
            $this->lastError = "HTTP " . $this->lastHttpCode . " al autenticar en el Web Service. Respuesta: " . substr($response, 0, 300);
            return false;
        }

        $json = json_decode($response, true);
        if (!$json || !isset($json['authToken']['token'])) {
            $this->lastError = "No se pudo extraer el token de la respuesta de login: " . substr($response, 0, 300);
            return false;
        }

        $this->token = $json['authToken']['token'];
        return $this->token;
    }

    /**
     * Formatea una fecha cualquiera a DD/MM/YYYY
     * @param string $fechaStr
     * @return string
     */
    public static function formatToWsDate($fechaStr) {
        $fechaStr = trim($fechaStr);
        if (empty($fechaStr)) {
            return date('d/m/Y');
        }

        // Si viene en formato YYYY-MM-DD
        if (preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/', $fechaStr, $m)) {
            return sprintf('%02d/%02d/%04d', $m[3], $m[2], $m[1]);
        }

        // Si viene en formato DD/MM/YYYY o DD-MM-YYYY
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/', $fechaStr, $m)) {
            return sprintf('%02d/%02d/%04d', $m[1], $m[2], $m[3]);
        }

        return $fechaStr;
    }

    /**
     * Ejecuta la consulta de consultas perinatales por rango de fechas
     * 
     * @param string $from Fecha desde (DD/MM/YYYY)
     * @param string $to Fecha hasta (DD/MM/YYYY)
     * @param array $opciones Opciones adicionales (healthCenterCode, etc)
     * @return array|false Lista de consultas o false si falló
     */
    public function searchConsultations($from, $to, $opciones = array()) {
        $this->lastError = null;
        $this->lastHttpCode = null;

        // Se solicita un token por cada llamada según la especificación
        $token = $this->login();
        if (!$token) {
            return false;
        }

        $fromFormatted = self::formatToWsDate($from);
        $toFormatted = self::formatToWsDate($to);

        $healthCenterCode = isset($opciones['healthCenterCode']) && !empty($opciones['healthCenterCode']) ? $opciones['healthCenterCode'] : null;
        $sex = isset($opciones['sex']) ? $opciones['sex'] : "F";
        $groupId = isset($opciones['groupId']) ? $opciones['groupId'] : "A";
        $ageFrom = isset($opciones['ageFrom']) ? intval($opciones['ageFrom']) : 1;
        $ageTo = isset($opciones['ageTo']) ? intval($opciones['ageTo']) : 100;
        $pregnant = isset($opciones['pregnant']) ? (bool)$opciones['pregnant'] : true;
        $newBorn = isset($opciones['newBorn']) ? (bool)$opciones['newBorn'] : false;
        $reportableDiagnosis = isset($opciones['reportableDiagnosis']) ? (bool)$opciones['reportableDiagnosis'] : true;
        $snomedCode = isset($opciones['snomedCode']) ? $opciones['snomedCode'] : null;

        $sipPlusFieldCodes = array(
            "0116", "0002", "0006", "F200", "1018", 
            "F201", "0019", "0126", "0283", "0284", 
            "0332", "0334", "0119", "0121", "0394", 
            "0182", "0113", "0115", "0101", "0092", 
            "0094", "0091", "0093"
        );

        $requestData = array(
            "healthCenterCode" => $healthCenterCode,
            "sex" => $sex,
            "groupId" => $groupId,
            "ageFrom" => $ageFrom,
            "ageTo" => $ageTo,
            "from" => $fromFormatted,
            "to" => $toFormatted,
            "reportableDiagnosis" => $reportableDiagnosis,
            "snomedCode" => $snomedCode,
            "sipPlusFieldCodes" => $sipPlusFieldCodes,
            "pregnant" => $pregnant,
            "newBorn" => $newBorn
        );

        $this->lastRequestPayload = json_encode($requestData);

        $ch = curl_init($this->searchUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->lastRequestPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: */*',
            'Authorization: Bearer ' . $token
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 0); // 0 = sin límite de tiempo (espera ilimitada para rangos extensos)
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

        $response = curl_exec($ch);
        $this->lastHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        $this->lastResponseRaw = $response;

        if ($curlErr) {
            $this->lastError = "Error cURL al consultar endpoints: " . $curlErr;
            return false;
        }

        if ($this->lastHttpCode != 200) {
            $this->lastError = "HTTP " . $this->lastHttpCode . " al ejecutar search. Respuesta: " . substr($response, 0, 400);
            return false;
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            $this->lastError = "La respuesta del servidor no fue un JSON válido: " . substr($response, 0, 300);
            return false;
        }

        return $data;
    }

    public function getLastError() {
        return $this->lastError;
    }

    public function getLastHttpCode() {
        return $this->lastHttpCode;
    }

    public function getLastResponseRaw() {
        return $this->lastResponseRaw;
    }

    public function getLastRequestPayload() {
        return $this->lastRequestPayload;
    }

    public function getToken() {
        return $this->token;
    }
}
