<?php 

namespace App\Storage;

use App\Decorator\DecoratorInterface;

/**
 * Storage class, responsible for save the received data and return then when asked
 */
class QueueStorage implements StorageInterface
{
    const MSG_TYPE = 1;

    protected $decorator;

    /**
     * [__construct description]
     * @param int $queueSequence [description]
     */
    public function __construct(int $queueSequence)
    {
        $this->queue = msg_get_queue($queueSequence);
    }

    /**
     * set decorator class, to manipulate data
     * 
     * @param DecoratorInterface $decorator 
     */
    public function setDecorator(DecoratorInterface $decorator): void
    {
        $this->decorator = $decorator;
    }

    /**
     * Add new itens on the queue
     * 
     * @param array $data 
     */
    public function add($data): bool
    {
        if ($this->decorator) {
            $data = $this->decorator->decorate($data);
        }

        return (bool) msg_send($this->queue, self::MSG_TYPE, $data);
    }

    /**
     * Remove the last inserted item from the file
     * 
     * @return array
     */
    public function get()
    {
        $stats = msg_stat_queue($this->queue); 

        if ($stats['msg_qnum'] == 0) {
            return false;
        }

        msg_receive($this->queue, self::MSG_TYPE, $msgtype, 1024, $data);

        if ($this->decorator) {
            return $this->decorator->revert($data);
        }

        return $data;
    }

    public function getTotal()
    {
        $stats = msg_stat_queue($this->queue); 

        return $stats['msg_qnum'] ?? 0;
    }
}