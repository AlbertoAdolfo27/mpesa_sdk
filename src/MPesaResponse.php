<?php
namespace MpesaSdk;

class MPesaResponse
{
    public static function Response(object|false $responseData): object
    {

        $httpCode = $responseData->httpCode;
        $body = $responseData->body;
        $body = json_decode($body);

        if ($httpCode === 0)
        $httpCode = 500;
        $response = array(
            "httpCode" => $httpCode
        );
        
        if (isset($body->output_ResponseCode))
            $response["responseCode"] = $body->output_ResponseCode;
        if (isset($body->output_ResponseDesc))
            $response["responseDescription"] = $body->output_ResponseDesc;
        if (isset($body->output_ConversationID))
            $response["conversationID"] = $body->output_ConversationID;
        if (isset($body->output_TransactionID))
            $response["transactionID"] = $body->output_TransactionID;
        if (isset($body->output_ThirdPartyReference))
            $response["thirdPartyReference"] = $body->output_ThirdPartyReference;
        if (isset($body->output_error))
            $response["error"] = $body->output_error;

        return (object) $response;
    }
}
