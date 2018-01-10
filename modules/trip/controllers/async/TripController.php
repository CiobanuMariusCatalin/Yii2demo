<?php

namespace app\modules\trip\controllers\async;

use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Trip;
use app\models\TripImage;

class TripController extends Controller
{

    public function behaviors()
    {
        //The permission name it's found in the authorizationConstants componenent
        $constants = Yii::$app->authorizationConstants;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$constants::REGULAR_USER_PERMISSION]
                    ],
//                    [
//                        'allow' => true,
//                        'roles' => ['@']
//                    ]
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        $result = array();
        $result['success'] = true;
        return json_encode($result);
    }

    //
    //Gets the pictures associtated with a trip from TripImage
    //@param id int the id of the trip
    //
    public function actionGetTripPictures($id)
    {
        $trip = Trip::getTrip($id);
        $imagesPaths = array();
        try {
           
            $images = $trip->getTripImage()->all();
        } catch (\Exception $e) {
            $imagesPaths['success'] = false;
            Yii::error('ErrorMessage:' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            $imagesPaths['message'] = 'Could not get collect the pictures please try again later';
            return json_encode($imagesPaths);
        }

        $imagesPaths['image'] = array();
        $imagesPaths['success'] = true;
        $imagesPaths['message'] = 'success';

        //send the image path and the id of the image. The id will be used for
        //the delete function
        foreach ($images as $image) {
            $imagesPaths['image'][] = [
                'imagePath' => $image->image_path,
                'id' => $image->id
            ];
        }
        return json_encode($imagesPaths);
    }

    //
    //Deletes the picture with the id given
    //@param id int the id of the picture that must be deleted
    //
    public function actionDeletePicture($id)
    {
        $result = array();
        $result['success'] = false;
        $tripImage = TripImage::findOne($id);
        
        //if there are no results for the id given
        if(is_null($tripImage)) {
            $result['message'] = 'Could not find a image with the id given';
            return json_encode($result);
        }
        
        //if the delete function finished with no errors notify the user everything is fine
        if($tripImage->delete()) {
            $result['success'] = true;
            $result['message'] = 'Successfully deleted image';
            return json_encode($result);
        } else {
            $result['message'] = 'An error has occurred, could not delete the image';
            return json_encode($result);
        }
        
    }

}
