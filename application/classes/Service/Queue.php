<?php
defined('SYSPATH') or die('No direct script access.');

class Service_Queue
{
    protected $MEMSIZE; //  объём выделяемой разделяемой памяти
    protected $SEMKEY;   //  ключ семафора
    protected $SHMKEY;   //  ключ разделяемой памяти
    
    private $semId;
    private $shmId;
    
    public function __construct($memsize, $semkey, $shmkey)
    {
        $this->MEMSIZE = $memsize;
        $this->SEMKEY = $semkey;
        $this->SHMKEY = $shmkey;
        
        //get id of semaphore
        $this->semId = sem_get($this->SEMKEY, 1);
        
        //get id of shared memory
        $this->shmId = shm_attach($this->SHMKEY, $this->MEMSIZE);
        
        if (!sem_acquire($this->semId))
        {
            $this->clear();
            throw new Exception();
        }
        
        //var by number 1 is counter of queue
        //if it not exist, then put into him 0
        if(!shm_has_var ($this->shmId, 1))
        {
            if (!shm_put_var($this->shmId, 1, 1))
            {
                $this->clear();
                throw new Exception();
            }
        }
        
    }
    
    public function clear()
    {
        sem_remove($thid->semId);
        shm_remove($this->shmId);
    }
    
    public function pushToQueue($value)
    {
        //get id of semaphore
        $this->semId = sem_get($this->SEMKEY, 1);
        
        //get id of shared memory
        $this->shmId = shm_attach($this->SHMKEY, $this->MEMSIZE);
        
        if (!sem_acquire($this->semId))
        {
            $this->clear();
            throw new Exception();
        }
        
        $counter = shm_get_var($this->shmId, 1);
        $counter++;
        
        if (!shm_put_var($this->shmId, $counter, $value))
        {
            $this->clear();
            throw new Exception();
        }
        
        if (!shm_put_var($this->shmId, 1, $counter))
        {
            $this->clear();
            throw new Exception();
        }
        
        sem_release($this->semId);
    }
    
    public function popFromQueue()
    {
        //get id of semaphore
        $this->semId = sem_get($this->SEMKEY, 1);
        
        //get id of shared memory
        $this->shmId = shm_attach($this->SHMKEY, $this->MEMSIZE);
        
        if (!sem_acquire($this->semId))
        {
            $this->clear();
            throw new Exception();
        }
        
        $counter = shm_get_var($this->shmId, 1);
        if($counter < 2)
        {
            sem_release($this->semId);
            return false;
        }
        $counter--;
        
        $result = shm_get_var($this->shmId, $counter);
        
        if (!shm_put_var($this->shmId, 1, $counter))
        {
            $this->clear();
            throw new Exception();
        }
        
        sem_release($this->semId);
        
        return $result;
    }
    
}