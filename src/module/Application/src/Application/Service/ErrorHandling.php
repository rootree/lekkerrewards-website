<?php
/**
 * @author Ivan Chura
 * Created at: 11.08.14 13:28
 */
namespace Application\Service;

class ErrorHandling
{
    /**
     * @var \Zend\Log\Logger
     */
    protected $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Exception $e
     */
    public function logException(\Exception $e)
    {
        $trace = $e->getTraceAsString();
        $i = 1;
        do {
            $messages[] = $i++ . ": " . $e->getMessage();
        } while ($e = $e->getPrevious());
        $log = "Exception:n" . implode("n", $messages);
        $log .= "nTrace:n" . $trace;
        $this->logger->err($log);
    }

    /**
     * @param  $e
     */
    public function logData($e)
    {
        $log = "\n\nData:\n" . $e;
        $this->logger->debug($log);
    }
}