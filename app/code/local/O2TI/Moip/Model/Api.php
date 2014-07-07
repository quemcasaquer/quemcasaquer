<?php
class O2TI_Moip_Model_Api {

    const TOKEN_TEST = "3UNGHOJCLVXZVOYF85JGILKALZSROU2O";
    const KEY_TEST = "VX2MOP4AEXFQYHBYIWT0GINNVXFZO9TJCKJ6AWDR";
    const TOKEN_PROD = "FEE5P78NA6RZAHBNH3GLMWZFWRE7IU3D";
    const KEY_PROD = "Y8DIATTADUNVOSXKDN0JVDAQ1KU7UPJHEGPM7SBA";

    private $conta_moip = null;

    public function getContaMoip() {
        return $this->conta_moip;
    }

    public function setContaMoip($conta_moip) {
        $this->conta_moip = $conta_moip;
    }

    public function getAmbiente() {
        return $this->ambiente;
    }

    public function setAmbiente($ambiente) {
        $this->ambiente = $ambiente;
    }

    

    public function generatePedido($data, $pgto) {
        if($pgto['credito_parcelamento'] == ""){
            $pgto['credito_parcelamento'] = 2;
        }
        $standard = Mage::getSingleton('moip/standard');
        $parcelamento = $standard->getInfoParcelamento();
        $meio = $pgto["forma_pagamento"];
        $vcmentoboleto = $pgto["vcmentoboleto"];
        $forma_pgto = "";
        $validacao_nasp = $standard->getConfigData('validador_retorno');
        $url_retorno =  Mage::getBaseUrl()."Moip/standard/success/validacao/".$validacao_nasp."/";
        $valorcompra = $data['valor'];
        $vcmentoboleto = $standard->getConfigData('vcmentoboleto');
        $vcmento = date('c', strtotime("+" . $vcmentoboleto . " days"));
        
        if($pgto['tipoderecebimento'] =="0"):
          $tipoderecebimento = "Parcelado";
        else:
           $tipoderecebimento = "Avista"; 
        endif;
        $parcelamento = $standard->getInfoParcelamento();
        $tipo_parcelamento = Mage::getSingleton('moip/standard')->getConfigData('jurostipo');

        $comissionamento = Mage::getStoreConfig('o2tiall/mktplace/comissionamento');


        if ($meio == "BoletoBancario"):
            if (Mage::getStoreConfig('o2tiall/pagamento_avancado/pagamento_boleto')):
                $valorcompra = $data['valor'];
                if ($valorcompra >= Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_valor'))
                {
                    $valorcompra = $valorcompra - $valorcompra * Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_desc') / 100;
                }
                if ($valorcompra < Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_valor3'))
                {
                    $valorcompra = $valorcompra - $valorcompra * Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_desc2') / 100;
                }
                if ($valorcompra >= Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_valor3')){
                    $valorcompra = $valorcompra - $valorcompra * Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_desc3') / 100;
                };
            endif;
        endif;

        if ($meio == "DebitoBancario"):
            $valorcompra = $data['valor'];
            if (Mage::getStoreConfig('o2tiall/pagamento_avancado/transf_desc')):
                $valorcompra = $data['valor'];
                if ($valorcompra >= Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_valor'))
                {
                    $valorcompra = $valorcompra - $valorcompra * Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_desc') / 100;
                }
                if ($valorcompra < Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_valor3'))
                {
                    $valorcompra = $valorcompra - $valorcompra * Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_desc2') / 100;
                }
                if ($valorcompra >= Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_valor3')){
                    $valorcompra = $valorcompra - $valorcompra * Mage::getStoreConfig('o2tiall/pagamento_avancado/boleto_desc3') / 100;
                };
            endif;
        endif;

       if( Mage::getSingleton('moip/standard')->getConfigData('ambiente') == "teste")
            $alterapedido = rand(999999, 99999999);
        else 
            $alterapedido = "";
        
        $id_proprio = $alterapedido.$pgto['conta_moip'].'_'.$data['id_transacao'];
       
    #    $xml = $this->generateXml($json);
        $xml = new SimpleXMLElement('<?xml version = "1.0" encoding = "UTF-8"?><EnviarInstrucao/>');
        $InstrucaoUnica = $xml->addChild('InstrucaoUnica');
        $InstrucaoUnica->addAttribute('TipoValidacao', 'Transparente');
        $InstrucaoUnica->addChild('Razao', 'Pagamento do Pedido #'.$data['id_transacao']);
        $Valores = $InstrucaoUnica->addChild('Valores');
        $Valor = $Valores->addChild('Valor',  number_format($valorcompra, 2, '.', ''));
        $Valor->addAttribute('moeda', 'BRL');
        $Recebedor = $InstrucaoUnica->addChild('Recebedor');
        $Recebedor->addChild('LoginMoIP', $pgto['conta_moip']);
        $Recebedor->addChild('Apelido', $pgto['apelido']);

        if($comissionamento){
            $Comissoes = $InstrucaoUnica->addChild('Comissoes');
                $Comissionamento = $Comissoes->addChild('Comissionamento');
                    $Comissionamento->addChild('Razao',  'Pagamento do Pedido #'.$data['id_transacao'].' da Loja '.$pgto['apelido']);
                    $Comissionado = $Comissionamento->addChild('Comissionado');
                        $Comissionado->addChild('LoginMoIP', Mage::getStoreConfig('o2tiall/mktplace/logincomissionamento'));
                    $Comissionamento->addChild('ValorPercentual', Mage::getStoreConfig('o2tiall/mktplace/porc_comissionamento'));
                 if(Mage::getStoreConfig('o2tiall/mktplace/pagadordataxa')){
                    $PagadorTaxa = $Comissoes->addChild('PagadorTaxa');
                    $PagadorTaxa->addChild('LoginMoIP',Mage::getStoreConfig('o2tiall/mktplace/logincomissionamento')); 
                 }
        }

        $InstrucaoUnica->addChild('IdProprio', $id_proprio);
        $Pagador = $InstrucaoUnica->addChild('Pagador');
        $Pagador->addChild('Nome',$data['pagador_nome']);
        $Pagador->addChild('Email',$data['pagador_email']);
        $Pagador->addChild('IdPagador',$data['pagador_email']);
        $EnderecoCobranca = $Pagador->addChild('EnderecoCobranca');
        $EnderecoCobranca->addChild('Logradouro', $data['pagador_logradouro']);
        $EnderecoCobranca->addChild('Numero', $data['pagador_complemento']);
        $EnderecoCobranca->addChild('Complemento', $data['pagador_complemento']);
        $EnderecoCobranca->addChild('Bairro', $data['pagador_bairro']);
        $EnderecoCobranca->addChild('Cidade', $data['pagador_cidade']);
        $EnderecoCobranca->addChild('Estado', $data['pagador_estado']);
        $EnderecoCobranca->addChild('Pais', 'BRA');
        $EnderecoCobranca->addChild('CEP', $data['pagador_cep']);
        $EnderecoCobranca->addChild('TelefoneFixo', $data['pagador_ddd'] . $data['pagador_telefone']);
        $Parcelamentos = $InstrucaoUnica->addChild('Parcelamentos', $vcmento);
        if($tipo_parcelamento == 1){
                $max_parcelas = $parcelamento['c_ate1'];
                $min_parcelas = $parcelamento['c_de1'];
                $juros = $parcelamento['c_juros1'];
                if($max_parcelas == 12){
                  $Parcelamento = $Parcelamentos->addChild('Parcelamento');
                  $Parcelamento->addChild('MinimoParcelas',$min_parcelas);
                  $Parcelamento->addChild('MaximoParcelas',$max_parcelas);
                  $Parcelamento->addChild('Recebimento',$tipoderecebimento);
                  $Parcelamento->addChild('Juros',$juros);
                } else{
                       $Parcelamento = $Parcelamentos->addChild('Parcelamento');
                       $Parcelamento->addChild('MinimoParcelas',$min_parcelas);
                       $Parcelamento->addChild('MaximoParcelas',$max_parcelas);
                       $Parcelamento->addChild('Recebimento',$tipoderecebimento);
                       $Parcelamento->addChild('Juros',$juros);

                       $Parcelamento = $Parcelamentos->addChild('Parcelamento');
                       $Parcelamento->addChild('MinimoParcelas',$max_parcelas+1);
                       $Parcelamento->addChild('MaximoParcelas',12);
                       $Parcelamento->addChild('Recebimento',$tipoderecebimento);
                       $Parcelamento->addChild('Juros',1.99); 
                }
        } else {
            for ($i=2; $i <= 12; $i++) {
                $Parcelamento = $Parcelamentos->addChild('Parcelamento');
                $juros_parcela = 's_juros'.$i;
                $Parcelamento->addChild('MinimoParcelas',$i);
                $Parcelamento->addChild('MaximoParcelas',$i);
                $Parcelamento->addChild('Recebimento',$tipoderecebimento);
                $Parcelamento->addChild('Juros',$parcelamento[$juros_parcela]);
                $Parcelamento->addChild('Repassar','true');
             }
        }
        $FormasPagamento = $InstrucaoUnica->addChild('FormasPagamento');
        $FormasPagamento->addChild('FormaPagamento', 'CartaoCredito' );
        $FormasPagamento->addChild('FormaPagamento', 'CartaoDebito' );
        $FormasPagamento->addChild('FormaPagamento', 'DebitoBancario' );
        $FormasPagamento->addChild('FormaPagamento', 'BoletoBancario' );
        $FormasPagamento->addChild('FormaPagamento', 'FinanciamentoBancario');
        if ($meio == "BoletoBancario"){
            $Boleto_xml = $InstrucaoUnica->addChild('Boleto');
            $Boleto_xml->addChild('Instrucao1', 'Pagamento do Pedido #'.$data['id_transacao']);
            $Boleto_xml->addChild('Instrucao2', 'NÃO RECEBER APÓS O VENCIMENTO');
            $Boleto_xml->addChild('Instrucao3', '+ Info em: '.Mage::getBaseUrl());
            $Boleto_xml->addChild('DataVencimento');
        }
        $InstrucaoUnica->addChild('URLNotificacao', $url_retorno);
        $request = $xml->asXML();
        $request = utf8_decode($request);
        $request = utf8_encode($request);
        #var_dump($request); die();
        return $request;
    }
    public function generateUrl($token) {
        if ($this->getAmbiente() == "teste")
            $url = $token;
        else
            $url = $token;
        return $url;
    }

    public function getParcelamentoComposto($valor) {
        $standard = Mage::getSingleton('moip/standard');
        $parcelamento = $standard->getInfoParcelamento();
        $parcelas = array();
        $juros = array();
        $primeiro = 1;
        $max_div = $valor/(int)Mage::getSingleton('moip/standard')->getConfigData('valorminimoparcela');

        if($parcelamento['c_ate1'] < $max_div){
            $max_div = $parcelamento['c_ate1'];
        }

            for ($i=1; $i <= $max_div; $i++) {
                if($i > 1){
                    $total_parcelado[$i] = $this->getJurosComposto($valor, $parcelamento['c_juros1'], $i)*$i;
                    $parcelas[$i] = $this->getJurosComposto($valor, $parcelamento['c_juros1'], $i);
                    $juros[$i] = $parcelamento['c_juros1'];
                }
                else {
                    $total_parcelado[$i] =  $valor;
                    $parcelas[$i] = $valor*$i;
                    $juros[$i] = 0;
                }
                if($i <= Mage::getSingleton('moip/standard')->getConfigData('nummaxparcelamax')){
                    $json_parcelas[$i] = array( 
                                                'parcela' => Mage::helper('core')->currency($parcelas[$i], true, false),
                                                'total_parcelado' =>  Mage::helper('core')->currency($total_parcelado[$i], true, false), 
                                                'juros' => $juros[$i]
                                            );
                    $primeiro++;
                }
             }
             if($primeiro < 12 && $primeiro < ($valor/(int)Mage::getSingleton('moip/standard')->getConfigData('valorminimoparcela')) )
             {
                 while ($primeiro <= 12) {
                    $total_parcelado[$primeiro] = number_format($this->getJurosComposto($valor, '1.99', $i)*$primeiro, 2, '.', '');
                    $parcelas[$primeiro] = $this->getJurosComposto($valor, '1.99', $primeiro);
                    $juros[$primeiro] = '1.99';
                    
                    $json_parcelas[$primeiro] = array( 
                                                'parcela' => Mage::helper('core')->currency($parcelas[$primeiro], true, false),
                                                'total_parcelado' =>  Mage::helper('core')->currency($total_parcelado[$primeiro], true, false), 
                                                'juros' => '1.99'
                                            );
                    $primeiro++;
                 }
             }
        $json_parcelas = Mage::helper('core')->jsonEncode((object)$json_parcelas);
        return $json_parcelas;

    }

     public function getParcelamentoSimples($valor) {
        $standard = Mage::getSingleton('moip/standard');
        $parcelamento = $standard->getInfoParcelamento();
        $parcelas = array();
        $juros = array();
        $primeiro = 1;
        $max_div = (int)($valor/Mage::getSingleton('moip/standard')->getConfigData('valorminimoparcela'));
        
        if(Mage::getSingleton('moip/standard')->getConfigData('nummaxparcelamax') > $max_div){
            $max_div = $max_div;
        } else {
            $max_div = Mage::getSingleton('moip/standard')->getConfigData('nummaxparcelamax');
        }

        for ($i=1; $i <= $max_div; $i++) {
                $juros_parcela = 's_juros'.$i;
              
                if($i > 1){
                    $taxa = $parcelamento[$juros_parcela] / 100;
                    $valor_add = $valor * $taxa;
                    $total_parcelado[$i] =  $valor + $valor_add;
                    $parcelas[$i] =  ($valor  + $valor_add)/$i;
                    $juros[$i] = $parcelamento[$juros_parcela];
                }
                else {
                    $total_parcelado[$i] =  $valor;
                    $parcelas[$i] = $valor*$i;
                    $juros[$i] = 0;
                }
                if($i <= Mage::getSingleton('moip/standard')->getConfigData('nummaxparcelamax')){
                    $json_parcelas[$i] = array( 
                                                'parcela' => Mage::helper('core')->currency($parcelas[$i], true, false),
                                                'total_parcelado' =>  Mage::helper('core')->currency($total_parcelado[$i], true, false), 
                                                'juros' => $juros[$i]
                                            );
                     }
             }
        $json_parcelas = Mage::helper('core')->jsonEncode((object)$json_parcelas);
        return $json_parcelas;

    }

    public function getParcelamento($valor) {
      
        $tipo_parcelamento = Mage::getSingleton('moip/standard')->getConfigData('jurostipo');

        if($tipo_parcelamento == 1){
            $tipo = $this->getParcelamentoComposto($valor);
        } else {
            $tipo = $this->getParcelamentoSimples($valor);
        }

        return $tipo;
        
    }

    public function getJurosSimples($valor, $juros, $parcela) {
        
        return $valParcela;
    }

    public function getJurosComposto($valor, $juros, $parcela) {

        $principal = $valor;
        $taxa =  $juros/100; 
        $valParcela = ($principal * $taxa)/(1 - (pow(1/(1+$taxa), $parcela)));
        
        return $valParcela;
    }

}
