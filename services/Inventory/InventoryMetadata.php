<?php

/** 
 * Implementation of IDataServiceMetadataProvider.
 * 
 * PHP version 5.3
 * 
 * @category  Service
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   SVN: 1.0
 * @link      http://odataphpproducer.codeplex.com
 * 
 */
use ODataProducer\Providers\Metadata\ResourceStreamInfo;
use ODataProducer\Providers\Metadata\ResourceAssociationSetEnd;
use ODataProducer\Providers\Metadata\ResourceAssociationSet;
use ODataProducer\Common\NotImplementedException;
use ODataProducer\Providers\Metadata\Type\EdmPrimitiveType;
use ODataProducer\Providers\Metadata\ResourceSet;
use ODataProducer\Providers\Metadata\ResourcePropertyKind;
use ODataProducer\Providers\Metadata\ResourceProperty;
use ODataProducer\Providers\Metadata\ResourceTypeKind;
use ODataProducer\Providers\Metadata\ResourceType;
use ODataProducer\Common\InvalidOperationException;
use ODataProducer\Providers\Metadata\IDataServiceMetadataProvider;
require_once 'ODataProducer/Providers/Metadata/IDataServiceMetadataProvider.php';
use ODataProducer\Providers\Metadata\ServiceBaseMetadata;
//Begin Resource Classes

/**
 * event entity type.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class event
{
    //Edm.Int32
    public $eventId;
            
    //Edm.String
    public $eventName;
            
    //Edm.String
    public $eventDescription;
            
    //Edm.String
    public $location;
            
    //Edm.DateTime
    public $eventStartDate;
            
    //Edm.DateTime
    public $eventEndDate;
            
    //Edm.String
    public $userName;
            
    //Navigation Property Inventory.user
    public $user;
    
    //Navigation Property Inventory.event_inventories
    public $event_inventories;
    
    //Navigation Property Inventory.event_users
    public $event_users;
    
}

/**
 * event_inventory entity type.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class event_inventory
{
    //Edm.Int32
    public $eventInventoryId;
            
    //Edm.Int32
    public $itemId;
            
    //Edm.Int32
    public $eventId;
            
    //Navigation Property Inventory.event
    public $event;
    
    //Navigation Property Inventory.inventory
    public $inventory;
    
}

/**
 * event_user entity type.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class event_user
{
    //Edm.Int32
    public $evetUserId;
            
    //Edm.String
    public $userName;
            
    //Edm.Int32
    public $eventId;
            
    //Navigation Property Inventory.event
    public $event;
    
    //Navigation Property Inventory.user
    public $user;
    
}

/**
 * inventory entity type.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class inventory
{
    //Edm.Int32
    public $inventoryItemId;
            
    //Edm.Int32
    public $itemId;
            
    //Edm.Int32
    public $condition;
            
    //Edm.String
    public $lastCheckedOutBy;
            
    //Edm.DateTime
    public $checkOutDate;
            
    //Navigation Property Inventory.user
    public $user;
    
    //Navigation Property Inventory.item
    public $item;
    
    //Navigation Property Inventory.event_inventories
    public $event_inventories;
    
}

/**
 * item entity type.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class item
{
    //Edm.Int32
    public $itemId;
            
    //Edm.String
    public $itemName;
            
    //Edm.String
    public $description;
            
    //Edm.Double
    public $pricing;
            
    //Edm.String
    public $locationPurchased;
            
    //Navigation Property Inventory.inventories
    public $inventories;
    
}

/**
 * user entity type.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class user
{
    //Edm.String
    public $userName;
            
    //Edm.String
    public $name;
            
    //Edm.String
    public $address;
            
    //Edm.String
    public $phone;
            
    //Navigation Property Inventory.events
    public $events;
    
    //Navigation Property Inventory.event_users
    public $event_users;
    
    //Navigation Property Inventory.inventories
    public $inventories;
    
}


/**
 * Create Inventory metadata.
 * 
 * @category  Service
 * @package   Service_Inventory
 * @author    Yash K. Kothari <odataphpproducer_alias@microsoft.com>
 * @copyright 2011 Microsoft Corp. (http://www.microsoft.com)
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @version   Release: 1.0
 * @link      http://odataphpproducer.codeplex.com
 */
class CreateInventoryMetadata
{
    /**
     * create metadata
     * 
     * @return InventoryMetadata
     */
    public static function create()
    {
        $metadata = new ServiceBaseMetadata('InventoryEntities', 'Inventory');
        
        //Register the entity (resource) type 'event'
        $eventEntityType = $metadata->addEntityType(
            new ReflectionClass('event'), 'event', 'Inventory'
        );
        $metadata->addKeyProperty(
            $eventEntityType, 'eventId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $eventEntityType, 'eventName', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $eventEntityType, 'eventDescription', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $eventEntityType, 'location', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $eventEntityType, 'eventStartDate', EdmPrimitiveType::DATETIME
        );
        $metadata->addPrimitiveProperty(
            $eventEntityType, 'eventEndDate', EdmPrimitiveType::DATETIME
        );
        $metadata->addPrimitiveProperty(
            $eventEntityType, 'userName', EdmPrimitiveType::STRING
        );
        
        //Register the entity (resource) type 'event_inventory'
        $event_inventoryEntityType = $metadata->addEntityType(
            new ReflectionClass('event_inventory'), 'event_inventory', 'Inventory'
        );
        $metadata->addKeyProperty(
            $event_inventoryEntityType, 'eventInventoryId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $event_inventoryEntityType, 'itemId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $event_inventoryEntityType, 'eventId', EdmPrimitiveType::INT32
        );
        
        //Register the entity (resource) type 'event_user'
        $event_userEntityType = $metadata->addEntityType(
            new ReflectionClass('event_user'), 'event_user', 'Inventory'
        );
        $metadata->addKeyProperty(
            $event_userEntityType, 'evetUserId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $event_userEntityType, 'userName', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $event_userEntityType, 'eventId', EdmPrimitiveType::INT32
        );
        
        //Register the entity (resource) type 'inventory'
        $inventoryEntityType = $metadata->addEntityType(
            new ReflectionClass('inventory'), 'inventory', 'Inventory'
        );
        $metadata->addKeyProperty(
            $inventoryEntityType, 'inventoryItemId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $inventoryEntityType, 'itemId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $inventoryEntityType, 'condition', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $inventoryEntityType, 'lastCheckedOutBy', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $inventoryEntityType, 'checkOutDate', EdmPrimitiveType::DATETIME
        );
        
        //Register the entity (resource) type 'item'
        $itemEntityType = $metadata->addEntityType(
            new ReflectionClass('item'), 'item', 'Inventory'
        );
        $metadata->addKeyProperty(
            $itemEntityType, 'itemId', EdmPrimitiveType::INT32
        );
        $metadata->addPrimitiveProperty(
            $itemEntityType, 'itemName', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $itemEntityType, 'description', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $itemEntityType, 'pricing', EdmPrimitiveType::DOUBLE
        );
        $metadata->addPrimitiveProperty(
            $itemEntityType, 'locationPurchased', EdmPrimitiveType::STRING
        );
        
        //Register the entity (resource) type 'user'
        $userEntityType = $metadata->addEntityType(
            new ReflectionClass('user'), 'user', 'Inventory'
        );
        $metadata->addKeyProperty(
            $userEntityType, 'userName', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $userEntityType, 'name', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $userEntityType, 'address', EdmPrimitiveType::STRING
        );
        $metadata->addPrimitiveProperty(
            $userEntityType, 'phone', EdmPrimitiveType::STRING
        );
        
        $eventsResourceSet = $metadata->addResourceSet(
            'events', $eventEntityType
        );
        $event_inventoriesResourceSet = $metadata->addResourceSet(
            'event_inventories', $event_inventoryEntityType
        );
        $event_usersResourceSet = $metadata->addResourceSet(
            'event_users', $event_userEntityType
        );
        $inventoriesResourceSet = $metadata->addResourceSet(
            'inventories', $inventoryEntityType
        );
        $itemsResourceSet = $metadata->addResourceSet(
            'items', $itemEntityType
        );
        $usersResourceSet = $metadata->addResourceSet(
            'users', $userEntityType
        );

        //Register the assoications (navigations)
        $metadata->addResourceReferenceProperty(
            $eventEntityType, 'user', $usersResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $userEntityType, 'events', $eventsResourceSet
        );
        $metadata->addResourceReferenceProperty(
            $event_inventoryEntityType, 'event', $eventsResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $eventEntityType, 'event_inventories', $event_inventoriesResourceSet
        );
        $metadata->addResourceReferenceProperty(
            $event_inventoryEntityType, 'inventory', $inventoriesResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $inventoryEntityType, 'event_inventories', $event_inventoriesResourceSet
        );
        $metadata->addResourceReferenceProperty(
            $event_userEntityType, 'event', $eventsResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $eventEntityType, 'event_users', $event_usersResourceSet
        );
        $metadata->addResourceReferenceProperty(
            $event_userEntityType, 'user', $usersResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $userEntityType, 'event_users', $event_usersResourceSet
        );
        $metadata->addResourceReferenceProperty(
            $inventoryEntityType, 'user', $usersResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $userEntityType, 'inventories', $inventoriesResourceSet
        );
        $metadata->addResourceReferenceProperty(
            $inventoryEntityType, 'item', $itemsResourceSet
        );
        $metadata->addResourceSetReferenceProperty(
            $itemEntityType, 'inventories', $inventoriesResourceSet
        );
        
        return $metadata;
    }
}
?>
