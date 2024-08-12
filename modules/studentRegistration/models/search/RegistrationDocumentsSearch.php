<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 8/29/2023
 * @time: 11:04 AM
 */
declare(strict_types=1);
namespace app\modules\studentRegistration\models\search;

use app\modules\studentRegistration\models\RequiredDocument;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class RegistrationDocumentsSearch extends RequiredDocument
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'studentCategory',
            'docName',
            'docDesc',
            'docRequired'
        ]);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'studentCategory',
                    'docName',
                    'docDesc',
                    'docRequired'
                ],
                'safe'
            ]
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = (new Query())
            ->select([
                'req_doc.required_document_id',
                'doc.document_id',
                'doc.document_name',
                'doc.document_desc',
                'doc.required',
                'cat.std_category_name'
            ])
            ->from('smis.sm_reg_required_document req_doc')
            ->innerJoin('smis.sm_reg_documents doc', 'doc.document_id=req_doc.fk_document_id')
            ->innerJoin('smis.sm_student_category cat', 'cat.std_category_id=req_doc.fk_category_id');

        $this->load($params['queryParams']);

        $query->andFilterWhere(['cat.std_category_name' => $this->getAttribute('studentCategory')]);
        $query->andFilterWhere(['like', 'doc.document_name', $this->getAttribute('docName')]);
        $query->andFilterWhere(['like', 'doc.document_desc', $this->getAttribute('docDesc')]);
        $query->andFilterWhere(['doc.required' => $this->getAttribute('docRequired')]);

        $query->orderBy(['cat.std_category_id' => SORT_ASC]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ]
        ]);
    }
}
