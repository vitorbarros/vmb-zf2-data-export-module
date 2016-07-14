<?php
namespace VMBDataExport\Traits;


trait VerifyPostData
{

    /**
     * Método que verifica se o post enviado está no padrão correto
     *
     * @author Vitor Barros
     * @param array $post
     * @return bool
     * @throws \Exception
     */
    public function validate(array $post)
    {

        if (isset($post['entity']) && isset($post['criteria']) && isset($post['type']) && isset($post['redirect_to']) && isset($post['headers'])) {
            if ($post['entity'] != null && $post['type'] != null && $post['redirect_to'] != null && $post['headers'] != null) {
                if ($post['type'] == 'csv' || $post['type'] == 'pdf' || $post['type'] == 'xls') {
                    return true;
                }
                throw new \Exception("Accepted types 'csv', 'pdf', 'xls'");
            }
            throw new \Exception("Can not be null 'entity','type','redirect_to','headers'");
        }
        throw new \Exception("Required indexes in post array 'entity','criteria','type','redirect_to','headers'");
    }
}