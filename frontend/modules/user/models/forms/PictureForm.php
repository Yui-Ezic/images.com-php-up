<?php

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;
use Intervention\Image\ImageManager;

class PictureForm extends Model
{
    public $picture;
    
    public function rules()
    {
        return [
            [['picture'], 'file',
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }
    
    public function __construct() 
    {
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
    }
    
    /**
     * Resize profile picture if it is larger than max width and max height in params
     */
    public function resizePicture() 
    {
        if ($this->picture->error) {
            return;
        }
        
        $width = Yii::$app->params['profilePicture']['maxWidth'];
        $height = Yii::$app->params['profilePicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));
        $image = $manager->make($this->picture->tempName);
        
        $image->resize($width, $height, function ($constraint) {           
            // Пропорции изображений оставлять такими же (например, для избежания широких или вытянутых лиц)
            $constraint->aspectRatio();          
            // Изображения, размером меньше заданных $width, $height не будут изменены: 
            $constraint->upsize();            
        })->save();
    }
    
    /**
     * @return integer
     */
    public function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}
