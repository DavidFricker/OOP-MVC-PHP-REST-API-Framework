<?php
class ReceiptController extends AbstractController
{
    public function get()
    {
        if(!$this->is_authorised())
        {
            return new Response(USR_UNAUTHORIZED);
        }
        
        if(!$this->request->get_parameters('identifier'))
        {
            return new Response(CMD_MALFORMED)->payload(['message' => 'Please enter your identifier.']);
        }
        
        return new Response(CMD_PROCESSED)->payload($Result);
    }
    
    // Genertate a single receipt key to sign a single email
    public function post()
    {
        if(!$this->is_authorised())
        {
            return new Response(USR_UNAUTHORIZED);
        }

        return new Response(CMD_PROCESSED)->payload(['receipt' => $receipt]);
    }

    public function delete()
    {
        if(!$this->is_authorised())
        {
            return new Response(USR_UNAUTHORIZED);
        }

        return new Response(CMD_PROCESSED);
    }
}