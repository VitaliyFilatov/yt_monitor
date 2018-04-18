<?php
defined('SYSPATH') or die('No direct script access.');

if (!function_exists('sem_get')) {
	function sem_get($key) {
		return fopen(__FILE__ . '.sem.' . $key, 'w+');
	}
	function sem_acquire($sem_id) {
		return flock($sem_id, LOCK_EX);
	}
	function sem_release($sem_id) {
		return flock($sem_id, LOCK_UN);
	}
}

if( !function_exists('ftok') )
{
	function ftok($filename = "", $proj = "")
	{
		if( empty($filename) || !file_exists($filename) )
		{
			return -1;
		}
		else
		{
			$filename = $filename . (string) $proj;
			for($key = array(); sizeof($key) < strlen($filename); $key[] = ord(substr($filename, sizeof($key), 1)));
			return dechex(array_sum($key));
		}
	}
}

class Service_Queue
{
    protected $MEMSIZE; //  объём выделяемой разделяемой памяти
    protected $SEMKEY;   //  ключ семафора
    protected $SHMKEY;   //  ключ разделяемой памяти
    
    private $semId;
    private $shmId;
    
//     public function __construct($memsize, $semkey, $shmkey)
//     {
//         $this->MEMSIZE = $memsize;
//         $this->SEMKEY = $semkey;
//         $this->SHMKEY = $shmkey;
        
//         //get id of semaphore
//         $this->semId = sem_get($this->SEMKEY, 1);
        
//         //get id of shared memory
//         $this->shmId = shm_attach($this->SHMKEY, $this->MEMSIZE);
        
//         if (!sem_acquire($this->semId))
//         {
//             $this->clear();
//             throw new Exception();
//         }
        
//         //var by number 1 is counter of queue
//         //if it not exist, then put into him 0
//         if(!shm_has_var ($this->shmId, 1))
//         {
//             if (!shm_put_var($this->shmId, 1, 1))
//             {
//                 $this->clear();
//                 throw new Exception();
//             }
//         }
        
//     }

	public function __construct($memsize, $semkey, $shmkey)
	{
		$this->MEMSIZE = $memsize;
		$this->SEMKEY = $semkey;
		$this->SHMKEY = $shmkey;
		
		$this->semId = sem_get($this->SEMKEY, 1);
		
		
		
	}
	
	public function init()
	{
		$this->semId = sem_get($this->SEMKEY, 1);
		
		$flag = sem_release($this->semId);
		
		if (!sem_acquire($this->semId))
		{
			$this->clear();
			throw new Exception();
		}
		
		// Create 100 byte shared memory block with system id of 0xff3
		$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
		if (!$this->shmId) {
			$this->clear();
			throw new Exception();
		}
		
		
		// Lets write a test string into shared memory
		$shm_bytes_written = shmop_write($this->shmId, "101", 0);
		if ($shm_bytes_written != strlen("101")) {
			$this->clear();
			throw new Exception();
		}
		
		
		shmop_close($this->shmId);
		
		sem_release($this->semId);
	}
	
	public function initResAnalyze()
	{
		$this->semId = sem_get($this->SEMKEY, 1);
		
		$flag = sem_release($this->semId);
		
		if (!sem_acquire($this->semId))
		{
			$this->clear();
			throw new Exception();
		}
		
		// Create 100 byte shared memory block with system id of 0xff3
		$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
		if (!$this->shmId) {
			$this->clear();
			throw new Exception();
		}
		
		
		// Lets write a test string into shared memory
		$shm_bytes_written = shmop_write($this->shmId, "????????????????", 0);
		if ($shm_bytes_written != strlen("????????????????")) {
			$this->clear();
			throw new Exception();
		}
		
		
		shmop_close($this->shmId);
		
		sem_release($this->semId);
	}
	
	public function initStop()
	{
		$this->semId = sem_get($this->SEMKEY, 1);
		
		$flag = sem_release($this->semId);
		
		if (!sem_acquire($this->semId))
		{
			$this->clear();
			throw new Exception();
		}
		
		// Create 100 byte shared memory block with system id of 0xff3
		$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
		if (!$this->shmId) {
			$this->clear();
			throw new Exception();
		}
		
		
		// Lets write a test string into shared memory
		$shm_bytes_written = shmop_write($this->shmId, "?????", 0);
		if ($shm_bytes_written != strlen("?????")) {
			$this->clear();
			throw new Exception();
		}
		
		
		shmop_close($this->shmId);
		
		sem_release($this->semId);
	}
    
    public function clear()
    {
    	shmop_delete($this->shmId);
    	shmop_close($shm_id);
    	sem_release($this->semId);
    }
    
    public function pushToQueue($value)
    {
    	//get id of semaphore
    	$this->semId = sem_get($this->SEMKEY, 1);
    	
    	
    	if (!sem_acquire($this->semId))
    	{
    		$this->clear();
    		throw new Exception();
    	}
    	
    	// Create 100 byte shared memory block with system id of 0xff3
    	$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
    	if (!$this->shmId) {
    		$this->clear();
    		throw new Exception();
    	}
    	
    	// Lets write a test string into shared memory
    	while(strlen($value) < 3)
    	{
    		$value = "0" . $value;
    	}
    	$shm_bytes_written = shmop_write($this->shmId, $value, 0);
    	if ($shm_bytes_written != strlen($value)) {
    		$this->clear();
    		throw new Exception();
    	}
    	
    	
    	shmop_close($this->shmId);
    	
    	sem_release($this->semId);
    }
    
    
    public function push($value)
    {
    	//get id of semaphore
    	$this->semId = sem_get($this->SEMKEY, 1);
    	
    	
    	if (!sem_acquire($this->semId))
    	{
    		$this->clear();
    		throw new Exception();
    	}
    	
    	// Create 100 byte shared memory block with system id of 0xff3
    	$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
    	if (!$this->shmId) {
    		$this->clear();
    		throw new Exception();
    	}
    	
    	// Lets write a test string into shared memory
    	$shm_bytes_written = shmop_write($this->shmId, $value, 0);
    	if ($shm_bytes_written != strlen($value)) {
    		$this->clear();
    		throw new Exception();
    	}
    	
    	
    	shmop_close($this->shmId);
    	
    	sem_release($this->semId);
    }
    

	public function popResAnalyze()
	{
		//get id of semaphore
		$this->semId = sem_get($this->SEMKEY, 1);
		
		
		if (!sem_acquire($this->semId))
		{
			$this->clear();
			throw new Exception();
		}
		
		// Create 100 byte shared memory block with system id of 0xff3
		$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
		if (!$this->shmId) {
			$this->clear();
			throw new Exception();
		}
		// Get shared memory block's size
		$shm_size = shmop_size($this->shmId);
		
		// Now lets read the string back
		$result = shmop_read($this->shmId, 0, 16);//3 digit of number
		if (!$result) {
			$this->clear();
			throw new Exception();
		}
		
		//Now lets delete the block and close the shared memory segment
		if (!shmop_delete($this->shmId)) {
			$this->clear();
			throw new Exception();
		}
		shmop_close($this->shmId);
		
		sem_release($this->semId);
		
		return $result;
	}
	
	public function popStop()
	{
		//get id of semaphore
		$this->semId = sem_get($this->SEMKEY, 1);
		
		
		if (!sem_acquire($this->semId))
		{
			$this->clear();
			throw new Exception();
		}
		
		// Create 100 byte shared memory block with system id of 0xff3
		$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
		if (!$this->shmId) {
			$this->clear();
			throw new Exception();
		}
		// Get shared memory block's size
		$shm_size = shmop_size($this->shmId);
		
		// Now lets read the string back
		$result = shmop_read($this->shmId, 0, 5);//5 chars 'pause' or 'astop'
		if (!$result) {
			$this->clear();
			throw new Exception();
		}
		
		//Now lets delete the block and close the shared memory segment
		if (!shmop_delete($this->shmId)) {
			$this->clear();
			throw new Exception();
		}
		shmop_close($this->shmId);
		
		sem_release($this->semId);
		
		return $result;
	}
    
    public function popFromQueue()
    {
    	//get id of semaphore
    	$this->semId = sem_get($this->SEMKEY, 1);
    	
    	
    	if (!sem_acquire($this->semId))
    	{
    		$this->clear();
    		throw new Exception();
    	}
    	
    	// Create 100 byte shared memory block with system id of 0xff3
    	$this->shmId = shmop_open($this->SHMKEY, "c", 0644, $this->MEMSIZE);
    	if (!$this->shmId) {
    		$this->clear();
    		throw new Exception();
    	}
    	// Get shared memory block's size
    	$shm_size = shmop_size($this->shmId);
    	
    	// Now lets read the string back
    	$result = shmop_read($this->shmId, 0, 3);//3 digit of number
    	if (!$result) {
    		$this->clear();
    		throw new Exception();
    	}
    	
    	//Now lets delete the block and close the shared memory segment
    	if (!shmop_delete($this->shmId)) {
    		$this->clear();
    		throw new Exception();
    	}
    	shmop_close($this->shmId);
    	
    	sem_release($this->semId);
        
        return $result;
    }
    
    public function begin()
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
    	$counter = 2;
    	
    	$result = shm_has_var($this->shmId, $counter);
    	
    	if($result == true)
    	{
    		$result = shm_get_var($this->shmId, $counter);
    		if (!shm_put_var($this->shmId, 1, $counter))
    		{
    			$this->clear();
    			throw new Exception();
    		}
    	}
    	
    	
    	
    	sem_release($this->semId);
    	
    	return $result;
    }
    
}