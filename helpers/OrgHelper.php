<?php
declare(strict_types=1);

namespace app\helpers;

use Yii;

class OrgHelper
{
    public static function saveDetailsInSession()
    {
        $db=Yii::$app->db;
        //the condition for unit_code='NDU-K' can be removed once the data has been restricted to a particular institution
        $parentInstitution="select org_unit.unit_code,org_unit.unit_id,org_unit.unit_name,unit_type_name,logo_url,
                        favicon_url,motto,email,website,postal_address,phone
                from smis.org_unit
                inner join smis.org_unit_history on org_unit_history.org_unit_id=org_unit.unit_id
                inner join smis.org_unit_types on org_unit_history.org_type_id= org_unit_types.unit_type_id
                inner join smis.org_institution_setup on org_institution_setup.unit_id=org_unit.unit_id
                where org_unit_history.parent_id is null and unit_code='NDU-K'";

        $parent_Institution = $db->createCommand($parentInstitution)->queryOne();

        $session = Yii::$app->session;
        // $session['orgDetails'] = [
        //     'parent_institution_name' => $parent_Institution['unit_name'],
        //     'parent_institution_unit_id' => $parent_Institution['unit_id'],
        //     'parent_institution_logo' => $parent_Institution['logo_url'],
        //     'parent_institution_favicon' => $parent_Institution['favicon_url'],
        //     'parent_institution_type' => $parent_Institution['unit_type_name'],
        //     'parent_institution_motto' => $parent_Institution['motto'],
        //     'parent_institution_address' => $parent_Institution['postal_address'],
        //     'parent_institution_email' => $parent_Institution['email'],
        //     'parent_institution_website' => $parent_Institution['website'],
        //     'parent_institution_unit_code' => $parent_Institution['unit_code'],
        //     'parent_institution_phone' => $parent_Institution['phone'],
        // ];

        $session['orgDetails'] = [
            'parent_institution_name' => 'NATIONAL DEFENSE UNIVERSITY - KENYA', //$parent_Institution['unit_name'],
            'parent_institution_unit_id' => '9', //$parent_Institution['unit_id'],
            'parent_institution_logo' => '/web/studentreg/img/ndu-arms.png', //$parent_Institution['logo_url'],
            'parent_institution_favicon' => '/web/studentreg/img/ndu-arms.png', //$parent_Institution['favicon_url'],
            'parent_institution_type' => 'UNIVERSITY', //$parent_Institution['unit_type_name'],
            'parent_institution_motto' => 'Prudentia, Excellencia et Opera', //$parent_Institution['motto'],
            'parent_institution_address' => 'P.O BOX, 3182 - 20100, Nakuru, Kenya', //$parent_Institution['postal_address'],
            'parent_institution_email' => 'info@ndu.ac.ke', //$parent_Institution['email'],
            'parent_institution_website' => 'www.ndu.ac.ke', //$parent_Institution['website'],
            'parent_institution_unit_code' => 'NDU-K', //$parent_Institution['unit_code'],
            'parent_institution_phone' => '+254051851141', //$parent_Institution['phone'],
        ];
    }
}
