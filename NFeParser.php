<?php

namespace Vmartins;

use DateTime;
use Exception;
use SimpleXMLElement;

class NFeParser
{
    private SimpleXMLElement $xml;

    public function __construct(string $nfe)
    {
        if (!filter_var($nfe, FILTER_VALIDATE_URL) === false) {
            $this->xml = simplexml_load_file($nfe);
        } else if (stripos($nfe, '<?xml') !== false) {
            $this->xml = simplexml_load_string($nfe);
        } else if (file_exists($nfe)) {
            $this->xml = simplexml_load_file($nfe);
        } else {
            throw new Exception('Invalid XML');
        }
    }

    /**
     * Mescla as chaves '@attributes' no array.
     * 
     * @return mixed
     */
    private function attributesMerge($element)
    {
        if (is_array($element)) {
            foreach ($element as $k => $v) {
                if ($k === '@attributes' && is_array($v)) {
                    $element = array_merge($v, $element);
                    unset($element[$k]);
                } else {
                    $element[$k] = $this->attributesMerge($v);
                }
            }
        }
    
        return $element;
    }

    /**
     * Retorna o resultado.
     * 
     * @return array
     */
    public function xml(): array
    {
        return json_decode(json_encode((array) $this->xml), true);
    }

    /**
     * Retorna o resultado em array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributesMerge($this->xml());
    }

    /**
     * Retorna o resultado em JSON.
     * 
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Retorna o resultado em stdClass.
     * 
     * @return object
     */
    public function toObject(): object
    {
        return json_decode($this->toJson());
    }

    /**
     * Código da UF do emitente do Documento Fiscal.
     * 
     * @return string
     */
    public function ufEmitente()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('cUF', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['cUF']) {
                case '11': return 'RO';
                case '12': return 'AC';
                case '13': return 'AM';
                case '14': return 'RR';
                case '15': return 'PA';
                case '16': return 'AP';
                case '17': return 'TO';
                case '21': return 'MA';
                case '22': return 'PI';
                case '23': return 'CE';
                case '24': return 'RN';
                case '25': return 'PB';
                case '26': return 'PE';
                case '27': return 'AL';
                case '28': return 'SE';
                case '29': return 'BA';
                case '31': return 'MG';
                case '32': return 'ES';
                case '33': return 'RJ';
                case '35': return 'SP';
                case '41': return 'PR';
                case '42': return 'SC';
                case '43': return 'RS';
                case '50': return 'MS';
                case '51': return 'MT';
                case '52': return 'GO';
                case '53': return 'DF';
                default: return $nfe['NFe']['infNFe']['ide']['cUF'];
            }
        }
    }

    /**
     * Código Numérico que compõe a Chave de Acesso.
     * 
     * @return string
     */
    public function codigoChave()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('cNF', $nfe['NFe']['infNFe']['ide'])
        ) {
            return $nfe['NFe']['infNFe']['ide']['cNF'];
        }
    }

    /**
     * Descrição da Natureza da Operação.
     * 
     * @return string
     */
    public function naturezaOperacao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('natOp', $nfe['NFe']['infNFe']['ide'])
        ) {
            return $nfe['NFe']['infNFe']['ide']['natOp'];
        }
    }

    /**
     * Indicador da forma de pagamento.
     * 
     * @return string
     */
    public function formaPagamento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('indPag', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['indPag']) {
                case '0': return '0 - Pagamento à vista';
                case '1': return '1 - Pagamento a prazo';
                case '2': return '2 - Outros';
                default: return $nfe['NFe']['infNFe']['ide']['indPag'];
            }
        }
    }

    /**
     * Modelo do Documento Fiscal.
     * 
     * @return string
     */
    public function modeloDocumento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('mod', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['mod']) {
                case '55': return '55 - NF-e emitida em substituição a Nota Fiscal modelo 1/1A';
                case '65': return '65 - NFC-e, utilizada nas operações de vendas no varejo, onde não for exigida a NF-e por dispositivo legal';
                default: return $nfe['NFe']['infNFe']['ide']['mod'];
            }
        }
    }

    /**
     * Série do Documento Fiscal.
     * 
     * @return string
     */
    public function serieDocumento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('serie', $nfe['NFe']['infNFe']['ide'])
        ) {
            return $nfe['NFe']['infNFe']['ide']['serie'];
        }
    }

    /**
     * Número do Documento Fiscal.
     * 
     * @return string
     */
    public function numeroDocumento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('nNF', $nfe['NFe']['infNFe']['ide'])
        ) {
            return $nfe['NFe']['infNFe']['ide']['nNF'];
        }
    }

    /**
     * Data e Hora de emissão do Documento Fiscal.
     * 
     * @return DateTime
     */
    public function emissaoDocumento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('dhEmi', $nfe['NFe']['infNFe']['ide'])
        ) {
            return new DateTime($nfe['NFe']['infNFe']['ide']['dhEmi']);
        }
    }

    /**
     * Data e Hora de Saída da Mercadoria/Produto.
     * No caso da NF de entrada, esta é a Data e Hora de entrada.
     * 
     * @return DateTime
     */
    public function saida()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('dhSaiEnt', $nfe['NFe']['infNFe']['ide'])
        ) {
            return new DateTime($nfe['NFe']['infNFe']['ide']['dhSaiEnt']);
        }
    }

    /**
     * Tipo de Operação.
     * 
     * @return string
     */
    public function tipoOperacao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('tpNF', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['tpNF']) {
                case '0': return '0 - Entrada';
                case '1': return '1 - Saída';
                default: return $nfe['NFe']['infNFe']['ide']['tpNF'];
            }
        }
    }

    /**
     * Identificador de local de destino da operação.
     * 
     * @return string
     */
    public function destinoOperacao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('idDest', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['idDest']) {
                case '1': return '1 - Operação interna';
                case '2': return '2 - Operação interestadual';
                case '3': return '3 - Operação com exterior';
                default: return $nfe['NFe']['infNFe']['ide']['idDest'];
            }
        }
    }

    /**
     * Código do Município de Ocorrência do Fato Gerador.
     * 
     * Tabela com código dos municípios:
     * www.sped.fazenda.gov.br/spedtabelas/appconsulta/obterTabelaExterna.aspx?idPacote=1&idTabela=4
     * 
     * @return string
     */
    public function municipioFatoGerador()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('cMunFG', $nfe['NFe']['infNFe']['ide'])
        ) {
            $municipios = json_decode(file_get_contents('municipios.json'), true);
            if (array_key_exists($nfe['NFe']['infNFe']['ide']['cMunFG'], $municipios)) {
                return $municipios[$nfe['NFe']['infNFe']['ide']['cMunFG']];
            }
        }
    }

    /**
     * Formato do DANFE.
     * 
     * @return string
     */
    public function formatoDanfe()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('tpImp', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['tpImp']) {
                case '0': return '0 - Sem geração de DANFE';
                case '1': return '1 - DANFE normal, Retrato';
                case '2': return '2 - DANFE normal, Paisagem';
                case '3': return '3 - DANFE Simplificado';
                case '4': return '4 - DANFE NFC-e';
                case '5': return '5 - DANFE NFC-e em mensagem eletrônica';
                default: return $nfe['NFe']['infNFe']['ide']['tpImp'];
            }
        }
    }

    /**
     * Tipo de Emissão.
     * 
     * @return string
     */
    public function tipoEmissao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('tpEmis', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['tpEmis']) {
                case '1': return '1 - Emissão normal (não em contingência)';
                case '2': return '2 - Contingência FS-IA, com impressão do DANFE em formulário de segurança';
                case '3': return '3 - Contingência SCAN (Sistema de Contingência do Ambiente Nacional)';
                case '4': return '4 - Contingência DPEC (Declaração Prévia da Emissão em Contingência)';
                case '5': return '5 - Contingência FS-DA, com impressão do DANFE em formulário de segurança';
                case '6': return '6 - Contingência SVC-AN (SEFAZ Virtual de Contingência do AN)';
                case '7': return '7 - Contingência SVC-RS (SEFAZ Virtual de Contingência do RS)';
                case '9': return '9 - Contingência off-line da NFC-e';
                default: return $nfe['NFe']['infNFe']['ide']['tpEmis'];
            }
        }
    }

    /**
     * Dígito Verificador da Chave de Acesso da NF-e.
     * 
     * @return string
     */
    public function digitoVerificador()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('cDV', $nfe['NFe']['infNFe']['ide'])
        ) {
            return $nfe['NFe']['infNFe']['ide']['cDV'];
        }
    }

    /**
     * Identificação do Ambiente.
     * 
     * @return string
     */
    public function ambiente()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('tpAmb', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['tpAmb']) {
                case '1': return '1 - Produção';
                case '2': return '2 - Homologação';
                default: return $nfe['NFe']['infNFe']['ide']['tpAmb'];
            }
        }
    }
    
    /**
     * Finalidade de emissão da NF-e.
     * 
     * @return string
     */
    public function finalidadeEmissao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('finNFe', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['finNFe']) {
                case '1': return '1 - NF-e normal';
                case '2': return '2 - NF-e complementar';
                case '3': return '3 - NF-e de ajuste';
                case '4': return '4 - Devolução de mercadoria';
                default: return $nfe['NFe']['infNFe']['ide']['finNFe'];
            }
        }
    }

    /**
     * Indica operação com Consumidor final.
     * 
     * @return string
     */
    public function operacaoConsumidor()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('indFinal', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['indFinal']) {
                case '1': return '1 - Normal';
                case '2': return '2 - Consumidor final';
                default: return $nfe['NFe']['infNFe']['ide']['indFinal'];
            }
        }
    }

    /**
     * Indicador de presença do comprador no 
     * estabelecimento comercial no momento da
     * operação
     * 
     * @return string
     */
    public function presencaConsumidor()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('indPres', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['indPres']) {
                case '0': return '0 - Não se aplica';
                case '1': return '1 - Operação presencial';
                case '2': return '2 - Operação não presencial, pela Internet';
                case '3': return '3 - Operação não presencial, Teleatendimento';
                case '4': return '4 - NFC-e em operação com entrega em domicílio';
                case '9': return '9 - Operação não presencial, outros';
                default: return $nfe['NFe']['infNFe']['ide']['indPres'];
            }
        }
    }

    /**
     * Processo de emissão da NF-e.
     * 
     * @return string
     */
    public function processoEmissao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('procEmi', $nfe['NFe']['infNFe']['ide'])
        ) {
            switch ($nfe['NFe']['infNFe']['ide']['procEmi']) {
                case '0': return '0 - Emissão de NF-e com aplicativo do contribuinte';
                case '1': return '1 - Emissão de NF-e avulsa pelo Fisco';
                case '2': return '2 - Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco';
                case '3': return '3 - Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco';
                default: return $nfe['NFe']['infNFe']['ide']['procEmi'];
            }
        }
    }

    /**
     * Versão do Processo de emissão da NF-e.
     * 
     * @return string
     */
    public function versaoProcesso()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('ide', $nfe['NFe']['infNFe'])
            && array_key_exists('verProc', $nfe['NFe']['infNFe']['ide'])
        ) {
            return $nfe['NFe']['infNFe']['ide']['verProc'];
        }
    }

    /**
     * CNPJ do emitente.
     * 
     * @return string
     */
    public function emitenteCnpj()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('CNPJ', $nfe['NFe']['infNFe']['emit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['CNPJ'];
        }
    }

    /**
     * CPF do emitente.
     * 
     * @return string
     */
    public function emitenteCpf()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('CPF', $nfe['NFe']['infNFe']['emit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['CPF'];
        }
    }

    /**
     * Razão Social ou Nome do emitente.
     * 
     * @return string
     */
    public function emitenteNome()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('xNome', $nfe['NFe']['infNFe']['emit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['xNome'];
        }
    }

    /**
     * Nome fantasia do emitente.
     * 
     * @return string
     */
    public function emitenteNomeFantasia()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('xFant', $nfe['NFe']['infNFe']['emit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['xFant'];
        }
    }

    /**
     * Inscrição Estadual do emitente.
     * 
     * @return string
     */
    public function emitenteInscricaoEstadual()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('IE', $nfe['NFe']['infNFe']['emit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['IE'];
        }
    }

    /**
     * Inscrição Municipal do emitente.
     * 
     * @return string
     */
    public function emitenteInscricaoMunicipal()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('IM', $nfe['NFe']['infNFe']['emit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['IM'];
        }
    }

    /**
     * Código de Regime Tributário do emitente.
     * 
     * @return string
     */
    public function emitenteRegimeTributario()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('CRT', $nfe['NFe']['infNFe']['emit'])
        ) {
            switch ($nfe['NFe']['infNFe']['emit']['CRT']) {
                case '1': return '1 - Simples Nacional';
                case '2': return '2 - Simples Nacional, excesso sublimite de receita bruta';
                case '3': return '3 - Regime Normal';
                default: return $nfe['NFe']['infNFe']['emit']['CRT'];
            }
        }
    }

    /**
     * Logradouro do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoLogradouro()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('xLgr', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['xLgr'];
        }
    }

    /**
     * Número do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoNumero()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('nro', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['nro'];
        }
    }

    /**
     * Complemento do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoComplemento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('xCpl', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['xCpl'];
        }
    }

    /**
     * Bairro do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoBairro()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('xBairro', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['xBairro'];
        }
    }

    /**
     * Município do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoMunicipio()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('cMun', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            if (array_key_exists('NFe', $nfe)
                && array_key_exists('infNFe', $nfe['NFe'])
                && array_key_exists('emit', $nfe['NFe']['infNFe'])
                && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
                && array_key_exists('xMun', $nfe['NFe']['infNFe']['emit']['enderEmit'])
            ) {
                return "{$nfe['NFe']['infNFe']['emit']['enderEmit']['cMun']} - {$nfe['NFe']['infNFe']['emit']['enderEmit']['xMun']}";
            } else {
                $municipios = json_decode(file_get_contents('municipios.json'), true);
                if (array_key_exists($nfe['NFe']['infNFe']['emit']['enderEmit']['cMun'], $municipios)) {
                    return "{$nfe['NFe']['infNFe']['emit']['enderEmit']['cMun']} - {$municipios[$nfe['NFe']['infNFe']['emit']['enderEmit']['cMun']]}";
                }
            }
        }
    }

    /**
     * UF do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoUf()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('UF', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['UF'];
        }
    }

    /**
     * CEP do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoCep()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('CEP', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['CEP'];
        }
    }    
    
    /**
     * País do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoPais()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('xPais', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['xPais'];
        }
    }

    /**
     * Telefone do endereço do emitente.
     * 
     * @return string
     */
    public function emitenteEnderecoTelefone()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('emit', $nfe['NFe']['infNFe'])
            && array_key_exists('enderEmit', $nfe['NFe']['infNFe']['emit'])
            && array_key_exists('fone', $nfe['NFe']['infNFe']['emit']['enderEmit'])
        ) {
            return $nfe['NFe']['infNFe']['emit']['enderEmit']['fone'];
        }
    }

    /**
     * CNPJ do destinatário.
     * 
     * @return string
     */
    public function destinatarioCnpj()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('CNPJ', $nfe['NFe']['infNFe']['dest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['CNPJ'];
        }
    }

    /**
     * CPF do destinatário.
     * 
     * @return string
     */
    public function destinatarioCpf()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('CPF', $nfe['NFe']['infNFe']['dest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['CPF'];
        }
    }

    /**
     * Razão Social ou Nome do destinatário.
     * 
     * @return string
     */
    public function destinatarioNome()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('xNome', $nfe['NFe']['infNFe']['dest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['xNome'];
        }
    }

    /**
     * Indicador da IE do destinatário.
     * 
     * @return string
     */
    public function destinatarioIe()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('indIEDest', $nfe['NFe']['infNFe']['dest'])
        ) {
            switch ($nfe['NFe']['infNFe']['dest']['indIEDest']) {
                case '1': return '1 - Contribuinte ICMS';
                case '2': return '2 - Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS';
                case '9': return '9 - Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS';
                default: return $nfe['NFe']['infNFe']['dest']['indIEDest'];
            }
        }
    }

    /**
     * Inscrição Estadual do destinatário.
     * 
     * @return string
     */
    public function destinatarioInscricaoEstadual()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('IE', $nfe['NFe']['infNFe']['dest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['IE'];
        }
    }

    /**
     * Inscrição Municipal do destinatário.
     * 
     * @return string
     */
    public function destinatarioInscricaoMunicipal()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('IM', $nfe['NFe']['infNFe']['dest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['IM'];
        }
    }

    /**
     * E-mail do destinatário.
     * 
     * @return string
     */
    public function destinatarioEmail()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('email', $nfe['NFe']['infNFe']['dest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['email'];
        }
    }

    /**
     * Logradouro do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoLogradouro()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('xLgr', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['xLgr'];
        }
    }

    /**
     * Número do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoNumero()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('nro', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['nro'];
        }
    }

    /**
     * Complemento do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoComplemento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('xCpl', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['xCpl'];
        }
    }

    /**
     * Bairro do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoBairro()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('xBairro', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['xBairro'];
        }
    }

    /**
     * Município do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoMunicipio()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('cMun', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            if (array_key_exists('NFe', $nfe)
                && array_key_exists('infNFe', $nfe['NFe'])
                && array_key_exists('dest', $nfe['NFe']['infNFe'])
                && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
                && array_key_exists('xMun', $nfe['NFe']['infNFe']['dest']['enderDest'])
            ) {
                return "{$nfe['NFe']['infNFe']['dest']['enderDest']['cMun']} - {$nfe['NFe']['infNFe']['dest']['enderDest']['xMun']}";
            } else {
                $municipios = json_decode(file_get_contents('municipios.json'), true);
                if (array_key_exists($nfe['NFe']['infNFe']['dest']['enderDest']['cMun'], $municipios)) {
                    return $municipios[$nfe['NFe']['infNFe']['dest']['enderDest']['cMun']];
                }
            }
        }
    }

    /**
     * UF do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoUf()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('UF', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['UF'];
        }
    }

    /**
     * CEP do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoCep()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('CEP', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['CEP'];
        }
    }    
    
    /**
     * País do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoPais()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('xPais', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['xPais'];
        }
    }

    /**
     * Telefone do endereço do destinatário.
     * 
     * @return string
     */
    public function destinatarioEnderecoTelefone()
    {
        $nfe = $this->toArray();
        if (array_key_exists('NFe', $nfe)
            && array_key_exists('infNFe', $nfe['NFe'])
            && array_key_exists('dest', $nfe['NFe']['infNFe'])
            && array_key_exists('enderDest', $nfe['NFe']['infNFe']['dest'])
            && array_key_exists('fone', $nfe['NFe']['infNFe']['dest']['enderDest'])
        ) {
            return $nfe['NFe']['infNFe']['dest']['enderDest']['fone'];
        }
    }

    /**
     * Versão do leiaute das informações de Protocolo.
     * 
     * @return string
     */
    public function protocoloVersao()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('versao', $nfe['protNFe'])
        ) {
            return $nfe['protNFe']['versao'];
        }
    }

    /**
     * Identificação do Ambiente do protocolo de resposta.
     * 
     * @return string
     */
    public function protocoloAmbiente()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('tpAmb', $nfe['protNFe']['infProt'])
        ) {
            switch ($nfe['protNFe']['infProt']['tpAmb']) {
                case '1': return '1 - Produção';
                case '2': return '2 - Homologação';
                default: return $nfe['protNFe']['infProt']['tpAmb'];
            }
        }
    }

    /**
     * Versão do Aplicativo que processou o Lote.
     * 
     * @return string
     */
    public function protocoloVersaoAplicativo()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('verAplic', $nfe['protNFe']['infProt'])
        ) {
            return $nfe['protNFe']['infProt']['verAplic'];
        }
    }

    /**
     * Chave de Acesso da NF-e.
     * 
     * @return string
     */
    public function protocoloChave()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('chNFe', $nfe['protNFe']['infProt'])
        ) {
            return $nfe['protNFe']['infProt']['chNFe'];
        }
    }

    /**
     * Data e hora do processamento.
     * 
     * @return string
     */
    public function protocoloDataProcessamento()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('dhRecbto', $nfe['protNFe']['infProt'])
        ) {
            return $nfe['protNFe']['infProt']['dhRecbto'];
        }
    }

    /**
     * Número do Protocolo da NF-e.
     * 
     * @return string
     */
    public function protocoloNumero()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('nProt', $nfe['protNFe']['infProt'])
        ) {
            return $nfe['protNFe']['infProt']['nProt'];
        }
    }

    /**
     * Digest Value da NF-e processada.
     * 
     * @return string
     */
    public function protocoloDigestValue()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('digVal', $nfe['protNFe']['infProt'])
        ) {
            return $nfe['protNFe']['infProt']['digVal'];
        }
    }

    /**
     * Descrição literal do status da resposta para a NF.
     * 
     * @return string
     */
    public function protocoloStatus()
    {
        $nfe = $this->toArray();
        if (array_key_exists('protNFe', $nfe)
            && array_key_exists('infProt', $nfe['protNFe'])
            && array_key_exists('xMotivo', $nfe['protNFe']['infProt'])
        ) {
            return $nfe['protNFe']['infProt']['xMotivo'];
        }
    }
}
