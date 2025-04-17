<?php
namespace MpesaSdk;

class MPesa
{
    private $apiKey;
    private $publicKey;
    private $origin;
    private $verifySSL;
    const SANDBOX = "api.sandbox.vm.co.mz";

    public function __construct(string $apiKey, string $publicKey, bool $verifySSL = true, string $origin = "*")
    {
        $this->apiKey = $apiKey;
        $this->publicKey = $publicKey;
        $this->origin = $origin;
        $this->verifySSL = (int) $verifySSL;
    }

    private static function criptAPIKey(string $apiKey, string $publicKey)
    {
        $openSSLPublicKey = SELF::getOpenSSLPublicKey($publicKey);

        openssl_public_encrypt($apiKey, $crypted, $openSSLPublicKey, OPENSSL_PKCS1_PADDING);
        return base64_encode($crypted);
    }

    private static function getOpenSSLPublicKey(string $publicKey)
    {
        $publicKeySplit = chunk_split($publicKey, 64, "\n");

        $key = "-----BEGIN PUBLIC KEY-----\n";
        $key .= $publicKeySplit;
        $key .= "-----END PUBLIC KEY-----\n";

        $openSSLPublicKey = openssl_get_publickey($key);
        return $openSSLPublicKey;
    }

    public static function generateUniqueReference(int $length = 6): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $randomString = '';
        for ($i = 0; $i < $length; $i++):
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        endfor;
        return $randomString . "" . time();
    }

    private function request(string $url, array $postfields, string $method = "POST"): object|false
    {
        $token = self::criptAPIKey($this->apiKey, $this->publicKey);

        $headers = array(
            'Content-Type: application/json',
            'Origin: ' . $this->origin,
            'Authorization: Bearer ' . $token,
        );

        $postfields = json_encode($postfields);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $dataResponse["httpCode"] = $httpCode;
        if ($response === false):
            $responseError = (object) array("output_error" => $error);
            $dataResponse["body"] = json_encode($responseError);
        else:
            $dataResponse["body"] = $response;
        endif;

        return (object) $dataResponse;
    }

    // Customer to Business transactions
    public function customerToBusiness(string $transactionReference, string $customerNumber, string $amount, string $thirdPartyReference, string $businessCode = "171717"): object
    {
        $url = "https://" . self::SANDBOX . ":18352/ipg/v1x/c2bPayment/singleStage/";
        $postfields =  array(
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $customerNumber,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $businessCode
        );

        $response = $this->request($url, $postfields);
        $response = MPesaResponse::Response($response);
        return $response;
    }

    // Business To Customer Transactions
    public function businessToCustomer(string $transactionReference, string $customerNumber, string $amount, string $thirdPartyReference, string $businessCode): object
    {
        $url = "https://" . self::SANDBOX . ":18345/ipg/v1x/b2cPayment/";
        $postfields =  array(
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $customerNumber,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $businessCode
        );

        $response = $this->request($url, $postfields);
        $response = MPesaResponse::Response($response);
        return $response;
    }

    // Business To Business Transactions
    public function businessToBusiness(string $transactionReference, string $amount, string $thirdPartyReference, string $primaryBusinessCode, $receiverBusinessCode): object
    {
        $url = "https://" . self::SANDBOX . ":18349/ipg/v1x/b2bPayment/";
        $postfields =  array(
            "input_TransactionReference" => $transactionReference,
            "input_PrimaryPartyCode" => $primaryBusinessCode,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ReceiverPartyCode" => $receiverBusinessCode
        );

        $response = $this->request($url, $postfields);
        $response = MPesaResponse::Response($response);
        return $response;
    }

    // Perform a reversal on a transaction
    public function reversal(string $transactionID, string $securityCredential, string $initiatorIdentifier, string $thirdPartyReference, string $businessCode, string $amount = ""): object
    {
        $url = "https://" . self::SANDBOX . ":18354/ipg/v1x/reversal/";
        $postfields =  array(
            "input_TransactionID" => $transactionID,
            "input_SecurityCredential" => $securityCredential,
            "input_InitiatorIdentifier" => $initiatorIdentifier,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $businessCode,
            "input_ReversalAmount" => $amount
        );

        if (empty($amount)):
            unset($postfields["input_ReversalAmount"]);
        endif;

        $response = $this->request($url, $postfields, "PUT");
        $response = MPesaResponse::Response($response);
        return $response;
    }

    // Query the status of a transaction
    public function transactionStatus(string $thirdPartyReference, string $queryReference, string $businessCode): object
    {
        $url = "https://" . self::SANDBOX . ":18353/ipg/v1x/queryTransactionStatus/";
        $postfields =  array(
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_QueryReference" => $queryReference,
            "input_ServiceProviderCode" => $businessCode
        );

        $response = $this->request($url, $postfields, "GET");
        $response = MPesaResponse::Response($response);
        return $response;
    }

    // Query the customer name
    public function customerName(string $customerNumber, string $businessCode, string $thirdPartyReference): object
    {
        $url = "https://" . self::SANDBOX . ":18345/ipg/v1x/b2cPayment/";
        $postfields =  array(
            "input_CustomerMSISDN" => $customerNumber,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $businessCode
        );

        $response = $this->request($url, $postfields, "GET");
        $response = MPesaResponse::Response($response);
        return $response;
    }
}
