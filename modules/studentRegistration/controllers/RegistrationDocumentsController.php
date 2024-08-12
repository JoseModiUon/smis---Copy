<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 8/29/2023
 * @time: 10:49 AM
 */
declare(strict_types=1);
namespace app\modules\studentRegistration\controllers;

use app\helpers\SmisHelper;
use app\modules\studentRegistration\models\RegistrationDocument;
use app\modules\studentRegistration\models\RequiredDocument;
use app\modules\studentRegistration\models\search\RegistrationDocumentsSearch;
use app\traits\ControllerTrait;
use Exception;
use Throwable;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class RegistrationDocumentsController extends BaseController
{
    use ControllerTrait;

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'associate-docs-with-categories'],
                        'roles' => ['smis_view_reg_docs']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['save'],
                        'roles' => ['smis_create_reg_docs']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'edit'],
                        'roles' => ['smis_update_reg_docs']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add-doc-to-category'],
                        'roles' => ['smis_add_reg_docs_to_student_categories']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['remove-doc-from-category'],
                        'roles' => ['smis_remove_reg_docs_from_student_categories']
                    ]
                ],
            ]
        ];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionIndex(): string
    {
        try{
            $docsSearchModel = new RegistrationDocumentsSearch();
            $docsProvider = $docsSearchModel->search([
                'queryParams' => Yii::$app->request->queryParams
            ]);

            $categories = (new Query())->from('smis.sm_student_category')->all();

            $categoriesList = ArrayHelper::map($categories, 'std_category_name', function ($category){
                return $category['std_category_name'];
            });

            $requiredStatusList = [
                'true' => 'Required',
                'false' => 'Not Required'
            ];

            return $this->render('index', [
                'title' => $this->createPageTitle('registration documents'),
                'docsSearchModel' => $docsSearchModel,
                'docsProvider' => $docsProvider,
                'categoriesList' => $categoriesList,
                'requiredStatusList' => $requiredStatusList
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     */
    public function actionEdit(): Response
    {
        try{
            $docId = Yii::$app->request->get('docId');

            $document = RegistrationDocument::find()->where(['document_id' => $docId])->asArray()->one();

            return $this->asJson(['success' => true, 'document' => $document]);

        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionSave(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $name = $post['name'];
            $description = $post['description'];
            $documentCount = RegistrationDocument::find()->where(['document_name' => $name])->count();
            if($documentCount > 0){
                throw new Exception('This document already exists.');
            }
            $document = new RegistrationDocument();
            $document->document_name = $name;
            $document->document_desc = (empty($description) ? null: $description);
            $document->required = $post['required-status'] == 'required';
            if(!$document->save()){
                if(!$document->validate()){
                    throw new Exception(SmisHelper::getModelErrors($document->getErrors()));
                }else{
                    throw new Exception('The document was not saved.');
                }
            }
            $transaction->commit();
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionUpdate(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $document = RegistrationDocument::findOne($post['id']);
            $document->document_name = $post['name'];
            $document->required = $post['required-status'] == 'required';
            $document->document_desc = (empty($post['description']) ? null: $post['description']);
            if(!$document->save()){
                if(!$document->validate()){
                    throw new Exception(SmisHelper::getModelErrors($document->getErrors()));
                }else{
                    throw new Exception('The document was not update.');
                }
            }
            $transaction->commit();
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionAssociateDocsWithCategories(): string
    {
        try{
            $get = Yii::$app->request->get();
            $docs = (new Query())->select(['document_id', 'document_name', 'required'])->from( 'smis.sm_reg_documents')->all();
            $categories = (new Query())->select(['std_category_id', 'std_category_name'])->from('smis.sm_student_category')
                ->all();

            return $this->render('associateDocsWithCategories', [
                'title' => $this->createPageTitle('associate docs with categories'),
                'docs' => $docs,
                'categories' => $categories,
                'scope' => $get['scope']
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     */
    public function actionAddDocToCategory(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $categories = $post['categoryIds'];
            $documents = $post['documentIds'];

            foreach ($categories as $category){
                foreach ($documents as $document){
                    $reqDocument = RequiredDocument::find()->where(['fk_document_id' => $document, 'fk_category_id' => $category])
                        ->count();
                    if($reqDocument > 0){
                        continue;
                    }
                    $reqDocument = new RequiredDocument();
                    $reqDocument->fk_document_id = $document;
                    $reqDocument->fk_category_id = $category;
                    if(!$reqDocument->save()){
                        if(!$reqDocument->validate()){
                            throw new Exception(SmisHelper::getModelErrors($reqDocument->getErrors()));
                        }else{
                            throw new Exception('Document not added to category.');
                        }
                    }
                }
            }

            $transaction->commit();
            return $this->redirect(['/student-registration/registration-documents/index']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionRemoveDocFromCategory(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $categories = $post['categoryIds'];
            $documents = $post['documentIds'];

            foreach ($categories as $category){
                foreach ($documents as $document){
                    $reqDocument = RequiredDocument::find()->where(['fk_document_id' => $document, 'fk_category_id' => $category])
                        ->one();
                    if($reqDocument){
                        if(!$reqDocument->delete()){
                            throw new Exception('Document not removed from category.');
                        }
                    }
                }
            }

            $transaction->commit();
            return $this->redirect(['/student-registration/registration-documents/index']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }
}
