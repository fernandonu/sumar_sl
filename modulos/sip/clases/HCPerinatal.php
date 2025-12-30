<?php
class HCPerinatal{
    private $idHcPerinatal;
    private $claveBeneficiario;
    private $var0001;
    private $var0002;
    private $var0003;
    private $var0004;
    private $var0005;
    private $var0006;
    private $var0009;
    private $var0010;
    private $var0011;
    private $var0012;
    private $var0013;
    private $var0014;
    private $var0015;
    private $var0016;
    private $var0017;
    private $var0018;
    private $var0019;
    private $usuarioCarga;
    private $fechahoraCarga;
    private $usuarioUltAct;
    private $fechahoraUltAct;
    private $finalizado;
    private $procesado;
    private $fechahoraProceso;
    private $estado;
    private $scoreRiesgo;
    /** Antecedentes **/
    private $var0020;
    private $var0022;
    private $var0024;
    private $var0026;
    private $var0028;
    private $var0030;
    private $var0021;
    private $var0023;
    private $var0025;
    private $var0027;
    private $var0029;
    private $var0031;
    private $var0032;
    private $var0033;
    private $var0034;
    private $var0035;
    private $var0036;
    private $var0037;
    private $var0040;
    private $var0041;
    private $var0045;
    private $var0046;
    private $var0038;
    private $var0039;
    private $var0042;
    private $var0047;
    private $var0043;
    private $var0044;
    private $var0048;
    private $var0049;
    private $var0050;
    private $var0051;
    private $var0052;
    private $var0053;
    private $var0054;
    /** Gestacion actual **/
    private $var0055;
    private $var0056;
    private $var0057;
    private $var0058;
    private $var0059;
    private $var0060;
    private $var0076;
    private $var0077;
    private $var0078;
    private $var0079;
    private $var0080;
    private $var0081;
    private $var0082;
    private $var0083;
    private $var0084;
    private $var0085;
    private $var0086;
    private $var0087;
    private $var0088;
    private $var0089;
    private $var0090;
    private $var0095;
    private $var0096;
    private $var0097;
    private $var0098;
    private $var0099;
    private $var0100;
    private $var0101;
    private $var0102;
    private $var0103;
    private $var0104;
    private $var0105;
    private $var0106;
    private $var0107;
    private $var0108;
    private $var0109;
    private $var0110;
    private $var0111;
    private $var0091;
    private $var0093;
    private $var0112;
    private $var0114;
    private $var0115;
    /** Parto - Aborto **/
    private $var0182;
    private $var0183;
    private $var0184;
    private $var0185;
    private $var0186;
    private $var0187;
    private $var0188;
    private $var0189;
    private $var0190;
    private $var0191;
    private $var0192;
    private $var0193;
    private $var0194;
    private $var0195;
    private $var0196;
    private $var0197;
    private $var0198;
    private $var0199;
    private $var0200;
    private $var0201;
    private $var0202;
    private $var0203;
    private $var0204;
    private $var0205;
    private $var0206;
    private $var0257;
    private $var0258;
    private $var0259;
    private $var0260;
    private $var0261;
    private $var0262;
    private $var0263;
    private $var0264;
    private $var0266;
    private $var0267;
    private $var0268;
    private $var0269;
    private $var0270;
    private $var0271;
    private $var0272;
    private $var0273;
    private $var0274;
    private $var0275;
    private $var0276;
    private $var0277;
    private $var0278;
    private $var0279;
    private $var0280;
    private $var0282;
    private $var0283;
    private $var0284;
    private $var0285;
    private $var0286;
    private $var0287;
    private $var0288;
    private $var0289;
    private $var0290;
    private $var0291;
    private $var0292;
    private $var0293;
    private $var0294;
    private $var0295;
    private $var0296;
    private $var0297;
    private $var0298;
    private $var0299;
    private $var0300;
    private $var0301;
    private $var0302;
    private $var0303;
    private $var0304;
    private $var0305;
    private $var0306;
    private $var0307;
    private $var0308;
    private $var0309;
    /** Recien Nacido **/
    private $var0310;
    private $var0311;
    private $var0312;
    private $var0313;
    private $var0314;
    private $var0315;
    private $var0316;
    private $var0319;
    private $var0317;
    private $var0318;
    private $var0320;
    private $var0321;
    private $var0322;
    private $var0323;
    private $var0324;
    private $var0325;
    private $var0326;
    private $var0327;
    private $var0328;
    private $var0329;
    private $var0330;
    private $var0331;
    private $var0332;
    private $var0333;
    private $var0334;
    private $var0335;
    private $var0368;
    private $var0336;
    private $var0337;
    private $var0338;
    private $var0339;
    private $var0340;
    private $var0341;
    private $var0342;
    private $var0343;
    private $var0344;
    private $var0345;
    private $var0346;
    private $var0347;
    private $var0348;
    private $var0367;
    private $var0425;
    private $var0370;
    private $var0371;
    private $var0372;
    private $var0373;
    private $var0374;
    private $var0375;
    private $var0376;
    private $var0377;
    private $var0378;
    private $var0395;
    private $var0379;
    private $var0380;
    private $var0381;
    private $var0382;
    private $var0383;
    private $var0384;
    private $var0385;
    private $var0386;
    private $var0388;
    private $var0389;
    private $var0390;
    private $var0391;
/* Especiales */
    private $var0432;
    private $var0409;
    private $var0061;
    private $var0062;
    private $var0063;
    private $var0064;
    private $var0065;
    private $var0066;
    private $var0067;
    private $var0068;
    private $var0069;
    private $var0070;
    private $var0071;
    private $var0072;
    private $var0073;
    private $var0074;
    private $var0075;
    private $var0410;
    private $var0433;
    private $var0434;
    private $var0435;
    private $var0436;
    private $var0413;
    private $var0419;
    private $var0415;
    private $var0414;
    private $var0420;
    private $var0421;
    private $var0416;
    private $var0422;
    private $var0423;
    private $var0418;
    private $var0424;
    private $var0437;
    private $var0438;
    private $var0439;
    private $var0440;
    private $var0441;
    private $var0412;
    private $var0411;
    
    public function __construct() {
        $this->finalizado = 0;
        $this->procesado = 0;
        $this->estado = 0;
        $this->scoreRiesgo = 0;
        $this->fechahoraCarga = date('Y-m-d H:i');
    }
    public function construirResult($result) {
        $this->idHcPerinatal = $result->fields['id_hcperinatal'];
        $this->claveBeneficiario = $result->fields['clave_beneficiario'];
        $this->var0001 = $result->fields['var_0001'];
        $this->var0002 = $result->fields['var_0002'];
        $this->var0003 = $result->fields['var_0003'];
        $this->var0004 = $result->fields['var_0004'];
        $this->var0005 = $result->fields['var_0005'];
        $this->var0006 = $result->fields['var_0006'];
        $this->var0009 = $result->fields['var_0009'];
        $this->var0010 = $result->fields['var_0010'];
        $this->var0011 = $result->fields['var_0011'];
        $this->var0012 = $result->fields['var_0012'];
        $this->var0013 = $result->fields['var_0013'];
        $this->var0014 = $result->fields['var_0014'];
        $this->var0015 = $result->fields['var_0015'];
        $this->var0016 = $result->fields['var_0016'];
        $this->var0017 = $result->fields['var_0017'];
        $this->var0018 = $result->fields['var_0018'];
        $this->var0019 = $result->fields['var_0019'];
        $this->usuarioCarga = $result->fields['usuario_carga'];
        $this->fechahoraCarga = $result->fields['fecha_hora_carga'];
        $this->usuarioUltAct = $result->fields['usuario_ultact'];
        $this->fechahoraUltAct = $result->fields['fecha_hora_ultact'];
        $this->finalizado = $result->fields['finalizado'];
        $this->procesado = $result->fields['procesado'];
        $this->fechahoraProceso = $result->fields['fecha_hora_proceso'];
        $this->scoreRiesgo = $result->fields['score_riesgo'];
        /** Antecedentes **/
        $this->var0020 = $result->fields['var_0020'];
        $this->var0022 = $result->fields['var_0022'];
        $this->var0024 = $result->fields['var_0024'];
        $this->var0026 = $result->fields['var_0026'];
        $this->var0028 = $result->fields['var_0028'];
        $this->var0030 = $result->fields['var_0030'];
        $this->var0021 = $result->fields['var_0021'];
        $this->var0023 = $result->fields['var_0023'];
        $this->var0025 = $result->fields['var_0025'];
        $this->var0027 = $result->fields['var_0027'];
        $this->var0029 = $result->fields['var_0029'];
        $this->var0031 = $result->fields['var_0031'];
        $this->var0032 = $result->fields['var_0032'];
        $this->var0033 = $result->fields['var_0033'];
        $this->var0034 = $result->fields['var_0034'];
        $this->var0035 = $result->fields['var_0035'];
        $this->var0036 = $result->fields['var_0036'];
        $this->var0037 = $result->fields['var_0037'];
        $this->var0040 = $result->fields['var_0040'];
        $this->var0041 = $result->fields['var_0041'];
        $this->var0045 = $result->fields['var_0045'];
        $this->var0046 = $result->fields['var_0046'];
        $this->var0038 = $result->fields['var_0038'];
        $this->var0039 = $result->fields['var_0039'];
        $this->var0042 = $result->fields['var_0042'];
        $this->var0047 = $result->fields['var_0047'];
        $this->var0043 = $result->fields['var_0043'];
        $this->var0044 = $result->fields['var_0044'];
        $this->var0048 = $result->fields['var_0048'];
        $this->var0049 = $result->fields['var_0049'];
        $this->var0050 = $result->fields['var_0050'];
        $this->var0051 = $result->fields['var_0051'];
        $this->var0052 = $result->fields['var_0052'];
        $this->var0053 = $result->fields['var_0053'];
        $this->var0054 = $result->fields['var_0054'];
        /** Gestacion actual **/
        $this->var0055 = $result->fields['var_0055'];
        $this->var0056 = $result->fields['var_0056'];
        $this->var0057 = $result->fields['var_0057'];
        $this->var0058 = $result->fields['var_0058'];
        $this->var0059 = $result->fields['var_0059'];
        $this->var0060 = $result->fields['var_0060'];
        $this->var0076 = $result->fields['var_0076'];
        $this->var0077 = $result->fields['var_0077'];
        $this->var0078 = $result->fields['var_0078'];
        $this->var0079 = $result->fields['var_0079'];
        $this->var0080 = $result->fields['var_0080'];
        $this->var0081 = $result->fields['var_0081'];
        $this->var0082 = $result->fields['var_0082'];
        $this->var0083 = $result->fields['var_0083'];
        $this->var0084 = $result->fields['var_0084'];
        $this->var0085 = $result->fields['var_0085'];
        $this->var0086 = $result->fields['var_0086'];
        $this->var0087 = $result->fields['var_0087'];
        $this->var0088 = $result->fields['var_0088'];
        $this->var0089 = $result->fields['var_0089'];
        $this->var0090 = $result->fields['var_0090'];
        $this->var0095 = $result->fields['var_0095'];
        $this->var0096 = $result->fields['var_0096'];
        $this->var0097 = $result->fields['var_0097'];
        $this->var0098 = $result->fields['var_0098'];
        $this->var0099 = $result->fields['var_0099'];
        $this->var0100 = $result->fields['var_0100'];
        $this->var0101 = $result->fields['var_0101'];
        $this->var0102 = $result->fields['var_0102'];
        $this->var0103 = $result->fields['var_0103'];
        $this->var0104 = $result->fields['var_0104'];
        $this->var0105 = $result->fields['var_0105'];
        $this->var0106 = $result->fields['var_0106'];
        $this->var0107 = $result->fields['var_0107'];
        $this->var0108 = $result->fields['var_0108'];
        $this->var0109 = $result->fields['var_0109'];
        $this->var0110 = $result->fields['var_0110'];
        $this->var0111 = $result->fields['var_0111'];
        $this->var0091 = $result->fields['var_0091'];
        $this->var0093 = $result->fields['var_0093'];
        $this->var0112 = $result->fields['var_0112'];
        $this->var0114 = $result->fields['var_0114'];
        $this->var0115 = $result->fields['var_0115'];
        /** Parto - Aborto **/
        $this->var0182 = $result->fields['var_0182'];
        $this->var0183 = $result->fields['var_0183'];
        $this->var0184 = $result->fields['var_0184'];
        $this->var0185 = $result->fields['var_0185'];
        $this->var0186 = $result->fields['var_0186'];
        $this->var0187 = $result->fields['var_0187'];
        $this->var0188 = $result->fields['var_0188'];
        $this->var0189 = $result->fields['var_0189'];
        $this->var0190 = $result->fields['var_0190'];
        $this->var0191 = $result->fields['var_0191'];
        $this->var0192 = $result->fields['var_0192'];
        $this->var0193 = $result->fields['var_0193'];
        $this->var0194 = $result->fields['var_0194'];
        $this->var0195 = $result->fields['var_0195'];
        $this->var0196 = $result->fields['var_0196'];
        $this->var0197 = $result->fields['var_0197'];
        $this->var0198 = $result->fields['var_0198'];
        $this->var0199 = $result->fields['var_0199'];
        $this->var0200 = $result->fields['var_0200'];
        $this->var0201 = $result->fields['var_0201'];
        $this->var0202 = $result->fields['var_0202'];
        $this->var0203 = $result->fields['var_0203'];
        $this->var0204 = $result->fields['var_0204'];
        $this->var0205 = $result->fields['var_0205'];
        $this->var0206 = $result->fields['var_0206'];
        $this->var0257 = $result->fields['var_0257'];
        $this->var0258 = $result->fields['var_0258'];
        $this->var0259 = $result->fields['var_0259'];
        $this->var0260 = $result->fields['var_0260'];
        $this->var0261 = $result->fields['var_0261'];
        $this->var0262 = $result->fields['var_0262'];
        $this->var0263 = $result->fields['var_0263'];
        $this->var0264 = $result->fields['var_0264'];
        $this->var0266 = $result->fields['var_0266'];
        $this->var0267 = $result->fields['var_0267'];
        $this->var0268 = $result->fields['var_0268'];
        $this->var0269 = $result->fields['var_0269'];
        $this->var0270 = $result->fields['var_0270'];
        $this->var0271 = $result->fields['var_0271'];
        $this->var0272 = $result->fields['var_0272'];
        $this->var0273 = $result->fields['var_0273'];
        $this->var0274 = $result->fields['var_0274'];
        $this->var0275 = $result->fields['var_0275'];
        $this->var0276 = $result->fields['var_0276'];
        $this->var0277 = $result->fields['var_0277'];
        $this->var0278 = $result->fields['var_0278'];
        $this->var0279 = $result->fields['var_0279'];
        $this->var0280 = $result->fields['var_0280'];
        $this->var0282 = $result->fields['var_0282'];
        $this->var0283 = $result->fields['var_0283'];
        $this->var0284 = $result->fields['var_0284'];
        $this->var0285 = $result->fields['var_0285'];
        $this->var0286 = $result->fields['var_0286'];
        $this->var0287 = $result->fields['var_0287'];
        $this->var0288 = $result->fields['var_0288'];
        $this->var0289 = $result->fields['var_0289'];
        $this->var0290 = $result->fields['var_0290'];
        $this->var0291 = $result->fields['var_0291'];
        $this->var0292 = $result->fields['var_0292'];
        $this->var0293 = $result->fields['var_0293'];
        $this->var0294 = $result->fields['var_0294'];
        $this->var0295 = $result->fields['var_0295'];
        $this->var0296 = $result->fields['var_0296'];
        $this->var0297 = $result->fields['var_0297'];
        $this->var0298 = $result->fields['var_0298'];
        $this->var0299 = $result->fields['var_0299'];
        $this->var0300 = $result->fields['var_0300'];
        $this->var0301 = $result->fields['var_0301'];
        $this->var0302 = $result->fields['var_0302'];
        $this->var0303 = $result->fields['var_0303'];
        $this->var0304 = $result->fields['var_0304'];
        $this->var0305 = $result->fields['var_0305'];
        $this->var0306 = $result->fields['var_0306'];
        $this->var0307 = $result->fields['var_0307'];
        $this->var0308 = $result->fields['var_0308'];
        $this->var0309 = $result->fields['var_0309'];
        /** Recien Nacido **/
        $this->var0310 = $result->fields['var_0310'];
        $this->var0311 = $result->fields['var_0311'];
        $this->var0312 = $result->fields['var_0312'];
        $this->var0313 = $result->fields['var_0313'];
        $this->var0314 = $result->fields['var_0314'];
        $this->var0315 = $result->fields['var_0315'];
        $this->var0316 = $result->fields['var_0316'];
        $this->var0319 = $result->fields['var_0319'];
        $this->var0317 = $result->fields['var_0317'];
        $this->var0318 = $result->fields['var_0318'];
        $this->var0320 = $result->fields['var_0320'];
        $this->var0321 = $result->fields['var_0321'];
        $this->var0322 = $result->fields['var_0322'];
        $this->var0323 = $result->fields['var_0323'];
        $this->var0324 = $result->fields['var_0324'];
        $this->var0325 = $result->fields['var_0325'];
        $this->var0326 = $result->fields['var_0326'];
        $this->var0327 = $result->fields['var_0327'];
        $this->var0328 = $result->fields['var_0328'];
        $this->var0329 = $result->fields['var_0329'];
        $this->var0330 = $result->fields['var_0330'];
        $this->var0331 = $result->fields['var_0331'];
        $this->var0332 = $result->fields['var_0332'];
        $this->var0333 = $result->fields['var_0333'];
        $this->var0334 = $result->fields['var_0334'];
        $this->var0335 = $result->fields['var_0335'];
        $this->var0368 = $result->fields['var_0368'];
        $this->var0336 = $result->fields['var_0336'];
        $this->var0337 = $result->fields['var_0337'];
        $this->var0338 = $result->fields['var_0338'];
        $this->var0339 = $result->fields['var_0339'];
        $this->var0340 = $result->fields['var_0340'];
        $this->var0341 = $result->fields['var_0341'];
        $this->var0342 = $result->fields['var_0342'];
        $this->var0343 = $result->fields['var_0343'];
        $this->var0344 = $result->fields['var_0344'];
        $this->var0345 = $result->fields['var_0345'];
        $this->var0346 = $result->fields['var_0346'];
        $this->var0347 = $result->fields['var_0347'];
        $this->var0348 = $result->fields['var_0348'];
        $this->var0367 = $result->fields['var_0367'];
        $this->var0425 = $result->fields['var_0425'];
        $this->var0370 = $result->fields['var_0370'];
        $this->var0371 = $result->fields['var_0371'];
        $this->var0372 = $result->fields['var_0372'];
        $this->var0373 = $result->fields['var_0373'];
        $this->var0374 = $result->fields['var_0374'];
        $this->var0375 = $result->fields['var_0375'];
        $this->var0376 = $result->fields['var_0376'];
        $this->var0377 = $result->fields['var_0377'];
        $this->var0378 = $result->fields['var_0378'];
        $this->var0395 = $result->fields['var_0395'];
        $this->var0379 = $result->fields['var_0379'];
        $this->var0380 = $result->fields['var_0380'];
        $this->var0381 = $result->fields['var_0381'];
        $this->var0382 = $result->fields['var_0382'];
        $this->var0383 = $result->fields['var_0383'];
        $this->var0384 = $result->fields['var_0384'];
        $this->var0385 = $result->fields['var_0385'];
        $this->var0386 = $result->fields['var_0386'];
        $this->var0388 = $result->fields['var_0388'];
        $this->var0389 = $result->fields['var_0389'];
        $this->var0390 = $result->fields['var_0390'];
        $this->var0391 = $result->fields['var_0391'];
        /** Especiales **/
        $this->var0432 = $result->fields['var_0432'];
        $this->var0409 = $result->fields['var_0409'];
        $this->var0061 = $result->fields['var_0061'];
        $this->var0062 = $result->fields['var_0062'];
        $this->var0063 = $result->fields['var_0063'];
        $this->var0064 = $result->fields['var_0064'];
        $this->var0065 = $result->fields['var_0065'];
        $this->var0066 = $result->fields['var_0066'];
        $this->var0067 = $result->fields['var_0067'];
        $this->var0068 = $result->fields['var_0068'];
        $this->var0069 = $result->fields['var_0069'];
        $this->var0070 = $result->fields['var_0070'];
        $this->var0071 = $result->fields['var_0071'];
        $this->var0072 = $result->fields['var_0072'];
        $this->var0073 = $result->fields['var_0073'];
        $this->var0074 = $result->fields['var_0074'];
        $this->var0075 = $result->fields['var_0075'];
        $this->var0410 = $result->fields['var_0410'];
        $this->var0433 = $result->fields['var_0433'];
        $this->var0434 = $result->fields['var_0434'];
        $this->var0435 = $result->fields['var_0435'];
        $this->var0436 = $result->fields['var_0436'];
        $this->var0413 = $result->fields['var_0413'];
        $this->var0419 = $result->fields['var_0419'];
        $this->var0415 = $result->fields['var_0415'];
        $this->var0414 = $result->fields['var_0414'];
        $this->var0420 = $result->fields['var_0420'];
        $this->var0421 = $result->fields['var_0421'];
        $this->var0416 = $result->fields['var_0416'];
        $this->var0422 = $result->fields['var_0422'];
        $this->var0423 = $result->fields['var_0423'];
        $this->var0418 = $result->fields['var_0418'];
        $this->var0425 = $result->fields['var_0425'];
        $this->var0437 = $result->fields['var_0437'];
        $this->var0438 = $result->fields['var_0438'];
        $this->var0439 = $result->fields['var_0439'];
        $this->var0440 = $result->fields['var_0440'];
        $this->var0441 = $result->fields['var_0441'];
        $this->var0412 = $result->fields['var_0412'];
        $this->var0411 = $result->fields['var_0411'];
        $this->setEstado();
        // $this->setHcScoreRiesgo();
    }   
/*
 * HC PERINATAL
 */    
    /* integer $idHcPerinatal  */
    public function setIdHcPerinatal($idHcPerinatal) {
        $this->idHcPerinatal = $idHcPerinatal;
    }    
    public function getIdHcPerinatal()
    {
        return $this->idHcPerinatal;
    }
    /* estado de la ficha */
    public function setEstado() {
        $this->estado = 0;
        if( $this->finalizado )  $this->estado = 1;
        if( $this->procesado )   $this->estado = 2;
    }    
    public function getEstado()
    {
        return $this->estado;
    }
    public function getEstadoTxt($procesado,$finalizado){
        $estado = 'NUEVA';
        if( $finalizado ) $estado = 'FINALIZADA';
        if( $procesado )  $estado = 'PROCESADA';
        return $estado;
    }
    
    /* varchar $claveBeneficiario  */
    public function getClaveBeneficiario() { return $this->claveBeneficiario; }     

    /* varchar $var0001 - nombre    */
    public function getVar0001() { return $this->var0001; }     

    /* varchar $var0002 - apellido    */
    public function getVar0002() { return $this->var0002; }
    
    /* varchar $var0003 - domiclio     */
    public function getVar0003() { return $this->var0003; }
    
    /* varchar $var0004 - localidad    */
    public function getVar0004() { return $this->var0004; }
    
    /* varchar $var0005 - telefono    */
    public function getVar0005() { return $this->var0005; }
    
    /* date $var0006 - fecha_nacimiento_madre    */
    public function getVar0006() { return $this->var0006; }
    
    /* integer $var0009 - edad_madre    */
    public function getVar0009() { return $this->var0009; }
    
    /* char $var0010 - edad_madre_rango    */
    public function getVar0010() { return $this->var0010; }     
    
    /* char $var0011 - etnia  */
    public function getVar0011() { return $this->var0011; }     
    
    /* char $var0012 - alfabeta    */
    public function getVar0012() { return $this->var0012; }
    
    /* char $var0012 - estudio   */
    public function getVar0013() { return $this->var0013; }
    
    /* interger $var0014 - aniosMayorNivel     */
    public function getVar0014() { return $this->var0014; }
    
    /* char $var0015 - estadoCivil     */
    public function getVar0015() { return $this->var0015; }     
    
    /* char $var0016 - viveSola     */
    public function getVar0016() { return $this->var0016; }
    
    /* varchar $var0017 -lugarControlPrenatal     */
    public function getVar0017() { return $this->var0017; }
    public function setVar0017($var0017) {
        $this->var0017 = $var0017;
    } 
    
    /* varchar $var0018 - lugarPartoAborto     */
    public function getVar0018() { return $this->var0018; }
    
    /* varchar $var0019 - nroIdentidad     */
    public function getVar0019() { return $this->var0019; }
    
    /* date $fechahoraCarga     */
    public function getFechahoraCarga() { return $this->fechahoraCarga; }
    
    /* varchar $usuarioCarga     */
    public function getUsuarioCarga() { return $this->usuarioCarga; }

    /* date $fechahoraUltAct     */
    public function getFechahoraUltAct() { return $this->fechahoraUltAct; }
    
    /* varchar $usuarioUltAct     */
    public function getUsuarioUltAct() { return $this->usuarioUltAct; }
    
    /* varchar $finalizado     */
    public function getFinalizado() { return $this->finalizado; }         
    
    /* varchar $procesado     */
    public function getProcesado() { return $this->procesado; }      

    /* date $fechahoraProceso     */
    public function getFechahoraProceso() { return $this->fechahoraProceso; }
    
    /* int $scoreRiesgo     */
    public function getScoreRiesgo() { return $this->scoreRiesgo; }
    
/*
 * HC ANTECEDENTES
 */
    /* char $var0020 - familiaresTBC     */
    public function getVar0020() { return $this->var0020; }
    
    /* char $var0022 - familiaresDiabetes     */
    public function getVar0022() { return $this->var0022; }
    
    /* char $var0024 - familiaresHipertension     */
    public function getVar0024() { return $this->var0024; }    
    
    /* char $var0026 - familiaresPreclampsia     */
    public function getVar0026() { return $this->var0026; }
    
    /* char $var0028 - familiaresEclampsia     */
    public function getVar0028() { return $this->var0028; }    
    
    /* char $var0030 - familiaresOtroGrave     */
    public function getVar0030() { return $this->var0030; }
    
    /* char $var0021 - personalesTBC     */
    public function getVar0021() { return $this->var0021; }
    
    /* char $var0023 - personalesDiabetes     */
    public function getVar0023() { return $this->var0023; }
    
    /* char $var0025 - personalesHipertension     */
    public function getVar0025() { return $this->var0025; }
    
    /* char $var0027 - personalesPreclampsia     */
    public function getVar0027() { return $this->var0027; }
    
    /* char $var0029 - personalesEclampsia     */
    public function getVar0029() { return $this->var0029; }
    
    /* char $var0031 - personalesOtroGrave     */
    public function getVar0031() { return $this->var0031; }
    
    /* char $var0032 - personalesCirugia     */
    public function getVar0032() { return $this->var0032; }
    
    /* char $var0033 - personalesInfertilidad     */
    public function getVar0033() { return $this->var0033; }
    
    /* char $var0034 - personalesCardiopatia     */
    public function getVar0034() { return $this->var0034; }
    
    /* char $var0035 - personalesNefropatia     */
    public function getVar0035() { return $this->var0035; }
    
    /* char $var0036 - personalesViolencia     */
    public function getVar0036() { return $this->var0036; }
    
    /* char $var0037 - otroAntecedente     */
    public function getVar0037() { return $this->var0037; }
    
    /* integer $var0040 - gestasPrevias     */
    public function getVar0040() { return $this->var0040; }
    
    /* integer $var0041 - abortos     */
    public function getVar0041() { return $this->var0041; }
    
    /* integer $var0045 - tresEspontaneoCons     */
    public function getVar0045() { return $this->var0045; }
    
    /* integer $var0046 - partosPrevios     */
    public function getVar0046() { return $this->var0046; }
    
    /* integer $var0038 - ultimoRnPrevio     */
    public function getVar0038() { return $this->var0038; }
    
    /* integer $var0039 - antecedentesGemelares     */
    public function getVar0039() { return $this->var0039; }
    
    /* integer $var0042 - partosVaginales      */
    public function getVar0042() { return $this->var0042; }
    
    /* integer $var0047 - cesareas      */
    public function getVar0047() { return $this->var0047; }
    
    /* integer $var0043 - nacidosVivos      */
    public function getVar0043() { return $this->var0043; }
    
    /* integer $var0044 - rnQueViven      */
    public function getVar0044() { return $this->var0044; }
    
    /* integer $var0048 - nacidoMuerto      */
    public function getVar0048() { return $this->var0048; }
    
    /* integer $var0049 - muerto1Sem      */
    public function getVar0049() { return $this->var0049; }
    
    /* integer $var0050 - muertoDespues1Sem      */
    public function getVar0050() { return $this->var0050; }
    
    /* date $var0051 - fechaEmbarazoAnterior     */
    public function getVar0051() { return $this->var0051; }
    
    /* char $var0052 - embAnteriorMenor1Anio     */
    public function getVar0052() { return $this->var0052; }
    
    /* char $var0053 - embarazoPlaneado     */
    public function getVar0053() { return $this->var0053; }
    
    /* char $var0054 - fracasoAnticonceptivo     */
    public function getVar0054() { return $this->var0054; }
    
/*
 * HC GESTACION ACTUAL
 */    
    /* integer $var0055 - pesoAnterior     */
    public function getVar0055() { return $this->var0055; }     

    /* integer $var0056 - tallaMadre     */
    public function getVar0056() { return $this->var0056; }     

    /* date $var0057 - fechaFUM     */
    public function getVar0057() { return $this->var0057; }     
    
    /* date $var0058 - fechaFPP     */
    public function getVar0058() { return $this->var0058; }     
    
    /* char $var0059 - egConfiablePorFUM     */
    public function getVar0059() { return $this->var0059; }     
    
    /* char $var0060 - egConfiablePorEco     */
    public function getVar0060() { return $this->var0060; }     
    
    /* char $var0076 - antirubeola     */
    public function getVar0076() { return $this->var0076; }     
    
    /* char $var0077 - antitetanica     */
    public function getVar0077() { return $this->var0077; }     
    
    /* integer $var0078 - antitetanicaDosis1     */
    public function getVar0078() { return $this->var0078; }     
    
    /* integer $var0079 - antitetanicaDosis2     */
    public function getVar0079() { return $this->var0079; }     
    
    /* char $var0080 - exOdontologicoNormal     */
    public function getVar0080() { return $this->var0080; }     
    
    /* char $var0081 - exMamasNormal     */
    public function getVar0081() { return $this->var0081; }     
    
    /* char $var0082 - cervixInspVisual     */
    public function getVar0082() { return $this->var0082; }     
    
    /* char $var0083 - cervixPap     */
    public function getVar0083() { return $this->var0083; }     
    
    /* char $var0084 - cervixColp     */
    public function getVar0084() { return $this->var0084; }     
    
    /* char $var0085 - grupoSanguineo     */
    public function getVar0085() { return trim($this->var0085); }     
    
    /* char $var0086 - factorRH     */
    public function getVar0086() { return $this->var0086; }     
    
    /* char $var0087 - inmunizacion     */
    public function getVar0087() { return $this->var0087; }     
    
    /* char $var0088 - toxoplasmosisMenor20sem     */
    public function getVar0088() { return $this->var0088; }     
    
    /* char $var0089 - toxoplasmosisMayor20sem     */
    public function getVar0089() { return $this->var0089; }     
    
    /* char $var0090 - toxoplasmosis1Consulta     */
    public function getVar0090() { return $this->var0090; }     
    
    /* integer $var0095 - hbMenor20sem     */
    public function getVar0095() { return $this->var0095;  }     
    
    /* char $var0096 - hbMenor20semMenor11g     */
    public function getVar0096() { return $this->var0096;  }     
    
    /* char $var0097 - hierro     */
    public function getVar0097() { return $this->var0097; }     
    
    /* char $var0098 - folatos     */
    public function getVar0098() { return $this->var0098; }
    
    /* integer $var0099 - hbMayor20sem     */
    public function getVar0099() { return $this->var0099; }
    
    /* char $var0100 - hbMayor20semMenor11g     */
    public function getVar0100() { return $this->var0100; }
    
    /* char $var0101 - chagas     */
    public function getVar0101() { return $this->var0101; }
    
    /* char $var0102 - paludismoMalaria     */
    public function getVar0102() { return $this->var0102; }
    
    /* char $var0103 - bacteriuriaMenor20sem     */
    public function getVar0103() { return $this->var0103; }
    
    /* char $var0104 - bacteriuriaMayor20sem     */
    public function getVar0104() { return $this->var0104; }
    
    /* integer $var0105 - glucemiaMenor20sem     */
    public function getVar0105() { return $this->var0105; }     
    
    /* char $var0106 - glucemiaMenor20semMayor105     */
    public function getVar0106() { return $this->var0106; }     
    
    /* char $var0107 - glucemiaMayor30semMayor105     */
    public function getVar0107() { return $this->var0107; }
    
    /* integer $var0108 - glucemiaMayor30sem     */
    public function getVar0108() { return $this->var0108; }     
    
    /* char $var0109 - estreptococoB     */
    public function getVar0109() { return $this->var0109; }     
    
    /* char $var0110 - preparacionParto     */
    public function getVar0110() {return $this->var0110;}
    
    /* char $var0111 - conserjeriaLactancia     */
    public function getVar0111() { return $this->var0111; }
    
    /* char $vihMenor20semSolicitado     */
    public function getVar0091() { return $this->var0091; } 
    
    /* char $var0093 - vihMayor20semSolicitado     */
    public function getVar0093() { return $this->var0093; }
    
    /* char $var0112 - sifilisMenor20semNotreponemica     */
    public function getVar0112() { return $this->var0112; }     
    
    /* char $var0114 - sifilisMayor20semNotreponemica     */
    public function getVar0114() { return $this->var0114; }     

    /* char $var0115 - sifilisMenor20semTratamiento     */
    public function getVar0115() { return $this->var0115; }     
    
/*
 * HC PARTO-ABORTO
 */    
    /* char $var0182 - partoAborto  */
    public function getVar0182() { return $this->var0182; } 

    /* date $var0183 - fechaIngreso  */
    public function getVar0183() { return $this->var0183; } 

    /* char $var0184 - carnet     */
    public function getVar0184() { return $this->var0184; } 
    
    /* integer $var0185 - consultasPrenatalesTotal     */
    public function getVar0185() { return $this->var0185; } 
    
    /* char $var0186 - hospitalizacion     */
    public function getVar0186() { return $this->var0186; } 
    
    /* integer $var0187 - hospitalizacionDias     */
    public function getVar0187() { return $this->var0187; } 
    
    /* char $var0188 - corticoides     */
    public function getVar0188() { return $this->var0188; } 
     
    /* char $var0189 - corticoidesSemanaInicio     */
    public function getVar0189() { return $this->var0189; } 
     
    /* char $var0190 - inicioParto     */
    public function getVar0190() { return $this->var0190; } 
    
    /* char $var0191 - rupturaMembrana     */
    public function getVar0191() { return $this->var0191; } 
    
    /* date $var0192 - rupturaMembranaFecha     */
    public function getVar0192() { return $this->var0192; } 
     
    /* date $var0193 - rupturaMembranaHoraMinuto     */
    public function getVar0193() { return $this->var0193; } 

    /* varchar $var0193H - rupturaMembranaHora     */
    public function getVar0193H() { return substr($this->var0193,0,2); } 
    /* varchar $var0193M - rupturaMembranaMinuto     */
    public function getVar0193M() { return substr($this->var0193,2,4); } 
     
    /* char $var0194 - rupturaMembranaMenor37sem     */
    public function getVar0194() { return $this->var0194; } 
    
    /* char $var0195 - rupturaMembranaMayor18hs     */
    public function getVar0195() { return $this->var0195; } 
    
    /* char $var0196 - rupturaMembranaTemperaturaMayor38     */
    public function getVar0196() { return $this->var0196; } 
    
    /* integer $var0197 - rupturaMembranaTemperatura     */
    public function getVar0197() { return $this->var0197; } 
    
    /* integer $var0198 - edadGestacionalPartoSemana     */
    public function getVar0198() { return $this->var0198; } 
     
    /* integer $var0199 - edadGestacionalPartoDias     */
    public function getVar0199() { return $this->var0199; } 
     
    /* char $var0200 - edadGestacionalPartoFUM     */
    public function getVar0200() { return $this->var0200; } 
      
    /* char $var0201 - edadGestacionalPartoEco     */
    public function getVar0201() { return $this->var0201; } 
 
    /* char $var0202 - presentacion     */
    public function getVar0202() { return $this->var0202; } 
 
    /* char $var0203 - tamanioFetalAcorde     */
    public function getVar0203() { return $this->var0203; } 
    
    /* char $var0204 - acompanianteTdp     */
    public function getVar0204() { return $this->var0204; } 

    /* char $var0205 - acompanianteP     */
    public function getVar0205() { return $this->var0205; } 
 
    /* char $var0206 - trabajoPartoDetalles     */
    public function getVar0206() { return $this->var0206; } 
 
    /* char $var0257 - enfermedades     */
    public function getVar0257() { return $this->var0257; } 
  
    /* char $var0258 - htaPrevia     */
    public function getVar0258() { return $this->var0258; } 
    
    /* char $var0259 - htaInducida     */
    public function getVar0259() { return $this->var0259; } 
    
    /* char $var0260 - preeclampsia     */
    public function getVar0260() { return $this->var0260; } 
 
    /* char $var0261 - eclampsia     */
    public function getVar0261() { return $this->var0261; } 
 
    /* char $var0262 - cardiopatia     */
    public function getVar0262() { return $this->var0262; } 
 
    /* char $var0263 - nefropatia     */
    public function getVar0263() { return $this->var0263; } 
 
    /* char $var0264 - diabetes     */
    public function getVar0264() { return $this->var0264; } 
  
    /* char $var0266 - infeccionOvular     */
    public function getVar0266() { return $this->var0266; } 

    /* char $var0267 - infeccionUrinaria     */
    public function getVar0267() { return $this->var0267; } 

    /* char $var0268 - amenazaPartoPretermino     */
    public function getVar0268() { return $this->var0268; } 
    public function setVar0268($var0268) {
        $this->var0268 = $var0268;
    }
    /* char $var0269 - rciu     */
    public function getVar0269() { return $this->var0269; } 
 
    /* char $var0270 - roturaPrematuraMembranas     */
    public function getVar0270() { return $this->var0270; } 

    /* char $var0271 - anemia     */
    public function getVar0271() { return $this->var0271; } 
 
    /* char $var0272 - otraCondicionGrave     */
    public function getVar0272() { return $this->var0272; } 
 
    /* char $var0273 - hemorragia1Trim     */
    public function getVar0273() { return $this->var0273; } 

    /* char $var0274 - hemorragia2Trim     */
    public function getVar0274() { return $this->var0274; } 
 
    /* char $var0275 - hemorragia3Trim     */
    public function getVar0275() { return $this->var0275; } 

    /* char $var0276 - hemorragiaPostparto     */
    public function getVar0276() { return $this->var0276; } 
 
    /* char $var0277 - infeccionPuerperal     */
    public function getVar0277() { return $this->var0277; } 
 
    /* integer $var0278 - codigoEnfermedad1     */
    public function getVar0278() { return $this->var0278; } 
    
    /* integer $var0279 - codigoEnfermedad2     */
    public function getVar0279() { return $this->var0279; } 
    
    /* integer $var0280 - codigoEnfermedad3     */
    public function getVar0280() { return $this->var0280; } 

    /* char $var0282 - nacimiento     */
    public function getVar0282() { return $this->var0282; } 

    /* char $var0283 - nacimientoHoraMinuto     */
    public function getVar0283() { return $this->var0283; } 
    /* varchar $var0283H - nacimientoHora     */
    public function getVar0283H() { return substr($this->var0283,0,2); } 
    /* varchar $var0283M - nacimientoMinuto     */
    public function getVar0283M() { return substr($this->var0283,2,4); }    
    
    /* date $var0284 - nacimientoDia     */
    public function getVar0284() { return $this->var0284; } 

    /* char $var0285 - nacimientoMultiple     */
    public function getVar0285() { return $this->var0285; } 

    /* integer $var0286 - nacimientoMultipleOrden     */
    public function getVar0286() { return $this->var0286; } 

    /* char $var0287 - nacimientoTerminacion     */
    public function getVar0287() { return $this->var0287; } 

    /* varchar $var0288 - motivoInduccionCirugia     */
    public function getVar0288() { return $this->var0288; } 

    /* integer $var0289 - codigoInduccion     */
    public function getVar0289() { return $this->var0289; } 

    /* integer $var0290 - codigoCirugia     */
    public function getVar0290() { return $this->var0290; } 

    /* integer $var0291 - posicionParto     */
    public function getVar0291() { return $this->var0291; } 

    /* integer $var0292 - episiotomia     */
    public function getVar0292() { return $this->var0292; } 

    /* char $var0293 - desgarros     */
    public function getVar0293() { return $this->var0293; } 

    /* integer $var0294 - desgarroGrado     */
    public function getVar0294() { return $this->var0294; } 

    /* char $var0295 - ocitocicosPrealumbramiento     */
    public function getVar0295() { return $this->var0295; } 

    /* char $var0296 - ocitocicosPostalumbramiento     */
    public function getVar0296() { return $this->var0296; } 

    /* char $var0297 - placentaCompleta     */
    public function getVar0297() { return $this->var0297; } 

    /* char $var0298 - placentaRetenida     */
    public function getVar0298() { return $this->var0298; } 

    /* char $var0299 - ligaduraCordonPrecoz     */
    public function getVar0299() { return $this->var0299; } 

    /* char $var0300 - ocitocicosTDP     */
    public function getVar0300() { return $this->var0300; } 

    /* char $var0301 - antibioticos     */
    public function getVar0301() { return $this->var0301; } 

    /* char $var0302 - analgesia     */
    public function getVar0302() { return $this->var0302; } 

    /* char $var0303 - analgesiaLocal     */
    public function getVar0303() { return $this->var0303; } 

    /* char $var0304 - analgesiaRegional     */
    public function getVar0304() { return $this->var0304; } 

    /* char $var0305 - analgesiaGeneral     */
    public function getVar0305() { return $this->var0305; } 

    /* char $var0306 - transfusion     */
    public function getVar0306() { return $this->var0306; } 

    /* char $var0307 - medicacionRecibidaOtros     */
    public function getVar0307() { return $this->var0307; } 

    /* integer $var0308 - codigoMedicacion1     */
    public function getVar0308() { return $this->var0308; } 

    /* integer $var0309 - codigoMedicacion2     */
    public function getVar0309() { return $this->var0309; } 

/**
 * HC RECIEN NACIDO
 **/
    /* char $var0310 - rnSexo     */
    public function getVar0310() { return $this->var0310; }
    
    /* integer $var0311 - rnPeso     */
    public function getVar0311() {return $this->var0311; } 
    
    /* integer $var0312 - rnPesoMen2500May4000     */
    public function getVar0312() {return $this->var0312; } 
    
    /* integer $var0313 - rnPerimCefalico     */
    public function getVar0313() {return $this->var0313; } 

    /* integer $var0314 - rnLongitud     */
    public function getVar0314() {return $this->var0314; } 

    /* integer $var0315 - rnEdadGestacionalSemana     */
    public function getVar0315() {return $this->var0315; } 

    /* integer $var0316 - rnEdadGestacionalDias     */
    public function getVar0316() {return $this->var0316; } 

    /* char $var0319 - rnEdadGestacionalEstimada     */
    public function getVar0319() { return $this->var0319; } 
    
    /* char $var0317 - rnEdadGestacionalFUM     */
    public function getVar0317() { return $this->var0317; } 

    /* char $var0318 - rnEdadGestacionalEco     */
    public function getVar0318() { return $this->var0318; } 

    /* char $var0320 - rnPesoEdadGestacional     */
    public function getVar0320() { return $this->var0320; } 

    /* integer $var0321 - rnApgarMinuto1     */
    public function getVar0321() { return $this->var0321; }
    
    /* integer $var0322 - rnApgarMinuto5     */
    public function getVar0322() { return $this->var0322; }

    /* char $var0323 - rnReanimacionEstimulacion     */
    public function getVar0323() { return $this->var0323; }
    
    /* char $var0324 - rnReanimacionRespiracion     */
    public function getVar0324() { return $this->var0324; }

    /* char $var0325 - rnReanimacionMascara     */
    public function getVar0325() { return $this->var0325; }

    /* char $var0326 - rnReanimacionOxigeno     */
    public function getVar0326() { return $this->var0326; }

    /* char $var0327 - rnReanimacionMasaje     */
    public function getVar0327() { return $this->var0327; }

    /* char $var0328 - rnReanimacionTubo     */
    public function getVar0328() { return $this->var0328; }

    /* char $var0329 - rnFalleceLugar     */
    public function getVar0329() { return $this->var0329; }

    /* char $var0330 - rnReferido     */
    public function getVar0330() { return $this->var0330; }

    /* char $var0331 - rnAtendioParto     */
    public function getVar0331() { return $this->var0331; }

    /* varchar $var0332 - rnAtendioPartoNombre     */
    public function getVar0332() { return $this->var0332; } 
    
    /* char $var0333 - rnAtendioNeonato     */
    public function getVar0333() { return $this->var0333; } 

    /* varchar $var0334 - rnAtendioNeonatoNombre     */
    public function getVar0334() { return $this->var0334; } 

    /* char $var0335 - rnDefectosCongenitos     */
    public function getVar0335() { return $this->var0335; } 

    /* integer $var0368 - rnDefectosCongenitosCodigo     */
    public function getVar0368() { return $this->var0368; } 

    /* char $var0336 - rnEnfermedades     */
    public function getVar0336() { return $this->var0336; } 

    /* integer $var0337 - rnEnfermedadesCodigo1     */
    public function getVar0337() { return $this->var0337; } 

    /* varchar $var0338 - rnEnfermedadesNotas1     */
    public function getVar0338() { return $this->var0338; } 

    /* integer $var0339 - rnEnfermedadesCodigo2     */
    public function getVar0339() { return $this->var0339; } 

    /* varchar $var0340 - rnEnfermedadesNotas2     */
    public function getVar0340() { return $this->var0340; } 

    /* integer $var0341 - rnEnfermedadesCodigo3     */
    public function getVar0341() { return $this->var0341; } 

    /* varchar $var0342 - rnEnfermedadesNotas3     */
    public function getVar0342() { return $this->var0342; } 

    /* char $var0343 - rnVdrl     */
    public function getVar0343() { return $this->var0343; } 

    /* char $var0344 - rnTsh     */
    public function getVar0344() { return $this->var0344; } 

    /* char $var0345 - rnHbpatia     */
    public function getVar0345() { return $this->var0345; } 

    /* char $var0346 - rnBilirrubina     */
    public function getVar0346() { return $this->var0346; } 

    /* char $var0347 - rnToxo     */
    public function getVar0347() { return $this->var0347; } 

    /* char $var0348 - rnMeconio     */
    public function getVar0348() { return $this->var0348; } 

    /* char $var0367 - antirubeolaPostparto     */
    public function getVar0367() { return $this->var0367; } 

    /* date $var0425 - rnEgresoFecha     */
    public function getVar0425() { return $this->var0425; } 

    /* date $var0370 - rnEgresoHoraMinutos     */
    public function getVar0370() { return $this->var0370; } 
    /* varchar $var0370H - rnEgresoHora     */
    public function getVar0370H() { return substr($this->var0370,0,2); } 
    /* varchar $var0370M - rnEgresoMinutos     */
    public function getVar0370M() { return substr($this->var0370,2,4); } 

    /* char $var0371 - rnEgreso     */
    public function getVar0371() { return $this->var0371; } 

    /* varchar $var0372 - rnLugarTraslado     */
    public function getVar0372() { return $this->var0372; } 

    /* char $var0373 - rnFalleceTraslado     */
    public function getVar0373() { return $this->var0373; } 

    /* char $var0374 - rnEdadEgreso     */
    public function getVar0374() { return $this->var0374; } 

    /* char $var0375 - rnEdadMenor1dia     */
    public function getVar0375() { return $this->var0375; } 

    /* char $var0376 - rnAlimentoAlta     */
    public function getVar0376() { return $this->var0376; } 

    /* char $var0377 - rnBocaArriba     */
    public function getVar0377() { return $this->var0377; } 

    /* char $var0378 - rnBCG     */
    public function getVar0378() { return $this->var0378; } 

    /* integer $var0395 - rnPesoEgreso     */
    public function getVar0395() { return $this->var0395; } 

    /* date $var0379 - egresoMaternoFecha     */
    public function getVar0379() { return $this->var0379; } 

    /* char $var0380 - trasladoMaterno     */
    public function getVar0380() { return $this->var0380; } 

    /* varchar $var0381 - lugarTrasladoMaterno     */
    public function getVar0381() { return $this->var0381; } 

    /* char $var0382 - egresoMaterno     */
    public function getVar0382() { return $this->var0382; } 

    /* char $var0383 - egresoMaternoFalleceTraslado     */
    public function getVar0383() { return $this->var0383; } 

    /* integer $var0384 - egresoMaternoFalleceTrasladoDias     */
    public function getVar0384() { return $this->var0384; } 

    /* char $var0385 - conserjeria     */
    public function getVar0385() { return $this->var0385; } 

    /* char $var0386 - metodoAnticoncepcion     */
    public function getVar0386() { return $this->var0386; } 

    /* varchar $var0388 - rnHistoriaId     */
    public function getVar0388() { return $this->var0388; } 

    /* varchar $var0389 - nombreRecienNacido     */
    public function getVar0389() { return $this->var0389; } 

    /* varchar $var0390 - rnResponsableEgreso     */
    public function getVar0390() { return $this->var0390; } 

    /* varchar $var0391 - responsableEgresoMaterno     */
    public function getVar0391() { return $this->var0391; } 

/**
 * HC ESPECIALES
 **/
    /* char $var0432 - personales_vih     */
    public function getVar0432() { return $this->var0432; }

    /* integer $var0409 - embarazo_ectopico     */
    public function getVar0409() { return $this->var0409; }

    /* char $var0061 - fuma_act_1_trim     */
    public function getVar0061() { return $this->var0061; }

    /* char $var0062 - fuma_pas_1_trim     */
    public function getVar0062() { return $this->var0062; }

    /* char $var0063 - drogas_1_trim     */
    public function getVar0063() { return $this->var0063; }

    /* char $var0064 - alcohol_1_trim     */
    public function getVar0064() { return $this->var0064; }

    /* char $var0065 - violencia_1_trim     */
    public function getVar0065() { return $this->var0065; }

    /* char $var0066 - fuma_act_2_trim     */
    public function getVar0066() { return $this->var0066; }

    /* char $var0067 - fuma_pas_2_trim     */
    public function getVar0067() { return $this->var0067; }

    /* char $var0068 - drogas_2_trim     */
    public function getVar0068() { return $this->var0068; }

    /* char $var0069 - alcohol_2_trim     */
    public function getVar0069() { return $this->var0069; }

    /* char $var0070 - violencia_2_trim     */
    public function getVar0070() { return $this->var0070; }

    /* char $var0071 - fuma_act_3_trim     */
    public function getVar0071() { return $this->var0071; }

    /* char $var0072 - fuma_pas_3_trim     */
    public function getVar0072() { return $this->var0072; }

    /* char $var0073 - drogas_3_trim     */
    public function getVar0073() { return $this->var0073; }

    /* char $var0074 - alcohol_3_trim     */
    public function getVar0074() { return $this->var0074; }

    /* char $var0075 - violencia_3_trim     */
    public function getVar0075() { return $this->var0075; }

    /* char $var0410 - gammaglobulina     */
    public function getVar0410() { return $this->var0410; }

    /* char $var0433- prueba_vih_menor20sem     */
    public function getVar0433() { return $this->var0433; }

    /* char $var0434- tarv_hb_menor20sem     */
    public function getVar0434() { return $this->var0434; }

    /* char $var0435- prueba_vih_mayor20sem     */
    public function getVar0435() { return $this->var0435; }

    /* char $var0436- tarv_hb_mayor20sem     */
    public function getVar0436() { return $this->var0436; }

    /* integer $var0413- sifilis_menor20sem_notreponemica_semana   */
    public function getVar0413() { return $this->var0413; }

    /* integer $var0419- sifilis_mayor20sem_notreponemica_semana   */
    public function getVar0419() { return $this->var0419; }

    /* char $var0415- sifilis_menor20sem_treponemica   */
    public function getVar0415() { return $this->var0415; }

    /* integer $var0414- sifilis_menor20sem_treponemica_semana   */
    public function getVar0414() { return $this->var0414; }

    /* integer $var0420- sifilis_mayor20sem_treponemica_semana   */
    public function getVar0420() { return $this->var0420; }

    /* char $var0421- sifilis_mayor20sem_treponemica   */
    public function getVar0421() { return $this->var0421; }

    /* integer $var0416- sifilis_menor20sem_tratamiento_semana   */
    public function getVar0416() { return $this->var0416; }

    /* integer $var0422- sifilis_mayor20sem_tratamiento_semana   */
    public function getVar0422() { return $this->var0422; }

    /* char $var0423- sifilis_mayor20sem_tratamiento   */
    public function getVar0423() { return $this->var0423; }

    /* char $var0418- sifilis_menor20sem_tratamiento_pareja   */
    public function getVar0418() { return $this->var0418; }

    /* char $var0424- sifilis_mayor20sem_tratamiento_pareja   */
    public function getVar0424() { return $this->var0424; }

    /* char $var0437- tdp_prueba_sifilis   */
    public function getVar0437() { return $this->var0437; }

    /* char $var0438- tdp_prueba_vih   */
    public function getVar0438() { return $this->var0438; }

    /* char $var0439- tdp_arv   */
    public function getVar0439() { return $this->var0439; }

    /* char $var0440- rn_vih_expuesto   */
    public function getVar0440() { return $this->var0440; }

    /* char $var0441- rn_vih_tratamiento   */
    public function getVar0441() { return $this->var0441; }

    /* char $var0412- rn_vdrl_tratamiento   */
    public function getVar0412() { return $this->var0412; }

    /* char $var0411- gammaglobulina_egreso   */
    public function getVar0411() { return $this->var0411; }

    
/*
 * SETTER HC PERINATAL
 */    
    public function setHcPerinatal($ficha){
        $this->idHcPerinatal = $ficha['idHcPerinatal'];
        $this->claveBeneficiario = $ficha['claveBeneficiario'];
        $this->var0001 = substr( trim($ficha['var_0001']), 0, 20); 
        $this->var0002 = substr( trim($ficha['var_0002']), 0, 20);
        $this->var0003 = substr( trim($ficha['var_0003']), 0, 50);
        $this->var0004 = substr( trim($ficha['var_0004']), 0, 30);
        $this->var0005 = substr( trim($ficha['var_0005']), 0, 20);
        $this->var0006 = $ficha['var_0006'];
        $this->var0009 = $ficha['var_0009'];
        $this->var0010 = $ficha['var_0010'];
        $this->var0011 = $ficha['var_0011'];
        $this->var0012 = $ficha['var_0012'];
        $this->var0013 = $ficha['var_0013'];
        $this->var0014 = ($ficha['var_0014']=='')?'NULL':$ficha['var_0014'];
        $this->var0015 = $ficha['var_0015'];
        $this->var0016 = $ficha['var_0016'];
        $this->var0017 = substr( trim($ficha['var_0017']), 0, 6);
        $this->var0018 = substr( trim($ficha['var_0018']), 0, 6);
        $this->var0019 = substr( trim($ficha['var_0019']), 0, 20);
        $this->usuarioCarga = $ficha['usuario'];
        if($ficha['finalizarFicha']==1){
            $this->finalizado = 1;
        }else{ $this->finalizado = 0; }
    }
/*
 * SETTER HC ANTECEDENTES
 */    
    public function setHcAntecedentes($ficha){
        $this->var0020 = $ficha['var_0020'];
        $this->var0022 = $ficha['var_0022'];
        $this->var0024 = $ficha['var_0024'];
        $this->var0026 = $ficha['var_0026'];
        $this->var0028 = $ficha['var_0028'];
        $this->var0030 = $ficha['var_0030'];
        $this->var0021 = $ficha['var_0021'];
        $this->var0023 = $ficha['var_0023'];
        $this->var0025 = $ficha['var_0025'];
        $this->var0027 = $ficha['var_0027'];
        $this->var0029 = $ficha['var_0029'];
        $this->var0031 = $ficha['var_0031'];
        $this->var0032 = $ficha['var_0032'];
        $this->var0033 = $ficha['var_0033'];
        $this->var0034 = $ficha['var_0034'];
        $this->var0035 = $ficha['var_0035'];
        $this->var0036 = $ficha['var_0036'];
        $this->var0037 = substr( trim($ficha['var_0037']), 0, 20);
        $this->var0040 = ($ficha['var_0040']=='')?0:$ficha['var_0040'];
        $this->var0041 = ($ficha['var_0041']=='')?0:$ficha['var_0041'];
        $this->var0045 = $ficha['var_0045'];
        $this->var0046 = ($ficha['var_0046']=='')?0:$ficha['var_0046'];
        $this->var0038 = $ficha['var_0038'];
        $this->var0039 = $ficha['var_0039'];
        $this->var0042 = ($ficha['var_0042']=='')?0:$ficha['var_0042'];
        $this->var0047 = ($ficha['var_0047']=='')?0:$ficha['var_0047'];
        $this->var0043 = ($ficha['var_0043']=='')?0:$ficha['var_0043'];
        $this->var0044 = ($ficha['var_0044']=='')?0:$ficha['var_0044'];
        $this->var0048 = ($ficha['var_0048']=='')?0:$ficha['var_0048'];
        $this->var0049 = ($ficha['var_0049']=='')?0:$ficha['var_0049'];
        $this->var0050 = ($ficha['var_0050']=='')?0:$ficha['var_0050'];
        $this->var0051 = $ficha['var_0051'];
        $this->var0052 = $ficha['var_0052'];
        $this->var0053 = $ficha['var_0053'];
        $this->var0054 = $ficha['var_0054'];    
    }
/*
 *   SETTER HC GESTACION ACTUAL
 */
    public function setHcGestacionActual($ficha){
        $this->var0055 = ($ficha['var_0055']=='') ? 'NULL' : $ficha['var_0055'];
        $this->var0056 = ($ficha['var_0056']=='') ? 'NULL' : $ficha['var_0056'];
        $this->var0057 = $ficha['var_0057'];
        $this->var0058 = $ficha['var_0058'];
        $this->var0059 = $ficha['var_0059'];
        $this->var0060 = $ficha['var_0060'];
        $this->var0076 = $ficha['var_0076'];
        $this->var0077 = $ficha['var_0077'];
        $this->var0078 = ($ficha['var_0078']=='') ? 'NULL' : $ficha['var_0078'];
        $this->var0079 = ($ficha['var_0079']=='') ? 'NULL' : $ficha['var_0079'];
        $this->var0080 = $ficha['var_0080'];
        $this->var0081 = $ficha['var_0081'];
        $this->var0082 = $ficha['var_0082'];
        $this->var0083 = $ficha['var_0083'];
        $this->var0084 = $ficha['var_0084'];
        $this->var0085 = $ficha['var_0085'];
        $this->var0086 = $ficha['var_0086'];
        $this->var0087 = $ficha['var_0087'];
        $this->var0088 = $ficha['var_0088'];
        $this->var0089 = $ficha['var_0089'];
        $this->var0090 = $ficha['var_0090'];
        $this->var0095 = ($ficha['var_0095']=='') ? 'NULL' : $ficha['var_0095'];
        $this->var0096 = $ficha['var_0096'];
        $this->var0097 = $ficha['var_0097'];
        $this->var0098 = $ficha['var_0098'];
        $this->var0099 = ($ficha['var_0099']=='') ? 'NULL' : $ficha['var_0099'];
        $this->var0100 = $ficha['var_0100'];
        $this->var0101 = $ficha['var_0101'];
        $this->var0102 = $ficha['var_0102'];
        $this->var0103 = $ficha['var_0103'];
        $this->var0104 = $ficha['var_0104'];
        $this->var0105 = ($ficha['var_0105']=='') ? 'NULL' : $ficha['var_0105'];
        $this->var0106 = $ficha['var_0106'];
        $this->var0107 = $ficha['var_0107'];
        $this->var0108 = ($ficha['var_0108']=='') ? 'NULL' : $ficha['var_0108'];
        $this->var0109 = $ficha['var_0109'];
        $this->var0110 = $ficha['var_0110'];
        $this->var0111 = $ficha['var_0111'];
        $this->var0091 = $ficha['var_0091'];
        $this->var0093 = $ficha['var_0093'];
        $this->var0112 = $ficha['var_0112'];
        $this->var0114 = $ficha['var_0114'];
        $this->var0115 = $ficha['var_0115'];
    }
/*
 * SETTER HC PARTO ABORTO
 */
    public function setHcPartoAborto($ficha){
        $this->var0182 = $ficha['var_0182'];
        $this->var0183 = $ficha['var_0183'];
        $this->var0184 = $ficha['var_0184'];
        $this->var0185 = ($ficha['var_0185']=='') ? 'NULL' : $ficha['var_0185'];
        $this->var0186 = $ficha['var_0186'];
        $this->var0187 = ($ficha['var_0187']=='') ? 'NULL' : $ficha['var_0187'];
        $this->var0188 = $ficha['var_0188'];
        $this->var0189 = (trim($ficha['var_0189'])=='') ? 'NULL' : $ficha['var_0189'];
        $this->var0190 = $ficha['var_0190'];
        $this->var0191 = $ficha['var_0191'];
        $this->var0192 = $ficha['var_0192'];
        $this->var0193 = $this->getPadL($ficha['var_0193H']).$this->getPadL($ficha['var_0193M']);
        $this->var0194 = $ficha['var_0194'];
        $this->var0195 = $ficha['var_0195'];
        $this->var0196 = $ficha['var_0196'];
        $this->var0197 = (trim($ficha['var_0197'])=='') ? 'NULL' : $ficha['var_0197'];
        $this->var0198 = ($ficha['var_0198']=='') ? 'NULL' : $ficha['var_0198'];
        $this->var0199 = ($ficha['var_0199']=='') ? 'NULL' : $ficha['var_0199'];
        $this->var0200 = $ficha['var_0200'];
        $this->var0201 = $ficha['var_0201'];
        $this->var0202 = $ficha['var_0202'];
        $this->var0203 = $ficha['var_0203'];
        $this->var0204 = $ficha['var_0204'];
        $this->var0205 = $ficha['var_0205'];
        $this->var0206 = $ficha['var_0206'];
        $this->var0257 = $ficha['var_0257'];
        $this->var0258 = $ficha['var_0258'];
        $this->var0259 = $ficha['var_0259'];
        $this->var0260 = $ficha['var_0260'];
        $this->var0261 = $ficha['var_0261'];
        $this->var0262 = $ficha['var_0262'];
        $this->var0263 = $ficha['var_0263'];
        $this->var0264 = $ficha['var_0264'];
        $this->var0266 = $ficha['var_0266'];
        $this->var0267 = $ficha['var_0267'];
        $this->var0268 = $ficha['var_0268'];
        $this->var0269 = $ficha['var_0269'];
        $this->var0270 = $ficha['var_0270'];
        $this->var0271 = $ficha['var_0271'];
        $this->var0272 = $ficha['var_0272'];
        $this->var0273 = $ficha['var_0273'];
        $this->var0274 = $ficha['var_0274'];
        $this->var0275 = $ficha['var_0275'];
        $this->var0276 = $ficha['var_0276'];
        $this->var0277 = $ficha['var_0277'];
        $this->var0278 = ($ficha['var_0278']=='') ? 'NULL' : $ficha['var_0278'];
        $this->var0279 = ($ficha['var_0279']=='') ? 'NULL' : $ficha['var_0279'];
        $this->var0280 = ($ficha['var_0280']=='') ? 'NULL' : $ficha['var_0280'];
        $this->var0282 = $ficha['var_0282'];
        $this->var0283 = $this->getPadL($ficha['var_0283H']).$this->getPadL($ficha['var_0283M']);
        $this->var0284 = $ficha['var_0284'];
        $this->var0285 = $ficha['var_0285'];
        $this->var0286 = (trim($ficha['var_0286'])=='') ? '0' : $ficha['var_0286'];
        $this->var0287 = $ficha['var_0287'];
        $this->var0288 = substr( trim($ficha['var_0288']), 0, 100);
        $this->var0289 = ($ficha['var_0289']=='') ? 'NULL' : $ficha['var_0289'];
        $this->var0290 = ($ficha['var_0290']=='') ? 'NULL' : $ficha['var_0290'];
        $this->var0291 = $ficha['var_0291'];
        $this->var0292 = $ficha['var_0292'];
        $this->var0293 = $ficha['var_0293'];
        $this->var0294 = ($ficha['var_0294']=='') ? 'NULL' : $ficha['var_0294'];
        $this->var0295 = $ficha['var_0295'];
        $this->var0296 = $ficha['var_0296'];
        $this->var0297 = $ficha['var_0297'];
        $this->var0298 = $ficha['var_0298'];
        $this->var0299 = $ficha['var_0299'];
        $this->var0300 = $ficha['var_0300'];
        $this->var0301 = $ficha['var_0301'];
        $this->var0302 = $ficha['var_0302'];
        $this->var0303 = $ficha['var_0303'];
        $this->var0304 = $ficha['var_0304'];
        $this->var0305 = $ficha['var_0305'];
        $this->var0306 = $ficha['var_0306'];
        $this->var0307 = $ficha['var_0307'];
        $this->var0308 = ($ficha['var_0308']=='') ? 'NULL' : $ficha['var_0308'];
        $this->var0309 = ($ficha['var_0309']=='') ? 'NULL' : $ficha['var_0309'];
    }
    
/*
 * SETTER HC RECIEN NACIDO
 */
public function setHcRecienNacido($ficha) {
        $this->var0310 = $ficha['var_0310'];
        $this->var0311 = ($ficha['var_0311']=='') ? 'NULL' : $ficha['var_0311'];
        $this->var0312 = $ficha['var_0312'];
        $this->var0313 = ($ficha['var_0313']=='') ? 'NULL' : $ficha['var_0313'];
        $this->var0314 = ($ficha['var_0314']=='') ? 'NULL' : $ficha['var_0314'];
        $this->var0315 = ($ficha['var_0315']=='') ? 'NULL' : $ficha['var_0315'];
        $this->var0316 = ($ficha['var_0316']=='') ? 'NULL' : $ficha['var_0316'];
        $this->var0319 = $ficha['var_0319'];
        $this->var0317 = $ficha['var_0317'];
        $this->var0318 = $ficha['var_0318'];
        $this->var0320 = $ficha['var_0320'];
        $this->var0321 = ($ficha['var_0321']=='') ? 'NULL' : $ficha['var_0321'];
        $this->var0322 = ($ficha['var_0322']=='') ? 'NULL' : $ficha['var_0322'];
        $this->var0323 = $ficha['var_0323'];
        $this->var0324 = $ficha['var_0324'];
        $this->var0325 = $ficha['var_0325'];
        $this->var0326 = $ficha['var_0326'];
        $this->var0327 = $ficha['var_0327'];
        $this->var0328 = $ficha['var_0328'];
        $this->var0329 = $ficha['var_0329'];
        $this->var0330 = $ficha['var_0330'];
        $this->var0331 = $ficha['var_0331'];
        $this->var0332 = substr( trim($ficha['var_0332']), 0, 50);
        $this->var0333 = $ficha['var_0333'];
        $this->var0334 = substr( trim($ficha['var_0334']), 0, 50);
        $this->var0335 = $ficha['var_0335'];
        $this->var0368 = ($ficha['var_0368']=='') ? 'NULL' : $ficha['var_0368'];
        $this->var0336 = $ficha['var_0336'];
        $this->var0337 = ($ficha['var_0337']=='') ? 'NULL' : $ficha['var_0337'];
        $this->var0338 = substr( trim($ficha['var_0338']), 0, 50);
        $this->var0339 = ($ficha['var_0339']=='') ? 'NULL' : $ficha['var_0339'];
        $this->var0340 = substr( trim($ficha['var_0340']), 0, 50);
        $this->var0341 = ($ficha['var_0341']=='') ? 'NULL' : $ficha['var_0341'];
        $this->var0342 = substr( trim($ficha['var_0342']), 0, 50);
        $this->var0343 = $ficha['var_0343'];
        $this->var0344 = $ficha['var_0344'];
        $this->var0345 = $ficha['var_0345'];
        $this->var0346 = $ficha['var_0346'];
        $this->var0347 = $ficha['var_0347'];
        $this->var0348 = $ficha['var_0348'];
        $this->var0367 = $ficha['var_0367'];
        $this->var0425 = $ficha['var_0425'];
        $this->var0370 = $this->getPadL($ficha['var_0370H']).$this->getPadL($ficha['var_0370M']);
        $this->var0371 = $ficha['var_0371'];
        $this->var0372 = trim($ficha['var_0372']);
        $this->var0373 = $ficha['var_0373'];
        $this->var0374 = ($ficha['var_0374']=='') ? 'NULL' : $ficha['var_0374'];
        $this->var0375 = $ficha['var_0375'];
        $this->var0376 = $ficha['var_0376'];
        $this->var0377 = $ficha['var_0377'];
        $this->var0378 = $ficha['var_0378'];
        $this->var0395 = ($ficha['var_0395']=='') ? 'NULL' : $ficha['var_0395'];
        $this->var0379 = $ficha['var_0379'];
        $this->var0380 = $ficha['var_0380'];
        $this->var0381 = substr( trim($ficha['var_0381']), 0, 10);
        $this->var0382 = $ficha['var_0382'];
        $this->var0383 = $ficha['var_0383'];
        $this->var0384 = ($ficha['var_0384']=='') ? 'NULL' : $ficha['var_0384'];
        $this->var0385 = $ficha['var_0385'];
        $this->var0386 = $ficha['var_0386'];
        $this->var0388 = substr( trim($ficha['var_0388']), 0, 30); 
        $this->var0389 = substr( trim($ficha['var_0389']), 0, 50);
        $this->var0390 = substr( trim($ficha['var_0390']), 0, 50);
        $this->var0391 = substr( trim($ficha['var_0391']), 0, 50);
}

    public function setHcEspeciales($ficha) {
        $this->var0432 = $ficha['var_0432'];
        $this->var0409 = ($ficha['var_0409']=='') ? 'NULL' : $ficha['var_0409'];
        $this->var0061 = $ficha['var_0061'];
        $this->var0062 = $ficha['var_0062'];
        $this->var0063 = $ficha['var_0063'];
        $this->var0064 = $ficha['var_0064'];
        $this->var0065 = $ficha['var_0065'];
        $this->var0066 = $ficha['var_0066'];
        $this->var0067 = $ficha['var_0067'];
        $this->var0068 = $ficha['var_0068'];
        $this->var0069 = $ficha['var_0069'];
        $this->var0070 = $ficha['var_0070'];
        $this->var0071 = $ficha['var_0071'];
        $this->var0072 = $ficha['var_0072'];
        $this->var0073 = $ficha['var_0073'];
        $this->var0074 = $ficha['var_0074'];
        $this->var0075 = $ficha['var_0075'];
        $this->var0410 = $ficha['var_0410'];
        $this->var0433 = $ficha['var_0433'];
        $this->var0434 = $ficha['var_0434'];
        $this->var0435 = $ficha['var_0435'];
        $this->var0436 = $ficha['var_0436'];
        $this->var0413 = ($ficha['var_0413']=='') ? 'NULL' : $ficha['var_0413'];
        $this->var0419 = ($ficha['var_0419']=='') ? 'NULL' : $ficha['var_0419'];
        $this->var0415 = $ficha['var_0415'];
        $this->var0414 = ($ficha['var_0414']=='') ? 'NULL' : $ficha['var_0414'];
        $this->var0420 = ($ficha['var_0420']=='') ? 'NULL' : $ficha['var_0420'];
        $this->var0421 = $ficha['var_0421'];
        $this->var0416 = ($ficha['var_0416']=='') ? 'NULL' : $ficha['var_0416'];
        $this->var0422 = ($ficha['var_0422']=='') ? 'NULL' : $ficha['var_0422'];
        $this->var0423 = $ficha['var_0423'];
        $this->var0418 = $ficha['var_0418'];
        $this->var0424 = $ficha['var_0424'];
        $this->var0437 = $ficha['var_0437'];
        $this->var0438 = $ficha['var_0438'];
        $this->var0439 = $ficha['var_0439'];
        $this->var0440 = $ficha['var_0440'];
        $this->var0441 = $ficha['var_0441'];
        $this->var0412 = $ficha['var_0412'];
        $this->var0411 = $ficha['var_0411'];
}

    /*
     * Si es editable la ficha. Solo se puede editar la última ficha. En cado de partos multiples se editan todas las del mismo parto.
     */
    public function esEditable(){
        if( $this->idHcPerinatal ){
            $sqlultima = "SELECT * FROM sip_clap.hcperinatal h INNER JOIN sip_clap.hcantecedentes a ON h.id_hcperinatal = a.id_hcperinatal
                    WHERE fecha_hora_carga = (SELECT MAX(fecha_hora_carga) FROM sip_clap.hcperinatal WHERE clave_beneficiario='$this->claveBeneficiario' )
                    AND clave_beneficiario='$this->claveBeneficiario'";
            $result = sql($sqlultima);
            return ($result->fields['fecha_hora_carga'] == $this->fechahoraCarga  || $result->fields['var_0040'] == $this->var0040 );
        }
        return true;
    }
    /*
     * Controla que no exista alguna ficha sin finalizar
     */
    public function permiteNuevaFicha(){
        if( $this->idHcPerinatal ){
            $sql = "SELECT count(*) as total FROM sip_clap.hcperinatal WHERE clave_beneficiario='$this->claveBeneficiario' and finalizado=0 ";
            $result = sql($sql);
            if($result->fields['total'] > 0) return false;      
            else return true;
        }
        return false;
    }

    /*
     * Buscar por clave de beneficiario
     */
    public static function buscarUltimaPorClaveBeneficiario($clave) {
        //Buscar siempre la última ficha creada
        $sql = "SELECT id_hcperinatal FROM sip_clap.hcperinatal
                WHERE fecha_hora_carga = 
                    (SELECT MAX(fecha_hora_carga) FROM sip_clap.hcperinatal WHERE clave_beneficiario='$clave' )
                AND clave_beneficiario='$clave'";
        $hc = sql($sql) or fin_pagina();
        $hcPerinatal = new HCPerinatal();
        if($hc->RecordCount()>0){
         //  if(!$hc->fields['finalizado']) {
                $result = HCPerinatal::getCompleteResult($hc->fields['id_hcperinatal']);
                if (!$result->EOF){
                    $hcPerinatal->construirResult($result);
                    $hcPerinatal->setIdHcPerinatal($hc->fields['id_hcperinatal']);
                }    
         //  }
        }
        return $hcPerinatal;
    }    
    
    /*
     * Buscar todas las fichas por clave
     */
    public static function buscarFichasPorClave($clave){
        $sql = "SELECT h.id_hcperinatal, h.finalizado, h.procesado, h.fecha_hora_carga, h.fecha_hora_proceso, p.var_0284 AS fecha_nacimiento, h.score_riesgo
                FROM sip_clap.hcperinatal h 
                INNER JOIN sip_clap.hcparto_aborto p ON h.id_hcperinatal = p.id_hcperinatal
                INNER JOIN sip_clap.hcantecedentes a ON h.id_hcperinatal = a.id_hcperinatal
                WHERE clave_beneficiario='$clave' ORDER BY fecha_hora_carga desc";
        $fichas = sql($sql);
        return $fichas;        
    }
            
    public function getHcPerinatalById($id){
        $hcPerinatal = new HCPerinatal();
        if (!empty($id)) {
          $hc = HCPerinatal::getCompleteResult($id);
          $hcPerinatal->construirResult($hc);
          $hcPerinatal->setIdHcPerinatal($id);
        }
        return $hcPerinatal;
    }

    /*
     * Obtener los datos de las distintas tablas para formar la ficha completa
     */
    public static function getCompleteResult($id){
        $sql = "SELECT *
                FROM sip_clap.hcperinatal p
                LEFT JOIN sip_clap.hcantecedentes a ON a.id_hcperinatal=p.id_hcperinatal
                LEFT JOIN sip_clap.hcgestacion_actual g ON g.id_hcperinatal=p.id_hcperinatal 
                LEFT JOIN sip_clap.hcparto_aborto pa ON pa.id_hcperinatal=p.id_hcperinatal 
                LEFT JOIN sip_clap.hcrecien_nacido r ON r.id_hcperinatal=p.id_hcperinatal
                LEFT JOIN sip_clap.hcespeciales e ON e.id_hcperinatal=p.id_hcperinatal ";
        $sql .= " WHERE p.id_hcperinatal = ".$id ;
        $result = sql($sql) or fin_pagina();
        return $result;
    }    
    /*
     * Limpiar los datos para el nacimiento multiple siguiente
     */
    public function limpiarDatosMultiples(){
        $this->idHcPerinatal = null;
        $this->finalizado = 0;
        $this->procesado = 0;
        $this->estado = 0;
        $this->scoreRiesgo = 0;
        $this->fechahoraCarga = date('Y-m-d H:i');
        $this->var0282 = $this->var0283 = $this->var0284 = $this->var0287 = $this->var0291 = $this->var0292 = $this->var0293 = $this->var0294 =
        $this->var0295 = $this->var0296 = $this->var0297 = $this->var0298 = $this->var0299 = $this->var0300 = $this->var0301 = $this->var0302 =
        $this->var0303 = $this->var0304 = $this->var0305 = $this->var0306 = $this->var0307 = null;
        $this->var0310 = $this->var0311 = $this->var0312 = $this->var0312 = $this->var0313 = $this->var0314 = $this->var0320 = $this->var0315 = 
        $this->var0321 = $this->var0322 = $this->var0323 = $this->var0324 = $this->var0325 = $this->var0326 = $this->var0327 = $this->var0316 = 
        $this->var0328 = $this->var0329 = $this->var0330 = $this->var0331 = $this->var0332 = $this->var0333 = $this->var0334 = $this->var0317 = null;
        $this->var0335 = $this->var0368 = $this->var0336 = $this->var0337 = $this->var0338 = $this->var0339 = $this->var0340 = $this->var0318 = 
        $this->var0341 = $this->var0342 = $this->var0440 = $this->var0441 = $this->var0343 = $this->var0412 = $this->var0343 = $this->var0319 = 
        $this->var0344 = $this->var0345 = $this->var0346 = $this->var0347 = $this->var0348 = $this->var0367 = $this->var0411 = null;
        $this->var0371 = $this->var0425 = $this->var0370 = $this->var0371 = $this->var0372 = $this->var0373 = $this->var0374 = $this->var0375 =
        $this->var0376 = $this->var0377 = $this->var0378 = $this->var0379 = $this->var0380 = $this->var0381 = $this->var0382 = $this->var0383 =
        $this->var0384 = $this->var0385 = $this->var0386 = $this->var0388 = $this->var0389 = $this->var0390 = $this->var0391 = $this->var0395 = null; 
    }

    /*
     * Cargar nueva ficha de embarazo multiple
     */
    public function crearSiguienteMultiple($id){
        $anterior = HCPerinatal::getCompleteResult($id);
        $this->construirResult($anterior);
        $this->limpiarDatosMultiples();
        // incrementar orden
        $this->var0286 = $anterior->fields['var_0286'] + 1;
        return $this;
    }
    
    /*
     * Cargar datos desde el beneficiario cuando no existe la ficha creada
     */
    public function setDatosDesdeBeneficiario($benef){
        // buscar si esta embarazada - uad.Beneficiarios.menor_embarazada
        // obtener fpp y fum - consultar si se da prioridad a fpp cargada o calcular
        $this->claveBeneficiario = $benef->getClaveBeneficiario();
        $this->var0001 = $benef->getNombreBenef();
        $this->var0002 = $benef->getApellidoBenef();
        $nrocalle = ($benef->getNumeroCalle())?''.$benef->getNumeroCalle():'';
        $this->var0003 = trim($benef->getCalle()).' '.trim($nrocalle);
        $this->var0004 = $benef->getLocalidad();
        $this->var0005 = $benef->getTelefono();
        $this->var0006 = $benef->getFechaNacimientoBenef();
        switch ($benef->getEstudios()) {
            case 'PRIMARIO': $this->var0013 = 'B';
                break;
            case 'SECUNDARIO': $this->var0013 = 'C';
                break;
            case 'UNIVERSITARIO': $this->var0013 = 'D';
                break;
        }
        $this->var0014 = ($benef->getAnioMayorNivel()==0)?'':$benef->getAnioMayorNivel();
        $this->var0011 = ($benef->getIndigena()=='S') ? 'B' : '';
        switch ($benef->getAlfabeta()) {
            case 'N': $this->var0012 = 'A';
                break;
            case 'S': $this->var0012 = 'B';
                break;
            default : $this->var0012 = '';
        }
        $this->var0019 = $benef->getNumeroDoc();
        // Marca de embarazo
        if($benef->getFechaDiagnosticoEmbarazo()!='1899-12-30' && $benef->getFechaDiagnosticoEmbarazo()!='' && $benef->getFechaDiagnosticoEmbarazo()!='0' ){
            $this->var0057 = ($benef->getFum()!='1899-12-30')?$benef->getFum():'';
            $this->var0058 = ($benef->getFechaProbableParto()!='1899-12-30')?$benef->getFechaProbableParto():'';
        }
    }
    public function setFicha($ficha){
        /* Setear valores del formulario */
        $this->setHcPerinatal($ficha);
        $this->setHcAntecedentes($ficha);
        $this->setHcGestacionActual($ficha);
        $this->setHcPartoAborto($ficha);
        $this->setHcRecienNacido($ficha);
        $this->setHcEspeciales($ficha);
        return $this;
    }
    public function setHcScoreRiesgo($ficha) {
        $nuevoScoreRiesgo = 0;
        $edad_madre = intval($ficha['var_0009']);
        $madre_sin_pareja = ($ficha['var_0016'] == 'B');
        $educacion = $ficha['var_0013'];
        $gestas_previas = intval($ficha['var_0040']);
        $fecha_ult_embarazo = strtotime($this->convertirFechaADb($ficha['var_0051']));
        $inmunizacion = ($ficha['var_0087'] == 'B');
        $abortos = intval($ficha['var_0041']);
        $fuma_act_1_trim = ($ficha['var_0061'] == 'B');
        $fuma_act_2_trim = ($ficha['var_0066'] == 'B');
        $fuma_act_3_trim = ($ficha['var_0071'] == 'B');
        $hipertension = ($ficha['var_0025'] == 'B');
        $diabetes = ($ficha['var_0106'] == 'X' || $ficha['var_0107'] == 'X');
        $vdrl = ($ficha['var_0343'] == 'B');
        $menor2500 = ($ficha['var_0038'] == 'B');
        $ant_nac_muertos = $ficha['var_0048'];
        $ant_nac_muertos_1_dec = $ficha['var_0049'];
        $ant_nac_muertos_1_des_dec = $ficha['var_0050'];
        $imc=($ficha['var_0055']/(($ficha['var_0056']/100)*($ficha['var_0056']/100)));

        if ($edad_madre < 15) {
          $nuevoScoreRiesgo += 1;
        } 
        if ($edad_madre >= 35) {
          $nuevoScoreRiesgo += 2;
        }
        if ($madre_sin_pareja) {
          $nuevoScoreRiesgo += 1;
        }
        if ($educacion == 'A') { // Ninguna
          $nuevoScoreRiesgo += 2;
        }
        if ($educacion == 'B') { // Primaria
          $nuevoScoreRiesgo += 1;
        }
        if ($gestas_previas == 0) {
          $nuevoScoreRiesgo += 1;
        }
        if ($gestas_previas >= 4) {
          $nuevoScoreRiesgo += 2;
        }
        if ($fecha_ult_embarazo !== false && $fecha_ult_embarazo > strtotime('-1 year')) {
          $nuevoScoreRiesgo += 4;
        }
        if ($inmunizacion) {
          $nuevoScoreRiesgo += 4;
        }
        if ($abortos > 0) {
          $nuevoScoreRiesgo += 1;
        }
        if ($fuma_act_1_trim || $fuma_act_2_trim || $fuma_act_3_trim) {
          $nuevoScoreRiesgo += 1;
        }
        if ($hipertension) {
          $nuevoScoreRiesgo += 4;
        }
        if ($diabetes) {
          $nuevoScoreRiesgo += 3;
        }
        if ($vdrl) {
          $nuevoScoreRiesgo += 4;
        }
        if ($menor2500) {
          $nuevoScoreRiesgo += 2;
        }
        if ($ant_nac_muertos > 0) {
          $nuevoScoreRiesgo += 4;
        }
        if ($ant_nac_muertos_1_dec > 0) {
          $nuevoScoreRiesgo += 3;
        }
        if ($ant_nac_muertos_1_des_dec > 0) {
          $nuevoScoreRiesgo += 3;
        }
        if ($imc > 29) {
          $nuevoScoreRiesgo += 1;
        }

        // Si es distinto cambio score.
        if ($nuevoScoreRiesgo != $this->scoreRiesgo) {
          $this->scoreRiesgo = $nuevoScoreRiesgo;
        }
        if ($this->scoreRiesgo==''){
          $this->scoreRiesgo = $nuevoScoreRiesgo;
        }

        //echo $nuevoScoreRiesgo;
    }
    public function saveFichaSip($ficha) {
        /* Setear valores del formulario */
        $this->setHcPerinatal($ficha);
        $this->setHcAntecedentes($ficha);
        $this->setHcGestacionActual($ficha);
        $this->setHcPartoAborto($ficha);
        $this->setHcRecienNacido($ficha);
        $this->setHcEspeciales($ficha);
        $this->setHcScoreRiesgo($ficha);

        if( empty($this->idHcPerinatal) ){
            // crear nueva ficha
            $this->Insertar();            
        }else{
            // actualizar datos
            $this->Actualizar();
        }
        return $this->idHcPerinatal;
    }

    public function Insertar() {
      global $db;
        // HC PERINATAL
            $sql_perinatal = $this->getSqlInsertHcPerinatal();
            $result = sql(mb_convert_encoding($sql_perinatal,'UTF-8'));
            $this->idHcPerinatal = $result->fields["id_hcperinatal"];
            // HC ANTECEDENTES
            $sql_antecedentes = $this->getSqlInsertHcAntecedentes();
            $result = sql(mb_convert_encoding($sql_antecedentes,'UTF-8'));
            //HC GESTACION ACTUAL
            $sql_gestact = $this->getSqlInsertHcGestacionActual();
            $result = sql(mb_convert_encoding($sql_gestact,'UTF-8'));
            //HC PARTO ABORTO
            $sql_parto = $this->getSqlInsertHcPartoAborto();
            $result = sql(mb_convert_encoding($sql_parto,'UTF-8'));
            //HC RECIEN NACIDO
            $sql_rn = $this->getSqlInsertHcRecienNacido();
            $result = sql(mb_convert_encoding($sql_rn,'UTF-8'));
            //HC ESPECIALES
            $sql_esp = $this->getSqlInsertHcEspeciales();
            $result = sql(mb_convert_encoding($sql_esp,'UTF-8'));
    }

    /* INSERT HC PERINATAL */
    public function getSqlInsertHcPerinatal() {
        $var0003 = utf8_decode($this->var0003);
        $sql = "INSERT INTO sip_clap.hcperinatal(
                    clave_beneficiario,var_0001,var_0002,var_0003,var_0004,var_0005,
                    var_0006,var_0009,var_0010,var_0011,var_0012,var_0013,var_0014,
                    var_0015,var_0016,var_0017,var_0018,var_0019,usuario_carga,fecha_hora_carga, finalizado, score_riesgo)
            VALUES ('$this->claveBeneficiario','$this->var0001','$this->var0002','$var0003','$this->var0004','$this->var0005',   
                    '" . $this->convertirFechaADb($this->var0006) . "',$this->var0009,'$this->var0010','$this->var0011','$this->var0012','$this->var0013',$this->var0014,
                    '$this->var0015','$this->var0016','$this->var0017','$this->var0018','$this->var0019', $this->usuarioCarga,current_timestamp, $this->finalizado, $this->scoreRiesgo )
         RETURNING id_hcperinatal;";

        
        return($sql);
    }
    
    /* INSERT HC ANTECEDENTES */   
    public function getSqlInsertHcAntecedentes() {
        $fechaEmbAnt = (empty($this->var0051)? 'NULL' : "'".$this->convertirFechaADb($this->var0051)."'" );
        $sql = "INSERT INTO sip_clap.hcantecedentes(
                    id_hcperinatal,var_0020, var_0022, var_0024, var_0026, var_0028, var_0030,
                    var_0021, var_0023, var_0025, var_0027, var_0029, var_0031, var_0032,
                    var_0033, var_0034, var_0035, var_0036, var_0037, var_0040, var_0041, 
                    var_0045, var_0046, var_0038, var_0039, var_0042, var_0047, var_0043, var_0044, 
                    var_0048, var_0049, var_0050, var_0051, var_0052, var_0053, var_0054)
            VALUES ($this->idHcPerinatal,'$this->var0020','$this->var0022','$this->var0024','$this->var0026','$this->var0028','$this->var0030',
                    '$this->var0021','$this->var0023','$this->var0025','$this->var0027','$this->var0029','$this->var0031','$this->var0032',
                    '$this->var0033', '$this->var0034','$this->var0035', '$this->var0036', '$this->var0037',$this->var0040,$this->var0041,
                    '$this->var0045',$this->var0046,'$this->var0038','$this->var0039',$this->var0042, $this->var0047,$this->var0043,$this->var0044, 
                    $this->var0048, $this->var0049, $this->var0050, ". $fechaEmbAnt .",'$this->var0052','$this->var0053','$this->var0054' )                    
            RETURNING id_hcperinatal;";
        return($sql);
    }
    /* INSERT HC GESTACION ACTUAL */
    public function getSqlInsertHcGestacionActual() {
        $fechaFUM = (empty($this->var0057)? 'NULL' : "'".$this->convertirFechaADb($this->var0057)."'" );
        $fechaFPP = (empty($this->var0058)? 'NULL' : "'".$this->convertirFechaADb($this->var0058)."'" );
        $sql = "INSERT INTO sip_clap.hcgestacion_actual(
                id_hcperinatal, var_0055, var_0056, var_0057, var_0058, var_0059, var_0060, 
                var_0076, var_0077, var_0078, var_0079, var_0080, var_0081, var_0082, 
                var_0083, var_0084, var_0085, var_0086, var_0087, var_0088, var_0089, 
                var_0090, var_0095, var_0096, var_0097, var_0098, var_0099, var_0100,
                var_0101, var_0102, var_0103, var_0104, var_0105, var_0106, var_0107, 
                var_0108, var_0109, var_0110, var_0111, var_0091, var_0093, var_0112,
                var_0114, var_0115)
            VALUES ($this->idHcPerinatal,$this->var0055,$this->var0056,".$fechaFUM.",".$fechaFPP.",  '$this->var0059','$this->var0060',
                '$this->var0076','$this->var0077', $this->var0078,  $this->var0079, '$this->var0080','$this->var0081','$this->var0082',
                '$this->var0083','$this->var0084','$this->var0085','$this->var0086','$this->var0087','$this->var0088','$this->var0089',
                '$this->var0090', $this->var0095, '$this->var0096','$this->var0097','$this->var0098', $this->var0099, '$this->var0100',
                '$this->var0101','$this->var0102','$this->var0103','$this->var0104', $this->var0105, '$this->var0106','$this->var0107',
                 $this->var0108, '$this->var0109','$this->var0110','$this->var0111','$this->var0091','$this->var0093','$this->var0112',
                '$this->var0114','$this->var0115')
            RETURNING id_hcperinatal;";
        return($sql);
    }
    /* INSERT HC PARTO ABORTO */
    public function getSqlInsertHcPartoAborto() {
        $fechaIngreso = (empty($this->var0183)? 'NULL' : "'".$this->convertirFechaADb($this->var0183)."'" );
        $rupturaMembranaFecha = (empty($this->var0192)? 'NULL' : "'".$this->convertirFechaADb($this->var0192)."'" );
        $nacimientoDia = (empty($this->var0284)? 'NULL' : "'".$this->convertirFechaADb($this->var0284)."'" );
        $sql = "INSERT INTO sip_clap.hcparto_aborto(
                    id_hcperinatal, var_0182, var_0183, var_0184, var_0185, var_0186, var_0187, 
                    var_0188,var_0189,var_0190,var_0191,var_0192,var_0193,var_0194,
                    var_0195,var_0196,var_0197,var_0198,var_0199,var_0200,var_0201,var_0202,
                    var_0203,var_0204,var_0205,var_0206,var_0257,var_0258,var_0259,
                    var_0260,var_0261,var_0262,var_0263,var_0264,var_0266,var_0267,
                    var_0268,var_0269,var_0270,var_0271,var_0272,var_0273,var_0274,
                    var_0275,var_0276,var_0277,var_0278,var_0279,var_0280,var_0282,var_0283,
                    var_0284,var_0285,var_0286,var_0287,var_0288,var_0289,var_0290,var_0291,
                    var_0292,var_0293,var_0294,var_0295,var_0296,var_0297,var_0298,var_0299,
                    var_0300,var_0301,var_0302,var_0303,var_0304,var_0305,var_0306,
                    var_0307,var_0308,var_0309)
            VALUES ($this->idHcPerinatal,'$this->var0182',".$fechaIngreso.",'$this->var0184',$this->var0185,'$this->var0186',$this->var0187,
                    '$this->var0188',$this->var0189,'$this->var0190','$this->var0191',".$rupturaMembranaFecha.",'$this->var0193','$this->var0194',
                    '$this->var0195','$this->var0196',$this->var0197,$this->var0198,$this->var0199,'$this->var0200','$this->var0201','$this->var0202',
                    '$this->var0203','$this->var0204','$this->var0205','$this->var0206','$this->var0257','$this->var0258','$this->var0259',
                    '$this->var0260','$this->var0261','$this->var0262','$this->var0263','$this->var0264','$this->var0266','$this->var0267',
                    '$this->var0268','$this->var0269','$this->var0270','$this->var0271','$this->var0272','$this->var0273','$this->var0274',
                    '$this->var0275','$this->var0276','$this->var0277',$this->var0278,$this->var0279,$this->var0280,'$this->var0282','$this->var0283',
                    ".$nacimientoDia.",'$this->var0285',$this->var0286,'$this->var0287','$this->var0288',$this->var0289,$this->var0290,'$this->var0291',
                    '$this->var0292','$this->var0293',$this->var0294,'$this->var0295','$this->var0296','$this->var0297','$this->var0298','$this->var0299',  
                    '$this->var0300','$this->var0301','$this->var0302','$this->var0303','$this->var0304','$this->var0305','$this->var0306',                    
                    '$this->var0307',$this->var0308,$this->var0309 )
         RETURNING id_hcperinatal;";
        return($sql);
    }
    /* INSERT HC RECIEN NACIDO */
    public function getSqlInsertHcRecienNacido() {
        $fechaEgreso = (empty($this->var0425)? 'NULL' : "'".$this->convertirFechaADb($this->var0425)."'" );
        $fechaEgresoMaterno = (empty($this->var0379)? 'NULL' : "'".$this->convertirFechaADb($this->var0379)."'" );
        $sql = "INSERT INTO sip_clap.hcrecien_nacido(
                id_hcperinatal, var_0310, var_0311, var_0312, var_0313, var_0314, var_0315, 
                var_0316, var_0319, var_0317, var_0318, var_0320, var_0321, var_0322,
                var_0323, var_0324, var_0325, var_0326, var_0327, var_0328, var_0329,
                var_0330, var_0331, var_0332, var_0333, var_0334, var_0335, var_0368,
                var_0336, var_0337, var_0338, var_0339, var_0340, var_0341, var_0342,
                var_0343, var_0344, var_0345, var_0346, var_0347, var_0348, var_0367,
                var_0425, var_0370, var_0371, var_0372, var_0373, var_0374, var_0375,
                var_0376, var_0377, var_0378, var_0395, var_0379, var_0380, var_0381,
                var_0382, var_0383, var_0384, var_0385,var_0386,
            var_0388,var_0389,var_0390,var_0391)
            VALUES ($this->idHcPerinatal, '$this->var0310', $this->var0311,'$this->var0312', $this->var0313, $this->var0314, $this->var0315,
                 $this->var0316 , '$this->var0319', '$this->var0317', '$this->var0318', '$this->var0320',  $this->var0321 ,  $this->var0322,
                '$this->var0323', '$this->var0324', '$this->var0325', '$this->var0326', '$this->var0327', '$this->var0328', '$this->var0329',
                '$this->var0330', '$this->var0331', '$this->var0332', '$this->var0333', '$this->var0334', '$this->var0335',  $this->var0368,
                '$this->var0336',  $this->var0337 , '$this->var0338',  $this->var0339 , '$this->var0340',  $this->var0341 , '$this->var0342',
                '$this->var0343', '$this->var0344', '$this->var0345', '$this->var0346', '$this->var0347', '$this->var0348', '$this->var0367',
                ".$fechaEgreso.", '$this->var0370', '$this->var0371', '$this->var0372', '$this->var0373',  $this->var0374 , '$this->var0375',
                '$this->var0376', '$this->var0377', '$this->var0378',  $this->var0395, ".$fechaEgresoMaterno.", '$this->var0380', '$this->var0381',
                '$this->var0382', '$this->var0383',  $this->var0384 , '$this->var0385', '$this->var0386', '$this->var0388', '$this->var0389',
                '$this->var0390', '$this->var0391' )
        RETURNING id_hcperinatal;";
        return($sql);            
    }
    
    /* INSERT HC ESPECIALES */
    public function getSqlInsertHcEspeciales() {
        $sql = "INSERT INTO sip_clap.hcespeciales(
            id_hcperinatal, var_0432, var_0409, var_0061, var_0062, var_0063, var_0064,
            var_0065, var_0066, var_0067, var_0068, var_0069, var_0070, var_0071,
            var_0072, var_0073, var_0074, var_0075, var_0410, var_0433, var_0434,
            var_0435, var_0436, var_0413, var_0419, var_0415, var_0414, var_0420,
            var_0421, var_0416, var_0422, var_0423, var_0418, var_0424, var_0437,
            var_0438, var_0439, var_0440, var_0441, var_0412, var_0411)
            VALUES ($this->idHcPerinatal, '$this->var0432', $this->var0409, '$this->var0061', '$this->var0062', '$this->var0063', '$this->var0064',
                '$this->var0065', '$this->var0066', '$this->var0067', '$this->var0068', '$this->var0069', '$this->var0070', '$this->var0071',
                '$this->var0072', '$this->var0073', '$this->var0074', '$this->var0075', '$this->var0410', '$this->var0433', '$this->var0434', 
                '$this->var0435', '$this->var0436',  $this->var0413 ,  $this->var0419 , '$this->var0415',  $this->var0414 ,  $this->var0420,
                '$this->var0421',  $this->var0416 ,  $this->var0422 , '$this->var0423', '$this->var0418', '$this->var0424', '$this->var0437', 
                '$this->var0438', '$this->var0439', '$this->var0440', '$this->var0441', '$this->var0412', '$this->var0411' )
            RETURNING id_hcperinatal;";
        return($sql); 
    }
    
    public function Actualizar() {
        try{
            $sql_perinatal = $this->getSQlUpdateHcPerinatal();
            

            $result = sql(mb_convert_encoding($sql_perinatal,'UTF-8')); 
            // HC ANTECEDENTES
            $sql_antecedentes = $this->getSQlUpdateHcAntecedentes();
            

            $result = sql(mb_convert_encoding($sql_antecedentes,'UTF-8'));
            // or excepcion("HC ANTECEDENTES");
            //HC GESTACION ACTUAL
            $sql_gestact = $this->getSQlUpdateHcGestacionActual();
            

            $result = sql(mb_convert_encoding($sql_gestact,'UTF-8'));
            // or excepcion("HC GESTACION ACTUAL");
            //HC PARTO ABORTO
            $sql_parto = $this->getSQlUpdateHcPartoAborto();
            

            $result = sql(mb_convert_encoding($sql_parto,'UTF-8'));
            // or excepcion("HC PARTO ABORTO");
            //HC RECIEN NACIDO
            $sql_rn = $this->getSQlUpdateHcRecienNacido();
            

            $result = sql(mb_convert_encoding($sql_rn,'UTF-8'));
            // or excepcion("HC RECIEN NACIDO");
            //HC ESPECIALES
            $sql_esp = $this->getSQlUpdateHcEspeciales();
            

            $result = sql(mb_convert_encoding($sql_esp,'UTF-8'));
            // or excepcion("HC ESPECIALES");
        } catch (Exception $e) {
            sql("ROLLBACK", "Error en rollback", 0);
            echo "Error: " . $e->getError() . "<br>";
        }              
    }
    /* UPDATE HC PERINATAL */  
    public function getSQlUpdateHcPerinatal() {
        $sql = "UPDATE sip_clap.hcperinatal
                SET var_0011='$this->var0011', var_0012='$this->var0012', var_0013='$this->var0013', var_0014=$this->var0014,
                    var_0015='$this->var0015', var_0016='$this->var0016', var_0017='$this->var0017',var_0018='$this->var0018',
                    finalizado=$this->finalizado, usuario_ultact=$this->usuarioCarga, fecha_hora_ultact=current_timestamp,
                    score_riesgo=$this->scoreRiesgo
                WHERE id_hcperinatal=".$this->idHcPerinatal;
        return($sql);
    }
    /* UPDATE HC ANTECEDENTES */  
    public function getSQlUpdateHcAntecedentes() {
        $fechaEmbAnt = (empty($this->var0051)? 'NULL' : "'".$this->convertirFechaADb($this->var0051)."'" );
        $sql = "UPDATE sip_clap.hcantecedentes
                SET var_0020='$this->var0020', var_0022='$this->var0022', var_0024='$this->var0024', var_0026='$this->var0026',
                    var_0028='$this->var0028', var_0030='$this->var0030', var_0021='$this->var0021', var_0023='$this->var0023', 
                    var_0025='$this->var0025', var_0027='$this->var0027', var_0029='$this->var0029', var_0031='$this->var0031', 
                    var_0032='$this->var0032', var_0033='$this->var0033', var_0034='$this->var0034', var_0035='$this->var0035', 
                    var_0036='$this->var0036', var_0037='$this->var0037', var_0040='$this->var0040', var_0041=$this->var0041, 
                    var_0045='$this->var0045', var_0046= $this->var0046 , var_0038='$this->var0038', var_0039='$this->var0039', 
                    var_0042= $this->var0042,   var_0047= $this->var0047, var_0043= $this->var0043 , var_0044=$this->var0044, 
                    var_0048= $this->var0048,   var_0049= $this->var0049, var_0050= $this->var0050 , var_0051=" . $fechaEmbAnt . ", 
                    var_0052='$this->var0052', var_0053='$this->var0053', var_0054='$this->var0054'
                WHERE id_hcperinatal=".$this->idHcPerinatal;
        return($sql);
    }
    /* UPDATE HC GESTACION ACTUAL */  
    public function getSQlUpdateHcGestacionActual() {
        $fechaFUM = (empty($this->var0057)? 'NULL' : "'".$this->convertirFechaADb($this->var0057)."'" );
        $fechaFPP = (empty($this->var0058)? 'NULL' : "'".$this->convertirFechaADb($this->var0058)."'" );
        $sql = "UPDATE sip_clap.hcgestacion_actual 
                SET var_0055=$this->var0055, var_0056=$this->var0056, var_0057=". $fechaFUM.", var_0058= ". $fechaFPP .", 
                    var_0059='$this->var0059', var_0060='$this->var0060', var_0076='$this->var0076', var_0077='$this->var0077', 
                    var_0078= $this->var0078,  var_0079= $this->var0079,  var_0080='$this->var0080', var_0081='$this->var0081', 
                    var_0082='$this->var0082', var_0083='$this->var0083', var_0084='$this->var0084', var_0085='$this->var0085', 
                    var_0086='$this->var0086', var_0087='$this->var0087', var_0088='$this->var0088', var_0089='$this->var0089', 
                    var_0090='$this->var0090', var_0095= $this->var0095,  var_0096='$this->var0096', var_0097='$this->var0097', 
                    var_0098='$this->var0098', var_0099= $this->var0099,  var_0100='$this->var0100', var_0101='$this->var0101', 
                    var_0102='$this->var0102', var_0103='$this->var0103', var_0104='$this->var0104', var_0105= $this->var0105, 
                    var_0106='$this->var0106', var_0107='$this->var0107', var_0108= $this->var0108,  var_0109='$this->var0109', 
                    var_0110='$this->var0110', var_0111='$this->var0111', var_0091='$this->var0091', var_0093='$this->var0093', 
                    var_0112='$this->var0112', var_0114='$this->var0114', var_0115='$this->var0115'
                WHERE id_hcperinatal=".$this->idHcPerinatal;
        return($sql);
    }    
    /* UPDATE HC PARTO ABORTO */  
    public function getSQlUpdateHcPartoAborto() {
        $fechaIngreso = (empty($this->var0183)? 'NULL' : "'".$this->convertirFechaADb($this->var0183)."'" );
        $rupturaMembranaFecha = (empty($this->var0192)? 'NULL' : "'".$this->convertirFechaADb($this->var0192)."'" );
        $nacimientoDia = (empty($this->var0284)? 'NULL' : "'".$this->convertirFechaADb($this->var0284)."'" );
        $sql = "UPDATE sip_clap.hcparto_aborto
                SET var_0182='$this->var0182', var_0183=".$fechaIngreso.", var_0184='$this->var0184', var_0185=$this->var0185, 
                    var_0186='$this->var0186', var_0187= $this->var0187 , var_0188='$this->var0188', var_0189=$this->var0189,
                    var_0190='$this->var0190', var_0191='$this->var0191', var_0192=".$rupturaMembranaFecha.", var_0193='$this->var0193',
                    var_0194='$this->var0194', var_0195='$this->var0195', var_0196='$this->var0196', var_0197= $this->var0197,
                    var_0198= $this->var0198 , var_0199= $this->var0199 , var_0200='$this->var0200', var_0201='$this->var0201',
                    var_0202='$this->var0202', var_0203='$this->var0203', var_0204='$this->var0204', var_0205='$this->var0205',
                    var_0206='$this->var0206', var_0257='$this->var0257', var_0258='$this->var0258', var_0259='$this->var0259',
                    var_0260='$this->var0260', var_0261='$this->var0261', var_0262='$this->var0262', var_0263='$this->var0263',
                    var_0264='$this->var0264', var_0266='$this->var0266', var_0267='$this->var0267', var_0268='$this->var0268',
                    var_0269='$this->var0269', var_0270='$this->var0270', var_0271='$this->var0271', var_0272='$this->var0272',
                    var_0273='$this->var0273', var_0274='$this->var0274', var_0275='$this->var0275', var_0276='$this->var0276',
                    var_0277='$this->var0277', var_0278= $this->var0278 , var_0279= $this->var0279 , var_0280= $this->var0280,
                    var_0282='$this->var0282', var_0283='$this->var0283', var_0284=".$nacimientoDia.", var_0285='$this->var0285',
                    var_0286= $this->var0286 , var_0287='$this->var0287', var_0288='$this->var0288', var_0289=$this->var0289,
                    var_0290= $this->var0290 , var_0291='$this->var0291', var_0292='$this->var0292', var_0293='$this->var0293',
                    var_0294= $this->var0294 , var_0295='$this->var0295', var_0296='$this->var0296', var_0297='$this->var0297',
                    var_0298='$this->var0298', var_0299='$this->var0299', var_0300='$this->var0300', var_0301='$this->var0301',
                    var_0302='$this->var0302', var_0303='$this->var0303', var_0304='$this->var0304', var_0305='$this->var0305',
                    var_0306='$this->var0306', var_0307='$this->var0307', var_0308= $this->var0308 , var_0309=$this->var0309
                WHERE id_hcperinatal=".$this->idHcPerinatal;
        return($sql);
    }
    
/* UPDATE HC RECIEN NACIDO */  
    public function getSQlUpdateHcRecienNacido() {
        $fechaEgreso = (empty($this->var0425)? 'NULL' : "'".$this->convertirFechaADb($this->var0425)."'" );
        $fechaEgresoMaterno = (empty($this->var0379)? 'NULL' : "'".$this->convertirFechaADb($this->var0379)."'" );
        $sql = "UPDATE sip_clap.hcrecien_nacido
                SET var_0310='$this->var0310', var_0311= $this->var0311 , var_0312='$this->var0312', var_0313= $this->var0313, 
                    var_0314= $this->var0314 , var_0315= $this->var0315 , var_0316= $this->var0316 , var_0319='$this->var0319',
                    var_0317='$this->var0317', var_0318='$this->var0318', var_0320='$this->var0320', var_0321= $this->var0321, 
                    var_0322= $this->var0322 , var_0323='$this->var0323', var_0324='$this->var0324', var_0325='$this->var0325', 
                    var_0326='$this->var0326', var_0327='$this->var0327', var_0328='$this->var0328', var_0329='$this->var0329',
                    var_0330='$this->var0330', var_0331='$this->var0331', var_0332='$this->var0332', var_0333='$this->var0333', 
                    var_0334='$this->var0334', var_0335='$this->var0335', var_0368= $this->var0368 , var_0336='$this->var0336', 
                    var_0337= $this->var0337 , var_0338='$this->var0338', var_0339= $this->var0339 , var_0340='$this->var0340', 
                    var_0341= $this->var0341 , var_0342='$this->var0342', var_0343='$this->var0343', var_0344='$this->var0344', 
                    var_0345='$this->var0345', var_0346='$this->var0346', var_0347='$this->var0347', var_0348='$this->var0348', 
                    var_0367='$this->var0367', var_0425=".$fechaEgreso.", var_0370='$this->var0370', var_0371='$this->var0371', 
                    var_0372='$this->var0372', var_0373='$this->var0373', var_0374= $this->var0374 , var_0375='$this->var0375',
                    var_0376='$this->var0376', var_0377='$this->var0377', var_0378='$this->var0378', var_0395= $this->var0395, 
                    var_0379=".$fechaEgresoMaterno.", var_0380='$this->var0380', var_0381='$this->var0381', var_0382='$this->var0382',
                    var_0383='$this->var0383', var_0384= $this->var0384 , var_0385='$this->var0385', var_0386='$this->var0386',
                    var_0388='$this->var0388', var_0389='$this->var0389', var_0390='$this->var0390', var_0391='$this->var0391'
        WHERE id_hcperinatal=".$this->idHcPerinatal;
        return($sql);
    }       
    
/* UPDATE HC RECIEN NACIDO */  
    public function getSQlUpdateHcEspeciales() {
        $sql = "UPDATE sip_clap.hcespeciales
                SET var_0432='$this->var0432', var_0409= $this->var0409,  var_0061='$this->var0061', var_0062='$this->var0062', 
                    var_0063='$this->var0063', var_0064='$this->var0064', var_0065='$this->var0065', var_0066='$this->var0066', 
                    var_0067='$this->var0067', var_0068='$this->var0068', var_0069='$this->var0069', var_0070='$this->var0070', 
                    var_0071='$this->var0071', var_0072='$this->var0072', var_0073='$this->var0073', var_0074='$this->var0074', 
                    var_0075='$this->var0075', var_0410='$this->var0410', var_0433='$this->var0433', var_0434='$this->var0434',
                    var_0435='$this->var0435', var_0436='$this->var0436', var_0413= $this->var0413,  var_0419= $this->var0419, 
                    var_0415='$this->var0415', var_0414= $this->var0414,  var_0420= $this->var0420,  var_0421='$this->var0421', 
                    var_0416= $this->var0416,  var_0422= $this->var0422,  var_0423='$this->var0423', var_0418='$this->var0418', 
                    var_0424='$this->var0424', var_0437='$this->var0437', var_0438='$this->var0438', var_0439='$this->var0439', 
                    var_0440='$this->var0440', var_0441='$this->var0441', var_0412='$this->var0412', var_0411='$this->var0411' 
            WHERE id_hcperinatal=".$this->idHcPerinatal;
        return($sql);
    }
    
    public function deleteFichaSip($ficha){
        $sql = "DELETE FROM sip_clap.hcperinatal
            WHERE id_hcperinatal=".$ficha['idHcPerinatal'];
        $result = sql($sql);
    }


    public function getFichasByPeriodo($desde,$hasta,$efector){
        $slqEfector = '';
        if( !empty($efector) ){
            $sqlEfector = " AND var_0018='".$efector."' ";
        }
        $sql = "SELECT id_hcperinatal FROM sip_clap.hcperinatal ";
        $sql .= "inner join sip_clap.hcparto_aborto using(id_hcperinatal) ";
        $sql .= "WHERE var_0284 between '$desde' AND '$hasta' AND finalizado = 1 ".$sqlEfector ;
        
        $result = sql($sql);
        return $result;
    }

    // Helpers

    // Cambio de fecha
    public function convertirFechaADb($fecha){
        $data = explode("-", $fecha);

        if (count($data) == 3) {
            if (strlen($data[2]) == 4) {
                $data = $data[2]."-".$data[1]."-".$data[0];
            }else{
                $data = $fecha;
            }
        }else{
            $data = $fecha;
        }

        return($data);
    }


    /*select * from facturacion.comprobante c 
inner join facturacion.prestacion p on p.id_comprobante=c.id_comprobante
inner join facturacion.nomenclador n on n.id_nomenclador=p.id_nomenclador
inner join trazadoras.embarazadas e on 
     ( e.cuie=c.cuie and e.fecha_control=c.fecha_comprobante and e.num_doc=''num_doc ) 
where c.clavebeneficiario='2050600001018569'
order by c.fecha_comprobante;*/

    // devuelve un número rellenando a la izquierda con un patrón
/*    public function getPadL($num,$cant=2,$pad="0") {
        return str_pad(trim($num), $cant, $pad, STR_PAD_LEFT);
    }*/
    public function getPadL($num,$cant=2,$pad="0") {
        $pad = ( empty($num) ) ? '' : str_pad(trim($num), $cant, $pad, STR_PAD_LEFT);
        return $pad;
    }


}