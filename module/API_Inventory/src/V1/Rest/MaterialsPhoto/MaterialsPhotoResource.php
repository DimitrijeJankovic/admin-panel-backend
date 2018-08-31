<?php
namespace API_Inventory\V1\Rest\MaterialsPhoto;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class MaterialsPhotoResource extends AbstractResourceListener
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
        $materialId = (int) $this->getEvent()->getRouteParam('materials_photo_id', false);
        
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
        
        // chack if material exists
        $materialSelect = $sql->select('materials')->where(['id' => $materialId]);
        try { $materialR = $adapter->query($sql->getSqlStringForSqlObject($materialSelect), $adapter::QUERY_MODE_EXECUTE)->toArray(); } 
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if(empty($materialR)){
            return new ApiProblem(404, $this->messages['Material not found'], null, $this->messages['Error'], []);
        }
        
        $fileExtension = explode("/",$_FILES["photo"]['type'])[1];
        
        $imageName = 'material_' . $materialId . "_photo_" . time().".$fileExtension";

        # Url where image will be recorded
        $photoUrl = \Application\Model\Config::MATERIAL_IMG_ANGULAR.$imageName;

        # Move image to location
        $imgUploaded = move_uploaded_file($_FILES["photo"]['tmp_name'], $photoUrl);

        if($imgUploaded){
            
            #Get old photo name
            $oldPhotoSelect = $sql->select('materials')
                    ->columns(['image_url'])
                    ->where(['id' => $materialId]);

            try { $oldPhoto = $adapter->query($sql->getSqlStringForSqlObject($oldPhotoSelect), $adapter::QUERY_MODE_EXECUTE)->toArray(); } 
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }
            
            # Delete old photo if it exist
            if (!empty($oldPhoto[0]['image_url'])) {
                
                $oldImgUrl = \Application\Model\Config::MATERIAL_IMG_ANGULAR.$oldPhoto[0]['image_url'];
                
                if (file_exists($oldImgUrl)) { unlink($oldImgUrl); }
                
            }

            $update = $sql->update('materials')->where(['id' => $materialId])->set(["image_url" => $imageName]);
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
            return new ApiProblem(404, $this->messages['material id must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        // chack if material exists
        $materialSelect = $sql->select('materials')->where(['id' => $id]);
        try { $materialR = $adapter->query($sql->getSqlStringForSqlObject($materialSelect), $adapter::QUERY_MODE_EXECUTE)->toArray(); } 
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if(empty($materialR)){
            return new ApiProblem(404, $this->messages['Material not found'], null, $this->messages['Error'], []);
        }
        
        if(!empty($materialR[0]['image_url'])){
            $img = \Application\Model\Config::MATERIAL_IMG_ANGULAR.$materialR[0]['image_url'];
            
            if (file_exists($img)) { unlink($img); }
            
            $updateMaterialPhoto = $sql->update('materials')->set(['image_url' => null])->where(['id' => $id]);

            try { $materialData = $adapter->query($sql->getSqlStringForSqlObject($updateMaterialPhoto), $adapter::QUERY_MODE_EXECUTE); }
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }

            return new ApiProblem(200, $this->messages['Material photo deleted'], null, $this->messages['Success'], []);
            
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
