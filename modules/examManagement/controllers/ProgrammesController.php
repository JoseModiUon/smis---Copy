<?php

namespace app\modules\examManagement\controllers;

use Yii;
use app\models\OrgProgrammes;
use app\models\search\OrgProgrammesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\OrgProgrammeCurriculum;
use app\modules\examManagement\models\ProgrammeRequirement;
use yii\data\ArrayDataProvider;

/**
 * OrgProgrammesController implements the CRUD actions for OrgProgrammes model.
 */
class ProgrammesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all OrgProgrammes models.
     *
     * @return string
     */


    public function actionIndex()
    {


        if(empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        } else {
            $prog_code = Yii::$app->request->get('prog_code');
        }
        /*  if(empty(Yii::$app->request->get('acad_session_id'))) {
              $acad_session = 0;
          }
          else{

              $acad_session=Yii::$app->request->get('acad_session_id');
          }*/

        if($prog_code == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()]);
            return $this->render(
                'index',
                ['dataexport' => $dataexport,

                    'prog_code' => $prog_code,
                 //   'acad_session'=>$acad_session,
                ]
            );
        }
        $dt = ProgrammeRequirement::GET_PROG_CURRICULUM($prog_code);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt]);

        return $this->render(
            'index',
            ['dataexport' => $dataexport,
                'prog_code' => $prog_code,
               // 'acad_session'=>$acad_session,
            ]
        );


    }


    /*



        public function actionProgrammeRequirements(){

    //        dd($_REQUEST);
               if(empty(Yii::$app->request->get('prog_code'))) {
                $prog_code = 0;
               }
               else{
                   $prog_code=Yii::$app->request->get('prog_code');
               }
                if(empty(Yii::$app->request->get('prog_curriculum_id'))) {
                    $acad_session = 0;
                }
                else{

                    $prog_curriculum_id=Yii::$app->request->get('prog_curriculum_id');
                }

             if($prog_code==0) {

                //$acad_level = 0;
                //$dataexport=[];

                $dataexport=   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()]);
                 return $this->render('index',
                ['dataexport'=>$dataexport,

           'prog_code'=>$prog_code,
           'prog_curriculum_id'=>$prog_curriculum_id,
           ]);
            }

             /// dd($acad_session);


               $dt = ProgrammeRequirement::GET_LEVEL_REQUIREMENTS($prog_curriculum_id);

               $dataexport=   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => $dt]);

           return $this->render('programme-requirement',
           ['dataexport'=>$dataexport,
           'prog_code'=>$prog_code,
           'prog_curriculum_id'=>$prog_curriculum_id,
           ]);

        }*/
    public function actionProgCurrGroupReqCourse()
    {

        //dd($_REQUEST);
        if(empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        } else {
            $prog_code = Yii::$app->request->get('prog_code');
        }
        if(empty(Yii::$app->request->get('id'))) {
            $prog_curr_group_requirement_id = 0;
        } else {

            $prog_curr_group_requirement_id = Yii::$app->request->get('id');
        }

        if($prog_code == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()]);
            return $this->render(
                'prog-curr-group-req-course',
                ['dataexport' => $dataexport,

                    'prog_code' => $prog_code,
                    'prog_curr_group_requirement_id' => $prog_curr_group_requirement_id,
                ]
            );
        }

        /// dd($acad_session);


        $dt = ProgrammeRequirement::GET_CURR_GROUP_REQ_COURSES($prog_curr_group_requirement_id);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt]);

        return $this->render(
            'prog-curr-group-req-course',
            ['dataexport' => $dataexport,
                'prog_code' => $prog_code,
                'prog_curr_group_requirement_id' => $prog_curr_group_requirement_id,
            ]
        );

    }
    public function actionProgrammeRequirements()
    {

        //        dd($_REQUEST);
        if(empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        } else {
            $prog_code = Yii::$app->request->get('prog_code');
        }
        if(empty(Yii::$app->request->get('prog_curriculum_id'))) {
            $acad_session = 0;
        } else {

            $prog_curriculum_id = Yii::$app->request->get('prog_curriculum_id');
        }

        if($prog_code == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()]);
            return $this->render(
                'prog-req',
                ['dataexport' => $dataexport,

                    'prog_code' => $prog_code,
                    'prog_curriculum_id' => $prog_curriculum_id,
                ]
            );
        }

        /// dd($acad_session);


        $dt = ProgrammeRequirement::GET_LEVEL_REQUIREMENTS($prog_curriculum_id);

        $lvl_requirement = ProgrammeRequirement::GET_LVL_REQUIREMENT($prog_curriculum_id);

        /*$dataexport=   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt]);*/

        return $this->render(
            'prog-req',
            [
                'lvl_requirement' => $lvl_requirement,
                'prog_code' => $prog_code,
                'prog_curriculum_id' => $prog_curriculum_id,
            ]
        );

    }
    /**
     * Displays a single OrgProgrammes model.
     * @param int $prog_id Prog ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_id),
        ]);
    }

    /**
     * Creates a new OrgProgrammes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgrammes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', $model->prog_short_name.' Created!');
                return $this->redirect(['index']);
                // return $this->redirect(['view', 'prog_id' => $model->prog_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgrammes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_id Prog ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_id)
    {
        $model = $this->findModel($prog_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->prog_short_name.' Updated!');
            return $this->redirect(['index']);

            //  return $this->redirect(['view', 'prog_id' => $model->prog_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgrammes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_id Prog ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_id)
    {
        $this->findModel($prog_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgrammes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_id Prog ID
     * @return OrgProgrammes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_id)
    {
        if (($model = OrgProgrammes::findOne(['prog_id' => $prog_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
