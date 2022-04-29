# NFeParser
Analisador de notas fiscais eletrônicas em formato XML.

## Instalação
```
composer config repositories.vmartins/nfeparser vcs https://github.com/vmartins/nfeparser.git
composer require vmartins/nfeparser
```

## Exemplo
```php
use Vmartins\NFeParser;
$nfe = new NFeParser('nfe.xml');
print_r($nfe->toArray());
```

## Métodos
|Nome|Descrição|
|-|-|
|```toArray()```|Retorna o resultado da análise em [array](https://php.net/array)|
|```toJson()```|Retorna o resultado da análise em [JSON](https://www.json.org)|
|```toObject()```|Retorna o resultado da análise em stdClass|
|```ufEmitente()```|Código da UF do emitente do Documento Fiscal|
|```codigoChave()```|Código Numérico que compõe a Chave de Acesso|
|```naturezaOperacao()```|Descrição da Natureza da Operação|
|```formaPagamento()```|Indicador da forma de pagamento|
|```modeloDocumento()```|Modelo do Documento Fiscal|
|```serieDocumento()```|Série do Documento Fiscal|
|```numeroDocumento()```|Número do Documento Fiscal|
|```emissaoDocumento()```|Data e Hora de emissão do Documento Fiscal|
|```saida()```|Data e Hora de Saída da Mercadoria/Produto|
|```tipoOperacao()```|Tipo de Operação|
|```destinoOperacao()```|Identificador de local de destino da operação|
|```municipioFatoGerador()```|Código do Município de Ocorrência do Fato Gerador|
|```formatoDanfe()```|Formato do DANFE|
|```tipoEmissao()```|Tipo de Emissão|
|```digitoVerificador()```|Dígito Verificador da Chave de Acesso da NF-e|
|```ambiente()```|Identificação do Ambiente|
|```finalidadeEmissao()```|Finalidade de emissão da NF-e|
|```operacaoConsumidor()```|Indica operação com Consumidor final|
|```presencaConsumidor()```|Indicador de presença do comprador|
|```processoEmissao()```|Processo de emissão da NF-e|
|```versaoProcesso()```|Versão do Processo de emissão da NF-e|
|```emitenteCnpj()```|CNPJ do emitente|
|```emitenteCpf()```|CPF do emitente|
|```emitenteNome()```|Razão Social ou Nome do emitente|
|```emitenteNomeFantasia()```|Nome fantasia do emitente|
|```emitenteInscricaoEstadual()```|Inscrição Estadual do emitente|
|```emitenteInscricaoMunicipal()```|Inscrição Municipal do emitente|
|```emitenteRegimeTributario()```|Código de Regime Tributário do emitente|
|```emitenteEnderecoLogradouro()```|Logradouro do endereço do emitente|
|```emitenteEnderecoNumero()```|Número do endereço do emitente|
|```emitenteEnderecoComplemento()```|Complemento do endereço do emitente|
|```emitenteEnderecoBairro()```|Bairro do endereço do emitente|
|```emitenteEnderecoMunicipio()```|Município do endereço do emitente|
|```emitenteEnderecoUf()```|UF do endereço do emitente|
|```emitenteEnderecoCep()```|CEP do endereço do emitente|
|```emitenteEnderecoPais()```|País do endereço do emitente|
|```emitenteEnderecoTelefone()```|Telefone do endereço do emitente|
|```destinatarioCnpj()```|CNPJ do destinatário|
|```destinatarioCpf()```|CPF do destinatário|
|```destinatarioNome()```|Razão Social ou Nome do destinatário|
|```destinatarioIe()```|Indicador da IE do destinatário|
|```destinatarioInscricaoEstadual()```|Inscrição Estadual do destinatário|
|```destinatarioInscricaoMunicipal()```|Inscrição Municipal do destinatário|
|```destinatarioEmail()```|E-mail do destinatário|
|```destinatarioEnderecoLogradouro()```|Logradouro do endereço do destinatário|
|```destinatarioEnderecoNumero()```|Número do endereço do destinatário|
|```destinatarioEnderecoComplemento()```|Complemento do endereço do destinatário|
|```destinatarioEnderecoBairro()```|Bairro do endereço do destinatário|
|```destinatarioEnderecoMunicipio()```|Município do endereço do destinatário|
|```destinatarioEnderecoUf()```|UF do endereço do destinatário|
|```destinatarioEnderecoCep()```|CEP do endereço do destinatário|
|```destinatarioEnderecoPais()```|País do endereço do destinatário|
|```destinatarioEnderecoTelefone()```|Telefone do endereço do destinatário|
|```protocoloVersao()```|Versão do leiaute das informações de Protocolo|
|```protocoloAmbiente()```|Identificação do Ambiente do protocolo de resposta|
|```protocoloVersaoAplicativo()```|Versão do Aplicativo que processou o Lote|
|```protocoloChave()```|Chave de Acesso da NF-e|
|```protocoloDataProcessamento()```|Data e hora do processamento|
|```protocoloNumero()```|Número do Protocolo da NF-e|
|```protocoloDigestValue()```|Digest Value da NF-e processada|
|```protocoloStatus()```|Descrição literal do status da resposta para a NF|
