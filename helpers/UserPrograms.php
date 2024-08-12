<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/26/2024
 * @time: 11:43 AM
 */

namespace app\helpers;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Yii;
use yii\db\Query;

final class UserPrograms
{
    /**
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public static function storeInSession(): void
    {
        $currentDate = SmisHelper::formatDate('now', 'Y-m-d');
        $mappedPrograms = (new Query())
            ->select(['prog.prog_code'])
            ->from('smis.sm_degree_prog_user user_prog')
            ->innerJoin('smis.org_programmes prog', 'prog.prog_id=user_prog.programme_id')
            ->where([
                'user_prog.status' => 'ACTIVE',
                'user_prog.user_id' => Yii::$app->user->identity->id
            ])
            ->andWhere(['<=', 'user_prog.effective_date', $currentDate])
            ->andWhere(['>=', 'user_prog.end_date', $currentDate])
            ->all();

        $session = Yii::$app->session;
        $userPrograms = [];
        if(!empty($mappedPrograms)) {
            foreach ($mappedPrograms as $mappedProgram) {
                $userPrograms[] = $mappedProgram['prog_code'];
            }
        }
        $session['userPrograms'] = $userPrograms;
    }
}