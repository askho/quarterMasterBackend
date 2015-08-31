<?php  	
 

	/** 
	 * Implementation of IDataServiceQueryProvider.
	 * 
	 * PHP version 5.3
	 * 
	 * @category  Service
	 * @package   Inventory;
	 * @author    MySQLConnector <odataphpproducer_alias@microsoft.com>
	 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
	 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
	 * @version   SVN: 1.0
	 * @link      http://odataphpproducer.codeplex.com
	 */     
	use ODataProducer\UriProcessor\ResourcePathProcessor\SegmentParser\KeyDescriptor;
	use ODataProducer\Providers\Metadata\ResourceSet;
	use ODataProducer\Providers\Metadata\ResourceProperty;
	use ODataProducer\Providers\Query\IDataServiceQueryProvider2;
	require_once "InventoryMetadata.php";
	require_once "ODataProducer/Providers/Query/IDataServiceQueryProvider2.php";
	
	/** The name of the database for Inventory*/
	define('DB_NAME', "inventory");
	/** MySQL database username */
	define('DB_USER', "root");
	/** MySQL database password */
	define('DB_PASSWORD', "password");
	/** MySQL hostname */
	define('DB_HOST', "127.0.0.1");
			
   			
	/**
     * InventoryQueryProvider implemetation of IDataServiceQueryProvider2.
	 * @category  Service
	 * @package   Inventory;
	 * @author    MySQLConnector <odataphpproducer_alias@microsoft.com>
	 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
	 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
	 * @version   Release: 1.0
	 * @link      http://odataphpproducer.codeplex.com
	 */
	class InventoryQueryProvider implements IDataServiceQueryProvider2
	{
    	/**
     	 * Handle to connection to Database     
     	 */
    	private $_connectionHandle = null;

      private $_expressionProvider = null;

    	/**
     	 * Constructs a new instance of InventoryQueryProvider
     	 * 
     	 */
	    public function __construct()
    	{
        	$this->_connectionHandle = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
        	if ( $this->_connectionHandle ) {
        		mysql_select_db(DB_NAME, $this->_connectionHandle);
        	} else {             
            	die(mysql_error());
        	} 
    	}

    	/**
	   	 * Library will use this function to check whether library has to
	     * apply orderby, skip and top.
	     * Note: Library will not delegate $select/$expand operation to IDSQP2
	     * implementation, they will always handled by Library.
	     * 
	     * @return Boolean True If user want library to apply the query options
	     *                 False If user is going to take care of orderby, skip
	     *                 and top options
	     */
	    public function canApplyQueryOptions()
	    {
	    	return true;
	    }

	    /**
    	 * Gets collection of entities belongs to an entity set
     	 * 
     	 * @param ResourceSet $resourceSet The entity set whose entities needs to be fetched
     	 * 
     	 * @return array(Object)
     	 */
    	public function getResourceSet(ResourceSet $resourceSet, $filterOption = null, 
        	$select=null, $orderby=null, $top=null, $skip=null)
    	{   
        	$resourceSetName =  $resourceSet->getName();
			 
        	if($resourceSetName === 'events')
        	{
        		$resourceSetName = 'event';
        	}	
       				 
        	if($resourceSetName === 'event_inventories')
        	{
        		$resourceSetName = 'event_inventory';
        	}	
       				 
        	if($resourceSetName === 'event_users')
        	{
        		$resourceSetName = 'event_user';
        	}	
       				 
        	if($resourceSetName === 'inventories')
        	{
        		$resourceSetName = 'inventory';
        	}	
       				 
        	if($resourceSetName === 'items')
        	{
        		$resourceSetName = 'item';
        	}	
       				 
        	if($resourceSetName === 'users')
        	{
        		$resourceSetName = 'user';
        	}	
       				 
			if( $resourceSetName !== 'event'
	        			
	    	and $resourceSetName !== 'event_inventory'
	        			
	    	and $resourceSetName !== 'event_user'
	        			
	    	and $resourceSetName !== 'inventory'
	        			
	    	and $resourceSetName !== 'item'
	        			
	    	and $resourceSetName !== 'user'
	        			)	       		
        	{
        		die('(InventoryQueryProvider) Unknown resource set ' . $resourceSetName);
        	}       	
        	$query = "SELECT * FROM $resourceSetName";
	        if ($filterOption != null) {
    	        $query .= ' WHERE ' . $filterOption;
        	}
        	$stmt = mysql_query($query);
        	if ($stmt === false) {
            	die(print_r(mysql_error(), true));
        	}

        	$returnResult = array();
        	switch ($resourceSetName) {
        		
				case 'event':
	        		
	        		$returnResult = $this->_serializeevents($stmt);
       				break;
				
				case 'event_inventory':
	        		
	        		$returnResult = $this->_serializeevent_inventories($stmt);
       				break;
				
				case 'event_user':
	        		
	        		$returnResult = $this->_serializeevent_users($stmt);
       				break;
				
				case 'inventory':
	        		
	        		$returnResult = $this->_serializeinventories($stmt);
       				break;
				
				case 'item':
	        		
	        		$returnResult = $this->_serializeitems($stmt);
       				break;
				
				case 'user':
	        		
	        		$returnResult = $this->_serializeusers($stmt);
       				break;
				
        	}
        	mysql_free_result($stmt);
        	return $returnResult;        
		} 


	    /**
    	 * Gets an entity instance from an entity set identifed by a key
	     * 
    	 * @param ResourceSet   $resourceSet   The entity set from which 
	     *                                     an entity needs to be fetched
    	 * @param KeyDescriptor $keyDescriptor The key to identify the entity to be fetched
     	 * 
	     * @return Object/NULL Returns entity instance if found else null
    	 */
	    public function getResourceFromResourceSet(ResourceSet $resourceSet, KeyDescriptor $keyDescriptor)
    	{   
        	$resourceSetName =  $resourceSet->getName();
    	     
        	if($resourceSetName === 'events')
        	{
        		$resourceSetName = 'event';
        	}	
       				 
        	if($resourceSetName === 'event_inventories')
        	{
        		$resourceSetName = 'event_inventory';
        	}	
       				 
        	if($resourceSetName === 'event_users')
        	{
        		$resourceSetName = 'event_user';
        	}	
       				 
        	if($resourceSetName === 'inventories')
        	{
        		$resourceSetName = 'inventory';
        	}	
       				 
        	if($resourceSetName === 'items')
        	{
        		$resourceSetName = 'item';
        	}	
       				 
        	if($resourceSetName === 'users')
        	{
        		$resourceSetName = 'user';
        	}	
       				
    		if( $resourceSetName !== 'event'
	        			
	    	and $resourceSetName !== 'event_inventory'
	        			
	    	and $resourceSetName !== 'event_user'
	        			
	    	and $resourceSetName !== 'inventory'
	        			
	    	and $resourceSetName !== 'item'
	        			
	    	and $resourceSetName !== 'user'
	        			)	       		
        	{
	        	die('(InventoryQueryProvider) Unknown resource set ' . $resourceSetName);
    	    }
        	$namedKeyValues = $keyDescriptor->getValidatedNamedValues();
        	$condition = null;
        	foreach ($namedKeyValues as $key => $value) {
	            $condition .= $key . ' = ' . $value[0] . ' and ';
    	    }
	
    	    $len = strlen($condition);
        	$condition = substr($condition, 0, $len - 5); 
	        $query = "SELECT * FROM $resourceSetName WHERE $condition";
    	    $stmt = mysql_query($query);
        	if ($stmt === false) {
            	die(print_r(mysql_error(), true));
        	}

        	//If resource not found return null to the library
        	if (!mysql_num_rows($stmt)) {
            	return null;
        	}

	        $result = null;
        	while ( $record = mysql_fetch_array($stmt, MYSQL_ASSOC)) {
    	    	switch ($resourceSetName) {
    	    		
				case 'event':
	        		
	        		$returnResult = $this->_serializeevent($record);
       				break;
				
				case 'event_inventory':
	        		
	        		$returnResult = $this->_serializeevent_inventory($record);
       				break;
				
				case 'event_user':
	        		
	        		$returnResult = $this->_serializeevent_user($record);
       				break;
				
				case 'inventory':
	        		
	        		$returnResult = $this->_serializeinventory($record);
       				break;
				
				case 'item':
	        		
	        		$returnResult = $this->_serializeitem($record);
       				break;
				
				case 'user':
	        		
	        		$returnResult = $this->_serializeuser($record);
       				break;
				
        		}
        	}	
        	mysql_free_result($stmt);
        	return $returnResult;        
    	}
    	
	    /**
    	 * Gets a related entity instance from an entity set identifed by a key
	     * 
    	 * @param ResourceSet      $sourceResourceSet    The entity set related to
	     *                                               the entity to be fetched.
    	 * @param object           $sourceEntityInstance The related entity instance.
     	 * @param ResourceSet      $targetResourceSet    The entity set from which
     	 *                                               entity needs to be fetched.
     	 * @param ResourceProperty $targetProperty       The metadata of the target 
     	 *                                               property.
     	 * @param KeyDescriptor    $keyDescriptor        The key to identify the entity 
     	 *                                               to be fetched.
     	 * 
     	 * @return Object/NULL Returns entity instance if found else null
     	 */
    	public function  getResourceFromRelatedResourceSet(ResourceSet $sourceResourceSet, 
        	$sourceEntityInstance, 
        	ResourceSet $targetResourceSet,
        	ResourceProperty $targetProperty,
        	KeyDescriptor $keyDescriptor
    	) {
        	$result = array();
        	$srcClass = get_class($sourceEntityInstance);
        	$navigationPropName = $targetProperty->getName();
        	$key = null;
        	foreach ($keyDescriptor->getValidatedNamedValues() as $keyName => $valueDescription) {
	            $key = $key . $keyName . '=' . $valueDescription[0] . ' and ';
    	    }
        	$key = rtrim($key, ' and ');
       		if($srcClass === 'event')
			{		
				if($navigationPropName === 'event_inventories') 
				{			
							
					$query = "SELECT * FROM event_inventory WHERE eventId = '$sourceEntityInstance->eventId' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_inventories($stmt);
				}
																						
				else if($navigationPropName === 'event_users') 
				{			
							
					$query = "SELECT * FROM event_user WHERE eventId = '$sourceEntityInstance->eventId' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_users($stmt);
				}
									
				else {
					die('event does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
			else if($srcClass === 'event_inventory')
			{		
				
			}	
			
			else if($srcClass === 'event_user')
			{		
				
			}	
			
			else if($srcClass === 'inventory')
			{		
				if($navigationPropName === 'event_inventories') 
				{			
							
					$query = "SELECT * FROM event_inventory WHERE itemId = '$sourceEntityInstance->inventoryItemId' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_inventories($stmt);
				}
									
				else {
					die('inventory does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
			else if($srcClass === 'item')
			{		
				if($navigationPropName === 'inventories') 
				{			
							
					$query = "SELECT * FROM inventory WHERE itemId = '$sourceEntityInstance->itemId' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeinventories($stmt);
				}
									
				else {
					die('item does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
			else if($srcClass === 'user')
			{		
				if($navigationPropName === 'events') 
				{			
							
					$query = "SELECT * FROM event WHERE userName = '$sourceEntityInstance->userName' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevents($stmt);
				}
																						
				else if($navigationPropName === 'event_users') 
				{			
							
					$query = "SELECT * FROM event_user WHERE userName = '$sourceEntityInstance->userName' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_users($stmt);
				}
																						
				else if($navigationPropName === 'inventories') 
				{			
							
					$query = "SELECT * FROM inventory WHERE lastCheckedOutBy = '$sourceEntityInstance->userName' and $key";
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeinventories($stmt);
				}
									
				else {
					die('user does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
       		return empty($result) ? null : $result[0];	
		}
		
    
	    /**
    	 * Get related resource set for a resource
     	* 
     	* @param ResourceSet      $sourceResourceSet    The source resource set
     	* @param mixed            $sourceEntityInstance The resource
     	* @param ResourceSet      $targetResourceSet    The resource set of 
     	*                                               the navigation property
     	* @param ResourceProperty $targetProperty       The navigation property to be 
     	*                                               retrieved
     	*                                               
     	* @return array(Objects)/array() Array of related resource if exists, if no 
     	*                                related resources found returns empty array
     	*/
    	public function  getRelatedResourceSet(ResourceSet $sourceResourceSet, 
        	$sourceEntityInstance, 
        	ResourceSet $targetResourceSet,
        	ResourceProperty $targetProperty,
	        $filterOption = null,
    	    $select=null, $orderby=null, $top=null, $skip=null
    	) {
	        $result = array();
    	    $srcClass = get_class($sourceEntityInstance);
	        $navigationPropName = $targetProperty->getName();
       		if($srcClass === 'event')
			{		
				if($navigationPropName === 'event_inventories') 
				{			
							
					$query = "SELECT * FROM event_inventory WHERE eventId = '$sourceEntityInstance->eventId'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_inventories($stmt);
				}
																						
				else if($navigationPropName === 'event_users') 
				{			
							
					$query = "SELECT * FROM event_user WHERE eventId = '$sourceEntityInstance->eventId'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_users($stmt);
				}
									
				else {
					die('event does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
			else if($srcClass === 'event_inventory')
			{		
				
			}	
			
			else if($srcClass === 'event_user')
			{		
				
			}	
			
			else if($srcClass === 'inventory')
			{		
				if($navigationPropName === 'event_inventories') 
				{			
							
					$query = "SELECT * FROM event_inventory WHERE itemId = '$sourceEntityInstance->inventoryItemId'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_inventories($stmt);
				}
									
				else {
					die('inventory does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
			else if($srcClass === 'item')
			{		
				if($navigationPropName === 'inventories') 
				{			
							
					$query = "SELECT * FROM inventory WHERE itemId = '$sourceEntityInstance->itemId'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeinventories($stmt);
				}
									
				else {
					die('item does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
			else if($srcClass === 'user')
			{		
				if($navigationPropName === 'events') 
				{			
							
					$query = "SELECT * FROM event WHERE userName = '$sourceEntityInstance->userName'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevents($stmt);
				}
																						
				else if($navigationPropName === 'event_users') 
				{			
							
					$query = "SELECT * FROM event_user WHERE userName = '$sourceEntityInstance->userName'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeevent_users($stmt);
				}
																						
				else if($navigationPropName === 'inventories') 
				{			
							
					$query = "SELECT * FROM inventory WHERE lastCheckedOutBy = '$sourceEntityInstance->userName'";
	                if ($filterOption != null) {
    	                $query .= ' AND ' . $filterOption;
        	        }
			        $stmt = mysql_query($query);
        			if ($stmt === false) {            
        				die(print_r(mysql_error(), true));
	    			}
	    			$result = $this->_serializeinventories($stmt);
				}
									
				else {
					die('user does not have navigation porperty with name: ' . $navigationPropName);
				}
				
			}	
			
       		return $result;	        
    	}    
    	
	    /**
    	 * Get related resource for a resource
     	* 
     	* @param ResourceSet      $sourceResourceSet    The source resource set
     	* @param mixed            $sourceEntityInstance The source resource
     	* @param ResourceSet      $targetResourceSet    The resource set of 
     	*                                               the navigation property
     	* @param ResourceProperty $targetProperty       The navigation property to be 
     	*                                               retrieved
     	* 
     	* @return Object/null The related resource if exists else null
     	*/
    	public function getRelatedResourceReference(ResourceSet $sourceResourceSet, 
        	$sourceEntityInstance, 
        	ResourceSet $targetResourceSet,
        	ResourceProperty $targetProperty
    	) {
        	$result = null;
        	$srcClass = get_class($sourceEntityInstance);
        	$navigationPropName = $targetProperty->getName();
			if($srcClass==='event')
			{
					 if($navigationPropName === 'user')
				{
					if (empty($sourceEntityInstance->userName))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM user WHERE userName = '$sourceEntityInstance->userName'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeuser(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else {
					die('event does not have navigation porperty with name: ' . $navigationPropName);
				}
											
			}
				
			else if($srcClass==='event_inventory')
			{
					 if($navigationPropName === 'event')
				{
					if (empty($sourceEntityInstance->eventId))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM event WHERE eventId = '$sourceEntityInstance->eventId'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeevent(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else if($navigationPropName === 'inventory')
				{
					if (empty($sourceEntityInstance->itemId))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM inventory WHERE itemId = '$sourceEntityInstance->itemId'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeinventory(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else {
					die('event_inventory does not have navigation porperty with name: ' . $navigationPropName);
				}
											
			}
				
			else if($srcClass==='event_user')
			{
					 if($navigationPropName === 'event')
				{
					if (empty($sourceEntityInstance->eventId))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM event WHERE eventId = '$sourceEntityInstance->eventId'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeevent(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else if($navigationPropName === 'user')
				{
					if (empty($sourceEntityInstance->userName))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM user WHERE userName = '$sourceEntityInstance->userName'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeuser(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else {
					die('event_user does not have navigation porperty with name: ' . $navigationPropName);
				}
											
			}
				
			else if($srcClass==='inventory')
			{
					 if($navigationPropName === 'user')
				{
					if (empty($sourceEntityInstance->lastCheckedOutBy))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM user WHERE lastCheckedOutBy = '$sourceEntityInstance->lastCheckedOutBy'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeuser(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else if($navigationPropName === 'item')
				{
					if (empty($sourceEntityInstance->itemId))
					{
                		$result = null;
					} else {
						$query = "SELECT * FROM item WHERE itemId = '$sourceEntityInstance->itemId'";
						$stmt = mysql_query($query);
						if ($stmt === false) {
							die(print_r(mysql_error(), true));
						}
						if (!mysql_num_rows($stmt)) {
							$result =  null;
						}
						$result = $this->_serializeitem(mysql_fetch_array($stmt, MYSQL_ASSOC));
					}
				}
								
				else {
					die('inventory does not have navigation porperty with name: ' . $navigationPropName);
				}
											
			}
				
			else if($srcClass==='item')
			{
										
			}
				
			else if($srcClass==='user')
			{
										
			}
				
			return $result;
		}
			
		
		/**
    	 * Serialize the sql result array into event objects
		 * 	
     	 * @param array(array) $result result of the sql query
     	 * 
     	 * @return array(Object)
     	 */
    	private function _serializeevents($result)
    	{
        	$events = array();
        	while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {         
            	$events[] = $this->_serializeevent($record);
        	}
        	return $events;
    	}
    	
    	/**
    	 * Serialize the sql row into event object
	     * 
    	 * @param array $record each row of event
	     * 
    	 * @return Object
	     */
	    private function _serializeevent($record)
    	{
        	$event = new event();
        	
			$event->eventId = $record['eventId'];							
								
			$event->eventName = $record['eventName'];							
								
			$event->eventDescription = $record['eventDescription'];							
								
			$event->location = $record['location'];							
								
			$event->eventStartDate = $record['eventStartDate'];							
								
			$event->eventEndDate = $record['eventEndDate'];							
								
			$event->userName = $record['userName'];							
								
    		return $event;
		}										
			
		/**
    	 * Serialize the sql result array into event_inventory objects
		 * 	
     	 * @param array(array) $result result of the sql query
     	 * 
     	 * @return array(Object)
     	 */
    	private function _serializeevent_inventories($result)
    	{
        	$event_inventories = array();
        	while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {         
            	$event_inventories[] = $this->_serializeevent_inventory($record);
        	}
        	return $event_inventories;
    	}
    	
    	/**
    	 * Serialize the sql row into event_inventory object
	     * 
    	 * @param array $record each row of event_inventory
	     * 
    	 * @return Object
	     */
	    private function _serializeevent_inventory($record)
    	{
        	$event_inventory = new event_inventory();
        	
			$event_inventory->eventInventoryId = $record['eventInventoryId'];							
								
			$event_inventory->itemId = $record['itemId'];							
								
			$event_inventory->eventId = $record['eventId'];							
								
    		return $event_inventory;
		}										
			
		/**
    	 * Serialize the sql result array into event_user objects
		 * 	
     	 * @param array(array) $result result of the sql query
     	 * 
     	 * @return array(Object)
     	 */
    	private function _serializeevent_users($result)
    	{
        	$event_users = array();
        	while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {         
            	$event_users[] = $this->_serializeevent_user($record);
        	}
        	return $event_users;
    	}
    	
    	/**
    	 * Serialize the sql row into event_user object
	     * 
    	 * @param array $record each row of event_user
	     * 
    	 * @return Object
	     */
	    private function _serializeevent_user($record)
    	{
        	$event_user = new event_user();
        	
			$event_user->evetUserId = $record['evetUserId'];							
								
			$event_user->userName = $record['userName'];							
								
			$event_user->eventId = $record['eventId'];							
								
    		return $event_user;
		}										
			
		/**
    	 * Serialize the sql result array into inventory objects
		 * 	
     	 * @param array(array) $result result of the sql query
     	 * 
     	 * @return array(Object)
     	 */
    	private function _serializeinventories($result)
    	{
        	$inventories = array();
        	while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {         
            	$inventories[] = $this->_serializeinventory($record);
        	}
        	return $inventories;
    	}
    	
    	/**
    	 * Serialize the sql row into inventory object
	     * 
    	 * @param array $record each row of inventory
	     * 
    	 * @return Object
	     */
	    private function _serializeinventory($record)
    	{
        	$inventory = new inventory();
        	
			$inventory->inventoryItemId = $record['inventoryItemId'];							
								
			$inventory->itemId = $record['itemId'];							
								
			$inventory->condition = $record['condition'];							
								
			$inventory->lastCheckedOutBy = $record['lastCheckedOutBy'];							
								
			$inventory->checkOutDate = $record['checkOutDate'];							
								
    		return $inventory;
		}										
			
		/**
    	 * Serialize the sql result array into item objects
		 * 	
     	 * @param array(array) $result result of the sql query
     	 * 
     	 * @return array(Object)
     	 */
    	private function _serializeitems($result)
    	{
        	$items = array();
        	while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {         
            	$items[] = $this->_serializeitem($record);
        	}
        	return $items;
    	}
    	
    	/**
    	 * Serialize the sql row into item object
	     * 
    	 * @param array $record each row of item
	     * 
    	 * @return Object
	     */
	    private function _serializeitem($record)
    	{
        	$item = new item();
        	
			$item->itemId = $record['itemId'];							
								
			$item->itemName = $record['itemName'];							
								
			$item->description = $record['description'];							
								
			$item->pricing = $record['pricing'];							
								
			$item->locationPurchased = $record['locationPurchased'];							
								
    		return $item;
		}										
			
		/**
    	 * Serialize the sql result array into user objects
		 * 	
     	 * @param array(array) $result result of the sql query
     	 * 
     	 * @return array(Object)
     	 */
    	private function _serializeusers($result)
    	{
        	$users = array();
        	while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {         
            	$users[] = $this->_serializeuser($record);
        	}
        	return $users;
    	}
    	
    	/**
    	 * Serialize the sql row into user object
	     * 
    	 * @param array $record each row of user
	     * 
    	 * @return Object
	     */
	    private function _serializeuser($record)
    	{
        	$user = new user();
        	
			$user->userName = $record['userName'];							
								
			$user->authToken = $record['authToken'];							
								
			$user->password = $record['password'];							
								
			$user->name = $record['name'];							
								
			$user->address = $record['address'];							
								
			$user->phone = $record['phone'];							
								
			$user->email = $record['email'];							
								
    		return $user;
		}										
			
    /**
    * The destructor
    */
    public function __destruct()
    {
    if ($this->_connectionHandle) {
    mysql_close($this->_connectionHandle);
    }
    }

    public function getExpressionProvider()
    {
    if (is_null($this->_expressionProvider)) {
    $this->_expressionProvider = new InventoryDSExpressionProvider();
    }

    return $this->_expressionProvider;
    }
    }

?>