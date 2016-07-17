<?php
namespace SanSIS\Core\BaseBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

abstract class MessageService
{
    private static $messages = null;
<<<<<<< HEAD
    private static $session = null; 
=======
    private static $session = null;
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4

    static private function loadMessages()
    {
        if (! self::$messages) {
            self::$messages = array();
            // Get Symfony to interface with this existing session
            if (!self::$session)
                self::$session = new Session(new PhpBridgeSessionStorage());
            $ds = DIRECTORY_SEPARATOR;
        	$fp = fopen(\AppKernel::getInstance()->getRootdir().$ds.'..'.$ds.'vendor'.$ds.'sansis'.$ds.'basebundle'.$ds.'SanSIS'.$ds.'Core'.$ds.'BaseBundle'.$ds.'Resources'.$ds.'messages'.$ds.'list.csv', 'r');
            while (($cols = fgetcsv($fp, 0, "\t")) !== false) {
                if ($cols[0] && isset($cols[1]) && isset($cols[2])) {
                    if (!isset(self::$messages[$cols[0]]))
                        self::$messages[$cols[0]] = array();
                    self::$messages[$cols[0]][$cols[1]] = $cols[2];
                }
            }
        }
    }

    static public function getMessage($type, $code)
    {
<<<<<<< HEAD
    	return self::$messages[$type][$code];
=======
        if (isset(self::$messages[$type][$code]) && self::$messages[$type][$code] != '') {
    	   return self::$messages[$type][$code];
        }
        else {
            return $code;
        }
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    }

    static public function addMessage($type, $code)
    {
        self::loadMessages();
        if (!self::$session)
            self::$session = new Session(new PhpBridgeSessionStorage());
    	self::$session->getFlashBag()->add($type, self::getMessage($type, $code));
    }
}