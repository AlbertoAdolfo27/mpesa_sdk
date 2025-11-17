# PT

# M-Pesa SDK PHP (Não Oficial)

SDK não oficial para integração com a API da Vodacom M-Pesa Moçambique, desenvolvido em PHP.

> Suporte a operações C2B, B2C, B2B, Reversão, Consulta de Transações e Consulta de Nome do Cliente.

---

## Instalação

```bash
composer require albertoadolfo27/mpesa_sdk
```

---

## Requisitos

- PHP 8.0+
- Extensão `openssl` habilitada
- API Key válida fornecida pela MPESA
- Chave pública fornecida pela MPESA

---

## Como Usar

### 1. Instanciar a SDK

```php
use MpesaSdk\MPesa;

$mpesa = new MPesa(
    apiKey: 'SUA_API_KEY',
    publicKey: 'CHAVE_PUBLICA_DA_VODACOM',
    origin: "*", // Argumento opcional. Valor padrão: '*'
    verifySSL: true // Argumento opcional. Valor padrão: true
);
```

### 2. Enviar pagamento C2B

```php
$response = $mpesa->customerToBusiness(
    transactionReference: 'REF123456',
    customerNumber: '25884XXXXXXX',
    amount: '100',
    thirdPartyReference: 'MEU_REF_INTERNO'
);
```

### 3. Pagamento B2C

```php
$response = $mpesa->businessToCustomer(
    transactionReference: 'REF789',
    customerNumber: '25884XXXXXXX',
    amount: '250',
    thirdPartyReference: 'REFCLIENTE123',
    businessCode: '171717'
);
```

### 4. Pagamento B2B

```php
$response = $mpesa->businessToBusiness(
    transactionReference: 'REFXYZ',
    amount: '500',
    thirdPartyReference: 'REFB2B001',
    primaryBusinessCode: '171717',
    receiverBusinessCode: '123456'
);
```

### 5. Reverter uma transação

```php
$response = $mpesa->reversal(
    transactionID: 'TX123456789',
    securityCredential: 'SEU_CREDENCIAL_SEGURANCA',
    initiatorIdentifier: 'INICIADOR',
    thirdPartyReference: 'REF001',
    businessCode: '171717',
    amount: '100'
);
```

### 6. Consultar transação

```php
$response = $mpesa->transactionStatus(
    thirdPartyReference: 'REF001',
    queryReference: 'TX123456789',
    businessCode: '171717'
);
```

### 7. Consultar nome do cliente

```php
$response = $mpesa->customerName(
    customerNumber: '25884XXXXXXX',
    businessCode: '171717',
    thirdPartyReference: 'NOMECONSULTA001'
);
```

## Códigos de Resposta

Todos os métodos retornam um objeto contendo:

```php
[
  'httpCode' => 200,
  'responseCode' => 'INS-0',
  'responseDescription' => 'Request processed successfully',
  'transactionID' => '...',
  'thirdPartyReference' => '...'
]
```


### Verificando o Código de Resposta

O SDK define uma série de constantes na classe `MPesaResponseCode` para facilitar a verificação dos códigos de resposta retornados pela API. Isso torna seu código mais legível e seguro contra erros de digitação.

#### Exemplo de Uso:

```php
use MpesaSdk\MPesaResponseCode;

$response = $mpesa->customerToBusiness(
    transactionReference: "TX123456",
    customerNumber: "841234567",
    amount: "100",
    thirdPartyReference: MPesa::generateUniqueReference()
);

if ($response->responseCode === MPesaResponseCode::SUCCESS) {
    echo "Transação realizada com sucesso!";
} else {
    echo "Erro: " . ($response->responseDescription ?? 'Erro desconhecido');
}
```

### Aqui está a tabela de códigos de resposta da classe MPesaResponseCode
<table>
  <thead>
    <tr>
      <th>Constante</th>
      <th>Código</th>
      <th>Descrição</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>SUCCESS</td><td>INS-0</td><td>Transação bem-sucedida</td></tr>
    <tr><td>CREATED</td><td>INS-0</td><td>Transação criada com sucesso</td></tr>
    <tr><td>INTERNAL_ERROR</td><td>INS-1</td><td>Erro interno</td></tr>
    <tr><td>INVALID_API_KEY</td><td>INS-2</td><td>Chave de API inválida</td></tr>
    <tr><td>USER_NOT_ACTIVE</td><td>INS-4</td><td>Usuário não está ativo</td></tr>
    <tr><td>TRANSACTION_CANCELLED</td><td>INS-5</td><td>Transação cancelada</td></tr>
    <tr><td>TRANSACTION_FAILED</td><td>INS-6</td><td>Falha na transação</td></tr>
    <tr><td>REQUEST_TIMEOUT</td><td>INS-9</td><td>Tempo limite excedido</td></tr>
    <tr><td>DUPLICATE_TRANSACTION</td><td>INS-10</td><td>Transação duplicada</td></tr>
    <tr><td>INVALID_SHORTCODE</td><td>INS-13</td><td>Código de serviço inválido</td></tr>
    <tr><td>INVALID_REFERENCE</td><td>INS-14</td><td>Referência inválida</td></tr>
    <tr><td>INVALID_AMOUNT</td><td>INS-15</td><td>Valor inválido</td></tr>
    <tr><td>TEMPORARY_OVERLOAD</td><td>INS-16</td><td>Sobrecarga temporária</td></tr>
    <tr><td>INVALID_TRANSACTION_REF</td><td>INS-17</td><td>Referência de transação inválida</td></tr>
    <tr><td>INVALID_TRANSACTION_ID</td><td>INS-18</td><td>ID da transação inválido</td></tr>
    <tr><td>INVALID_THIRD_PARTY_REF</td><td>INS-19</td><td>Referência de terceiro inválida</td></tr>
    <tr><td>MISSING_PARAMETERS</td><td>INS-20</td><td>Parâmetros ausentes</td></tr>
    <tr><td>PARAMETER_VALIDATION_FAILED</td><td>INS-21</td><td>Validação de parâmetro falhou</td></tr>
    <tr><td>INVALID_OPERATION_TYPE</td><td>INS-22</td><td>Tipo de operação inválida</td></tr>
    <tr><td>UNKNOWN_STATUS</td><td>INS-23</td><td>Status desconhecido</td></tr>
    <tr><td>INVALID_INITIATOR_ID</td><td>INS-24</td><td>ID do iniciador inválido</td></tr>
    <tr><td>INVALID_CREDENTIAL</td><td>INS-25</td><td>Credencial inválida</td></tr>
    <tr><td>NOT_AUTHORIZED</td><td>INS-26</td><td>Não autorizado</td></tr>
    <tr><td>DIRECT_DEBIT_MISSING</td><td>INS-993</td><td>Débito direto ausente</td></tr>
    <tr><td>DIRECT_DEBIT_EXISTS</td><td>INS-994</td><td>Débito direto já existe</td></tr>
    <tr><td>CUSTOMER_PROFILE_ISSUE</td><td>INS-995</td><td>Problemas com perfil do cliente</td></tr>
    <tr><td>ACCOUNT_NOT_ACTIVE</td><td>INS-996</td><td>Conta inativa</td></tr>
    <tr><td>LINKING_TRANSACTION_MISSING</td><td>INS-997</td><td>Transação de vinculação ausente</td></tr>
    <tr><td>INVALID_MARKET</td><td>INS-998</td><td>Mercado inválido</td></tr>
    <tr><td>INITIATOR_AUTH_ERROR</td><td>INS-2001</td><td>Erro de autenticação do iniciador</td></tr>
    <tr><td>INVALID_RECEIVER</td><td>INS-2002</td><td>Destinatário inválido</td></tr>
    <tr><td>INSUFFICIENT_BALANCE</td><td>INS-2006</td><td>Saldo insuficiente</td></tr>
    <tr><td>INVALID_MSISDN</td><td>INS-2051</td><td>Número MSISDN inválido</td></tr>
    <tr><td>INVALID_LANGUAGE_CODE</td><td>INS-2057</td><td>Código de idioma inválido</td></tr>
  </tbody>
</table>

---

## Gerando Referência Única

Para facilitar a criação de uma referência única a ser usada no parâmetro `thirdPartyReference`, o SDK oferece o método auxiliar:

```php
$uniqueReference = MPesa::generateUniqueReference();
```


## Estrutura do Projeto

```bash
src/
├── MPesa.php              # Classe principal da SDK
├── MPesaResponse.php      # Formatação de resposta
└── MPesaResponseCode.php  # Lista de todos os códigos de resposta
```

---

## To-Do

- [ ] Testes unitários com PHPUnit
- [ ] Implementar suporte a webhooks

---

## Autor

**Alberto Jordane Adolfo**  
[Alberto Jordane Adolfo](https://github.com/AlbertoAdolfo27)

---

## Licença

Este projeto está licenciado sob a licença MIT.

</br></br>

---

</br></br>

# EN

# M-Pesa PHP SDK (Unofficial)

Unofficial SDK for integrating with the Vodacom M-Pesa Mozambique API, built in PHP.

> Supports C2B, B2C, B2B operations, Reversal, Transaction Status, and Customer Name Lookup.

---

## Installation

```bash
composer require albertoadolfo27/mpesa_sdk
```

---

## Requirements

- PHP 8.0+
- `openssl` extension enabled
- Valid API Key provided by MPESA
- Public key provided by MPESA

---

## How to Use

### 1. Instantiate the SDK

```php
use MpesaSdk\MPesa;

$mpesa = new MPesa(
    apiKey: 'YOUR_API_KEY',
    publicKey: 'VODACOM_PUBLIC_KEY',
    origin: "*", // Optional argument. Default value: '*'
    verifySSL: true // Optional argument. Defaut value: true
);
```

### 2. Send C2B Payment

```php
$response = $mpesa->customerToBusiness(
    transactionReference: 'REF123456',
    customerNumber: '25884XXXXXXX',
    amount: '100',
    thirdPartyReference: 'MY_INTERNAL_REF'
);
```

### 3. B2C Payment

```php
$response = $mpesa->businessToCustomer(
    transactionReference: 'REF789',
    customerNumber: '25884XXXXXXX',
    amount: '250',
    thirdPartyReference: 'CLIENTREF123',
    businessCode: '171717'
);
```

### 4. B2B Payment

```php
$response = $mpesa->businessToBusiness(
    transactionReference: 'REFXYZ',
    amount: '500',
    thirdPartyReference: 'REFB2B001',
    primaryBusinessCode: '171717',
    receiverBusinessCode: '123456'
);
```

### 5. Reverse a Transaction

```php
$response = $mpesa->reversal(
    transactionID: 'TX123456789',
    securityCredential: 'YOUR_SECURITY_CREDENTIAL',
    initiatorIdentifier: 'INITIATOR',
    thirdPartyReference: 'REF001',
    businessCode: '171717',
    amount: '100'
);
```

### 6. Check Transaction Status

```php
$response = $mpesa->transactionStatus(
    thirdPartyReference: 'REF001',
    queryReference: 'TX123456789',
    businessCode: '171717'
);
```

### 7. Get Customer Name

```php
$response = $mpesa->customerName(
    customerNumber: '25884XXXXXXX',
    businessCode: '171717',
    thirdPartyReference: 'NAMEQUERY001'
);
```

## Response Codes

All methods return an object like:

```php
[
  'httpCode' => 200,
  'responseCode' => 'INS-0',
  'responseDescription' => 'Request processed successfully',
  'transactionID' => '...',
  'thirdPartyReference' => '...'
]
```

---

### Checking the Response Code

The SDK defines a set of constants in the `MPesaResponseCode` class to simplify checking the response codes returned by the API. This makes your code more readable and helps prevent typos.

#### Example:

```php
use MpesaSdk\MPesaResponseCode;

$response = $mpesa->customerToBusiness(
    transactionReference: "TX123456",
    customerNumber: "841234567",
    amount: "100",
    thirdPartyReference: MPesa::generateUniqueReference()
);

if ($response->responseCode === MPesaResponseCode::SUCCESS) {
    echo "Transaction completed successfully!";
} else {
    echo "Error: " . ($response->responseDescription ?? 'Unknown error');
}
```

### See the Full List of Response Codes from MPesaResponseCode

<table>
  <thead>
    <tr>
      <th>Constant</th>
      <th>Code</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>SUCCESS</td><td>INS-0</td><td>Successful transaction</td></tr>
    <tr><td>CREATED</td><td>INS-0</td><td>Transaction created successfully</td></tr>
    <tr><td>INTERNAL_ERROR</td><td>INS-1</td><td>Internal error</td></tr>
    <tr><td>INVALID_API_KEY</td><td>INS-2</td><td>Invalid API key</td></tr>
    <tr><td>USER_NOT_ACTIVE</td><td>INS-4</td><td>User is not active</td></tr>
    <tr><td>TRANSACTION_CANCELLED</td><td>INS-5</td><td>Transaction cancelled</td></tr>
    <tr><td>TRANSACTION_FAILED</td><td>INS-6</td><td>Transaction failed</td></tr>
    <tr><td>REQUEST_TIMEOUT</td><td>INS-9</td><td>Request timeout</td></tr>
    <tr><td>DUPLICATE_TRANSACTION</td><td>INS-10</td><td>Duplicate transaction</td></tr>
    <tr><td>INVALID_SHORTCODE</td><td>INS-13</td><td>Invalid service code</td></tr>
    <tr><td>INVALID_REFERENCE</td><td>INS-14</td><td>Invalid reference</td></tr>
    <tr><td>INVALID_AMOUNT</td><td>INS-15</td><td>Invalid amount</td></tr>
    <tr><td>TEMPORARY_OVERLOAD</td><td>INS-16</td><td>Temporary overload</td></tr>
    <tr><td>INVALID_TRANSACTION_REF</td><td>INS-17</td><td>Invalid transaction reference</td></tr>
    <tr><td>INVALID_TRANSACTION_ID</td><td>INS-18</td><td>Invalid transaction ID</td></tr>
    <tr><td>INVALID_THIRD_PARTY_REF</td><td>INS-19</td><td>Invalid third-party reference</td></tr>
    <tr><td>MISSING_PARAMETERS</td><td>INS-20</td><td>Missing parameters</td></tr>
    <tr><td>PARAMETER_VALIDATION_FAILED</td><td>INS-21</td><td>Parameter validation failed</td></tr>
    <tr><td>INVALID_OPERATION_TYPE</td><td>INS-22</td><td>Invalid operation type</td></tr>
    <tr><td>UNKNOWN_STATUS</td><td>INS-23</td><td>Unknown status</td></tr>
    <tr><td>INVALID_INITIATOR_ID</td><td>INS-24</td><td>Invalid initiator ID</td></tr>
    <tr><td>INVALID_CREDENTIAL</td><td>INS-25</td><td>Invalid credential</td></tr>
    <tr><td>NOT_AUTHORIZED</td><td>INS-26</td><td>Not authorized</td></tr>
    <tr><td>DIRECT_DEBIT_MISSING</td><td>INS-993</td><td>Direct debit missing</td></tr>
    <tr><td>DIRECT_DEBIT_EXISTS</td><td>INS-994</td><td>Direct debit already exists</td></tr>
    <tr><td>CUSTOMER_PROFILE_ISSUE</td><td>INS-995</td><td>Customer profile issues</td></tr>
    <tr><td>ACCOUNT_NOT_ACTIVE</td><td>INS-996</td><td>Inactive account</td></tr>
    <tr><td>LINKING_TRANSACTION_MISSING</td><td>INS-997</td><td>Linking transaction missing</td></tr>
    <tr><td>INVALID_MARKET</td><td>INS-998</td><td>Invalid market</td></tr>
    <tr><td>INITIATOR_AUTH_ERROR</td><td>INS-2001</td><td>Initiator authentication error</td></tr>
    <tr><td>INVALID_RECEIVER</td><td>INS-2002</td><td>Invalid receiver</td></tr>
    <tr><td>INSUFFICIENT_BALANCE</td><td>INS-2006</td><td>Insufficient balance</td></tr>
    <tr><td>INVALID_MSISDN</td><td>INS-2051</td><td>Invalid MSISDN number</td></tr>
    <tr><td>INVALID_LANGUAGE_CODE</td><td>INS-2057</td><td>Invalid language code</td></tr>
  </tbody>
</table>


---

## Generating a Unique Reference

To easily generate a unique value to be used in the `thirdPartyReference` parameter, you can use the helper method provided by the SDK:

```php
$uniqueReference = MPesa::generateUniqueReference();
```

---

## Project Structure

```bash
src/
├── MPesa.php              # Main SDK class
├── MPesaResponse.php      # Response formatting
└── MPesaResponseCode.php  # List of all response codes
```

---

## To-Do

- [ ] Unit tests with PHPUnit
- [ ] Webhook support

---

## Author

**Alberto Jordane Adolfo**  
[Alberto Jordane Adolfo](https://github.com/AlbertoAdolfo27)

---

## License

This project is licensed under the MIT license.
