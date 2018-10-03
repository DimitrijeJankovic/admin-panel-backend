<?php
namespace API_Inventory\V1\Rest\OrderItemPhoto;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;

class OrderItemPhotoResource extends AbstractResourceListener
{
    
    private $adapter;
    private $messages;
    
    public function __construct(Adapter $adapter) {
        
        $this->adapter = $adapter;
        
        # Get language       
        $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtoupper($_SERVER['HTTP_ACCEPT_LANGUAGE']) : "EN";
        $lang_messages = new LanguageService($language);
        $this->messages = $lang_messages->messages;
    }
    
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        
        $item_id = (int) $this->getEvent()->getRouteParam('item_id', false);
        
        if((!isset($item_id) || empty($item_id))){            
            return new ApiProblem(404, $this->messages['Order item id must be provided'], null, $this->messages['Warning'], []);           
        }
        
        # Validate user input
        if (!isset($_FILES["photo"])) {
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);
        }
        if ($_FILES["photo"]["size"] > 1024000) {
            return new ApiProblem(412, $this->messages['Image must be smaller then 1Mb'], null, $this->messages['Warning'], []);
        }
        if ($_FILES["photo"]["type"] != "image/jpeg" && $_FILES["photo"]["type"] != "image/png") {
            return new ApiProblem(412, $this->messages['Invalid file type'], null, $this->messages['Warning'], []);
        }
        if ($_FILES["photo"]["error"]) {
            return new ApiProblem(412, $this->messages['Error uploading file'], null, $this->messages['Warning'], []);
        }
        if (!isset($_FILES["photo"]["tmp_name"])) {
            return new ApiProblem(414, $this->messages['Error uploading file'], null, $this->messages['Warning'], []);
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        // chack if order exists
        $itemSelect = $sql->select('order_items')->where(['id' => $item_id]);
        try { $item = $adapter->query($sql->getSqlStringForSqlObject($itemSelect), $adapter::QUERY_MODE_EXECUTE)->toArray(); } 
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if(empty($item)){
            return new ApiProblem(404, $this->messages['Item not found'], null, $this->messages['Error'], []);
        }
        
        $fileExtension = explode("/",$_FILES["photo"]['type'])[1];
        
        $imageName = 'order_item_' . $item_id . "_photo_" . time().".$fileExtension";

        # Url where image will be recorded
        $photoUrl = \Application\Model\Config::ORDER_ITEM_IMG_ANGULAR.$imageName;
        
        # Move image to location
        $imgUploaded = move_uploaded_file($_FILES["photo"]['tmp_name'], $photoUrl);
        
        if($imgUploaded){
            
            #Get old photo name
            $oldPhotoSelect = $sql->select('order_items')
                    ->columns(['photo_attach_image'])
                    ->where(['id' => $item_id]);

            try { $oldPhoto = $adapter->query($sql->getSqlStringForSqlObject($oldPhotoSelect), $adapter::QUERY_MODE_EXECUTE)->toArray(); } 
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }
            
            # Delete old photo if it exist
            if (!empty($oldPhoto[0]['photo_attach_image'])) {
                
                $oldImgUrl = \Application\Model\Config::ORDER_ITEM_IMG_ANGULAR.$oldPhoto[0]['photo_attach_image'];
                
                if (file_exists($oldImgUrl)) { unlink($oldImgUrl); }
                
            }

            $update = $sql->update('order_items')->where(['id' => $item_id])->set(["photo_attach_image" => $imageName]);
            try { $adapter->query($sql->getSqlStringForSqlObject($update), $adapter::QUERY_MODE_EXECUTE); } 
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

            return new ApiProblem(200, $this->messages['Image uploaded'], null, $this->messages['Success'], []);
            
        }else{
            return new ApiProblem(404, $this->messages['Could not upload profile image'], null, $this->messages['Error'], []);
        }
        
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        
        if((!isset($id) || empty($id))){            
            return new ApiProblem(404, $this->messages['Order item id must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        // chack if order exists
        $itemSelect = $sql->select('order_items')->where(['id' => $id]);
        try { $item = $adapter->query($sql->getSqlStringForSqlObject($itemSelect), $adapter::QUERY_MODE_EXECUTE)->toArray(); } 
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if(empty($item)){
            return new ApiProblem(404, $this->messages['Item not found'], null, $this->messages['Error'], []);
        }
        
        if(!empty($item[0]['photo_attach_image'])){
            $img = \Application\Model\Config::ORDER_ITEM_IMG_ANGULAR.$item[0]['photo_attach_image'];
            
            if (file_exists($img)) { unlink($img); }
            
            $updateItemPhoto = $sql->update('order_items')->set(['photo_attach_image' => null])->where(['id' => $id]);
            try { $itemData = $adapter->query($sql->getSqlStringForSqlObject($updateItemPhoto), $adapter::QUERY_MODE_EXECUTE); }
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }

            return new ApiProblem(200, $this->messages['Item photo deleted'], null, $this->messages['Success'], []);
            
        }else{
            return new ApiProblem(404, $this->messages['Photo does not exist'], null, $this->messages['Warning'], []);
        }
        
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
