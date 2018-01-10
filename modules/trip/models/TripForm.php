<?php

namespace app\modules\trip\models;

use Yii;
use app\models\Trip;
use app\models\User;
use app\models\TripImage;
class TripForm extends Trip
{

    public $pictures;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'destination_country', 'destination_city', 'destination_latitude', 'destination_longitude', 'home_latitude', 'home_longitude'], 'required'],
            [['destination_latitude', 'destination_longitude', 'home_latitude', 'home_longitude'], 'number'],
            [['user_id'], 'integer'],
            [['name', 'destination_country', 'destination_city'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 2000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            //max 10mb
            [['pictures'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 10,
                'tooBig' => 'File has to be smaller than 10mb'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'destination_country' => 'Destination Country',
            'destination_city' => 'Destination City',
            'destination_latitude' => 'Destination Latitude',
            'destination_longitude' => 'Destination Longitude',
            'home_latitude' => 'Home Latitude',
            'home_longitude' => 'Home Longitude',
            'user_id' => 'User ID',
        ];
    }

    //
    //This method is used to save the trip pictures on the server on the 
    // /upload path. In the trip_image table is saved the image path.
    //@return boolean
    //

    public function upload()
    {
        Yii::info('TripForm/upload/ pictures size: ' . count($this->pictures));
     //   $i = 0;
        foreach ($this->pictures as $image) {
//            if ($i == 1) {
//                throw \Exception('Demo Exception');
//            }
            $tripImageModel = new TripImage();
            
            //set the trip id for the tripImage
            $tripImageModel->trip_id = $this->id;
            
            //I use the tripImage id for the image name so first i have to insert
            //the data, but because the image_path is set as not null i have to 
            //put a placeholder value
            $tripImageModel->image_path = 'temp will be overwritten in a few lines';
            if (!$tripImageModel->save()) {
                return false;
            }
            
            //create image path in web/upload, set the name as the id of the row
            $imagePath = 'upload/imagePath/' . $tripImageModel->id . '.' . $image->extension;
            
            //set the correct image_path
            $tripImageModel->image_path = '/' . $imagePath;
            if (!$tripImageModel->save()) {
                return false;
            }
            Yii::info('TripForm/upload/ this is image: ' . $image->baseName . ' tripImageModel id is: ' . $tripImageModel->id);
            
            //save the picture on the server
            $image->saveAs($imagePath);
//            $i++;
        }
        return true;
    }

}
