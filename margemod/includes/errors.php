<?php 

    class Margemod_Errors 
    {
        
        const EMAIL_SENDER = 'support@elmaonline.nl';
        const EMAIL_RECIPIENT = 'support@elmaonline.nl';
        const EMAIL_SUBJECT = 'Hollandgold error report';    
            
        public function get_http_response_code($domain) {
            $headers = get_headers($domain);
            return substr($headers[0], 9, 3);
        }

        public function catchError($case, $params = array()){
            if (method_exists($this, $case)){
                 return $this->$case($params);
             }else{
                 return null;
             }                
        }
        
        public function get_remote_data($params = array()){
            if(isset($params['url'])){
                $code = $this->get_http_response_code($params['url']);
                if($code != '200' and $code){
                    $this->send_report("Warning! " . $params['url'] . " responce " . $code);
                } elseif(!$code) {
                    $this->send_report("Warning! " . $params['url'] . " connection error");                            
                }
            } else {
                return "Missing paramerer: URL";                  
            }
        }       
        
        public function database_actions($params = array()){
            if(isset($params['message'])){
                $this->send_report($params['message']);
            } else {
                return "Missing paramerer: message";                        
            }
        }   

        public function update_products($params = array()){
            if(isset($params['api_response'])){
                if(!$params['api_response']){
                    $this->send_report("Warning! Magento API Error, data not saved!");
                }             
            } else {
                return "Missing paramerer: api_response.";
            }    
        }   
        
        public function send_report($message){       
            $to = self::EMAIL_RECIPIENT;
            $subject = self::EMAIL_SUBJECT;
            $headers = "From:" . self::EMAIL_SENDER;
            mail($to,$subject,$message, $headers);
       }
    }

?>